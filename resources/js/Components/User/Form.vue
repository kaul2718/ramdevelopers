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
    import { router } from '@inertiajs/vue3'

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
    
    //RUTA DASHBOARD
    const goToBack = () => {
        router.visit('/users');
    };
</script>

<template>
    <FormSection @submitted="$emit('submit')">
        <template #buttons>            
            <button @click.prevent="goToBack" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="CurrentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75 3 12m0 0 3.75-3.75M3 12h18" />
                </svg>
            </button>
        </template>

        <template #title>
            {{ updating ? 'Actualizar Usuario' : 'Crear Usuario' }}
        </template>

        <template #description>
            ( * ) Campos obligatorios
        </template>

        <template #form>
<<<<<<< HEAD
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="name" value="Nombre"></InputLabel>
                <TextInput id="name" v-model="form.name" type="text" autocomplete="given-name"
                    class="mt-1 block w-full">
=======
            <div class="contenedor--input">
                <TextInput 
                id="name" 
                    v-model="form.name" 
                    type="text" 
                    autocomplete="given-name"
                    class="campo--input">
>>>>>>> origin/desarrollo/ronaldo
                </TextInput>
                <InputLabel class="label--input" for="name" value="Nombre"></InputLabel>
                <InputError :message="$page.props.errors.name" class="mt-2" />
            </div>

<<<<<<< HEAD
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="lastname" value="Apellido"></InputLabel>
                <TextInput id="lastname" v-model="form.lastname" type="text" autocomplete="family-name"
                    class="mt-1 block w-full">
=======
            <div class="contenedor--input">
                <TextInput 
                    id="lastname" 
                    v-model="form.lastname" 
                    type="text" 
                    autocomplete="family-name"
                    class="campo--input">
>>>>>>> origin/desarrollo/ronaldo
                </TextInput>
                <InputLabel class="label--input" for="lastname" value="Apellido"></InputLabel>
                <InputError :message="$page.props.errors.lastname" class="mt-2" />
            </div>

<<<<<<< HEAD
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="email" value="Correo Electrónico"></InputLabel>
                <TextInput id="email" v-model="form.email" type="email" autocomplete="email" class="mt-1 block w-full">
=======
            <div class="contenedor--input">
                <TextInput 
                    id="email" 
                    v-model="form.email" 
                    type="email" 
                    autocomplete="email"
                    class="campo--input">
>>>>>>> origin/desarrollo/ronaldo
                </TextInput>
                <InputLabel class="label--input" for="email" value="Correo Electrónico"></InputLabel>
                <InputError :message="$page.props.errors.email" class="mt-2" />
            </div>

<<<<<<< HEAD
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="phone" value="Teléfono (Opcional)"></InputLabel>
                <TextInput id="phone" v-model="form.phone" type="tel" autocomplete="tel" class="mt-1 block w-full">
=======
            <div class="contenedor--input">
                <TextInput 
                    id="phone" 
                    v-model="form.phone" 
                    type="tel" 
                    autocomplete="tel"
                    class="campo--input">
>>>>>>> origin/desarrollo/ronaldo
                </TextInput>
                <InputLabel class="label--input" for="phone" value="Teléfono (Opcional)"></InputLabel>
                <InputError :message="$page.props.errors.phone" class="mt-2" />
            </div>

<<<<<<< HEAD
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="usr_id_ctry" value="País (Opcional)"></InputLabel>
                <select id="usr_id_ctry" v-model="form.usr_id_ctry"
                    class="mt-1 block w-full border-gray-300 rounded-md">
=======
            <div class="contenedor--input">
                <select 
                    id="usr_id_ctry" 
                    v-model="form.usr_id_ctry"
                    class="campo--input">
>>>>>>> origin/desarrollo/ronaldo
                    <option value="">Selecciona un país</option>
                    <option v-for="country in countries" :key="country.ctry_id" :value="country.ctry_id">
                        {{ country.ctry_name }}
                    </option>
                </select>
                <InputLabel class="label--input" for="usr_id_ctry" value="País (Opcional)"></InputLabel>
                <InputError :message="$page.props.errors.usr_id_ctry" class="mt-2" />
            </div>

<<<<<<< HEAD
            <div v-if="!updating" class="col-span-6 sm:col-span-3">
                <InputLabel for="password" value="Contraseña"></InputLabel>
                <TextInput id="password" v-model="form.password" type="password" autocomplete="new-password"
                    class="mt-1 block w-full">
=======
            <div v-if="!updating" class="contenedor--input">
                <TextInput 
                    id="password" 
                    v-model="form.password" 
                    type="password" 
                    autocomplete="new-password"
                    class="campo--input">
>>>>>>> origin/desarrollo/ronaldo
                </TextInput>
                <InputLabel class="label--input" for="password" value="Contraseña"></InputLabel>
                <InputError :message="$page.props.errors.password" class="mt-2" />
            </div>

<<<<<<< HEAD
            <div v-if="updating" class="col-span-6 sm:col-span-3">
                <InputLabel for="password" value="Contraseña (Dejar en blanco para mantener actual)"></InputLabel>
                <TextInput id="password" v-model="form.password" type="password" autocomplete="new-password"
                    class="mt-1 block w-full">
=======
            <div v-if="updating" class="contenedor--input">
                <TextInput 
                    id="password" 
                    v-model="form.password" 
                    type="password" 
                    autocomplete="new-password"
                    class="campo--input">
