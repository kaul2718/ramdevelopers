<script>
    export default {
        name: 'BusinessStateForm'
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
        businessState: Object
    })

    const notificationStore = useNotificationStore()

    const form = ref({
        busiSta_name: '',
        busiSta_code: '',
        busiSta_description: '',
        busiSta_active: 1,
    })

    const errors = ref({})
    const isSubmitting = ref(false)

    const validateForm = () => {
        errors.value = {}

        if (!form.value.busiSta_name?.trim()) {
            errors.value.busiSta_name = 'El nombre es requerido'
        }
        if (!form.value.busiSta_code?.trim()) {
            errors.value.busiSta_code = 'El código es requerido'
        }

        return Object.keys(errors.value).length === 0
    }

    const handleSubmit = async () => {
        if (!validateForm()) {
            return
        }

        isSubmitting.value = true
        const formData = new FormData()

        if (form.value.busiSta_name) formData.append('busiSta_name', form.value.busiSta_name)
        if (form.value.busiSta_code) formData.append('busiSta_code', form.value.busiSta_code)
        if (form.value.busiSta_description) formData.append('busiSta_description', form.value.busiSta_description)
        formData.append('busiSta_active', Number(form.value.busiSta_active ?? 1))

        try {
            if (props.updating) {
                formData.append('_method', 'PUT')
                await axios.post(route('businessstate.update', props.businessState.busiSta_id), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                notificationStore.success('Estado del Proyecto actualizado exitosamente')
            } else {
                await axios.post(route('businessstate.store'), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                notificationStore.success('Estado del Proyecto creado exitosamente')
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
            console.error('Error submitting business state:', error)
            if (error.response?.data?.errors) {
                errors.value = error.response.data.errors
                const errorMessages = Object.values(errors.value).flat().join(', ')
                notificationStore.error(errorMessages)
            } else {
                notificationStore.error(props.updating ? 'Error al actualizar el estado del proyecto' : 'Error al crear el estado del proyecto')
            }
        } finally {
            isSubmitting.value = false
        }
    }

    onMounted(() => {
        if (props.updating && props.businessState) {
            form.value = {
                busiSta_name: props.businessState.busiSta_name || '',
                busiSta_code: props.businessState.busiSta_code || '',
                busiSta_description: props.businessState.busiSta_description || '',
                busiSta_active: Number(props.businessState.busiSta_active ?? 1)
            }
        }
    })

    watch(() => props.businessState, (newVal) => {
        if (newVal && props.updating) {
            form.value = {
                busiSta_name: newVal.busiSta_name || '',
                busiSta_code: newVal.busiSta_code || '',
                busiSta_description: newVal.busiSta_description || '',
                busiSta_active: Number(newVal.busiSta_active ?? 1)
            }
        }
    }, { deep: true })
</script>

<template>
    <FormSection @submit.prevent="handleSubmit">
        <template #title>
            {{ updating ? 'Editar Estado del Proyecto' : 'Crear Nuevo Estado del Proyecto' }}
        </template>

        <template #description>
            {{ updating ? 'Actualiza la información del estado del proyecto.' : 'Agrega un nuevo estado del proyecto.' }}
        </template>

        <template #form>
            <div class="caja--doble">
                <div class="contenedor--input">
                    <TextInput
                        id="busiSta_name"
                        v-model="form.busiSta_name"
                        type="text"
                        class="campo--input"
                        placeholder="Ej: Nuevo"
                        @keyup.enter="handleSubmit"
                    />
                    <InputLabel for="busiSta_name" value="Nombre" />
                    <InputError :message="errors.busiSta_name" class="mt-2" />
                </div>

                <div class="contenedor--input">
                    <TextInput
                        id="busiSta_code"
                        v-model="form.busiSta_code"
                        type="text"
                        class="campo--input"
                        placeholder="Ej: ACT"
                        @keyup.enter="handleSubmit"
                    />
                    <InputLabel for="busiSta_code" value="Código" />
                    <InputError :message="errors.busiSta_code" class="mt-2" />
                </div>

                <div v-if="updating" class="contenedor--input">
                    <select id="busiSta_active" v-model.number="form.busiSta_active" class="campo--input">
                        <option :value="1">Activo</option>
                        <option :value="0">Inactivo</option>
                    </select>
                    <InputLabel for="busiSta_active" value="Estado" />
                </div>

                <div class="contenedor--input">
                    <TextInput
                        id="busiSta_description"
                        v-model="form.busiSta_description"
                        type="text"
                        class="campo--input"
                        placeholder="Ingresa una descripción (opcional)"
                    />
                    <InputLabel for="busiSta_description" value="Descripción" />
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
