<!-- filepath: resources/js/Pages/User/Create.vue -->
<script>
export default {
    name: 'UserCreate'
}
</script>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import UserForm from '@/Components/User/Form.vue'

defineProps({
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
    }
})

const form = useForm({
    name: '',
    lastname: '',
    email: '',
    phone: '',
    password: '',
    idiomas: '',
    profile_photo_path: null,
    usr_id_ctry: '',
    usr_active: 1,
    roles: [],
    permissions: [],
})
</script>

<template>
    <AppLayout title="Crear Usuario">
        <UserForm 
            :form="form"
            :countries="countries"
            :roles="roles"
            :permissions="permissions"
            @submit="form.post(route('users.store'), { multipart: true })" 
        />
    </AppLayout>
</template>