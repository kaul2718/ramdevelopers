<!-- filepath: resources/js/Components/Developer/Form.vue -->
<script>
export default {
    name: 'DeveloperForm'
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
    users: {
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
            {{ updating ? 'Actualizar Desarrollador' : 'Crear Desarrollador' }}
        </template>

        <template #description>
            {{ updating ? 'Actualizar la información del desarrollador seleccionado' : 'Crear un nuevo desarrollador' }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="devr_commercial_name" value="Nombre Comercial"></InputLabel>
                <TextInput 
                    id="devr_commercial_name" 
                    v-model="form.devr_commercial_name" 
                    type="text" 
                    autocomplete="off"
                    class="mt-1 block w-full"
                    placeholder="Ej: TechDev Solutions">
                </TextInput>
                <InputError :message="$page.props.errors.devr_commercial_name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="devr_legal_name" value="Nombre Legal"></InputLabel>
                <TextInput 
                    id="devr_legal_name" 
                    v-model="form.devr_legal_name" 
                    type="text" 
                    autocomplete="off"
                    class="mt-1 block w-full"
                    placeholder="Ej: TechDev Solutions S.A.">
                </TextInput>
                <InputError :message="$page.props.errors.devr_legal_name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="devr_email_contact" value="Email de Contacto"></InputLabel>
                <TextInput 
                    id="devr_email_contact" 
                    v-model="form.devr_email_contact" 
                    type="email" 
                    autocomplete="email"
                    class="mt-1 block w-full"
                    placeholder="contacto@ejemplo.com">
                </TextInput>
                <InputError :message="$page.props.errors.devr_email_contact" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="devr_phone_contact" value="Teléfono de Contacto"></InputLabel>
                <TextInput 
                    id="devr_phone_contact" 
                    v-model="form.devr_phone_contact" 
                    type="text" 
                    autocomplete="tel"
                    class="mt-1 block w-full"
                    placeholder="+1 (555) 123-4567">
                </TextInput>
                <InputError :message="$page.props.errors.devr_phone_contact" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6">
                <InputLabel for="devr_website" value="Sitio Web (Opcional)"></InputLabel>
                <TextInput 
                    id="devr_website" 
                    v-model="form.devr_website" 
                    type="url" 
                    autocomplete="url"
                    class="mt-1 block w-full"
                    placeholder="https://www.ejemplo.com">
                </TextInput>
                <InputError :message="$page.props.errors.devr_website" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="ctry_id" value="País"></InputLabel>
                <select 
                    id="ctry_id" 
                    v-model="form.ctry_id"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="" disabled>Selecciona un país</option>
                    <option v-for="country in countries" :key="country.ctry_id" :value="country.ctry_id">
                        {{ country.ctry_name }}
                    </option>
                </select>
                <InputError :message="$page.props.errors.ctry_id" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="user_id" value="Usuario Asociado"></InputLabel>
                <select 
                    id="user_id" 
                    v-model="form.user_id"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="" disabled>Selecciona un usuario</option>
                    <option v-for="user in users" :key="user.id" :value="user.id">
                        {{ user.name }}
                    </option>
                </select>
                <InputError :message="$page.props.errors.user_id" class="mt-2" />
            </div>

            <!-- Solo visible cuando se está editando -->
            <div v-if="updating" class="col-span-6 sm:col-span-6">
                <InputLabel for="devr_active" value="Estado"></InputLabel>
                <select 
                    id="devr_active" 
                    v-model="form.devr_active"
                    class="mt-1 block w-full border-gray-300 rounded-md">
                    <option :value="true">Activo</option>
                    <option :value="false">Inactivo</option>
                </select>
                <InputError :message="$page.props.errors.devr_active" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                <template #texto--boton>{{ updating ? 'Actualizar' : 'Crear' }}</template>
                <template #icono--boton>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </template> 
            </PrimaryButton>
        </template>
    </FormSection>
</template>
