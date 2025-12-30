<!-- filepath: resources/js/Components/User/ViewModal.vue -->
<script setup>
import DialogModal from '@/Components/DialogModal.vue';
import UserForm from './Form.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import axios from 'axios';
import { useNotificationStore } from '@/stores/notificationStore';

const notificationStore = useNotificationStore();

const props = defineProps({
    show: {
        type: Boolean,
        required: true
    },
    user: {
        type: Object,
        default: null
    },
    countries: {
        type: Array,
        default: () => []
    },
    roles: {
        type: Array,
        default: () => []
    },
    userRoles: {
        type: Array,
        default: () => []
    },
    permissions: {
        type: Array,
        default: () => []
    },
    userDirectPermissions: {
        type: Array,
        default: () => []
    },
    isEditingInitial: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['close']);

const isEditing = ref(false);
const form = ref({
    name: '',
    lastname: '',
    email: '',
    phone: '',
    password: '',
    idiomas: '',
    profile_photo_path: null,
    usr_id_ctry: '',
    usr_active: 0,
    roles: [],
    permissions: [],
});

// Almacenar los datos originales para detectar cambios
const originalForm = ref({
    name: '',
    lastname: '',
    email: '',
    phone: '',
    password: '',
    idiomas: '',
    profile_photo_path: null,
    usr_id_ctry: '',
    usr_active: 0,
    roles: [],
    permissions: [],
});

// Computed para detectar si hay cambios
const hasChanges = computed(() => {
    return (
        form.value.name !== originalForm.value.name ||
        form.value.lastname !== originalForm.value.lastname ||
        form.value.email !== originalForm.value.email ||
        form.value.phone !== originalForm.value.phone ||
        form.value.idiomas !== originalForm.value.idiomas ||
        form.value.usr_id_ctry !== originalForm.value.usr_id_ctry ||
        form.value.usr_active !== originalForm.value.usr_active ||
        JSON.stringify(form.value.roles) !== JSON.stringify(originalForm.value.roles) ||
        form.value.profile_photo_path !== null // Si se seleccionó una foto
    );
});

// Watcher para inicializar en modo edición o vista cuando el modal se abre
watch(() => props.show, (newVal) => {
    if (newVal) {
        // Modal se está abriendo
        if (props.isEditingInitial) {
            // Abrir directamente en modo edición
            if (props.user) {
                // Extraer los IDs de los roles del objeto roles anidado
                const roleIds = props.userRoles.map(role => {
                    return typeof role === 'object' ? role.id : role;
                });
                
                const formData = {
                    name: props.user.name || '',
                    lastname: props.user.lastname || '',
                    email: props.user.email || '',
                    phone: props.user.phone || '',
                    password: '',
                    idiomas: props.user.idiomas || '',
                    profile_photo_path: null,
                    usr_id_ctry: props.user.usr_id_ctry || '',
                    usr_active: props.user.usr_active ? 1 : 0,
                    roles: roleIds,
                    permissions: props.userDirectPermissions || [],
                };
                
                form.value = formData;
                // Guardar los datos originales
                originalForm.value = JSON.parse(JSON.stringify(formData));
            }
            isEditing.value = true;
        } else {
            // Abrir en modo vista
            isEditing.value = false;
        }
    }
});

const toggleEdit = () => {
    if (isEditing.value) {
        // Si estaba editando, volver a vista
        isEditing.value = false;
    } else {
        // Si estaba viendo, preparar para editar
        if (props.user) {
            // Extraer los IDs de los roles del objeto roles anidado
            const roleIds = props.userRoles.map(role => {
                return typeof role === 'object' ? role.id : role;
            });
            
            const formData = {
                name: props.user.name || '',
                lastname: props.user.lastname || '',
                email: props.user.email || '',
                phone: props.user.phone || '',
                password: '',
                idiomas: props.user.idiomas || '',
                profile_photo_path: null,
                usr_id_ctry: props.user.usr_id_ctry || '',
                usr_active: props.user.usr_active ? 1 : 0,
                roles: roleIds,
                permissions: props.userDirectPermissions || [],
            };
            
            form.value = formData;
            // Guardar los datos originales
            originalForm.value = JSON.parse(JSON.stringify(formData));
        }
        isEditing.value = true;
    }
};

const isSubmitting = ref(false);

const handleSubmit = async () => {
    // Validar si hay cambios
    if (!hasChanges.value) {
        notificationStore.error('No has realizado cambios');
        return;
    }
    
    isSubmitting.value = true;
    const formData = new FormData();
    
    if (form.value.name) formData.append('name', form.value.name);
    if (form.value.lastname) formData.append('lastname', form.value.lastname);
    if (form.value.email) formData.append('email', form.value.email);
    if (form.value.phone) formData.append('phone', form.value.phone);
    if (form.value.password) formData.append('password', form.value.password);
    if (form.value.idiomas) formData.append('idiomas', form.value.idiomas);
    if (form.value.usr_id_ctry) formData.append('usr_id_ctry', form.value.usr_id_ctry);
    formData.append('usr_active', form.value.usr_active || 0);
    
    if (form.value.roles && form.value.roles.length > 0) {
        form.value.roles.forEach((role, index) => {
            formData.append(`roles[${index}]`, role);
        });
    }
    
    if (form.value.permissions && form.value.permissions.length > 0) {
        form.value.permissions.forEach((permission, index) => {
            formData.append(`permissions[${index}]`, permission);
        });
    }
    
    if (form.value.profile_photo_path instanceof File) {
        formData.append('profile_photo_path', form.value.profile_photo_path);
    }
    
    formData.append('_method', 'PUT');
    
    try {
        await axios.post(route('users.update', props.user.id), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        });
        
        // Cerrar la modal
        closeModal();
        // Guardar flag en sessionStorage para mostrar notificación después
        sessionStorage.setItem('showSuccessNotification', 'true');
        // Refrescar la página usando Inertia para ver los cambios
        router.visit(route('users.index'));
        
    } catch (error) {
        isSubmitting.value = false;
        console.error('Error updating user:', error.response?.data || error.message);
        notificationStore.error('Error al actualizar el usuario');
        if (error.response?.data?.errors) {
            console.error('Validation errors:', error.response.data.errors);
        }
    }
};

