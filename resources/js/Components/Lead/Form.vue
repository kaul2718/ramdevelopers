<script>
    export default {
        name: 'LeadForm'
    }
</script>

<script setup>
    import { useNotificationStore } from '@/stores/notificationStore'
    import FormSection from '@/Components/FormSection.vue'
    import InputError from '@/Components/InputError.vue'
    import InputLabel from '@/Components/InputLabel.vue'
    import PrimaryButton from '@/Components/PrimaryButton.vue'
    import TextInput from '@/Components/TextInput.vue'
    import axios from 'axios'
    import { ref, watch, onMounted } from 'vue'

    const emit = defineEmits(['success', 'cancel'])

    const props = defineProps({
        updating: Boolean,
        lead: Object,
        developments: Array,
        countries: Array,
        sources: Array,
        statuses: Array,
        users: Array
    })

    const notificationStore = useNotificationStore()

    const form = ref({
        lead_client_name: '',
        lead_client_email: '',
        lead_client_phone: '',
        lead_language: '',
        ctry_id: '',
        devt_id: '',
        leadSou_id: '',
        leadSta_id: '',
        user_id: '',
        lead_message: ''
    })

    const errors = ref({})
    const isSubmitting = ref(false)

    // Inicializar formulario con datos del lead si es modo edición
    onMounted(() => {
        if (props.updating && props.lead) {
            form.value = {
                lead_client_name: props.lead.lead_client_name || '',
                lead_client_email: props.lead.lead_client_email || '',
                lead_client_phone: props.lead.lead_client_phone || '',
                lead_language: props.lead.lead_language || '',
                ctry_id: String(props.lead.ctry_id) || '',
                devt_id: String(props.lead.devt_id) || '',
                leadSou_id: String(props.lead.leadSou_id) || '',
                leadSta_id: String(props.lead.leadSta_id) || '',
                user_id: String(props.lead.user_id) || '',
                lead_message: props.lead.lead_message || ''
            }
        }
    })

    // Watcher para reiniciar el formulario si cambian los props
    watch(() => props.lead, (newLead) => {
        if (props.updating && newLead) {
            form.value = {
                lead_client_name: newLead.lead_client_name || '',
                lead_client_email: newLead.lead_client_email || '',
                lead_client_phone: newLead.lead_client_phone || '',
                lead_language: newLead.lead_language || '',
                ctry_id: String(newLead.ctry_id) || '',
                devt_id: String(newLead.devt_id) || '',
                leadSou_id: String(newLead.leadSou_id) || '',
                leadSta_id: String(newLead.leadSta_id) || '',
                user_id: String(newLead.user_id) || '',
                lead_message: newLead.lead_message || ''
            }
            errors.value = {}
        }
    }, { deep: true })

    const validateForm = () => {
        errors.value = {}

        if (!form.value.lead_client_name?.trim()) {
            errors.value.lead_client_name = 'El nombre del cliente es requerido'
        }

        return Object.keys(errors.value).length === 0
    }

    const handleSubmit = async () => {
        if (!validateForm()) return

        isSubmitting.value = true
        const formData = new FormData()

        formData.append('lead_client_name', form.value.lead_client_name)
        formData.append('lead_client_email', form.value.lead_client_email)
        formData.append('lead_client_phone', form.value.lead_client_phone)
        formData.append('lead_language', form.value.lead_language)
        formData.append('ctry_id', form.value.ctry_id)
        formData.append('devt_id', form.value.devt_id)
        formData.append('leadSou_id', form.value.leadSou_id)
        formData.append('leadSta_id', form.value.leadSta_id)
        formData.append('user_id', form.value.user_id)
        formData.append('lead_message', form.value.lead_message)

        try {
            let response
            if (props.updating) {
                // Para actualización, usar POST con _method=PUT
                formData.append('_method', 'PUT')
                response = await axios.post(
                    route('lead.update', props.lead.lead_id),
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                )
            } else {
                // Para creación, usar POST
                response = await axios.post(
                    route('lead.store'),
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                )
            }

            const message = props.updating ? 'Lead actualizado correctamente' : 'Lead creado correctamente'
            notificationStore.success(message)
            emit('success')
        } catch (e) {            
            if (e.response?.data?.errors) {
                errors.value = e.response.data.errors
            } else if (e.response?.data?.message) {
                notificationStore.error(e.response.data.message)
            } else {
                notificationStore.error(e.message || 'Error al procesar lead')
            }
        } finally {
            isSubmitting.value = false
        }
    }

    const getError = (field) => {
        const error = errors.value[field]
        if (!error) return null
        return Array.isArray(error) ? error[0] : error
    }
</script>

