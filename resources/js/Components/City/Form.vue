<!-- filepath: resources/js/Components/City/Form.vue -->
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

defineProps({
    form: {
        type: Object,
        required: true
    },
    countries: {
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
</script>

<template>
    <FormSection @submitted="$emit('submit')">
        <template #title>
            {{ updating ? 'Actualizar Ciudad' : 'Crear Ciudad' }}
        </template>

        <template #description>
            {{ updating ? 'Actualizar la ciudad seleccionada' : 'Crear una nueva ciudad' }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="city_name" value="Nombre de la Ciudad"></InputLabel>
                <TextInput 
                    id="city_name" 
                    v-model="form.city_name" 
                    type="text" 
                    autocomplete="name"
                    class="mt-1 block w-full">
                </TextInput>
                <InputError :message="$page.props.errors.city_name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="city_region" value="Región (Opcional)"></InputLabel>
                <TextInput 
                    id="city_region" 
                    v-model="form.city_region" 
                    type="text" 
                    autocomplete="name"
                    class="mt-1 block w-full">
                </TextInput>
                <InputError :message="$page.props.errors.city_region" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="ctry_id" value="País"></InputLabel>
                <select 
                    id="ctry_id" 
                    v-model="form.ctry_id"
                    class="mt-1 block w-full border-gray-300 rounded-md">
                    <option value="" disabled>Selecciona un país</option>
                    <option v-for="country in countries" :key="country.ctry_id" :value="country.ctry_id">
                        {{ country.ctry_name }}
                    </option>
                </select>
                <InputError :message="$page.props.errors.ctry_id" class="mt-2" />
            </div>

            <!-- Solo visible cuando se está editando -->
            <div v-if="updating" class="col-span-6 sm:col-span-6">
                <InputLabel for="city_active" value="Estado"></InputLabel>
                <select 
                    id="city_active" 
                    v-model="form.city_active"
                    class="mt-1 block w-full border-gray-300 rounded-md">
                    <option :value="true">Activo</option>
                    <option :value="false">Inactivo</option>
                </select>
                <InputError :message="$page.props.errors.city_active" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <PrimaryButton>
                {{ updating ? 'Actualizar' : 'Crear' }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>