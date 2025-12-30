<!-- filepath: resources/js/Components/LeadStatus/Form.vue -->
<script>
export default {
    name: 'LeadStatusForm'
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
    },
    errors: {
        type: Object,
        required: false,
        default: () => ({})
    }
})

defineEmits(['submit'])
</script>

<template>
    <FormSection @submitted="$emit('submit')">
        <template #title>
            {{ updating ? 'Actualizar Estado de Lead' : 'Crear Estado de Lead' }}
        </template>

        <template #description>
            {{ updating ? 'Actualizar el Estado de Lead seleccionado' : 'Crear un nuevo Estado de Lead' }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="leadSta_name" value="Nombre"></InputLabel>
                <TextInput 
                    id="leadSta_name" 
                    v-model="form.leadSta_name" 
                    type="text" 
                    autocomplete="name"
                    class="mt-1 block w-full"
                    :class="errors.leadSta_name || $page.props.errors.leadSta_name ? 'border-red-500' : ''">
                </TextInput>
                <InputError :message="errors.leadSta_name || $page.props.errors.leadSta_name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="leadSta_code" value="Código"></InputLabel>
                <TextInput 
                    id="leadSta_code" 
                    v-model="form.leadSta_code" 
                    type="text" 
                    autocomplete="name"
                    class="mt-1 block w-full"
                    :class="errors.leadSta_code || $page.props.errors.leadSta_code ? 'border-red-500' : ''">
                </TextInput>
                <InputError :message="errors.leadSta_code || $page.props.errors.leadSta_code" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="leadSta_description" value="Descripción"></InputLabel>
                <TextInput 
                    id="leadSta_description" 
                    v-model="form.leadSta_description" 
                    type="text" 
                    autocomplete="name"
                    class="mt-1 block w-full">
                </TextInput>
                <InputError :message="$page.props.errors.leadSta_description" class="mt-2" />
            </div>

            <!-- Solo visible cuando se está editando -->
            <div v-if="updating" class="col-span-6 sm:col-span-6">
                <InputLabel for="leadSta_active" value="Estado"></InputLabel>
                <select 
                    id="leadSta_active" 
                    v-model="form.leadSta_active"
                    class="mt-1 block w-full border-gray-300 rounded-md">
                    <option :value="true">Activo</option>
                    <option :value="false">Inactivo</option>
                </select>
                <InputError :message="$page.props.errors.leadSta_active" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <PrimaryButton>
                {{ updating ? 'Actualizar' : 'Crear' }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>