const closeModal = () => {
    isEditing.value = false;
    emit('close');
};
</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="2xl">
        <template #title v-if="user">
            {{ isEditing ? 'Editar Usuario' : 'Información del Usuario' }}
        </template>

        <template #content v-if="user">
            <!-- Vista -->
            <div v-if="!isEditing" class="space-y-4">
                <!-- Foto y Nombre -->
                <div class="flex items-center gap-4 pb-4 border-b">
                    <img v-if="user.profile_photo_url" 
                        :src="user.profile_photo_url" 
                        :alt="user.name" 
                        class="w-16 h-16 rounded-full object-cover border-2 border-indigo-500">
                    <div v-else 
                        class="w-16 h-16 rounded-full bg-gray-300 flex items-center justify-center text-2xl font-semibold text-gray-700 border-2 border-indigo-500">
                        {{ user.name.charAt(0) }}
                    </div>
                    <div>
                        <h3 class="modal-title">{{ user.name }} {{ user.lastname }}</h3>
                        <p class="modal-subtitle">{{ user.email }}</p>
                        <div class="mt-2">
                            <span v-if="user.usr_active" 
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
                    <h4 class="modal-section-title">Información Personal</h4>
                    <div class="modal-grid">
                        <div>
                            <p class="modal-label">Nombre</p>
                            <p class="modal-value">{{ user.name }}</p>
                        </div>
                        <div>
                            <p class="modal-label">Apellido</p>
                            <p class="modal-value">{{ user.lastname }}</p>
                        </div>
                        <div>
                            <p class="modal-label">Teléfono</p>
                            <p class="modal-value">{{ user.phone || 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="modal-label">Idioma</p>
                            <p class="modal-value">{{ user.idiomas || 'No especificado' }}</p>
                        </div>
                        <div v-if="user.country" class="col-span-2">
                            <p class="modal-label">País</p>
                            <p class="modal-value inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-sm font-medium text-blue-700">
                                {{ user.country.ctry_name }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Roles -->
                <div v-if="user.roles && user.roles.length > 0">
                    <h4 class="modal-section-title">Roles Asignados</h4>
                    <div class="flex flex-wrap gap-2">
                        <span v-for="role in user.roles" 
                            :key="role.id" 
                            class="inline-flex items-center rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-800">
                            {{ role.name }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Formulario de Edición -->
            <div v-else>
                <UserForm
                    :form="form"
                    :countries="countries"
                    :roles="roles"
                    :permissions="permissions"
                    :updating="true"
                    :user="user"
                    @submit="handleSubmit"
                />
            </div>
        </template>

        <template #footer v-if="user">
            <button
                @click="closeModal"
                class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                {{ isEditing ? 'Cancelar' : 'Cerrar' }}
            </button>
            <button 
                v-if="!isEditing && $page.props.user.permissions.includes('update users')"
                @click="toggleEdit"
                class="ml-2 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                Editar
            </button>
            <button 
                v-if="isEditing"
                @click="handleSubmit"
                :disabled="isSubmitting || !hasChanges"
                class="ml-2 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-green-600 flex items-center gap-2">
                <span v-if="isSubmitting" class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                {{ isSubmitting ? 'Guardando...' : 'Guardar Cambios' }}
            </button>
        </template>
    </DialogModal>
</template>

