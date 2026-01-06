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
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="commSta_code" value="Código"></InputLabel>
                <TextInput 
                    id="commSta_code" 
                    v-model="form.commSta_code" 
                    type="text" 
                    autocomplete="off"
                    class="mt-1 block w-full">
                </TextInput>
                <InputError :message="errors.commSta_code" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="commSta_name" value="Nombre"></InputLabel>
                <TextInput 
                    id="commSta_name" 
                    v-model="form.commSta_name" 
                    type="text" 
                    autocomplete="off"
                    class="mt-1 block w-full">
                </TextInput>
                <InputError :message="errors.commSta_name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="commSta_description" value="Descripción"></InputLabel>
                <TextInput 
                    id="commSta_description" 
                    v-model="form.commSta_description" 
                    type="text" 
                    autocomplete="off"
                    class="mt-1 block w-full">
                </TextInput>
                <InputError :message="errors.commSta_description" class="mt-2" />
            </div>

            <div v-if="updating" class="col-span-6 sm:col-span-6">
                <InputLabel for="commSta_active" value="Estado"></InputLabel>
                <select 
                    id="commSta_active" 
                    v-model.number="form.commSta_active"
                    class="mt-1 block w-full border-gray-300 rounded-md">
                    <option :value="1">Activo</option>
                    <option :value="0">Inactivo</option>
                </select>
                <InputError :message="errors.commSta_active" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <PrimaryButton :disabled="isSubmitting">
                {{ isSubmitting ? 'Guardando...' : (updating ? 'Actualizar' : 'Crear') }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>
