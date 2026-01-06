<script>
    export default {
        name: 'CommercialStatusForm'
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
        commercialStatus: Object
    })

    const notificationStore = useNotificationStore()

    const form = ref({
        commSta_name: '',
        commSta_code: '',
        commSta_description: '',
        commSta_active: 1
    })

    const errors = ref({})
    const isSubmitting = ref(false)

    const validateForm = () => {
        errors.value = {}

        if (!form.value.commSta_name?.trim()) {
            errors.value.commSta_name = 'El nombre es requerido'
        }
        if (!form.value.commSta_code?.trim()) {
            errors.value.commSta_code = 'El código es requerido'
        }

        return Object.keys(errors.value).length === 0
    }

    const handleSubmit = async () => {
        if (!validateForm()) {
            return
        }

        isSubmitting.value = true
        const formData = new FormData()

        if (form.value.commSta_name) formData.append('commSta_name', form.value.commSta_name)
        if (form.value.commSta_code) formData.append('commSta_code', form.value.commSta_code)
        if (form.value.commSta_description) formData.append('commSta_description', form.value.commSta_description)
        formData.append('commSta_active', Number(form.value.commSta_active ?? 1))

        try {
            if (props.updating) {
                formData.append('_method', 'PUT')
                await axios.post(route('commercialstatus.update', props.commercialStatus.commSta_id), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                notificationStore.success('Tipo de proyecto actualizado exitosamente')
            } else {
                await axios.post(route('commercialstatus.store'), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                notificationStore.success('Tipo de proyecto creado exitosamente')
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
            console.error('Error submitting commercial status:', error)
            if (error.response?.data?.errors) {
                errors.value = error.response.data.errors
                const errorMessages = Object.values(errors.value).flat().join(', ')
                notificationStore.error(errorMessages)
            } else {
                notificationStore.error(props.updating ? 'Error al actualizar el tipo de proyecto' : 'Error al crear el tipo de proyecto')
            }
        } finally {
            isSubmitting.value = false
        }
    }

    onMounted(() => {
        if (props.updating && props.commercialStatus) {
            form.value = {
                commSta_name: props.commercialStatus.commSta_name || '',
                commSta_code: props.commercialStatus.commSta_code || '',
                commSta_description: props.commercialStatus.commSta_description || '',
                commSta_active: Number(props.commercialStatus.commSta_active ?? 1)
            }
        }
    })

    watch(() => props.commercialStatus, (newVal) => {
        if (newVal && props.updating) {
            form.value = {
                commSta_name: newVal.commSta_name || '',
                commSta_code: newVal.commSta_code || '',
                commSta_description: newVal.commSta_description || '',
                commSta_active: Number(newVal.commSta_active ?? 1)
            }
        }
    }, { deep: true })
</script>


<template>
    <FormSection @submitted="handleSubmit">
        <template #title>
            {{ updating ? 'Actualizar Tipo de Proyecto' : 'Crear Tipo de Proyecto' }}
        </template>

        <template #description>
            {{ updating ? 'Actualizar el Tipo de Proyecto seleccionado' : 'Crear un nuevo Tipo de Proyecto' }}
        </template>

        <template #form>
            <div class="contenedor--input">
                <TextInput 
                    id="commSta_code" 
                    v-model="form.commSta_code" 
                    type="text" 
                    autocomplete="off"
                    class="campo--input">
                </TextInput>
                <InputLabel for="commSta_code" value="Código"></InputLabel>
                <InputError :message="errors.commSta_code" class="mt-2" />
            </div>

            <div class="contenedor--input">
                <TextInput 
                    id="commSta_name" 
                    v-model="form.commSta_name" 
                    type="text" 
                    autocomplete="off"
                    class="campo--input">
                </TextInput>
                <InputLabel for="commSta_name" value="Nombre"></InputLabel>
                <InputError :message="errors.commSta_name" class="mt-2" />
            </div>

            <div class="contenedor--input">
                <TextInput 
                    id="commSta_description" 
                    v-model="form.commSta_description" 
                    type="text" 
                    autocomplete="off"
                    class="campo--input">
                </TextInput>
                <InputLabel for="commSta_description" value="Descripción"></InputLabel>
                <InputError :message="errors.commSta_description" class="mt-2" />
            </div>

            <div v-if="updating" class="contenedor--input">
                <select 
                    id="commSta_active" 
                    v-model.number="form.commSta_active"
                    class="campo--input">
                    <option :value="1">Activo</option>
                    <option :value="0">Inactivo</option>
                </select>
                <InputLabel for="commSta_active" value="Estado"></InputLabel>
                <InputError :message="errors.commSta_active" class="mt-2" />
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
