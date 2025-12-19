<script>
export default {
    name: 'CommercialStatusForm'
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
            {{ updating ? 'Actualizar Estado Comercial' : 'Crear Estado Comercial' }}
        </template>
        <template #description>
            {{ updating ? 'Actualizar el Estado Comercial seleccionado' : 'Crear un nuevo Estado Comercial' }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="commSta_name" value="Nombre"></InputLabel>
                <TextInput id="commSta_name" v-model="form.commSta_name" type="text" autocomplete="name"
                    class="mt-1 block w-full"></TextInput>
                <InputError :message="$page.props.errors.commSta_name" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="commSta_code" value="Código"></InputLabel>
                <TextInput id="commSta_code" v-model="form.commSta_code" type="text" autocomplete="name"
                    class="mt-1 block w-full"></TextInput>
                <InputError :message="$page.props.errors.commSta_code" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="commSta_description" value="Descripción"></InputLabel>
                <TextInput id="commSta_description" v-model="form.commSta_description" type="text" autocomplete="name"
                    class="mt-1 block w-full"></TextInput>
                <InputError :message="$page.props.errors.commSta_description" class="mt-2" />
            </div>

            <div v-if="updating" class="col-span-6 sm:col-span-6">
                <InputLabel for="commSta_active" value="Estado"></InputLabel>
                <select id="commSta_active" v-model="form.commSta_active"
                    class="mt-1 block w-full border-gray-300 rounded-md">
                    <option :value="true">Activo</option>
                    <option :value="false">Inactivo</option>
                </select>
                <InputError :message="$page.props.errors.commSta_active" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <PrimaryButton>
                {{ updating ? 'Actualizar' : 'Crear' }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>
