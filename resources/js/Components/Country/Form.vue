<!-- filepath: resources/js/Components/Country/Form.vue -->
<script>
    export default {
        name: 'CountryForm'
    }
</script>

<script setup>
    import FormSection from '@/Components/FormSection.vue'
    import InputError from '@/Components/InputError.vue'
    import InputLabel from '@/Components/InputLabel.vue'
    import PrimaryButton from '@/Components/PrimaryButton.vue'
    import TextInput from '@/Components/TextInput.vue'
    import { useNotificationStore } from '@/stores/notificationStore'
    import axios from 'axios'
    import { ref, watch, onMounted } from 'vue'

    const emit = defineEmits(['success', 'cancel'])

    const props = defineProps({
        updating: Boolean,
        country: Object
    })

    const notificationStore = useNotificationStore()

    const form = ref({
        ctry_name: '',
        ctry_code: '',
        ctry_active: 1
    })

    const errors = ref({})
    const isSubmitting = ref(false)

    const validateForm = () => {
        errors.value = {}

        if (!form.value.ctry_name?.trim()) {
            errors.value.ctry_name = 'El nombre del país es requerido'
        }
        if (!form.value.ctry_code?.trim()) {
            errors.value.ctry_code = 'El código ISO es requerido'
        } else if (form.value.ctry_code.length < 2) {
            errors.value.ctry_code = 'El código ISO debe tener al menos 2 caracteres'
        }

        return Object.keys(errors.value).length === 0
    }

    const handleSubmit = async () => {
        if (!validateForm()) {
            return
        }

        isSubmitting.value = true
        const formData = new FormData()

        if (form.value.ctry_name) formData.append('ctry_name', form.value.ctry_name)
        if (form.value.ctry_code) formData.append('ctry_code', form.value.ctry_code)
        formData.append('ctry_active', Number(form.value.ctry_active ?? 1))

        try {
            if (props.updating) {
                formData.append('_method', 'PUT')
                await axios.post(route('countries.update', props.country.ctry_id), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                notificationStore.success('País actualizado exitosamente')
            } else {
                await axios.post(route('countries.store'), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                notificationStore.success('País creado exitosamente')
            }

            setTimeout(() => {
                const notyfToasts = document.querySelectorAll('.notyf__toast')
                notyfToasts.forEach(toast => {
                    toast.style.opacity = '0'
                    toast.style.transition = 'opacity 0.3s ease-out'
                    setTimeout(() => {
                        toast.remove()
                    }, 300)
                })
            }, 3000)

            emit('success')
        } catch (error) {
            console.error('Error submitting country:', error)
            if (error.response?.data?.errors) {
                errors.value = error.response.data.errors
                const errorMessages = Object.values(errors.value).flat().join(', ')
                notificationStore.error(errorMessages)
            } else {
                notificationStore.error(props.updating ? 'Error al actualizar el país' : 'Error al crear el país')
            }
        } finally {
            isSubmitting.value = false
        }
    }

    onMounted(() => {
        if (props.updating && props.country) {
            form.value = {
                ctry_name: props.country.ctry_name || '',
                ctry_code: props.country.ctry_code || '',
                ctry_active: Number(props.country.ctry_active ?? 1)
            }
        }
    })

    watch(() => props.country, (newVal) => {
        if (newVal && props.updating) {
            form.value = {
                ctry_name: newVal.ctry_name || '',
                ctry_code: newVal.ctry_code || '',
                ctry_active: Number(newVal.ctry_active ?? 1)
            }
        }
    }, { deep: true })
</script>

<template>
    <FormSection @submit.prevent="handleSubmit">
        <template #title>
            {{ updating ? 'Editar País' : 'Crear Nuevo País' }}
        </template>

        <template #description>
            {{ updating ? 'Actualiza la información del país.' : 'Agrega un nuevo país.' }}
        </template>

        <template #form>
            <div class="caja--doble">
                <div class="contenedor--input">
                    <TextInput
                        id="ctry_name"
                        v-model="form.ctry_name"
                        type="text"
                        class="campo--input"
                        placeholder="Ej: Colombia"
                        @keyup.enter="handleSubmit"
                    />
                    <InputLabel for="ctry_name" value="Nombre del País" />
                    <InputError :message="errors.ctry_name" class="mt-2" />
                </div>

                <div class="contenedor--input">
                    <TextInput
                        id="ctry_code"
                        v-model="form.ctry_code"
                        type="text"
                        class="campo--input"
                        placeholder="Ej: CO"
                        maxlength="5"
                        @keyup.enter="handleSubmit"
                    />
                    <InputLabel for="ctry_code" value="Código (ISO)" />
                    <InputError :message="errors.ctry_code" class="mt-2" />
                </div>

                <div v-if="updating" class="contenedor--input">
                    <select id="ctry_active" v-model.number="form.ctry_active" class="campo--input">
                        <option :value="1">Activo</option>
                        <option :value="0">Inactivo</option>
                    </select>
                    <InputLabel for="ctry_active" value="Estado" />
                </div>
            </div>
        </template>

        <template #actions>
            <PrimaryButton :disabled="isSubmitting">
                <template #texto--boton>{{ isSubmitting ? 'Guardando...' : (updating ? 'Actualizar' : 'Crear') }}</template>
                <template #icono--boton>
                    <path v-if="!updating" stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    <path v-if="updating" stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </template>
            </PrimaryButton>
        </template>
    </FormSection>
</template>