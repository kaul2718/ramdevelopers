<script>
export default {
    name: 'DeveloperEdit'
}
</script>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Form from '@/Components/Developer/Form.vue';
import { Link } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/vue3'
import { watch } from 'vue'

const props = defineProps({
    developer: {
        type: Object,
        required: true
    },
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
    devr_active: true,
})

// Poblamos el formulario con los datos del desarrollador cuando está disponible
watch(() => props.developer, (newDeveloper) => {
    form.devr_commercial_name = newDeveloper.devr_commercial_name
    form.devr_legal_name = newDeveloper.devr_legal_name
    form.devr_email_contact = newDeveloper.devr_email_contact
    form.devr_phone_contact = newDeveloper.devr_phone_contact
    form.devr_website = newDeveloper.devr_website
    form.ctry_id = newDeveloper.ctry_id
    form.user_id = newDeveloper.user_id
    form.devr_active = newDeveloper.devr_active
}, { immediate: true })

</script>

<template>
    <AppLayout title="Editar Desarrollador">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">Editar Desarrollador</h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <Form 
                            :form="form"
                            :countries="countries"
                            :users="users"
                            :updating="true"
                            @submit="form.put(route('developers.update', props.developer.devr_id))" 
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
