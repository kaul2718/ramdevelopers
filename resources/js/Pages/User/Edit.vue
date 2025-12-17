<!-- filepath: resources/js/Pages/User/Edit.vue -->
<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import UserForm from '@/Components/User/Form.vue';
import axios from 'axios';

const props = defineProps({
    user: {
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
    userRoles: {
        type: Array,
        required: true
    },
    permissions: {
        type: Array,
        default: () => []
    },
    userDirectPermissions: {
        type: Array,
        default: () => []
    }
})

const form = useForm({
    name: props.user?.name || '',
    lastname: props.user?.lastname || '',
    email: props.user?.email || '',
    phone: props.user?.phone || '',
    password: '',
    idiomas: props.user?.idiomas || '',
    profile_photo_path: null,
    usr_id_ctry: props.user?.usr_id_ctry || '',
    usr_active: props.user?.usr_active ? 1 : 0,
    roles: props.userRoles || [],
    permissions: props.userDirectPermissions || [],
})

const handleSubmit = async () => {
    // Crear un FormData nativo para enviar archivos correctamente
    const formData = new FormData();
    
    // Agregar todos los campos
    formData.append('name', form.name);
    formData.append('lastname', form.lastname);
    formData.append('email', form.email);
    formData.append('phone', form.phone);
    formData.append('password', form.password);
    formData.append('idiomas', form.idiomas);
    formData.append('usr_id_ctry', form.usr_id_ctry);
    formData.append('usr_active', form.usr_active);
    
    // Agregar roles como array
    if (form.roles && form.roles.length > 0) {
        form.roles.forEach((role, index) => {
            formData.append(`roles[${index}]`, role);
        });
    }
    
    // Agregar permisos directos como array
    if (form.permissions && form.permissions.length > 0) {
        form.permissions.forEach((permission, index) => {
            formData.append(`permissions[${index}]`, permission);
        });
    }
    
    // Agregar archivo si existe
    if (form.profile_photo_path instanceof File) {
        formData.append('profile_photo_path', form.profile_photo_path);
    }
    
    // Agregar método PUT
    formData.append('_method', 'PUT');
    
    try {
        await axios.post(route('users.update', props.user.id), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        });
        // Redirigir después del éxito
        window.location.href = route('users.index');
    } catch (error) {
        console.error('Error updating user:', error);
    }
}
</script>

<template>
    <AppLayout title="Editar Usuario">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Usuario
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <UserForm 
                            :updating="true" 
                            :form="form"
                            :countries="countries"
                            :roles="roles"
                            :permissions="permissions"
                            @submit="handleSubmit" 
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>