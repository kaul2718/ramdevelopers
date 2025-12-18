<!-- filepath: resources/js/Components/Development/Form.vue -->
<script>
export default {
    name: 'DevelopmentForm'
}
</script>

<script setup>
import FormSection from '@/Components/FormSection.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { computed } from 'vue'

const props = defineProps({
    form: {
        type: Object,
        required: true
    },
    developers: {
        type: Array,
        required: true
    },
    countries: {
        type: Array,
        required: true
    },
    cities: {
        type: Array,
        required: true
    },
    approvalStatuses: {
        type: Array,
        required: true
    },
    businessStates: {
        type: Array,
        required: true
    },
    commercialStatuses: {
        type: Array,
        required: true
    },
    updating: {
        type: Boolean,
        required: false,
        default: false
    }
})

defineEmits(['submit'])

// Filtrar ciudades según el país seleccionado
const filteredCities = computed(() => {
    if (!props.form.ctry_id) {
        return []
    }
    return props.cities.filter(city => city.ctry_id === props.form.ctry_id)
})

</script>

<template>
    <FormSection @submitted="$emit('submit')">
        <template #title>
            {{ updating ? 'Actualizar Desarrollo' : 'Crear Desarrollo' }}
        </template>

        <template #description>
            {{ updating ? 'Actualizar la información del desarrollo seleccionado' : 'Crear un nuevo desarrollo' }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="devr_id" value="Desarrollador"></InputLabel>
                <select 
                    id="devr_id" 
                    v-model.number="form.devr_id"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="" disabled>Selecciona un desarrollador</option>
                    <option v-for="developer in developers" :key="developer.devr_id" :value="developer.devr_id">
                        {{ developer.devr_commercial_name }}
                    </option>
                </select>
                <InputError :message="$page.props.errors.devr_id" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="ctry_id" value="País"></InputLabel>
                <select 
                    id="ctry_id" 
                    v-model.number="form.ctry_id"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="" disabled>Selecciona un país</option>
                    <option v-for="country in countries" :key="country.ctry_id" :value="country.ctry_id">
                        {{ country.ctry_name }}
                    </option>
                </select>
                <InputError :message="$page.props.errors.ctry_id" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="city_id" value="Ciudad"></InputLabel>
                <select 
                    id="city_id" 
                    v-model.number="form.city_id"
                    :disabled="!form.ctry_id"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    <option value="" disabled>{{ form.ctry_id ? 'Selecciona una ciudad' : 'Primero selecciona un país' }}</option>
                    <option v-for="city in filteredCities" :key="city.city_id" :value="city.city_id">
                        {{ city.city_name }}
                    </option>
                </select>
                <InputError :message="$page.props.errors.city_id" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="apvSta_id" value="Estado de Aprobación"></InputLabel>
                <select 
                    id="apvSta_id" 
                    v-model.number="form.apvSta_id"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="" disabled>Selecciona un estado</option>
                    <option v-for="status in approvalStatuses" :key="status.apvSta_id" :value="status.apvSta_id">
                        {{ status.apvSta_name }}
                    </option>
                </select>
                <InputError :message="$page.props.errors.apvSta_id" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="busiSta_id" value="Estado de Negocio"></InputLabel>
                <select 
                    id="busiSta_id" 
                    v-model.number="form.busiSta_id"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="" disabled>Selecciona un estado</option>
                    <option v-for="state in businessStates" :key="state.busiSta_id" :value="state.busiSta_id">
                        {{ state.busiSta_name }}
                    </option>
                </select>
                <InputError :message="$page.props.errors.busiSta_id" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="commSta_id" value="Estado Comercial"></InputLabel>
                <select 
                    id="commSta_id" 
                    v-model.number="form.commSta_id"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="" disabled>Selecciona un estado</option>
                    <option v-for="status in commercialStatuses" :key="status.commSta_id" :value="status.commSta_id">
                        {{ status.commSta_name }}
                    </option>
                </select>
                <InputError :message="$page.props.errors.commSta_id" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="devt_title" value="Título del Desarrollo"></InputLabel>
                <TextInput 
                    id="devt_title" 
                    v-model="form.devt_title" 
                    type="text" 
                    autocomplete="off"
                    class="mt-1 block w-full"
                    placeholder="Ej: Proyecto Residencial Downtown">
                </TextInput>
                <InputError :message="$page.props.errors.devt_title" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="devt_slug" value="Slug"></InputLabel>
                <TextInput 
                    id="devt_slug" 
                    v-model="form.devt_slug" 
                    type="text" 
                    autocomplete="off"
                    class="mt-1 block w-full"
                    placeholder="Ej: proyecto-residencial-downtown">
                </TextInput>
                <InputError :message="$page.props.errors.devt_slug" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="devt_address" value="Dirección"></InputLabel>
                <TextInput 
                    id="devt_address" 
                    v-model="form.devt_address" 
                    type="text" 
                    autocomplete="off"
                    class="mt-1 block w-full"
                    placeholder="Ej: Calle Principal 123, Zona 10">
                </TextInput>
                <InputError :message="$page.props.errors.devt_address" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="devt_delivery_year" value="Año de Entrega"></InputLabel>
                <TextInput 
                    id="devt_delivery_year" 
                    v-model="form.devt_delivery_year" 
                    type="number" 
                    autocomplete="off"
                    class="mt-1 block w-full"
                    placeholder="Ej: 2025">
                </TextInput>
                <InputError :message="$page.props.errors.devt_delivery_year" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="devt_price_from" value="Precio Desde"></InputLabel>
                <TextInput 
                    id="devt_price_from" 
                    v-model="form.devt_price_from" 
                    type="number" 
                    step="0.01"
                    autocomplete="off"
                    class="mt-1 block w-full"
                    placeholder="Ej: 50000">
                </TextInput>
                <InputError :message="$page.props.errors.devt_price_from" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="devt_price_to" value="Precio Hasta"></InputLabel>
                <TextInput 
                    id="devt_price_to" 
                    v-model="form.devt_price_to" 
                    type="number" 
                    step="0.01"
                    autocomplete="off"
                    class="mt-1 block w-full"
                    placeholder="Ej: 150000">
                </TextInput>
                <InputError :message="$page.props.errors.devt_price_to" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="devt_estimated_profit" value="Ganancia Estimada"></InputLabel>
                <TextInput 
                    id="devt_estimated_profit" 
                    v-model="form.devt_estimated_profit" 
                    type="number" 
                    step="0.01"
                    autocomplete="off"
                    class="mt-1 block w-full"
                    placeholder="Ej: 20000">
                </TextInput>
                <InputError :message="$page.props.errors.devt_estimated_profit" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="devt_is_featured" value="Destacado"></InputLabel>
                <select 
                    id="devt_is_featured" 
                    v-model="form.devt_is_featured"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option :value="false">No</option>
                    <option :value="true">Sí</option>
                </select>
                <InputError :message="$page.props.errors.devt_is_featured" class="mt-2" />
            </div>

            <div v-if="updating" class="col-span-6 sm:col-span-3">
                <InputLabel for="devt_active" value="Activo"></InputLabel>
                <select 
                    id="devt_active" 
                    v-model="form.devt_active"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option :value="true">Activo</option>
                    <option :value="false">Inactivo</option>
                </select>
                <InputError :message="$page.props.errors.devt_active" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="devt_short_description" value="Descripción Corta"></InputLabel>
                <textarea 
                    id="devt_short_description" 
                    v-model="form.devt_short_description" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    placeholder="Descripción breve del desarrollo"
                    rows="2">
                </textarea>
                <InputError :message="$page.props.errors.devt_short_description" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="devt_long_description" value="Descripción Larga"></InputLabel>
                <textarea 
                    id="devt_long_description" 
                    v-model="form.devt_long_description" 
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    placeholder="Descripción detallada del desarrollo"
                    rows="4">
                </textarea>
                <InputError :message="$page.props.errors.devt_long_description" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                {{ updating ? 'Actualizar' : 'Crear' }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>
