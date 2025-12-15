<!-- filepath: resources/js/Components/DocumentType/Form.vue -->
<script>
export default {
    name: 'DocumentTypeForm'
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
            {{ updating ? 'Actualizar Tipo de Documento' : 'Crear Tipo de Documento' }}
        </template>

        <template #description>
            {{ updating ? 'Actualizar el Tipo de Documento seleccionado' : 'Crear un nuevo Tipo de Documento' }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="docTyp_name" value="Nombre"></InputLabel>
                <TextInput 
                    id="docTyp_name" 
                    v-model="form.docTyp_name" 
                    type="text" 
                    autocomplete="name"
                    class="mt-1 block w-full">
                </TextInput>
                <InputError :message="$page.props.errors.docTyp_name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="docTyp_code" value="Código"></InputLabel>
                <TextInput 
                    id="docTyp_code" 
                    v-model="form.docTyp_code" 
                    type="text" 
                    autocomplete="name"
                    class="mt-1 block w-full">
                </TextInput>
                <InputError :message="$page.props.errors.docTyp_code" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="docTyp_description" value="Descripción"></InputLabel>
                <TextInput 
                    id="docTyp_description" 
                    v-model="form.docTyp_description" 
                    type="text" 
                    autocomplete="name"
                    class="mt-1 block w-full">
                </TextInput>
                <InputError :message="$page.props.errors.docTyp_description" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="docTyp_isInternal" value="¿Es Interno?"></InputLabel>
                <select 
                    id="docTyp_isInternal" 
                    v-model="form.docTyp_isInternal"
                    class="mt-1 block w-full border-gray-300 rounded-md">
                    <option :value="true">Sí</option>
                    <option :value="false">No</option>
                </select>
                <InputError :message="$page.props.errors.docTyp_isInternal" class="mt-2" />
            </div>

            <!-- Solo visible cuando se está editando -->
            <div v-if="updating" class="col-span-6 sm:col-span-6">
                <InputLabel for="docTyp_active" value="Estado"></InputLabel>
                <select 
                    id="docTyp_active" 
                    v-model="form.docTyp_active"
                    class="mt-1 block w-full border-gray-300 rounded-md">
                    <option :value="true">Activo</option>
                    <option :value="false">Inactivo</option>
                </select>
                <InputError :message="$page.props.errors.docTyp_active" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <PrimaryButton>
                {{ updating ? 'Actualizar' : 'Crear' }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>