<template>
    <FormSection @submitted="handleSubmit">
        <template #title>
            {{ updating ? 'Actualizar Lead' : 'Crear Nuevo Lead' }}
        </template>

        <template #description>
            {{ updating ? 'Actualizar la información del lead seleccionado' : 'Crear un nuevo lead en el sistema' }}
        </template>

        <template #form>
            <div class="caja--doble">
                <!-- Nombre del Cliente -->
                <div class="contenedor--input">
                    <TextInput
                        id="lead_client_name"
                        v-model="form.lead_client_name"
                        type="text"
                        autocomplete="off"
                        placeholder=" "
                        class="campo--input"
                    />
                    <InputLabel for="lead_client_name" value="Nombre del Cliente *"></InputLabel>
                    <InputError :message="getError('lead_client_name')" class="mt-2" />
                </div>

                <!-- Email -->
                <div class="contenedor--input">
                    <TextInput
                        id="lead_client_email"
                        v-model="form.lead_client_email"
                        type="email"
                        autocomplete="off"
                        placeholder=" "
                        class="campo--input"
                    />
                    <InputLabel for="lead_client_email" value="Email"></InputLabel>
                    <InputError :message="getError('lead_client_email')" class="mt-2" />
                </div>

                <!-- Teléfono -->
                <div class="contenedor--input">
                    <TextInput
                        id="lead_client_phone"
                        v-model="form.lead_client_phone"
                        type="tel"
                        autocomplete="off"
                        placeholder=" "
                        class="campo--input"
                    />
                    <InputLabel for="lead_client_phone" value="Teléfono"></InputLabel>
                    <InputError :message="getError('lead_client_phone')" class="mt-2" />
                </div>

                <!-- Idioma -->
                <div class="contenedor--input">
                    <select
                        id="lead_language"
                        v-model="form.lead_language"
                        placeholder=" "
                        class="campo--input"
                    >
                        <option value=""></option>
                        <option value="Español">Español</option>
                        <option value="Inglés">Inglés</option>
                        <option value="Francés">Francés</option>
                    </select>
                    <InputLabel for="lead_language" value="Idiomas (Opcional)"></InputLabel>
                    <InputError :message="getError('lead_language')" class="mt-2" />
                </div>

                <!-- País -->
                <div class="contenedor--input">
                    <select
                        id="ctry_id"
                        v-model="form.ctry_id"
                        placeholder=" "
                        class="campo--input"
                    >
                        <option value=""></option>
                        <option v-for="country in countries" :key="country.ctry_id" :value="String(country.ctry_id)">
                            {{ country.ctry_name }}
                        </option>
                    </select>
                    <InputLabel for="ctry_id" value="País"></InputLabel>
                    <InputError :message="getError('ctry_id')" class="mt-2" />
                </div>

                <!-- Desarrollo -->
                <div class="contenedor--input">
                    <select
                        id="devt_id"
                        v-model="form.devt_id"
                        placeholder=" "
                        class="campo--input"
                    >
                        <option value=""></option>
                        <option v-for="dev in developments" :key="dev.devt_id" :value="String(dev.devt_id)">
                            {{ dev.devt_title }}
                        </option>
                    </select>
                    <InputLabel for="devt_id" value="Desarrollo"></InputLabel>
                    <InputError :message="getError('devt_id')" class="mt-2" />
                </div>

                <div class="caja--doble">
                    <!-- Fuente del Lead -->
                    <div class="contenedor--input">
                        <select
                            id="leadSou_id"
                            v-model="form.leadSou_id"
                            placeholder=" "
                            class="campo--input"
                        >
                            <option value=""></option>
                            <option v-for="source in sources" :key="source.leadSou_id" :value="String(source.leadSou_id)">
                                {{ source.leadSou_name }}
                            </option>
                        </select>
                        <InputLabel for="leadSou_id" value="Origen"></InputLabel>
                        <InputError :message="getError('leadSou_id')" class="mt-2" />
                    </div>

                    <!-- Estado del Lead -->
                    <div class="contenedor--input">
                        <select
                            id="leadSta_id"
                            v-model="form.leadSta_id"
                            placeholder=" "
                            class="campo--input"
                        >
                            <option value=""></option>
                            <option v-for="status in statuses" :key="status.leadSta_id" :value="String(status.leadSta_id)">
                                {{ status.leadSta_name }}
                            </option>
                        </select>
                        <InputLabel for="leadSta_id" value="Estado"></InputLabel>
                        <InputError :message="getError('leadSta_id')" class="mt-2" />
                    </div>
                </div>

                <!-- Usuario Responsable -->
                <div class="contenedor--input">
                    <select
                        id="user_id"
                        v-model="form.user_id"
                        placeholder=" "
                        class="campo--input"
                    >
                        <option value=""></option>
                        <option v-for="user in users" :key="user.id" :value="String(user.id)">
                            {{ user.name }}
                        </option>
                    </select>
                    <InputLabel for="user_id" value="Usuario Responsable"></InputLabel>
                    <InputError :message="getError('user_id')" class="mt-2" />
                </div>

                <!-- Mensaje -->
                <div class="contenedor--input" style="grid-column: 1 / -1;">
                    <TextInput
                        id="lead_message"
                        v-model="form.lead_message"
                        rows="5"
                        placeholder=" "
                        class="campo--input"
                    ></TextInput>
                    <InputLabel for="lead_message" value="Mensaje"></InputLabel>
                    <InputError :message="getError('lead_message')" class="mt-2" />
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
