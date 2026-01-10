<script>
    export default {
        name: 'LeadStatusForm'
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
        leadStatus: Object
    })

    const notificationStore = useNotificationStore()

    const form = ref({
        leadSta_code: '',
        leadSta_name: '',
        leadSta_description: '',
        leadSta_active: true
    })

    const errors = ref({})
    const isSubmitting = ref(false)

    // Inicializar formulario con datos del leadStatus si es modo edición
    onMounted(() => {
        if (props.updating && props.leadStatus) {
            form.value = {
                leadSta_code: props.leadStatus.leadSta_code || '',
                leadSta_name: props.leadStatus.leadSta_name || '',
                leadSta_description: props.leadStatus.leadSta_description || '',
                leadSta_active: Number(props.leadStatus.leadSta_active ?? 1)
            }
        }
    })

    // Watcher para reiniciar el formulario si cambian los props
    watch(() => props.leadStatus, (newLeadStatus) => {
        if (props.updating && newLeadStatus) {
            form.value = {
                leadSta_code: newLeadStatus.leadSta_code || '',
                leadSta_name: newLeadStatus.leadSta_name || '',
                leadSta_description: newLeadStatus.leadSta_description || '',
                leadSta_active: Number(newLeadStatus.leadSta_active ?? 1)
            }
            errors.value = {}
        }
    }, { deep: true })

    const validateForm = () => {
        errors.value = {}

        if (!form.value.leadSta_name?.trim()) {
            errors.value.leadSta_name = 'El nombre es requerido'
        }
        if (!form.value.leadSta_code?.trim()) {
            errors.value.leadSta_code = 'El código es requerido'
        }

        return Object.keys(errors.value).length === 0
    }

    const handleSubmit = async () => {
        if (!validateForm()) return

        isSubmitting.value = true
        const formData = new FormData()

        formData.append('leadSta_code', form.value.leadSta_code)
        formData.append('leadSta_name', form.value.leadSta_name)
        formData.append('leadSta_description', form.value.leadSta_description)
        formData.append('leadSta_active', form.value.leadSta_active ? 1 : 0)

        try {
            let response
            if (props.updating) {
                // Para actualización, usar POST con _method=PUT
                formData.append('_method', 'PUT')
                response = await axios.post(
                    route('leadstatus.update', props.leadStatus.leadSta_id),
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
                    route('leadstatus.store'),
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                )
            }

            const message = props.updating ? 'Estado de lead actualizado correctamente' : 'Estado de lead creado correctamente'
            notificationStore.success(message)
            emit('success')
        } catch (e) {
            console.error('Error details:', e.response?.data)
            if (e.response?.data?.errors) {
                errors.value = e.response.data.errors
            } else {
                notificationStore.error(e.response?.data?.message || 'Error al procesar estado de lead')
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
            {{ updating ? 'Actualizar Estado de Lead' : 'Crear Estado de Lead' }}
        </template>

        <template #description>
            {{ updating ? 'Actualizar el Estado de Lead seleccionado' : 'Crear un nuevo Estado de Lead' }}
        </template>

        <template #form>
            <div class="caja--doble">
                <div class="contenedor--input">
                    <TextInput
                        id="leadSta_code"
                        v-model="form.leadSta_code"
                        type="text"
                        autocomplete="off"
                        class="campo--input">
                    </TextInput>
                    <InputLabel for="leadSta_code" value="Código"></InputLabel>
                    <InputError :message="getError('leadSta_code') || $page.props.errors.leadSta_code" class="mt-2" />
                </div>

                <div class="contenedor--input">
                    <TextInput
                        id="leadSta_name"
                        v-model="form.leadSta_name"
                        type="text"
                        autocomplete="off"
                        class="campo--input">
                    </TextInput>
                    <InputLabel for="leadSta_name" value="Nombre"></InputLabel>
                    <InputError :message="getError('leadSta_name') || $page.props.errors.leadSta_name" class="mt-2" />
                </div>

                <div class="contenedor--input">
                    <TextInput
                        id="leadSta_description"
                        v-model="form.leadSta_description"
                        type="text"
                        autocomplete="off"
                        class="campo--input">
                    </TextInput>
                    <InputLabel for="leadSta_description" value="Descripción (Opcional)"></InputLabel>
                    <InputError :message="$page.props.errors.leadSta_description" class="mt-2" />
                </div>

                <div v-if="updating" class="contenedor--input">
                    <select id="leadSta_active" v-model.number="form.leadSta_active" class="campo--input">
                        <option :value="1">Activo</option>
                        <option :value="0">Inactivo</option>
                    </select>
                    <InputLabel for="leadSta_active" value="Estado"></InputLabel>
                    <InputError :message="$page.props.errors.leadSta_active" class="mt-2" />
                </div>
            </div>
        </template>

        <template #actions>
            <PrimaryButton>
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