<!-- filepath: resources/js/Pages/City/Create.vue -->
<script>
export default {
    name: 'CityCreate'
}
</script>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Form from '@/Components/City/Form.vue'

defineProps({
    countries: {
        type: Array,
        required: true
    }
})

const form = useForm({
    city_name: '',
    city_region: '',
    ctry_id: '',
    city_active: true, 
})
</script>

<template>
    <AppLayout title="Crear Ciudad">
        <template #header>
            <h1 
                v-if="$page.props.user.permissions.includes('create city')"
                class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Ciudad
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <Form 
                            :form="form"
                            :countries="countries"
                            :updating="false"
                            @submit="form.post(route('cities.store'))" 
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>