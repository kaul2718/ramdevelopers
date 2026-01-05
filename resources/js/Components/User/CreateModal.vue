<script setup>
import DialogModal from '@/Components/DialogModal.vue';
import UserForm from '@/Components/User/Form.vue';
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { useNotificationStore } from '@/stores/notificationStore';

const props = defineProps({
    show: {
        type: Boolean,
        required: true
    },
    countries: {
        type: Array,
        default: () => []
    },
    roles: {
        type: Array,
        default: () => []
    },
    permissions: {
        type: Array,
        default: () => []
    }
});

const emit = defineEmits(['close']);

const notificationStore = useNotificationStore();

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
    roles: [],
    profile_photo_path: null
});

const isSubmitting = ref(false);
const errors = ref({});

const validateForm = () => {
    errors.value = {};
    
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
    if (!form.value.password?.trim()) {
        errors.value.password = 'La contraseña es requerida';
    } else if (form.value.password.length < 8) {
        errors.value.password = 'La contraseña debe tener al menos 8 caracteres';
    }
    if (!form.value.password_confirmation?.trim()) {
        errors.value.password_confirmation = 'La confirmación de contraseña es requerida';
    } else if (form.value.password !== form.value.password_confirmation) {
        errors.value.password_confirmation = 'Las contraseñas no coinciden';
    }
    if (!form.value.usr_id_ctry) {
        errors.value.usr_id_ctry = 'El país es requerido';
    }
    if (!form.value.roles || form.value.roles.length === 0) {
        errors.value.roles = 'Debe seleccionar al menos un rol';
    }
    
    return Object.keys(errors.value).length === 0;
};

const handleSubmit = async () => {
    if (!validateForm()) {
        // Los errores se mostrarán en los campos del formulario
        // No necesitamos una notificación genérica
        return;
    }

    isSubmitting.value = true;
    const formData = new FormData();
    
    if (form.value.name) formData.append('name', form.value.name);
    if (form.value.lastname) formData.append('lastname', form.value.lastname);
    if (form.value.email) formData.append('email', form.value.email);
    if (form.value.phone) formData.append('phone', form.value.phone);
    if (form.value.password) formData.append('password', form.value.password);
    if (form.value.password_confirmation) formData.append('password_confirmation', form.value.password_confirmation);
    if (form.value.idiomas) formData.append('idiomas', form.value.idiomas);
    if (form.value.usr_id_ctry) formData.append('usr_id_ctry', form.value.usr_id_ctry);
    formData.append('usr_active', form.value.usr_active ? 1 : 0);
    
    if (form.value.roles && form.value.roles.length > 0) {
        form.value.roles.forEach((role, index) => {
            formData.append(`roles[${index}]`, role);
        });
    }
    
    if (form.value.profile_photo_path instanceof File) {
        formData.append('profile_photo_path', form.value.profile_photo_path);
    }
    
    try {
        await axios.post(route('users.store'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        });
        
        // Guardar flag en sessionStorage para mostrar notificación después del refresco
        sessionStorage.setItem('showCreateNotification', 'true');
        
        // Cerrar modal y refrescar la página
        closeModal();
        router.visit(route('users.index'));
    } catch (error) {
        console.error('Error creating user:', error);
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors;
            const errorMessages = Object.values(errors.value).flat().join(', ');
            notificationStore.error(errorMessages);
        } else {
            notificationStore.error('Error al crear el usuario');
        }
    } finally {
        isSubmitting.value = false;
    }
};

const closeModal = () => {
    // Resetear el formulario
    form.value = {
        name: '',
        lastname: '',
        email: '',
        phone: '',
        idiomas: '',
        usr_id_ctry: null,
        usr_active: true,
        password: '',
        password_confirmation: '',
        roles: [],
        profile_photo_path: null
    };
    emit('close');
};

watch(() => props.show, (newVal) => {
    if (newVal) {
        // Resetear el formulario cuando se abre el modal
        form.value = {
            name: '',
            lastname: '',
            email: '',
            phone: '',
            idiomas: '',
            usr_id_ctry: null,
            usr_active: true,
            password: '',
            password_confirmation: '',
            roles: [],
            profile_photo_path: null
        };
    }
});
</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="2xl">
        <template #title>
            <h2 class="modal-title text-xl">Crear Nuevo Usuario</h2>
        </template>

        <template #content>
            <UserForm 
                :form="form"
                :countries="countries"
                :roles="roles"
                :permissions="permissions"
                :errors="errors"
                @submit="handleSubmit"
            />
        </template>

        <template #footer>
            <button
                @click="closeModal"
                class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                Cancelar
            </button>
            <button
                @click="handleSubmit"
                :disabled="isSubmitting"
                class="px-4 py-2 text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition ml-3 disabled:opacity-50">
                {{ isSubmitting ? 'Guardando...' : 'Crear Usuario' }}
            </button>
        </template>
    </DialogModal>
</template>
