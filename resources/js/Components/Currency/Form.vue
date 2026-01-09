<script setup>
import FormSection from '@/Components/FormSection.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { ref, watch, onMounted } from 'vue'
import axios from 'axios'
import { useNotificationStore } from '@/stores/notificationStore'

const notificationStore = useNotificationStore()

const props = defineProps({
    updating: {
        type: Boolean,
        default: false
    },
    form: {
        type: Object,
        default: null
    },
    currency: {
        type: Object,
        default: null
    },
    showSubmitButton: {
        type: Boolean,
        default: true
    }
})

const emit = defineEmits(['submit', 'saved', 'cancel'])

const form = ref({
    curr_code: '',
    curr_symbol: '',
    curr_name: '',
    curr_symbol_first: false,
    curr_active: true
})

const errors = ref({})
const isLoading = ref(false)

// Inicializar formulario cuando se monta el componente
onMounted(() => {
    if (props.form) {
        Object.assign(form.value, props.form)
    } else if (props.updating && props.currency) {
        form.value = {
            curr_code: props.currency.curr_code || '',
            curr_symbol: props.currency.curr_symbol || '',
            curr_name: props.currency.curr_name || '',
            curr_symbol_first: props.currency.curr_symbol_first || false,
            curr_active: props.currency.curr_active !== undefined ? props.currency.curr_active : true
        }
    }
})


// Observar cambios en el prop currency para actualizar el formulario
watch(() => props.currency, (newCurrency) => {
    if (props.updating && newCurrency) {
        form.value = {
            curr_code: newCurrency.curr_code || '',
            curr_symbol: newCurrency.curr_symbol || '',
            curr_name: newCurrency.curr_name || '',
            curr_symbol_first: newCurrency.curr_symbol_first || false,
            curr_active: newCurrency.curr_active !== undefined ? newCurrency.curr_active : true
        }
        errors.value = {}
    }
}, { deep: true })

const validateForm = () => {
    errors.value = {}
    
    const requiredFields = {
        curr_code: 'Código de moneda',
        curr_symbol: 'Símbolo',
        curr_name: 'Nombre de la moneda'
    }

    for (const [field, label] of Object.entries(requiredFields)) {
        if (!form.value[field] || (typeof form.value[field] === 'string' && form.value[field].trim() === '')) {
            errors.value[field] = `${label} es requerido`
        }
    }

    if (form.value.curr_code && form.value.curr_code.length > 3) {
        errors.value.curr_code = 'El código no puede exceder 3 caracteres'
    }

    return Object.keys(errors.value).length === 0
}

const handleSubmit = async () => {
    if (!validateForm()) {
        return
    }

    isLoading.value = true

    try {
        const endpoint = props.updating 
            ? route('currency.update', props.currency.curr_id)
            : route('currency.store')
        
        const data = { ...form.value }
        
        // Para actualización, agregar _method PUT
        if (props.updating) {
            data._method = 'PUT'
        }

        const response = await axios.post(endpoint, data)

        notificationStore.success(
            props.updating 
                ? 'Moneda actualizada correctamente'
                : 'Moneda creada correctamente'
        )

        const currency = response.data.currency || response.data
        emit('saved', currency)
    } catch (error) {
        const errorMessage = typeof error.response?.data?.message === 'string'
            ? error.response.data.message
            : Array.isArray(error.response?.data?.errors)
                ? error.response.data.errors.join(', ')
                : 'Error al guardar la moneda'

        notificationStore.error(errorMessage)
    } finally {
        isLoading.value = false
    }
}
</script>

<template>
    <FormSection @submitted="handleSubmit">
        <template #title>
            {{ updating ? 'Actualizar Moneda' : 'Crear Moneda' }}
        </template>

        <template #description>
            {{ updating ? 'Actualizar la información de la moneda' : 'Crear una nueva moneda' }}
        </template>

        <template #form>
            <!-- Código -->
            <div class="contenedor--input">
                <TextInput
                    id="curr_code"
                    v-model="form.curr_code"
                    type="text"
                    class="campo--input"
                    placeholder="Ej: USD"
                    maxlength="3"
                />
                <InputLabel for="curr_code" value="Código de Moneda" />
                <InputError :message="errors.curr_code" class="mt-2" />
            </div>

            <!-- Símbolo -->
            <div class="contenedor--input">
                <TextInput
                    id="curr_symbol"
                    v-model="form.curr_symbol"
                    type="text"
                    class="campo--input"
                    placeholder="Ej: $"
                />
                <InputLabel for="curr_symbol" value="Símbolo" />
                <InputError :message="errors.curr_symbol" class="mt-2" />
            </div>

            <!-- Nombre -->
            <div class="contenedor--input">
                <TextInput
                    id="curr_name"
                    v-model="form.curr_name"
                    type="text"
                    class="campo--input"
                    placeholder="Ej: Dólar estadounidense"
                />
                <InputLabel for="curr_name" value="Nombre de la Moneda" />
                <InputError :message="errors.curr_name" class="mt-2" />
            </div>

            <!-- Símbolo Primero -->
            <div class="contenedor--input">
                <select id="curr_symbol_first" v-model.boolean="form.curr_symbol_first" class="campo--input">
                    <option :value="false">Símbolo al final (100€)</option>
                    <option :value="true">Símbolo al inicio (€100)</option>
                </select>
                <InputLabel for="curr_symbol_first" value="Posición del Símbolo" />
                <InputError :message="errors.curr_symbol_first" class="mt-2" />
            </div>

            <!-- Activo - Solo en actualización -->
            <div v-if="updating" class="contenedor--input">
                <select id="curr_active" v-model.boolean="form.curr_active" class="campo--input">
                    <option :value="false">Inactivo</option>
                    <option :value="true">Activo</option>
                </select>
                <InputLabel for="curr_active" value="Estado" />
                <InputError :message="errors.curr_active" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <PrimaryButton v-if="showSubmitButton" :disabled="isLoading">
                <template #texto--boton>{{ isLoading ? 'Guardando...' : (updating ? 'Actualizar' : 'Crear') }}</template>
                <template #icono--boton>
                    <path v-if="!updating" stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    <path v-if="updating" stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </template>
            </PrimaryButton>
        </template>
    </FormSection>
</template>
