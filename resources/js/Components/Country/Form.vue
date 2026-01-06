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
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="ctry_name" value="Nombre del País" />
                <TextInput
                    id="ctry_name"
                    v-model="form.ctry_name"
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="Ej: Colombia"
                    @keyup.enter="handleSubmit"
                />
                <InputError :message="errors.ctry_name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="ctry_code" value="Código (ISO)" />
                <TextInput
                    id="ctry_code"
                    v-model="form.ctry_code"
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="Ej: CO"
                    maxlength="5"
                    @keyup.enter="handleSubmit"
                />
                <InputError :message="errors.ctry_code" class="mt-2" />
            </div>

            <div v-if="updating" class="col-span-6 sm:col-span-3">
                <InputLabel for="ctry_active" value="Estado" />
                <select
                    id="ctry_active"
                    v-model.number="form.ctry_active"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                >
                    <option :value="1">Activo</option>
                    <option :value="0">Inactivo</option>
                </select>
            </div>
        </template>

        <template #actions>
            <PrimaryButton :disabled="isSubmitting">
                {{ updating ? 'Actualizar' : 'Crear' }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>