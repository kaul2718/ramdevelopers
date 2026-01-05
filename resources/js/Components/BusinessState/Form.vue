<script>
export default {
    name: 'BusinessStateForm'
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
            {{ updating ? 'Actualizar Estado del Proyecto' : 'Crear Estado del Proyecto' }}
        </template>
        <template #description>
            {{ updating ? 'Actualizar el Estado del Proyecto seleccionado' : 'Crear un nuevo Estado del Proyecto' }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="name" value="Nombre"></InputLabel>
                <TextInput id="busiSta_name" v-model="form.busiSta_name" type="text" autocomplete="name"
                    class="mt-1 block w-full"></TextInput>
                <InputError :message="$page.props.errors.busiSta_name" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="busiSta_code" value="Código"></InputLabel>
                <TextInput id="busiSta_code" v-model="form.busiSta_code" type="text" autocomplete="name"
                    class="mt-1 block w-full"></TextInput>
                <InputError :message="$page.props.errors.busiSta_code" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="busiSta_description" value="Descripción"></InputLabel>
                <TextInput id="busiSta_description" v-model="form.busiSta_description" type="text" autocomplete="name"
                    class="mt-1 block w-full"></TextInput>
                <InputError :message="$page.props.errors.busiSta_description" class="mt-2" />
            </div>

            <div v-if="updating" class="col-span-6 sm:col-span-6">
                <InputLabel for="busiSta_active" value="Estado"></InputLabel>
                <select id="busiSta_active" v-model="form.busiSta_active"
                    class="mt-1 block w-full border-gray-300 rounded-md">
                    <option :value="true">Activo</option>
                    <option :value="false">Inactivo</option>
                </select>
                <InputError :message="$page.props.errors.busiSta_active" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <PrimaryButton>
                {{ updating ? 'Actualizar' : 'Crear' }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>
