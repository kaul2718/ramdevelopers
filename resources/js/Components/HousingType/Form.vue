<script>
    export default {
        name: 'HousingTypeForm'
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
        housingType: Object
    })

    const notificationStore = useNotificationStore()

    const form = ref({
        houTyp_name: '',
        houTyp_code: '',
        houTyp_description: '',
        houTyp_active: 1
    })

    const errors = ref({})
    const isSubmitting = ref(false)

    const validateForm = () => {
        errors.value = {}

        if (!form.value.houTyp_name?.trim()) {
            errors.value.houTyp_name = 'El nombre es requerido'
        }
        if (!form.value.houTyp_code?.trim()) {
            errors.value.houTyp_code = 'El código es requerido'
        }

        return Object.keys(errors.value).length === 0
    }

    const handleSubmit = async () => {
        if (!validateForm()) {
            return
        }

        isSubmitting.value = true
        const formData = new FormData()

        if (form.value.houTyp_name) formData.append('houTyp_name', form.value.houTyp_name)
        if (form.value.houTyp_code) formData.append('houTyp_code', form.value.houTyp_code)
        if (form.value.houTyp_description) formData.append('houTyp_description', form.value.houTyp_description)
        formData.append('houTyp_active', Number(form.value.houTyp_active ?? 1))

        try {
            if (props.updating) {
                formData.append('_method', 'PUT')
                await axios.post(route('housingtype.update', props.housingType.houTyp_id), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                notificationStore.success('Tipo de vivienda actualizado exitosamente')
            } else {
                await axios.post(route('housingtype.store'), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                notificationStore.success('Tipo de vivienda creado exitosamente')
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
            console.error('Error submitting housing type:', error)
            if (error.response?.data?.errors) {
                errors.value = error.response.data.errors
                const errorMessages = Object.values(errors.value).flat().join(', ')
                notificationStore.error(errorMessages)
            } else {
                notificationStore.error(props.updating ? 'Error al actualizar el tipo de vivienda' : 'Error al crear el tipo de vivienda')
            }
        } finally {
            isSubmitting.value = false
        }
    }

    onMounted(() => {
        if (props.updating && props.housingType) {
            form.value = {
                houTyp_name: props.housingType.houTyp_name || '',
                houTyp_code: props.housingType.houTyp_code || '',
                houTyp_description: props.housingType.houTyp_description || '',
                houTyp_active: Number(props.housingType.houTyp_active ?? 1)
            }
        }
    })

    watch(() => props.housingType, (newVal) => {
        if (newVal && props.updating) {
            form.value = {
                houTyp_name: newVal.houTyp_name || '',
                houTyp_code: newVal.houTyp_code || '',
                houTyp_description: newVal.houTyp_description || '',
                houTyp_active: Number(newVal.houTyp_active ?? 1)
            }
        }
    }, { deep: true })
</script>


<template>
    <FormSection @submitted="handleSubmit">
        <template #title>
            {{ updating ? 'Actualizar Tipo de Vivienda' : 'Crear Tipo de Vivienda' }}
        </template>

        <template #description>
            {{ updating ? 'Actualizar el Tipo de Vivienda seleccionado' : 'Crear un nuevo Tipo de Vivienda' }}
        </template>

        <template #form>
            <div class="caja--doble">
                <div class="contenedor--input">
                    <TextInput 
                        id="houTyp_code" 
                        v-model="form.houTyp_code" 
                        type="text" 
                        autocomplete="off"
                        class="campo--input">
                    </TextInput>
                    <InputLabel for="houTyp_code" value="Código"></InputLabel>
                    <InputError :message="errors.houTyp_code" class="mt-2" />
                </div>

                <div class="contenedor--input">
                    <TextInput 
                        id="houTyp_name" 
                        v-model="form.houTyp_name" 
                        type="text" 
                        autocomplete="off"
                        class="campo--input">
                    </TextInput>
                    <InputLabel for="houTyp_name" value="Nombre"></InputLabel>
                    <InputError :message="errors.houTyp_name" class="mt-2" />
                </div>

                <div class="contenedor--input">
                    <TextInput 
                        id="houTyp_description" 
                        v-model="form.houTyp_description" 
                        type="text" 
                        autocomplete="off"
                        class="campo--input">
                    </TextInput>
                    <InputLabel for="houTyp_description" value="Descripción"></InputLabel>
                    <InputError :message="errors.houTyp_description" class="mt-2" />
                </div>

                <div v-if="updating" class="contenedor--input">
                    <select 
                        id="houTyp_active" 
                        v-model.number="form.houTyp_active"
                        class="campo--input">
                        <option :value="1">Activo</option>
                        <option :value="0">Inactivo</option>
                    </select>
                    <InputLabel for="houTyp_active" value="Estado"></InputLabel>
                    <InputError :message="errors.houTyp_active" class="mt-2" />
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
