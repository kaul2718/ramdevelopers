<script>
export default {
    name: 'ApprovalStatusForm'
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
            {{ updating ? 'Actualizar Estado de Aprobación' : ' Crear Estado de Aprobación' }}
        </template>
        <template #description>
            {{ updating ? 'Actualizar el Estado de Aprobación seleccionado' : ' Crear un nuevo Estado de Aprobación' }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="name" value="Name"></InputLabel>
                <TextInput id="apvSta_name" v-model="form.apvSta_name" type="text" autocomplete="name"
                    class="mt-1 block w-full"></TextInput>
                <InputError :message="$page.props.errors.apvSta_name" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="apvSta_code" value="Código"></InputLabel>
                <TextInput id="apvSta_code" v-model="form.apvSta_code" type="text" autocomplete="name"
                    class="mt-1 block w-full"></TextInput>
                <InputError :message="$page.props.errors.apvSta_code" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="apvSta_description" value="Descripción"></InputLabel>
                <TextInput id="apvSta_description" v-model="form.apvSta_description" type="text" autocomplete="name"
                    class="mt-1 block w-full"></TextInput>
                <InputError :message="$page.props.errors.apvSta_description" class="mt-2" />
            </div>

            <!-- Solo visible cuando se está editando -->
            <div v-if="updating" class="col-span-6 sm:col-span-6">
                <InputLabel for="apvSta_active" value="Estado"></InputLabel>

                <select id="apvSta_active" v-model="form.apvSta_active"
                    class="mt-1 block w-full border-gray-300 rounded-md">
                    <option :value="true">Activo</option>
                    <option :value="false">Inactivo</option>
                </select>

                <InputError :message="$page.props.errors.apvSta_active" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <PrimaryButton>
                {{ updating ? 'Actualizar' : 'Crear' }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>