>>>>>>> origin/desarrollo/ronaldo
                </TextInput>
                <InputLabel class="label--input" for="password" value="Contraseña (Dejar en blanco para mantener actual)"></InputLabel>
                <InputError :message="$page.props.errors.password" class="mt-2" />
            </div>

<<<<<<< HEAD
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="idiomas" value="Idiomas (Opcional)"></InputLabel>
                <TextInput id="idiomas" v-model="form.idiomas" type="text" placeholder="Ej: Español, Inglés, Francés"
                    class="mt-1 block w-full">
=======
            <div class="contenedor--input">
                <TextInput 
                    id="idiomas" 
                    v-model="form.idiomas" 
                    type="text" 
                    placeholder="Ej: Español, Inglés, Francés"
                    class="campo--input">
>>>>>>> origin/desarrollo/ronaldo
                </TextInput>
                <InputLabel class="label--input" for="idiomas" value="Idiomas (Opcional)"></InputLabel>
                <InputError :message="$page.props.errors.idiomas" class="mt-2" />
                <p class="text-xs text-gray-500 mt-1">Separa los idiomas por comas</p>
            </div>

<<<<<<< HEAD
            <div class="col-span-6 sm:col-span-3">
                <InputLabel for="profile_photo_path" value="Foto de Perfil (Opcional)"></InputLabel>
                <input ref="photoInput" id="profile_photo_path" type="file" accept="image/*" @change="handlePhotoChange"
                    class="mt-1 block w-full text-sm text-gray-500
=======
            <div class="contenedor--input">
                <input 
                    ref="photoInput"
                    id="profile_photo_path" 
                    type="file"
                    accept="image/*"
                    @change="handlePhotoChange"
                    class="campo--input mt-1 block w-full text-sm text-gray-500
>>>>>>> origin/desarrollo/ronaldo
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-indigo-50 file:text-indigo-700
                        hover:file:bg-indigo-100">
                </input>
                <InputLabel class="label--input" for="profile_photo_path" value="Foto de Perfil (Opcional)"></InputLabel>
                <InputError :message="$page.props.errors.profile_photo_path" class="mt-2" />
                <p class="text-xs text-gray-500 mt-1">Formatos: JPG, PNG, GIF (máx. 5MB)</p>
            </div>

<<<<<<< HEAD
            <div v-if="updating" class="col-span-6 sm:col-span-3">
                <InputLabel for="usr_active" value="Estado"></InputLabel>
                <select id="usr_active" v-model.number="form.usr_active"
                    class="mt-1 block w-full border-gray-300 rounded-md">
=======
            <div v-if="updating" class="contenedor--input">
                <select 
                    id="usr_active" 
                    v-model.number="form.usr_active"
                    class="campo--input">
>>>>>>> origin/desarrollo/ronaldo
                    <option :value="1">Activo</option>
                    <option :value="0">Inactivo</option>
                </select>
                <InputLabel class="label--input" for="usr_active" value="Estado"></InputLabel>
                <InputError :message="$page.props.errors.usr_active" class="mt-2" />
            </div>

            <div class="col-span-6">
<<<<<<< HEAD
                <InputLabel value="Rol" />

                <select v-model="form.role_id" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm
               focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="" disabled>Seleccione un rol</option>

                    <option v-for="role in roles" :key="role.id" :value="role.id">
                        {{ role.name }}
                    </option>
                </select>

                <InputError :message="$page.props.errors.role_id" class="mt-2" />
            </div>



=======
                <InputLabel class="label--input" value="Roles"></InputLabel>
                <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div v-for="role in roles" :key="role.id" class="flex items-center">
                        <input 
                            :id="`role_${role.id}`"
                            type="checkbox" 
                            :value="role.id"
                            v-model="form.roles"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        >
                        <label :for="`role_${role.id}`" class="ms-2 text-sm text-gray-700">
                            {{ role.name }}
                        </label>
                    </div>
                </div>
                <InputError :message="$page.props.errors.roles" class="mt-2" />
            </div>

            <div v-if="permissions.length > 0" class="col-span-6">
                <InputLabel class="label--input" value="Permisos Adicionales"></InputLabel>
                <p class="text-xs text-gray-600 mt-1 mb-3">Asigna permisos adicionales directos al usuario, independientemente de sus roles</p>
                <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div v-for="permission in permissions" :key="permission.id" class="flex items-center">
                        <input 
                            :id="`permission_${permission.id}`"
                            type="checkbox" 
                            :value="permission.id"
                            v-model="form.permissions"
                            class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                        >
                        <label :for="`permission_${permission.id}`" class="ms-2 text-sm text-gray-700">
                            {{ permission.name }}
                            <span class="text-xs text-gray-500 ms-1">({{ permission.guard_name }})</span>
                        </label>
                    </div>
                </div>
                <InputError :message="$page.props.errors.permissions" class="mt-2" />
            </div>
>>>>>>> origin/desarrollo/ronaldo
        </template>

        <template #actions>
            <PrimaryButton type="button" :class="{ 'opacity-25': enabling }" :disabled="enabling">
                <template #texto--boton>{{ updating ? 'Actualizar' : 'Crear' }}</template>
                <template #icono--boton>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </template> 
            </PrimaryButton>
        </template>
    </FormSection>
</template>