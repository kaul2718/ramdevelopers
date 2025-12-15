<script>
export default {
    name: 'LeadSourceForm'
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
            {{ updating ? 'Actualizar Fuente de Lead' : 'Crear Fuente de Lead' }}
        </template>

        <template #description>
            {{ updating ? 'Actualizar la Fuente de Lead seleccionada' : 'Crear una nueva Fuente de Lead' }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="leadSou_name" value="Nombre"></InputLabel>
                <TextInput 
                    id="leadSou_name" 
                    v-model="form.leadSou_name" 
                    type="text" 
                    autocomplete="name"
                    class="mt-1 block w-full">
                </TextInput>
                <InputError :message="$page.props.errors.leadSou_name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="leadSou_code" value="Código"></InputLabel>
                <TextInput 
                    id="leadSou_code" 
                    v-model="form.leadSou_code" 
                    type="text" 
                    autocomplete="name"
                    class="mt-1 block w-full">
                </TextInput>
                <InputError :message="$page.props.errors.leadSou_code" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="leadSou_description" value="Descripción"></InputLabel>
                <TextInput 
                    id="leadSou_description" 
                    v-model="form.leadSou_description" 
                    type="text" 
                    autocomplete="name"
                    class="mt-1 block w-full">
                </TextInput>
                <InputError :message="$page.props.errors.leadSou_description" class="mt-2" />
            </div>

            <!-- Solo visible cuando se está editando -->
            <div v-if="updating" class="col-span-6 sm:col-span-6">
                <InputLabel for="leadSou_active" value="Estado"></InputLabel>
                <select 
                    id="leadSou_active" 
                    v-model="form.leadSou_active"
                    class="mt-1 block w-full border-gray-300 rounded-md">
                    <option :value="true">Activo</option>
                    <option :value="false">Inactivo</option>
                </select>
                <InputError :message="$page.props.errors.leadSou_active" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <PrimaryButton>
                {{ updating ? 'Actualizar' : 'Crear' }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>
