<script>
export default {
    name: 'DocumentTypeForm'
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
    documentType: Object
})

const notificationStore = useNotificationStore()

const form = ref({
    docTyp_name: '',
    docTyp_code: '',
    docTyp_description: '',
    docTyp_isInternal: 0,
    docTyp_active: 1
})

const errors = ref({})
const isSubmitting = ref(false)

const validateForm = () => {
    errors.value = {}

    if (!form.value.docTyp_name?.trim()) {
        errors.value.docTyp_name = 'El nombre del tipo es requerido'
    }
    if (!form.value.docTyp_code?.trim()) {
        errors.value.docTyp_code = 'El código es requerido'
    }

    return Object.keys(errors.value).length === 0
}

const handleSubmit = async () => {
    if (!validateForm()) {
        return
    }

    isSubmitting.value = true
    const formData = new FormData()

    if (form.value.docTyp_name) formData.append('docTyp_name', form.value.docTyp_name)
    if (form.value.docTyp_code) formData.append('docTyp_code', form.value.docTyp_code)
    if (form.value.docTyp_description) formData.append('docTyp_description', form.value.docTyp_description)
    formData.append('docTyp_isInternal', Number(form.value.docTyp_isInternal ?? 0))
    formData.append('docTyp_active', Number(form.value.docTyp_active ?? 1))

    try {
        if (props.updating) {
            formData.append('_method', 'PUT')
            await axios.post(route('documenttype.update', props.documentType.docTyp_id), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            notificationStore.success('Tipo de documento actualizado exitosamente')
        } else {
            await axios.post(route('documenttype.store'), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            notificationStore.success('Tipo de documento creado exitosamente')
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
        console.error('Error submitting document type:', error)
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors
            const errorMessages = Object.values(errors.value).flat().join(', ')
            notificationStore.error(errorMessages)
        } else {
            notificationStore.error(props.updating ? 'Error al actualizar el tipo de documento' : 'Error al crear el tipo de documento')
        }
    } finally {
        isSubmitting.value = false
    }
}

onMounted(() => {
    if (props.updating && props.documentType) {
        form.value = {
            docTyp_name: props.documentType.docTyp_name || '',
            docTyp_code: props.documentType.docTyp_code || '',
            docTyp_description: props.documentType.docTyp_description || '',
            docTyp_isInternal: Number(props.documentType.docTyp_isInternal ?? 0),
            docTyp_active: Number(props.documentType.docTyp_active ?? 1)
        }
    }
})

watch(() => props.documentType, (newVal) => {
    if (newVal && props.updating) {
        form.value = {
            docTyp_name: newVal.docTyp_name || '',
            docTyp_code: newVal.docTyp_code || '',
            docTyp_description: newVal.docTyp_description || '',
            docTyp_isInternal: Number(newVal.docTyp_isInternal ?? 0),
            docTyp_active: Number(newVal.docTyp_active ?? 1)
        }
    }
}, { deep: true })
</script>


<template>
    <FormSection @submitted="handleSubmit">
        <template #title>
            {{ updating ? 'Actualizar Tipo de Documento' : 'Crear Tipo de Documento' }}
        </template>

        <template #description>
            {{ updating ? 'Actualizar el Tipo de Documento seleccionado' : 'Crear un nuevo Tipo de Documento' }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="docTyp_code" value="Código"></InputLabel>
                <TextInput 
                    id="docTyp_code" 
                    v-model="form.docTyp_code" 
                    type="text" 
                    autocomplete="off"
                    class="mt-1 block w-full">
                </TextInput>
                <InputError :message="errors.docTyp_code" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="docTyp_name" value="Nombre"></InputLabel>
                <TextInput 
                    id="docTyp_name" 
                    v-model="form.docTyp_name" 
                    type="text" 
                    autocomplete="off"
                    class="mt-1 block w-full">
                </TextInput>
                <InputError :message="errors.docTyp_name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="docTyp_description" value="Descripción"></InputLabel>
                <TextInput 
                    id="docTyp_description" 
                    v-model="form.docTyp_description" 
                    type="text" 
                    autocomplete="off"
                    class="mt-1 block w-full">
                </TextInput>
                <InputError :message="errors.docTyp_description" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="docTyp_isInternal" value="¿Es Interno?"></InputLabel>
                <select 
                    id="docTyp_isInternal" 
                    v-model.number="form.docTyp_isInternal"
                    class="mt-1 block w-full border-gray-300 rounded-md">
                    <option :value="1">Sí</option>
                    <option :value="0">No</option>
                </select>
                <InputError :message="errors.docTyp_isInternal" class="mt-2" />
            </div>

            <div v-if="updating" class="col-span-6 sm:col-span-6">
                <InputLabel for="docTyp_active" value="Estado"></InputLabel>
                <select 
                    id="docTyp_active" 
                    v-model.number="form.docTyp_active"
                    class="mt-1 block w-full border-gray-300 rounded-md">
                    <option :value="1">Activo</option>
                    <option :value="0">Inactivo</option>
                </select>
                <InputError :message="errors.docTyp_active" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <PrimaryButton :disabled="isSubmitting">
                {{ isSubmitting ? 'Guardando...' : (updating ? 'Actualizar' : 'Crear') }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>