<script setup>
import FormSection from '@/Components/FormSection.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'

const props = defineProps({
    form: {
        type: Object,
        required: true
    },
    leads: Array,
    users: Array,
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
            {{ updating ? 'Actualizar Nota' : 'Crear Nueva Nota' }}
        </template>
        <template #description>
            {{ updating ? 'Actualizar la información de la nota del lead' : 'Crear una nueva nota para un lead' }}
        </template>

        <template #form>
            <!-- Lead -->
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="lead_id" value="Lead *"></InputLabel>
                <select
                    id="lead_id"
                    v-model.number="form.lead_id"
                    class="mt-1 block w-full border-gray-300 rounded-md"
                    required
                >
                    <option value="">-- Seleccionar --</option>
                    <option v-for="lead in leads" :key="lead.lead_id" :value="lead.lead_id">
                        {{ lead.lead_client_name }}
                    </option>
                </select>
                <InputError :message="$page.props.errors.lead_id" class="mt-2" />
            </div>

            <!-- Usuario -->
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="user_id" value="Usuario *"></InputLabel>
                <select
                    id="user_id"
                    v-model.number="form.user_id"
                    class="mt-1 block w-full border-gray-300 rounded-md"
                    required
                >
                    <option value="">-- Seleccionar --</option>
                    <option v-for="user in users" :key="user.id" :value="user.id">
                        {{ user.name }} {{ user.lastname }}
                    </option>
                </select>
                <InputError :message="$page.props.errors.user_id" class="mt-2" />
            </div>

            <!-- Título -->
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="leadNot_title" value="Título"></InputLabel>
                <TextInput 
                    id="leadNot_title" 
                    v-model="form.leadNot_title" 
                    type="text" 
                    class="mt-1 block w-full"
                    maxlength="150"
                />
                <InputError :message="$page.props.errors.leadNot_title" class="mt-2" />
            </div>

            <!-- Descripción -->
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="leadNot_description" value="Descripción"></InputLabel>
                <textarea
                    id="leadNot_description"
                    v-model="form.leadNot_description"
                    rows="5"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                ></textarea>
                <InputError :message="$page.props.errors.leadNot_description" class="mt-2" />
            </div>

            <!-- Activo - Solo visible cuando se está editando -->
            <div v-if="updating" class="col-span-6 sm:col-span-6">
                <InputLabel for="leadNot_active" value="Estado"></InputLabel>
                <select 
                    id="leadNot_active" 
                    v-model="form.leadNot_active"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option :value="true">Activo</option>
                    <option :value="false">Inactivo</option>
                </select>
                <InputError :message="$page.props.errors.leadNot_active" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                {{ updating ? 'Actualizar' : 'Crear' }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>
