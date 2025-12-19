<script>
export default {
    name: 'DevelopmentEdit'
}
</script>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Form from '@/Components/Development/Form.vue';
import { Link } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/vue3'
import { watch } from 'vue'

const props = defineProps({
    development: {
        type: Object,
        required: true
    },
    developers: {
        type: Array,
        required: true
    },
    countries: {
        type: Array,
        required: true
    },
    cities: {
        type: Array,
        required: true
    },
    approvalStatuses: {
        type: Array,
        required: true
    },
    businessStates: {
        type: Array,
        required: true
    },
    commercialStatuses: {
        type: Array,
        required: true
    }
})

const form = useForm({
    devr_id: '',
    ctry_id: '',
    city_id: '',
    apvSta_id: '',
    busiSta_id: '',
    commSta_id: '',
    devt_title: '',
    devt_slug: '',
    devt_address: '',
    devt_short_description: '',
    devt_long_description: '',
    devt_price_from: '',
    devt_price_to: '',
    devt_delivery_year: '',
    devt_estimated_profit: '',
    devt_is_featured: false,
    devt_active: true,
})

// Poblamos el formulario con los datos del desarrollo cuando está disponible
watch(() => props.development, (newDevelopment) => {
    form.devr_id = newDevelopment.devr_id
    form.ctry_id = newDevelopment.ctry_id
    form.city_id = newDevelopment.city_id
    form.apvSta_id = newDevelopment.apvSta_id
    form.busiSta_id = newDevelopment.busiSta_id
    form.commSta_id = newDevelopment.commSta_id
    form.devt_title = newDevelopment.devt_title
    form.devt_slug = newDevelopment.devt_slug
    form.devt_address = newDevelopment.devt_address
    form.devt_short_description = newDevelopment.devt_short_description
    form.devt_long_description = newDevelopment.devt_long_description
    form.devt_price_from = newDevelopment.devt_price_from
    form.devt_price_to = newDevelopment.devt_price_to
    form.devt_delivery_year = newDevelopment.devt_delivery_year
    form.devt_estimated_profit = newDevelopment.devt_estimated_profit
    form.devt_is_featured = newDevelopment.devt_is_featured
    form.devt_active = newDevelopment.devt_active
}, { immediate: true })

</script>

<template>
    <AppLayout title="Editar Desarrollo">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">Editar Desarrollo</h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <Form 
                            :form="form"
                            :developers="developers"
                            :countries="countries"
                            :cities="cities"
                            :approvalStatuses="approvalStatuses"
                            :businessStates="businessStates"
                            :commercialStatuses="commercialStatuses"
                            :updating="true"
                            @submit="form.put(route('development.update', props.development.devt_id))" 
                        />
                    </div>
                </div>

                <!-- Sección para administrar archivos -->
                <div v-if="development.developmentFiles" class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-lg font-semibold mb-4">Archivos del Desarrollo</h2>
                        <div v-if="development.developmentFiles.length > 0" class="space-y-2">
                            <div v-for="file in development.developmentFiles" :key="file.devFile_id" class="flex justify-between items-center p-3 bg-gray-50 rounded">
                                <span>{{ file.devFile_name }}</span>
                                <Link :href="route('developmentfile.destroy', file.devFile_id)" method="delete" as="button" class="text-red-600 hover:text-red-900">Eliminar</Link>
                            </div>
                        </div>
                        <p v-else class="text-gray-500">No hay archivos agregados</p>
                        <Link :href="route('developmentfile.create', {development_id: development.devt_id})" class="mt-4 inline-block text-white bg-indigo-500 hover:bg-indigo-700 py-2 px-4 rounded">
                            Agregar Archivo
                        </Link>
                    </div>
                </div>

                <!-- Sección para administrar imágenes -->
                <div v-if="development.developmentImages" class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-lg font-semibold mb-4">Imágenes del Desarrollo</h2>
                        <div v-if="development.developmentImages.length > 0" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            <div v-for="image in development.developmentImages" :key="image.devImg_id" class="relative group">
                                <img :src="image.devImg_url" :alt="`Imagen ${image.devImg_order}`" class="w-full h-32 object-cover rounded">
                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded flex items-center justify-center gap-2">
                                    <button v-if="image.devImg_isCover" class="text-xs bg-yellow-500 text-white px-2 py-1 rounded">Portada</button>
                                    <Link :href="route('developmentimage.destroy', image.devImg_id)" method="delete" as="button" class="text-xs bg-red-600 text-white px-2 py-1 rounded">Eliminar</Link>
                                </div>
                            </div>
                        </div>
                        <p v-else class="text-gray-500">No hay imágenes agregadas</p>
                        <Link :href="route('developmentimage.create', {development_id: development.devt_id})" class="mt-4 inline-block text-white bg-indigo-500 hover:bg-indigo-700 py-2 px-4 rounded">
                            Agregar Imagen
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
