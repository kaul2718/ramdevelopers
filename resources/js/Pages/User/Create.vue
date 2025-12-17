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
        <template #header>
            <h1 
                v-if="$page.props.user.permissions.includes('create users')"
                class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Usuario
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <UserForm 
                            :form="form"
                            :countries="countries"
                            :roles="roles"
                            :permissions="permissions"
                            @submit="form.post(route('users.store'), { multipart: true })" 
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>