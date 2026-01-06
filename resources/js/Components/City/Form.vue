<script>
export default {
    name: 'CityForm'
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
    city: Object,
    countries: {
        type: Array,
        default: () => []
    }
})

const notificationStore = useNotificationStore()

const form = ref({
    city_name: '',
    city_region: '',
    ctry_id: '',
    city_active: 1
})

const errors = ref({})
const isSubmitting = ref(false)

const validateForm = () => {
    errors.value = {}

    if (!form.value.city_name?.trim()) {
        errors.value.city_name = 'El nombre de la ciudad es requerido'
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

    if (form.value.city_name) formData.append('city_name', form.value.city_name)
    if (form.value.city_region) formData.append('city_region', form.value.city_region)
    if (form.value.ctry_id) formData.append('ctry_id', form.value.ctry_id)
    formData.append('city_active', Number(form.value.city_active ?? 1))

    try {
        if (props.updating) {
            formData.append('_method', 'PUT')
            await axios.post(route('cities.update', props.city.city_id), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            notificationStore.success('Ciudad actualizada exitosamente')
        } else {
            await axios.post(route('cities.store'), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            notificationStore.success('Ciudad creada exitosamente')
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
        console.error('Error submitting city:', error)
        if (error.response?.data?.errors) {
            const rawErrors = error.response.data.errors
            errors.value = Object.keys(rawErrors).reduce((acc, key) => {
                const errorValue = rawErrors[key]
                acc[key] = Array.isArray(errorValue) ? errorValue[0] : errorValue
                return acc
            }, {})
            const errorMessages = Object.values(errors.value).join(', ')
            notificationStore.error(errorMessages)
        } else {
            notificationStore.error(props.updating ? 'Error al actualizar la ciudad' : 'Error al crear la ciudad')
        }
    } finally {
        isSubmitting.value = false
    }
}

onMounted(() => {
    if (props.updating && props.city) {
        form.value = {
            city_name: props.city.city_name || '',
            city_region: props.city.city_region || '',
            ctry_id: props.city.ctry_id || '',
            city_active: Number(props.city.city_active ?? 1)
        }
    }
})

watch(() => props.city, (newVal) => {
    if (newVal && props.updating) {
        form.value = {
            city_name: newVal.city_name || '',
            city_region: newVal.city_region || '',
            ctry_id: newVal.ctry_id || '',
            city_active: Number(newVal.city_active ?? 1)
        }
    }
}, { deep: true })
</script>

<template>
    <FormSection @submit.prevent="handleSubmit">
        <template #title>
            {{ updating ? 'Editar Ciudad' : 'Crear Nueva Ciudad' }}
        </template>

        <template #description>
            {{ updating ? 'Actualiza la información de la ciudad.' : 'Agrega una nueva ciudad.' }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="city_name" value="Nombre de la Ciudad" />
                <TextInput
                    id="city_name"
                    v-model="form.city_name"
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="Ej: Bogotá"
                    @keyup.enter="handleSubmit"
                />
                <InputError :message="errors.city_name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="ctry_id" value="País" />
                <select
                    id="ctry_id"
                    v-model="form.ctry_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                >
                    <option value="" disabled>Selecciona un país</option>
                    <option v-for="country in countries" :key="country.ctry_id" :value="country.ctry_id">
                        {{ country.ctry_name }}
                    </option>
                </select>
                <InputError :message="errors.ctry_id" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="city_region" value="Región (Opcional)" />
                <TextInput
                    id="city_region"
                    v-model="form.city_region"
                    type="text"
                    class="mt-1 block w-full"
                    placeholder="Ej: Cundinamarca"
                />
                <InputError :message="errors.city_region" class="mt-2" />
            </div>

            <div v-if="updating" class="col-span-6 sm:col-span-3">
                <InputLabel for="city_active" value="Estado" />
                <select
                    id="city_active"
                    v-model.number="form.city_active"
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
       