<script>
export default {
    name: 'DeveloperCreate'
}
</script>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Form from '@/Components/Developer/Form.vue'

defineProps({
    countries: {
        type: Array,
        required: true
    },
    users: {
        type: Array,
        required: true
    }
})

const form = useForm({
    devr_commercial_name: '',
    devr_legal_name: '',
    devr_email_contact: '',
    devr_phone_contact: '',
    devr_website: '',
    ctry_id: '',
    user_id: '',
})
</script>

<template>
    <AppLayout title="Crear Desarrollador">
        <template #header>
            <h1 
                v-if="$page.props.user.permissions.includes('create developer')"
                class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Desarrollador
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <Form 
                            :form="form"
                            :countries="countries"
                            :users="users"
                            :updating="false"
                            @submit="form.post(route('developers.store'))" 
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
