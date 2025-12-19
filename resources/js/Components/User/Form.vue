<!-- filepath: resources/js/Components/User/Form.vue -->
<script>
export default {
    name: 'UserForm'
}
</script>

<script setup>
import FormSection from '@/Components/FormSection.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { ref } from 'vue'

const selectedPhotoName = ref('')
const photoInput = ref(null)

const props = defineProps({
    form: {
        type: Object,
        required: true
    },
    countries: {
        type: Array,
        required: true
    },
    roles: {
        type: Array,
        required: true
    },
    permissions: {
        type: Array,
        default: () => []
    },
    updating: {
        type: Boolean,
        required: false,
        default: false
    }
})

defineEmits(['submit'])



const handlePhotoChange = (event) => {
    const file = event.target.files[0]
    if (file) {
        selectedPhotoName.value = file.name
        props.form.profile_photo_path = file
    }
}
</script>

<template>
    <FormSection @submitted="$emit('submit')">
        <template #title>
            {{ updating ? 'Actualizar Usuario' : 'Crear Usuario' }}
        </template>

        <template #description>
            {{ updating ? 'Actualizar el usuario seleccionado' : 'Crear un nuevo usuario' }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="name" value="Nombre"></InputLabel>
                <TextInput id="name" v-model="form.name" type="text" autocomplete="given-name"
                    class="mt-1 block w-full">
                </TextInput>
                <InputError :message="$page.props.errors.name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="lastname" value="Apellido"></InputLabel>
                <TextInput id="lastname" v-model="form.lastname" type="text" autocomplete="family-name"
                    class="mt-1 block w-full">
                </TextInput>
                <InputError :message="$page.props.errors.lastname" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="email" value="Correo Electrónico"></InputLabel>
                <TextInput id="email" v-model="form.email" type="email" autocomplete="email" class="mt-1 block w-full">
                </TextInput>
                <InputError :message="$page.props.errors.email" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="phone" value="Teléfono (Opcional)"></InputLabel>
                <TextInput id="phone" v-model="form.phone" type="tel" autocomplete="tel" class="mt-1 block w-full">
                </TextInput>
                <InputError :message="$page.props.errors.phone" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="usr_id_ctry" value="País (Opcional)"></InputLabel>
                <select id="usr_id_ctry" v-model="form.usr_id_ctry"
                    class="mt-1 block w-full border-gray-300 rounded-md">
                    <option value="">Selecciona un país</option>
                    <option v-for="country in countries" :key="country.ctry_id" :value="country.ctry_id">
                        {{ country.ctry_name }}
                    </option>
                </select>
                <InputError :message="$page.props.errors.usr_id_ctry" class="mt-2" />
            </div>

            <div v-if="!updating" class="col-span-6 sm:col-span-3">
                <InputLabel for="password" value="Contraseña"></InputLabel>
                <TextInput id="password" v-model="form.password" type="password" autocomplete="new-password"
                    class="mt-1 block w-full">
                </TextInput>
                <InputError :message="$page.props.errors.password" class="mt-2" />
            </div>

            <div v-if="updating" class="col-span-6 sm:col-span-3">
                <InputLabel for="password" value="Contraseña (Dejar en blanco para mantener actual)"></InputLabel>
                <TextInput id="password" v-model="form.password" type="password" autocomplete="new-password"
                    class="mt-1 block w-full">
                </TextInput>
                <InputError :message="$page.props.errors.password" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="idiomas" value="Idiomas (Opcional)"></InputLabel>
                <select id="idiomas" v-model="form.idiomas"
                    class="mt-1 block w-full border-gray-300 rounded-md">
                    <option value="">Selecciona un idioma</option>
                    <option value="Español">Español</option>
                    <option value="Inglés">Inglés</option>
                    <option value="Francés">Francés</option>
                </select>
                <InputError :message="$page.props.errors.idiomas" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="profile_photo_path" value="Foto de Perfil (Opcional)"></InputLabel>
                <input ref="photoInput" id="profile_photo_path" type="file" accept="image/*" @change="handlePhotoChange"
                    class="mt-1 block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-indigo-50 file:text-indigo-700
                        hover:file:bg-indigo-100">
                </input>
                <span v-if="selectedPhotoName" class="text-xs text-gray-600 mt-2 block">
                    Archivo seleccionado: {{ selectedPhotoName }}
                </span>
                <InputError :message="$page.props.errors.profile_photo_path" class="mt-2" />
                <p class="text-xs text-gray-500 mt-1">Formatos: JPG, PNG, GIF (máx. 5MB)</p>
            </div>

            <div v-if="updating" class="col-span-6 sm:col-span-3">
                <InputLabel for="usr_active" value="Estado"></InputLabel>
                <select id="usr_active" v-model.number="form.usr_active"
                    class="mt-1 block w-full border-gray-300 rounded-md">
                    <option :value="1">Activo</option>
                    <option :value="0">Inactivo</option>
                </select>
                <InputError :message="$page.props.errors.usr_active" class="mt-2" />
            </div>

            <div class="col-span-6">
                <InputLabel value="Rol" />

                <select v-model="form.roles" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="" disabled>Seleccione un rol</option>

                    <option v-for="role in roles" :key="role.id" :value="[role.id]">
                        {{ role.name }}
                    </option>
                </select>

                <InputError :message="$page.props.errors.roles" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <PrimaryButton>
                {{ updating ? 'Actualizar' : 'Crear' }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>