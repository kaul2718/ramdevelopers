<script>
export default {
    name: 'CityEdit'
}
</script>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Form from '@/Components/City/Form.vue';
import { Link } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/vue3'
import { watch } from 'vue'

const props = defineProps({
    city: {
        type: Object,
        required: true
    },
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

// Poblamos el formulario con los datos de la ciudad cuando está disponible
watch(() => props.city, (newCity) => {
    form.city_name = newCity.city_name
    form.city_region = newCity.city_region
    form.ctry_id = newCity.ctry_id
    form.city_active = newCity.city_active
}, { immediate: true })

</script>

<template>
    <AppLayout title="Editar Ciudad">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">Editar Ciudad</h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <Form 
                            :form="form"
                            :countries="countries"
                            :updating="true"
                            @submit="form.put(route('cities.update', props.city.city_id))" 
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
