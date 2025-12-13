<!-- filepath: resources/js/Pages/Country/Edit.vue -->
<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import CountryForm from '@/Components/Country/Form.vue';

const props = defineProps({
    country: {
        type: Object,
        required: true
    }
})

const form = useForm({
    ctry_name: props.country?.ctry_name || '',
    ctry_code: props.country?.ctry_code || '',
    ctry_active: props.country?.ctry_active ?? true,
})
</script>

<template>
    <AppLayout title="Editar País">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar País
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <CountryForm 
                            :updating="true" 
                            :form="form"
                            @submit="form.put(route('countries.update', props.country.ctry_id))" 
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>