<script>
export default {
    name: 'DeveloperForm'
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
    developer: Object,
    countries: {
        type: Array,
        default: () => []
    },
    users: {
        type: Array,
        default: () => []
    }
})

const notificationStore = useNotificationStore()

const form = ref({
    devr_commercial_name: '',
    devr_legal_name: '',
    devr_email_contact: '',
    devr_phone_contact: '',
    devr_website: '',
    ctry_id: '',
    user_id: '',
    devr_active: 1
})

const errors = ref({})
const isSubmitting = ref(false)

const validateForm = () => {
    errors.value = {}

    if (!form.value.devr_commercial_name?.trim()) {
        errors.value.devr_commercial_name = 'El nombre comercial es requerido'
    }
    if (!form.value.devr_legal_name?.trim()) {
        errors.value.devr_legal_name = 'El nombre legal es requerido'
    }
    if (!form.value.devr_email_contact?.trim()) {
        errors.value.devr_email_contact = 'El email es requerido'
    }
    if (!form.value.ctry_id) {
        errors.value.ctry_id = 'Debes seleccionar un país'
    }

    return Object.keys(errors.value).length === 0
}

const handleSubmit = async () => {
    if (!validateForm()) {
        return
    }

    isSubmitting.value = true
    const formData = new FormData()

    if (form.value.devr_commercial_name) formData.append('devr_commercial_name', form.value.devr_commercial_name)
    if (form.value.devr_legal_name) formData.append('devr_legal_name', form.value.devr_legal_name)
    if (form.value.devr_email_contact) formData.append('devr_email_contact', form.value.devr_email_contact)
    if (form.value.devr_phone_contact) formData.append('devr_phone_contact', form.value.devr_phone_contact)
    if (form.value.devr_website) formData.append('devr_website', form.value.devr_website)
    if (form.value.ctry_id) formData.append('ctry_id', form.value.ctry_id)
    if (form.value.user_id) formData.append('user_id', form.value.user_id)
    formData.append('devr_active', Number(form.value.devr_active ?? 1))

    try {
        if (props.updating) {
            formData.append('_method', 'PUT')
            await axios.post(route('developers.update', props.developer.devr_id), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            notificationStore.success('Desarrollador actualizado exitosamente')
        } else {
            await axios.post(route('developers.store'), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            notificationStore.success('Desarrollador creado exitosamente')
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
        console.error('Error submitting developer:', error)
        if (error.response?.data?.errors) {
            const rawErrors = error.response.data.errors
            // Convertir arrays a strings
            errors.value = Object.keys(rawErrors).reduce((acc, key) => {
                const errorValue = rawErrors[key]
                acc[key] = Array.isArray(errorValue) ? errorValue[0] : errorValue
                return acc
            }, {})
            const errorMessages = Object.values(errors.value).join(', ')
            notificationStore.error(errorMessages)
        } else {
            notificationStore.error(props.updating ? 'Error al actualizar el desarrollador' : 'Error al crear el desarrollador')
        }
    } finally {
        isSubmitting.value = false
    }
}

onMounted(() => {
    if (props.updating && props.developer) {
        form.value = {
            devr_commercial_name: props.developer.devr_commercial_name || '',
            devr_legal_name: props.developer.devr_legal_name || '',
            devr_email_contact: props.developer.devr_email_contact || '',
            devr_phone_contact: props.developer.devr_phone_contact || '',
            devr_website: props.developer.devr_website || '',
            ctry_id: props.developer.ctry_id || '',
            user_id: props.developer.user_id || '',
            devr_active: Number(props.developer.devr_active ?? 1)
        }
    }
})
</script>

<template>
    <FormSection @submitted="handleSubmit">
        <template #title>
            {{ updating ? 'Actualizar Desarrollador' : 'Crear Desarrollador' }}
        </template>

        <template #description>
            {{ updating ? 'Actualizar la información del desarrollador seleccionado' : 'Crear un nuevo desarrollador' }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="devr_commercial_name" value="Nombre Comercial" />
                <TextInput
                    id="devr_commercial_name"
                    v-model="form.devr_commercial_name"
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="Ej: TechDev Solutions"
                />
                <InputError :message="errors.devr_commercial_name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="devr_legal_name" value="Nombre Legal" />
                <TextInput
                    id="devr_legal_name"
                    v-model="form.devr_legal_name"
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="Ej: TechDev Solutions S.A."
                />
                <InputError :message="errors.devr_legal_name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="devr_email_contact" value="Email de Contacto" />
                <TextInput
                    id="devr_email_contact"
                    v-model="form.devr_email_contact"
                    type="email"
                    class="mt-1 block w-full"
                    placeholder="contacto@ejemplo.com"
                />
                <InputError :message="errors.devr_email_contact" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="devr_phone_contact" value="Teléfono de Contacto" />
                <TextInput
                    id="devr_phone_contact"
                    v-model="form.devr_phone_contact"
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="+1 (555) 123-4567"
                />
                <InputError :message="errors.devr_phone_contact" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="devr_website" value="Sitio Web (Opcional)" />
                <TextInput
                    id="devr_website"
                    v-model="form.devr_website"
                    type="url"
                    class="mt-1 block w-full"
                    placeholder="https://www.ejemplo.com"
                />
                <InputError :message="errors.devr_website" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="ctry_id" value="País" />
                <select
                    id="ctry_id"
                    v-model="form.ctry_id"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                >
                    <option value="">Selecciona un país</option>
                    <option v-for="country in countries" :key="country.ctry_id" :value="country.ctry_id">
                        {{ country.ctry_name }}
                    </option>
                </select>
                <InputError :message="errors.ctry_id" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="user_id" value="Usuario Asociado" />
                <select
                    id="user_id"
                    v-model="form.user_id"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                >
                    <option value="">Selecciona un usuario (Opcional)</option>
                    <option v-for="user in users" :key="user.id" :value="user.id">
                        {{ user.name }} {{ user.lastname }}
                    </option>
                </select>
                <InputError :message="errors.user_id" class="mt-2" />
            </div>

            <div v-if="updating" class="col-span-6 sm:col-span-6">
                <InputLabel for="devr_active" value="Estado" />
                <select
                    id="devr_active"
                    v-model="form.devr_active"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                >
                    <option :value="1">Activo</option>
                    <option :value="0">Inactivo</option>
                </select>
                <InputError :message="errors.devr_active" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <PrimaryButton :disabled="isSubmitting">
                {{ isSubmitting ? 'Guardando...' : (updating ? 'Actualizar' : 'Crear') }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>
