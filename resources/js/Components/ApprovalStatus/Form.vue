<script>
export default {
    name: 'ApprovalStatusForm'
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
    approvalStatus: Object
})

const notificationStore = useNotificationStore()

const form = ref({
    apvSta_code: '',
    apvSta_name: '',
    apvSta_description: '',
    apvSta_active: true
})

const errors = ref({})
const isSubmitting = ref(false)

// Inicializar formulario con datos del approvalStatus si es modo edición
onMounted(() => {
    if (props.updating && props.approvalStatus) {
        form.value = {
            apvSta_code: props.approvalStatus.apvSta_code || '',
            apvSta_name: props.approvalStatus.apvSta_name || '',
            apvSta_description: props.approvalStatus.apvSta_description || '',
            apvSta_active: Number(props.approvalStatus.apvSta_active ?? 1)
        }
    }
})

// Watcher para reiniciar el formulario si cambian los props
watch(() => props.approvalStatus, (newApprovalStatus) => {
    if (props.updating && newApprovalStatus) {
        form.value = {
            apvSta_code: newApprovalStatus.apvSta_code || '',
            apvSta_name: newApprovalStatus.apvSta_name || '',
            apvSta_description: newApprovalStatus.apvSta_description || '',
            apvSta_active: Number(newApprovalStatus.apvSta_active ?? 1)
        }
        errors.value = {}
    }
}, { deep: true })

const validateForm = () => {
    errors.value = {}

    if (!form.value.apvSta_name?.trim()) {
        errors.value.apvSta_name = 'El nombre es requerido'
    }
    if (!form.value.apvSta_code?.trim()) {
        errors.value.apvSta_code = 'El código es requerido'
    }

    return Object.keys(errors.value).length === 0
}

const handleSubmit = async () => {
    if (!validateForm()) return

    isSubmitting.value = true
    const formData = new FormData()

    formData.append('apvSta_code', form.value.apvSta_code)
    formData.append('apvSta_name', form.value.apvSta_name)
    formData.append('apvSta_description', form.value.apvSta_description)
    formData.append('apvSta_active', form.value.apvSta_active ? 1 : 0)

    try {
        let response
        if (props.updating) {
            // Para actualización, usar POST con _method=PUT
            formData.append('_method', 'PUT')
            response = await axios.post(
                route('approvalstatus.update', props.approvalStatus.apvSta_id),
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
                route('approvalstatus.store'),
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            )
        }

        const message = props.updating ? 'Estado de aprobación actualizado correctamente' : 'Estado de aprobación creado correctamente'
        notificationStore.success(message)
        
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
    } catch (e) {
        console.error('Error details:', e.response?.data)
        if (e.response?.data?.errors) {
            errors.value = e.response.data.errors
        } else {
            notificationStore.error(e.response?.data?.message || 'Error al procesar estado de aprobación')
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
            {{ updating ? 'Actualizar Estado de Aprobación' : 'Crear Estado de Aprobación' }}
        </template>

        <template #description>
            {{ updating ? 'Actualizar el Estado de Aprobación seleccionado' : 'Crear un nuevo Estado de Aprobación' }}
        </template>

        <template #form>
            <div class="contenedor--input">
                <TextInput
                    id="apvSta_code"
                    v-model="form.apvSta_code"
                    type="text"
                    autocomplete="off"
                    class="campo--input">
                </TextInput>
                <InputLabel for="apvSta_code" value="Código"></InputLabel>
                <InputError :message="getError('apvSta_code')" class="mt-2" />
            </div>

            <div class="contenedor--input">
                <TextInput
                    id="apvSta_name"
                    v-model="form.apvSta_name"
                    type="text"
                    autocomplete="off"
                    class="campo--input">
                </TextInput>
                <InputLabel for="apvSta_name" value="Nombre"></InputLabel>
                <InputError :message="getError('apvSta_name')" class="mt-2" />
            </div>

            <div class="contenedor--input">
                <TextInput
                    id="apvSta_description"
                    v-model="form.apvSta_description"
                    type="text"
                    autocomplete="off"
                    class="campo--input">
                </TextInput>
                <InputLabel for="apvSta_description" value="Descripción (Opcional)"></InputLabel>
                <InputError :message="getError('apvSta_description')" class="mt-2" />
            </div>

            <div v-if="updating" class="contenedor--input">
                <select id="apvSta_active" v-model.number="form.apvSta_active" class="campo--input">
                    <option :value="1">Activo</option>
                    <option :value="0">Inactivo</option>
                </select>
                <InputLabel for="apvSta_active" value="Estado"></InputLabel>
                <InputError :message="getError('apvSta_active')" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <PrimaryButton>
                <template #texto--boton>{{ updating ? 'Actualizar' : 'Crear' }}</template>
                <template #icono--boton>
                    <path v-if="!updating" stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    <path v-if="updating" stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </template>
            </PrimaryButton>
        </template>
    </FormSection>
</template>