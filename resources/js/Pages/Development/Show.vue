<script>
export default {
    name: 'DevelopmentShow'
}
</script>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
    development: {
        type: Object,
        required: true
    }
})

const showImagePreview = ref(false);
const selectedImage = ref(null);

const openImagePreview = (image) => {
    selectedImage.value = image;
    showImagePreview.value = true;
};

const closeImagePreview = () => {
    showImagePreview.value = false;
    selectedImage.value = null;
};
</script>

<template>
    <AppLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h1 class="font-semibold text-xl text-gray-800 leading-tight">{{ development.devt_title }}</h1>
                <Link :href="route('development.index')" class="text-indigo-600 hover:text-indigo-900">
                    ← Volver
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Información General -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-2xl font-bold mb-6 text-gray-900">Información General</h2>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Título del Desarrollo</label>
                            <p class="text-gray-900">{{ development.devt_title || 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Desarrollador</label>
                            <p class="text-gray-900">{{ development.developer?.devr_commercial_name || 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">País</label>
                            <p class="text-gray-900">{{ development.country?.ctry_name || 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ciudad</label>
                            <p class="text-gray-900">{{ development.city?.city_name || 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dirección</label>
                            <p class="text-gray-900">{{ development.devt_address || 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                            <p class="text-gray-900">{{ development.devt_slug || 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Año de Entrega</label>
                            <p class="text-gray-900">{{ development.devt_delivery_year || 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Estados -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-2xl font-bold mb-6 text-gray-900">Estados</h2>
                    
                    <div class="grid grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estado de Aprobación</label>
                            <span class="inline-flex items-center rounded-md bg-green-50 px-3 py-1 text-sm font-medium text-green-700 border border-green-200">
                                {{ development.approval_status?.apvSta_name || 'N/A' }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estado del Proyecto</label>
                            <span class="inline-flex items-center rounded-md bg-blue-50 px-3 py-1 text-sm font-medium text-blue-700 border border-blue-200">
                                {{ development.business_status?.busiSta_name || 'N/A' }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Proyecto</label>
                            <span class="inline-flex items-center rounded-md bg-purple-50 px-3 py-1 text-sm font-medium text-purple-700 border border-purple-200">
                                {{ development.commercial_status?.commSta_name || 'N/A' }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Vivienda</label>
                            <span class="inline-flex items-center rounded-md bg-orange-50 px-3 py-1 text-sm font-medium text-orange-700 border border-orange-200">
                                {{ development.housing_type?.houTyp_name || 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Precios y Ganancias -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-2xl font-bold mb-6 text-gray-900">Información Financiera</h2>
                    
                    <div class="grid grid-cols-4 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Moneda</label>
                            <p class="text-lg font-semibold text-gray-900">{{ development.currency?.curr_symbol }} {{ development.currency?.curr_name || 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Precio Desde</label>
                            <p class="text-lg font-semibold text-gray-900">{{ development.currency?.curr_symbol }} {{ development.devt_price_from?.toLocaleString() || 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Precio Hasta</label>
                            <p class="text-lg font-semibold text-gray-900">{{ development.currency?.curr_symbol }} {{ development.devt_price_to?.toLocaleString() || 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Honorarios</label>
                            <p class="text-lg font-semibold text-green-600">
                                {{ development.devt_estimated_profit ? (development.devt_estimated_profit_is_percentage ? `${development.devt_estimated_profit}%` : `${development.currency?.curr_symbol} ${development.devt_estimated_profit?.toLocaleString()}`) : '0' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Características de la Propiedad -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-2xl font-bold mb-6 text-gray-900">Características de la Propiedad</h2>
                    
                    <div class="grid grid-cols-3 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Número de Dormitorios</label>
                            <p class="text-lg font-semibold text-gray-900">{{ development.devt_bedrooms === 0 ? 'No' : `Sí (${development.devt_bedrooms})` }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Trasteros</label>
                            <p class="text-lg font-semibold text-gray-900">{{ development.devt_storage_rooms === 0 ? 'No' : `Sí (${development.devt_storage_rooms})` }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Parking</label>
                            <p class="text-lg font-semibold text-gray-900">{{ development.devt_parking_spaces === 0 ? 'No' : `Sí (${development.devt_parking_spaces})` }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Terraza</label>
                            <p class="text-lg font-semibold text-gray-900">{{ development.devt_terraces === 0 ? 'No' : `Sí (${development.devt_terraces})` }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Piscina</label>
                            <p class="text-lg font-semibold text-gray-900">{{ development.devt_swimming_pools === 0 ? 'No' : `Sí (${development.devt_swimming_pools})` }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Área Infantil</label>
                            <p class="text-lg font-semibold text-gray-900">{{ development.devt_children_areas === 0 ? 'No' : `Sí (${development.devt_children_areas})` }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Zonas Verdes</label>
                            <p class="text-lg font-semibold text-gray-900">{{ development.devt_green_zones === 0 ? 'No' : `Sí (${development.devt_green_zones})` }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ascensor</label>
                            <p class="text-lg font-semibold text-gray-900">{{ development.devt_elevators === 0 ? 'No' : `Sí (${development.devt_elevators})` }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Golf</label>
                            <p class="text-lg font-semibold text-gray-900">{{ development.devt_golf_courses === 0 ? 'No' : `Sí (${development.devt_golf_courses})` }}</p>
                        </div>
                    </div>
                </div>

                <!-- Flags -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-2xl font-bold mb-6 text-gray-900">Configuración</h2>
                    
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" :checked="development.devt_is_featured" disabled class="rounded">
                            <label class="ml-2 text-gray-700">Mostrar como Destacado</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" :checked="development.devt_active" disabled class="rounded">
                            <label class="ml-2 text-gray-700">Activo</label>
                        </div>
                    </div>
                </div>

                <!-- Descripciones -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-2xl font-bold mb-6 text-gray-900">Descripciones</h2>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Resumen</label>
                        <p class="text-gray-900 bg-gray-50 p-3 rounded">{{ development.devt_short_description || 'Sin resumen' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Descripción </label>
                        <p class="text-gray-900 bg-gray-50 p-3 rounded whitespace-pre-line">{{ development.devt_long_description || 'Sin descripción' }}</p>
                    </div>
                </div>

                <!-- Imágenes -->
                <div v-if="development.images && development.images.length > 0" class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-2xl font-bold mb-6 text-gray-900">Galería de Imágenes</h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div v-for="image in development.images" :key="image.devImg_id" class="border rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                            <div class="relative group">
                                <img :src="`/storage/${image.devImg_url}`" :alt="image.devImg_title" class="w-full h-48 object-cover">
                                <div v-if="image.devImg_is_cover" class="absolute top-2 right-2 bg-yellow-400 text-yellow-900 px-2 py-1 rounded text-xs font-semibold">
                                    ★ Portada
                                </div>
                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <button @click="openImagePreview(image)" class="px-3 py-2 bg-blue-600 text-white rounded text-xs font-medium hover:bg-blue-700">
                                        Ver
                                    </button>
                                </div>
                            </div>
                            <div class="p-3">
                                <p class="font-medium text-gray-900">{{ image.devImg_title }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Archivos -->
                <div v-if="development.files && development.files.length > 0" class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-2xl font-bold mb-6 text-gray-900">Documentos Adjuntos</h2>
                    
                    <div class="space-y-3">
                        <div v-for="file in development.files" :key="file.devFile_id" class="border rounded-lg p-4 flex items-center justify-between hover:bg-gray-50">
                            <div class="flex items-center space-x-3 flex-1">
                                <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">{{ file.devFile_name }}</p>
                                    <p class="text-xs text-gray-500">{{ file.documentType?.docTyp_name || 'Documento' }}</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a :href="`/storage/${file.devFile_url}`" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-medium">
                                    Ver
                                </a>
                                <a :href="`/storage/${file.devFile_url}`" download :aria-label="`Descargar ${file.devFile_name}`" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm font-medium">
                                    Descargar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de preview de imagen -->
        <div v-if="showImagePreview" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50">
            <div class="relative max-w-4xl max-h-[90vh] bg-white rounded-lg overflow-hidden">
                <!-- Botón cerrar -->
                <button 
                    @click="closeImagePreview"
                    class="absolute top-4 right-4 text-gray-700 hover:text-gray-900 bg-white rounded-full p-2 shadow-lg z-10"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                
                <!-- Imagen -->
                <img 
                    v-if="selectedImage"
                    :src="`/storage/${selectedImage.devImg_url}`" 
                    :alt="selectedImage.devImg_title"
                    class="w-full h-full object-contain"
                >
                
                <!-- Título y info -->
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
                    <p class="text-white font-semibold">{{ selectedImage?.devImg_title }}</p>
                    <div v-if="selectedImage?.devImg_is_cover" class="mt-2 inline-block bg-yellow-400 text-yellow-900 px-2 py-1 rounded text-xs font-semibold">
                        ★ Portada
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
