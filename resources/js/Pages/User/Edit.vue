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
        if (form.name) formData.append('name', form.name);
        if (form.lastname) formData.append('lastname', form.lastname);
        if (form.email) formData.append('email', form.email);
        if (form.phone) formData.append('phone', form.phone);
        if (form.password) formData.append('password', form.password);
        if (form.idiomas) formData.append('idiomas', form.idiomas);
        if (form.usr_id_ctry) formData.append('usr_id_ctry', form.usr_id_ctry);
        formData.append('usr_active', form.usr_active || 0);
        
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
        
        // Agregar archivo SI Y SOLO SI es un File (no agregar si es null o string)
        if (form.profile_photo_path instanceof File) {
            formData.append('profile_photo_path', form.profile_photo_path);
        }
        
        // Agregar método PUT
        formData.append('_method', 'PUT');
        
        try {
            const response = await axios.post(route('users.update', props.user.id), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                }
            });
            // Redirigir después del éxito
            window.location.href = route('users.index');
        } catch (error) {
            console.error('Error updating user:', error.response?.data || error.message);
            if (error.response?.data?.errors) {
                console.error('Validation errors:', error.response.data.errors);
            }
        }
    }
</script>

<template>
    <AppLayout title="Editar Usuario">
        <UserForm 
            :updating="true" 
            :form="form"
            :countries="countries"
            :roles="roles"
            :permissions="permissions"
            @submit="handleSubmit" 
        />
    </AppLayout>
</template>