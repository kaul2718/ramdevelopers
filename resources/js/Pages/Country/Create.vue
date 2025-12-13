<!-- filepath: resources/js/Pages/Country/Create.vue -->
<script>
export default {
    name: 'CountryCreate'
}
</script>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import CountryForm from '@/Components/Country/Form.vue'

const form = useForm({
    ctry_name: '',
    ctry_code: '',
    ctry_active: true, 
})
</script>

<template>
    <AppLayout title="Crear País">
        <template #header>
            <h1 
                v-if="$page.props.user.permissions.includes('create countries')"
                class="font-semibold text-xl text-gray-800 leading-tight">
                Crear País
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <CountryForm 
                            :form="form"
                            @submit="form.post(route('countries.store'))" 
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>