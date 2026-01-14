<script>
    export default {
        name: 'UserForm'
    }
</script>

<script setup>
    import { useNotificationStore } from '@/stores/notificationStore'
    import FormSection from '@/Components/FormSection.vue'
    import InputError from '@/Components/InputError.vue'
    import InputLabel from '@/Components/InputLabel.vue'
    import PrimaryButton from '@/Components/PrimaryButton.vue'
    import TextInput from '@/Components/TextInput.vue'
    import axios from 'axios'
    import { ref, watch, onMounted } from 'vue'

    const selectedPhotoName = ref('')
    const photoInput = ref(null)

    const emit = defineEmits(['success', 'cancel', 'close'])

    const props = defineProps({
        countries: Array,
        roles: Array,
        permissions: Array,
        updating: Boolean,
        user: Object,
        readonly: Boolean
    })

    const notificationStore = useNotificationStore()

    const form = ref({
        name: '',
        lastname: '',
        email: '',
        phone: '',
        idiomas: '',
        usr_id_ctry: null,
        usr_active: true,
        password: '',
        password_confirmation: '',
        roles: '',
        profile_photo_path: null
    })

    const errors = ref({})
    const isSubmitting = ref(false)

    // Inicializar formulario con datos del usuario si es modo edición
    onMounted(() => {
        if (props.updating && props.user) {
            form.value = {
                name: props.user.name || '',
                lastname: props.user.lastname || '',
                email: (props.user.email || '').toLowerCase(),
                phone: props.user.phone || '',
                idiomas: props.user.idiomas || '',
                usr_id_ctry: props.user.usr_id_ctry || null,
                usr_active: Number(props.user.usr_active ?? 1),
                password: '',
                password_confirmation: '',
                roles: props.user.roles?.[0]?.id || '',
                profile_photo_path: null
            }
            selectedPhotoName.value = ''
        }
    })

    // Watcher para reiniciar el formulario si cambian los props
    watch(() => props.user, (newUser) => {
        if (props.updating && newUser) {
            form.value = {
                name: newUser.name || '',
                lastname: newUser.lastname || '',
                email: (newUser.email || '').toLowerCase(),
                phone: newUser.phone || '',
                idiomas: newUser.idiomas || '',
                usr_id_ctry: newUser.usr_id_ctry || null,
                usr_active: Number(newUser.usr_active ?? 1),
                password: '',
                password_confirmation: '',
                roles: newUser.roles?.[0]?.id || '',
                profile_photo_path: null
            }
            selectedPhotoName.value = ''
            errors.value = {}
        }
    }, { deep: true })

    const handlePhotoChange = (event) => {
        const file = event.target.files[0]
        if (file) {
            selectedPhotoName.value = file.name
            form.value.profile_photo_path = file
        }
    }

    const validateForm = () => {
        errors.value = {};
        
        // Convertir email a minúsculas
        if (form.value.email) {
            form.value.email = form.value.email.toLowerCase()
        }
        
        if (!form.value.name?.trim()) {
            errors.value.name = 'El nombre es requerido';
        }
        if (!form.value.lastname?.trim()) {
            errors.value.lastname = 'El apellido es requerido';
        }
        if (!form.value.email?.trim()) {
            errors.value.email = 'El email es requerido';
        } else if (!form.value.email.includes('@')) {
            errors.value.email = 'El email debe ser válido';
        }
        
        // Validar contraseña según el modo
        if (!props.updating) {
            // Modo crear: contraseña es obligatoria
            if (!form.value.password?.trim()) {
                errors.value.password = 'La contraseña es requerida';
            } else if (form.value.password.length < 6) {
                errors.value.password = 'La contraseña debe tener al menos 6 caracteres';
            }
            if (!form.value.password_confirmation?.trim()) {
                errors.value.password_confirmation = 'La confirmación de contraseña es requerida';
            } else if (form.value.password !== form.value.password_confirmation) {
                errors.value.password_confirmation = 'Las contraseñas no coinciden';
            }
        } else {
            // Modo actualizar: contraseña es opcional
            if (form.value.password?.trim()) {
                if (form.value.password.length < 6) {
                    errors.value.password = 'La contraseña debe tener al menos 6 caracteres';
                }
                if (!form.value.password_confirmation?.trim()) {
                    errors.value.password_confirmation = 'La confirmación de contraseña es requerida cuando cambias la contraseña';
                } else if (form.value.password !== form.value.password_confirmation) {
                    errors.value.password_confirmation = 'Las contraseñas no coinciden';
                }
            } else {
                // Si no hay contraseña, limpiar la confirmación también
                form.value.password_confirmation = ''
            }
        }
        
        if (!form.value.usr_id_ctry) {
            errors.value.usr_id_ctry = 'El país es requerido';
        }
        if (!form.value.roles) {
            errors.value.roles = 'Debe seleccionar un rol';
        }
        
        return Object.keys(errors.value).length === 0;
    };

    const handleSubmit = async () => {
        if (!validateForm()) return

        isSubmitting.value = true
        const formData = new FormData()

        // Preparar los datos según el tipo de operación
        Object.entries(form.value).forEach(([key, value]) => {
            if (key === 'roles' && value) {
                // Enviar role como número único
                formData.append('roles[0]', Number(value))
            } else if (key === 'password' || key === 'password_confirmation') {
                // Solo agregar contraseña si no está vacía
                if (value && value.trim() !== '') {
                    formData.append(key, value)
                }
            } else if (key === 'usr_id_ctry' && value) {
                // usr_id_ctry debe ser número
                formData.append(key, Number(value))
            } else if (key === 'usr_active') {
                // usr_active debe ser booleano o número
                formData.append(key, value ? 1 : 0)
            } else if (value !== null && value !== '') {
                formData.append(key, value)
            }
        })

        try {
            let response
            if (props.updating) {
                // Para actualización con FormData, usar POST con _method=PUT
                formData.append('_method', 'PUT')
                response = await axios.post(
                    route('users.update', props.user.id),
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                )
            } else {
                // Para creación, usar POST
                response = await axios.post(
                    route('users.store'),
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                )
            }
            
            const message = props.updating ? 'Usuario actualizado correctamente' : 'Usuario creado correctamente'
            notificationStore.success(message)
            
            setTimeout(() => {
                const notyfToasts = document.querySelectorAll('.notyf__toast')
                notyfToasts.forEach(toast => {
                    toast.style.opacity = '0'
                    toast.style.transition = 'opacity 0.3s ease-out'
                    setTimeout(() => {
                        toast.remove()
                    }, 300)
                })
            }, 3000)
            
            emit('success')
        } catch (e) {
            console.error('Error details:', e.response?.data)
            if (e.response?.data?.errors) {
                errors.value = e.response.data.errors
            } else {
                notificationStore.error(e.response?.data?.message || 'Error al procesar usuario')
            }
        } finally {
            isSubmitting.value = false
        }
    }

    const getError = (field) => {
        const error = errors.value[field]
        if (!error) return null
        return Array.isArray(error) ? error[0] : error
    }



