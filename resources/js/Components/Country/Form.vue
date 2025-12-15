<!-- filepath: resources/js/Components/Country/Form.vue -->
<script>
export default {
    name: 'CountryForm'
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
            {{ updating ? 'Actualizar País' : 'Crear País' }}
        </template>

        <template #description>
            {{ updating ? 'Actualizar el país seleccionado' : 'Crear un nuevo país' }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="ctry_name" value="Nombre del País"></InputLabel>
                <TextInput 
                    id="ctry_name" 
                    v-model="form.ctry_name" 
                    type="text" 
                    autocomplete="name"
                    class="mt-1 block w-full">
                </TextInput>
                <InputError :message="$page.props.errors.ctry_name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="ctry_code" value="Código (ISO)"></InputLabel>
                <TextInput 
                    id="ctry_code" 
                    v-model="form.ctry_code" 
                    type="text" 
                    maxlength="5"
                    placeholder="ej: CO, MX, ES"
                    autocomplete="name"
                    class="mt-1 block w-full">
                </TextInput>
                <InputError :message="$page.props.errors.ctry_code" class="mt-2" />
            </div>

            <!-- Solo visible cuando se está editando -->
            <div v-if="updating" class="col-span-6 sm:col-span-6">
                <InputLabel for="ctry_active" value="Estado"></InputLabel>
                <select 
                    id="ctry_active" 
                    v-model="form.ctry_active"
                    class="mt-1 block w-full border-gray-300 rounded-md">
                    <option :value="true">Activo</option>
                    <option :value="false">Inactivo</option>
                </select>
                <InputError :message="$page.props.errors.ctry_active" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <PrimaryButton>
                {{ updating ? 'Actualizar' : 'Crear' }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>