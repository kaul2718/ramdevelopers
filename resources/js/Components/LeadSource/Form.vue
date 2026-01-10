<script>
    export default {
        name: 'LeadSourceForm'
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
        leadSource: Object
    })

    const notificationStore = useNotificationStore()

    const form = ref({
        leadSou_code: '',
        leadSou_name: '',
        leadSou_description: '',
        leadSou_active: true
    })

    const errors = ref({})
    const isSubmitting = ref(false)

    // Inicializar formulario con datos del leadSource si es modo edición
    onMounted(() => {
        if (props.updating && props.leadSource) {
            form.value = {
                leadSou_code: props.leadSource.leadSou_code || '',
                leadSou_name: props.leadSource.leadSou_name || '',
                leadSou_description: props.leadSource.leadSou_description || '',
                leadSou_active: Number(props.leadSource.leadSou_active ?? 1)
            }
        }
    })

    // Watcher para reiniciar el formulario si cambian los props
    watch(() => props.leadSource, (newLeadSource) => {
        if (props.updating && newLeadSource) {
            form.value = {
                leadSou_code: newLeadSource.leadSou_code || '',
                leadSou_name: newLeadSource.leadSou_name || '',
                leadSou_description: newLeadSource.leadSou_description || '',
                leadSou_active: Number(newLeadSource.leadSou_active ?? 1)
            }
            errors.value = {}
        }
    }, { deep: true })

    const validateForm = () => {
        errors.value = {}

        if (!form.value.leadSou_name?.trim()) {
            errors.value.leadSou_name = 'El nombre es requerido'
        }
        if (!form.value.leadSou_code?.trim()) {
            errors.value.leadSou_code = 'El código es requerido'
        }

        return Object.keys(errors.value).length === 0
    }

    const handleSubmit = async () => {
        if (!validateForm()) return

        isSubmitting.value = true
        const formData = new FormData()

        formData.append('leadSou_code', form.value.leadSou_code)
        formData.append('leadSou_name', form.value.leadSou_name)
        formData.append('leadSou_description', form.value.leadSou_description)
        formData.append('leadSou_active', form.value.leadSou_active ? 1 : 0)

        try {
            let response
            if (props.updating) {
                // Para actualización, usar POST con _method=PUT
                formData.append('_method', 'PUT')
                response = await axios.post(
                    route('leadsources.update', props.leadSource.leadSou_id),
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
                    route('leadsources.store'),
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                )
            }

            const message = props.updating ? 'Fuente de lead actualizada correctamente' : 'Fuente de lead creada correctamente'
            notificationStore.success(message)
            emit('success')
        } catch (e) {
            console.error('Error details:', e.response?.data)
            if (e.response?.data?.errors) {
                errors.value = e.response.data.errors
            } else {
                notificationStore.error(e.response?.data?.message || 'Error al procesar fuente de lead')
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
            {{ updating ? 'Actualizar Fuente de Lead' : 'Crear Fuente de Lead' }}
        </template>

        <template #description>
            {{ updating ? 'Actualizar la Fuente de Lead seleccionada' : 'Crear una nueva Fuente de Lead' }}
        </template>

        <template #form>
            <div class="caja--doble">
                <div class="contenedor--input">
                    <TextInput
                        id="leadSou_code"
                        v-model="form.leadSou_code"
                        type="text"
                        autocomplete="off"
                        class="campo--input">
                    </TextInput>
                    <InputLabel for="leadSou_code" value="Código"></InputLabel>
                    <InputError :message="getError('leadSou_code') || $page.props.errors.leadSou_code" class="mt-2" />
                </div>

                <div class="contenedor--input">
                    <TextInput
                        id="leadSou_name"
                        v-model="form.leadSou_name"
                        type="text"
                        autocomplete="off"
                        class="campo--input">
                    </TextInput>
                    <InputLabel for="leadSou_name" value="Nombre"></InputLabel>
                    <InputError :message="getError('leadSou_name') || $page.props.errors.leadSou_name" class="mt-2" />
                </div>

                <div class="contenedor--input">
                    <TextInput
                        id="leadSou_description"
                        v-model="form.leadSou_description"
                        type="text"
                        autocomplete="off"
                        class="campo--input">
                    </TextInput>
                    <InputLabel for="leadSou_description" value="Descripción (Opcional)"></InputLabel>
                    <InputError :message="$page.props.errors.leadSou_description" class="mt-2" />
                </div>

                <div v-if="updating" class="contenedor--input">
                    <select id="leadSou_active" v-model.number="form.leadSou_active" class="campo--input">
                        <option :value="1">Activo</option>
                        <option :value="0">Inactivo</option>
                    </select>
                    <InputLabel for="leadSou_active" value="Estado"></InputLabel>
                    <InputError :message="$page.props.errors.leadSou_active" class="mt-2" />
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