</script>   

<template>
    <!-- Modo Lectura: Solo mostrar valores usando FormSection -->
    <FormSection v-if="readonly">
        <template #title>
            Detalles del Usuario
        </template>

        <template #description>
            Información completa del usuario seleccionado
        </template>

        <template #form>
            <div class="space-y-6">
                <!-- Encabezado: Foto y Nombre -->
                <div class="flex items-center gap-4 pb-4 border-b">
                    <img v-if="user?.profile_photo_path" 
                        :src="'/storage/' + user.profile_photo_path" 
                        :alt="user?.name" 
                        class="w-16 h-16 rounded-full object-cover border-2 border-indigo-500">
                    <div v-else
                        class="w-16 h-16 rounded-full bg-gray-300 flex items-center justify-center text-2xl font-semibold text-gray-700 border-2 border-indigo-500">
                        {{ user?.name?.charAt(0) }}
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">{{ user?.name }} {{ user?.lastname }}</h3>
                        <p class="text-sm text-gray-600">{{ user?.email }}</p>
                        <div class="mt-2">
                            <span v-if="user?.usr_active"
                                class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800">
                                ✓ Activo
                            </span>
                            <span v-else
                                class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-800">
                                ✗ Inactivo
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Información Personal -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 mb-4">Información Personal</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase">Nombre</p>
                            <p class="mt-1 text-sm text-gray-900">{{ user?.name }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase">Apellido</p>
                            <p class="mt-1 text-sm text-gray-900">{{ user?.lastname }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase">Correo</p>
                            <p class="mt-1 text-sm text-gray-900">{{ user?.email }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase">Teléfono</p>
                            <p class="mt-1 text-sm text-gray-900">{{ user?.phone || 'No especificado' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase">País</p>
                            <p v-if="user?.country" class="mt-1">
                                <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-sm font-medium text-blue-700">
                                    {{ user.country.ctry_name }}
                                </span>
                            </p>
                            <p v-else class="mt-1 text-sm text-gray-900">No especificado</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase">Idiomas</p>
                            <p class="mt-1 text-sm text-gray-900">{{ user?.idiomas || 'No especificado' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Roles Asignados -->
                <div v-if="user?.roles && user.roles.length > 0">
                    <h4 class="text-sm font-semibold text-gray-900 mb-4">Roles Asignados</h4>
                    <div class="flex flex-wrap gap-2">
                        <span v-for="role in user.roles" 
                            :key="role.id"
                            class="inline-flex items-center rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-800">
                            {{ role.name }}
                        </span>
                    </div>
                </div>
            </div>
        </template>

        <template #actions>
            <button @click="$emit('close')" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition font-medium">
                Cerrar
            </button>
        </template>
    </FormSection>

    <!-- Modo Edición/Creación: Formulario editable -->
    <FormSection v-else @submitted="handleSubmit">
        <template #title>
            {{ updating ? 'Actualizar Usuario' : 'Crear Usuario' }}
        </template>

        <template #description>
            {{ updating ? 'Actualizar el usuario seleccionado' : 'Crear un nuevo usuario' }}
        </template>

        <template #form>
            <div class="caja--doble">
                <div class="contenedor--input">
                    <TextInput id="name" v-model="form.name" type="text" autocomplete="given-name" class="campo--input">
                    </TextInput>
                    <InputLabel for="name" value="Nombre"></InputLabel>
                    <InputError :message="errors.name || $page.props.errors.name" class="mt-2" />
                </div>

                <div class="contenedor--input">
                    <TextInput id="lastname" v-model="form.lastname" type="text" autocomplete="family-name"
                        class="campo--input">
                    </TextInput>
                    <InputLabel for="lastname" value="Apellido"></InputLabel>
                    <InputError :message="errors.lastname || $page.props.errors.lastname" class="mt-2" />
                </div>

                <div class="contenedor--input">
                    <TextInput id="email" v-model="form.email" type="email" autocomplete="email" class="campo--input">
                    </TextInput>
                    <InputLabel for="email" value="Correo Electrónico"></InputLabel>
                    <InputError :message="errors.email || $page.props.errors.email" class="mt-2" />
                </div>

                <div class="contenedor--input">
                    <TextInput id="phone" v-model="form.phone" type="tel" autocomplete="tel" class="campo--input">
                    </TextInput>
                    <InputLabel for="phone" value="Teléfono (Opcional)"></InputLabel>
                    <InputError :message="errors.phone || $page.props.errors.phone" class="mt-2" />
                </div>

                <div class="contenedor--input">
                    <select id="usr_id_ctry" v-model.number="form.usr_id_ctry" class="campo--input">
                        <option value="">Selecciona un país</option>
                        <option v-for="country in countries" :key="country.ctry_id" :value="country.ctry_id">
                            {{ country.ctry_name }}
                        </option>
                    </select>
                    <InputLabel for="usr_id_ctry" value="País "></InputLabel>
                    <InputError :message="errors.usr_id_ctry || $page.props.errors.usr_id_ctry" class="mt-2" />
                </div>

                <div v-if="!updating" class="contenedor--input">
                    <TextInput id="password" v-model="form.password" type="password" autocomplete="new-password"
                        class="campo--input">
                    </TextInput>
                    <InputLabel for="password" value="Contraseña"></InputLabel>
                    <InputError :message="errors.password || $page.props.errors.password" class="mt-2" />
                </div>

                <div v-if="updating" class="contenedor--input">
                    <TextInput id="password" v-model="form.password" type="password" autocomplete="new-password"
                        class="campo--input">
                    </TextInput>
                    <InputLabel for="password" value="Contraseña (Dejar en blanco para mantener actual)"></InputLabel>
                    <InputError :message="errors.password || $page.props.errors.password" class="mt-2" />
                </div>

                <div class="contenedor--input">
                    <TextInput id="password_confirmation" v-model="form.password_confirmation" type="password"
                        autocomplete="new-password" class="campo--input">
                    </TextInput>
                    <InputLabel for="password_confirmation" value="Confirmar Contraseña"></InputLabel>
                    <InputError :message="errors.password_confirmation || $page.props.errors.password_confirmation"
                        class="mt-2" />
                </div>

                <div class="contenedor--input">
                    <select id="idiomas" v-model="form.idiomas" class="campo--input">
                        <option value="">Selecciona un idioma</option>
                        <option value="Español">Español</option>
                        <option value="Inglés">Inglés</option>
                        <option value="Francés">Francés</option>
                    </select>
                    <InputLabel for="idiomas" value="Idiomas (Opcional)"></InputLabel>
                    <InputError :message="$page.props.errors.idiomas" class="mt-2" />
                </div>

                <div v-if="updating" class="contenedor--input">
                    <select id="usr_active" v-model.number="form.usr_active" class="campo--input">
                        <option :value="1">Activo</option>
                        <option :value="0">Inactivo</option>
                    </select>
                    <InputLabel for="usr_active" value="Estado"></InputLabel>
                    <InputError :message="errors.usr_active || $page.props.errors.usr_active" class="mt-2" />
                </div>

                <div class="contenedor--input">
                    <select v-model.number="form.roles" class="campo--input">
                        <option value="" disabled>Seleccione un rol</option>
                        <option v-for="role in roles" :key="role.id" :value="role.id">
                            {{ role.name }}
                        </option>
                    </select>

                    <InputLabel value="Rol" />
                    <InputError :message="getError('roles')" class="mt-2" />
                </div>

                <div class="contenedor--input">
                    <input ref="photoInput" id="profile_photo_path" type="file" accept="image/*" @change="handlePhotoChange"
                        class="campo--input text-sm text-gray-500
                            file:mr-4 file:py-2.5 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-indigo-50 file:text-indigo-700
                            hover:file:bg-indigo-100">
                    </input>
                    <InputLabel for="profile_photo_path" :value="updating ? 'Actualizar foto (Opcional)' : 'Subir foto (Opcional)'"></InputLabel>
                    <InputError :message="errors.profile_photo_path || $page.props.errors.profile_photo_path"
                        class="mt-2" />
                    <p class="text-xs text-gray-500 mt-1">Formatos: JPG, PNG, GIF (máx. 5MB)</p>
                </div>

                <div v-if="updating && user?.profile_photo_path" class="contenedor--input">
                    <img :src="'/storage/' + user.profile_photo_path" :alt="user.name" class="size-20 rounded-full object-cover border border-gray-200">
                    <InputLabel value="Foto actual" style="transform: translateY(-6.4em);"/>
                </div>
            </div>
        </template>

        <template #actions>
            <PrimaryButton>
                <template #texto--boton>{{ updating ? 'Actualizar' : 'Crear' }}</template>
                <template #icono--boton>
                    <path v-if="!updating" stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    <path v-if="updating" stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </template>
            </PrimaryButton>
        </template>
    </FormSection>
</template>