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

    watch(() => props.developer, (newDeveloper) => {
        if (props.updating && newDeveloper) {
            form.value = {
                devr_commercial_name: newDeveloper.devr_commercial_name || '',
                devr_legal_name: newDeveloper.devr_legal_name || '',
                devr_email_contact: newDeveloper.devr_email_contact || '',
                devr_phone_contact: newDeveloper.devr_phone_contact || '',
                devr_website: newDeveloper.devr_website || '',
                ctry_id: newDeveloper.ctry_id || '',
                user_id: newDeveloper.user_id || '',
                devr_active: Number(newDeveloper.devr_active ?? 1)
            }
            errors.value = {}
        }
    }, { deep: true, immediate: true })
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
            <div class="caja--doble">
                <div class="contenedor--input">
                    <TextInput
                        id="devr_commercial_name"
                        v-model="form.devr_commercial_name"
                        type="text"
                        class="campo--input"
                        placeholder="Ej: TechDev Solutions"
                    />
                    <InputLabel for="devr_commercial_name" value="Nombre Comercial" />
                    <InputError :message="errors.devr_commercial_name" class="mt-2" />
                </div>

                <div class="contenedor--input">
                    <TextInput
                        id="devr_legal_name"
                        v-model="form.devr_legal_name"
                        type="text"
                        class="campo--input"
                        placeholder="Ej: TechDev Solutions S.A."
                    />
                    <InputLabel for="devr_legal_name" value="Nombre Legal" />
                    <InputError :message="errors.devr_legal_name" class="mt-2" />
                </div>

                <div class="contenedor--input">
                    <TextInput
                        id="devr_email_contact"
                        v-model="form.devr_email_contact"
                        type="email"
                        class="campo--input"
                        placeholder="contacto@ejemplo.com"
                    />
                    <InputLabel for="devr_email_contact" value="Email de Contacto" />
                    <InputError :message="errors.devr_email_contact" class="mt-2" />
                </div>

                <div class="contenedor--input">
                    <TextInput
                        id="devr_phone_contact"
                        v-model="form.devr_phone_contact"
                        type="text"
                        class="campo--input"
                        placeholder="+1 (555) 123-4567"
                    />
                    <InputLabel for="devr_phone_contact" value="Teléfono de Contacto" />
                    <InputError :message="errors.devr_phone_contact" class="mt-2" />
                </div>

                <div class="contenedor--input">
                    <TextInput
                        id="devr_website"
                        v-model="form.devr_website"
                        type="url"
                        class="campo--input"
                        placeholder="https://www.ejemplo.com"
                    />
                    <InputLabel for="devr_website" value="Sitio Web (Opcional)" />
                    <InputError :message="errors.devr_website" class="mt-2" />
                </div>

                <div class="contenedor--input">
                    <select id="ctry_id" v-model.number="form.ctry_id" class="campo--input">
                        <option value="">Selecciona un país</option>
                        <option v-for="country in countries" :key="country.ctry_id" :value="country.ctry_id">
                            {{ country.ctry_name }}
                        </option>
                    </select>
                    <InputLabel for="ctry_id" value="País" />
                    <InputError :message="errors.ctry_id" class="mt-2" />
                </div>

                <div class="contenedor--input">
                    <select id="user_id" v-model.number="form.user_id" class="campo--input">
                        <option value="">Selecciona un usuario (Opcional)</option>
                        <option v-for="user in users" :key="user.id" :value="user.id">
                            {{ user.name }} {{ user.lastname }}
                        </option>
                    </select>
                    <InputLabel for="user_id" value="Usuario Responsable" />
                    <InputError :message="errors.user_id" class="mt-2" />
                </div>

                <div v-if="updating" class="contenedor--input">
                    <select id="devr_active" v-model="form.devr_active" class="campo--input">
                        <option :value="1">Activo</option>
                        <option :value="0">Inactivo</option>
                    </select>
                    <InputLabel for="devr_active" value="Estado" />
                    <InputError :message="errors.devr_active" class="mt-2" />
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
