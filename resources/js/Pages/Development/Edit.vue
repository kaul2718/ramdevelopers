<script>
export default {
    name: 'DevelopmentEdit'
}
</script>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Form from '@/Components/Development/Form.vue';
import FileModal from '@/Components/Development/FileModal.vue';
import ImageModal from '@/Components/Development/ImageModal.vue';
import { Link, router } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/vue3'
import { watch, ref } from 'vue'
import axios from 'axios'
import { useNotificationStore } from '@/stores/notificationStore'

const notificationStore = useNotificationStore()

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
    },
    housingTypes: {
        type: Array,
        required: true
    },
    currencies: {
        type: Array,
        required: true
    },
    documentTypes: {
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
    houTyp_id: '',
    curr_id: '',
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
    devt_storage_rooms: '0',
    devt_parking_spaces: '0',
    devt_terraces: '0',
    devt_swimming_pools: '0',
    devt_children_areas: '0',
    devt_green_zones: '0',
    devt_elevators: '0',
    devt_golf_courses: '0',
    devt_bedrooms: '0'
})

const showFileModal = ref(false);
const showImageModal = ref(false);
const showImagePreview = ref(false);
const selectedImage = ref(null);

// Poblamos el formulario con los datos del desarrollo cuando está disponible
watch(() => props.development, (newDevelopment) => {
    form.devr_id = newDevelopment.devr_id
    form.ctry_id = newDevelopment.ctry_id
    form.city_id = newDevelopment.city_id
    form.apvSta_id = newDevelopment.apvSta_id
    form.busiSta_id = newDevelopment.busiSta_id
    form.commSta_id = newDevelopment.commSta_id
    form.houTyp_id = newDevelopment.houTyp_id
    form.curr_id = newDevelopment.curr_id || ''
    form.devt_title = newDevelopment.devt_title
    form.devt_slug = newDevelopment.devt_slug
    form.devt_address = newDevelopment.devt_address
    form.devt_short_description = newDevelopment.devt_short_description
    form.devt_long_description = newDevelopment.devt_long_description
    form.devt_price_from = String(newDevelopment.devt_price_from)
    form.devt_price_to = String(newDevelopment.devt_price_to)
    form.devt_delivery_year = String(newDevelopment.devt_delivery_year)
    form.devt_estimated_profit = String(newDevelopment.devt_estimated_profit)
    form.devt_is_featured = Boolean(newDevelopment.devt_is_featured)
    form.devt_active = Boolean(newDevelopment.devt_active)
    form.devt_storage_rooms = String(newDevelopment.devt_storage_rooms || '0')
    form.devt_parking_spaces = String(newDevelopment.devt_parking_spaces || '0')
    form.devt_terraces = String(newDevelopment.devt_terraces || '0')
    form.devt_swimming_pools = String(newDevelopment.devt_swimming_pools || '0')
    form.devt_children_areas = String(newDevelopment.devt_children_areas || '0')
    form.devt_green_zones = String(newDevelopment.devt_green_zones || '0')
    form.devt_elevators = String(newDevelopment.devt_elevators || '0')
    form.devt_golf_courses = String(newDevelopment.devt_golf_courses || '0')
    form.devt_bedrooms = String(newDevelopment.devt_bedrooms || '0')
}, { immediate: true })

const confirmDelete = (itemId, type) => {
    if (confirm(`¿Está seguro que desea eliminar este ${type === 'file' ? 'archivo' : 'imagen'}?`)) {
        deleteItem(itemId, type);
    }
};

const deleteItem = async (itemId, type) => {
    try {
        const endpoint = type === 'file' 
            ? `/developmentfile/${itemId}` 
            : `/developmentimages/${itemId}`;
        
        
        // Agregar headers con CSRF token
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        const response = await axios.delete(endpoint, {
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            }
        });
        
        // Mostrar notificación de éxito
        const successMessage = type === 'file' 
            ? 'Archivo eliminado correctamente' 
            : 'Imagen eliminada correctamente';
        notificationStore.success(successMessage);
        
        // Recargar la página después de eliminar
        setTimeout(() => {
            router.reload();
        }, 1000);
    } catch (error) {
        notificationStore.error('Hubo un error al eliminar. Por favor, intenta de nuevo.');
    }
};

const handleFilesSaved = () => {
    showFileModal.value = false;
    notificationStore.success('Archivo agregado correctamente')
    setTimeout(() => {
        router.reload();
    }, 1000)
};

const handleImagesSaved = () => {
    showImageModal.value = false;
    notificationStore.success('Imagen agregada correctamente')
    setTimeout(() => {
        router.reload();
    }, 1000)
};

const openImagePreview = (image) => {
    selectedImage.value = image;
    showImagePreview.value = true;
};

const closeImagePreview = () => {
    showImagePreview.value = false;
    selectedImage.value = null;
};

const handleSave = async () => {
    try {
        await form.put(route('development.update', props.development.devt_id))
        notificationStore.success('Desarrollo actualizado correctamente')
    } catch (error) {
        notificationStore.error('Error al actualizar el desarrollo')
    }
};

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
                            :housingTypes="housingTypes"
                            :currencies="currencies"
                            :updating="true"
                            :show-submit-button="false"
                            @submit="form.put(route('development.update', props.development.devt_id))" 
                        />
                    </div>
                </div>

                <!-- Sección para administrar archivos -->
                <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Documentos Adjuntos</h2>
                            <button @click="showFileModal = true" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm font-medium">
                                Agregar Archivo
                            </button>
                        </div>
                        <div v-if="development.files && development.files.length > 0" class="space-y-3">
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
                                    <a :href="`/storage/${file.devFile_url}`" target="_blank" class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-xs font-medium">
                                        Ver
                                    </a>
                                    <button @click="confirmDelete(file.devFile_id, 'file')" class="px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-xs font-medium">
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center py-6">
                            <p class="text-gray-500">No hay archivos agregados</p>
                        </div>
                    </div>
                </div>

                <!-- Sección para administrar imágenes -->
                <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Galería de Imágenes</h2>
                            <button @click="showImageModal = true" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm font-medium">
                                Agregar Imagen
                            </button>
                        </div>
                        <div v-if="development.images && development.images.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div v-for="image in development.images" :key="image.devImg_id" class="border rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                                <div class="relative group">
                                    <img :src="`/storage/${image.devImg_url}`" :alt="image.devImg_title" class="w-full h-48 object-cover">
                                    <div v-if="image.devImg_is_cover" class="absolute top-2 right-2 bg-yellow-400 text-yellow-900 px-2 py-1 rounded text-xs font-semibold">
                                        ★ Portada
                                    </div>
                                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                        <button @click="openImagePreview(image)" class="px-3 py-2 bg-blue-600 text-white rounded text-xs font-medium">
                                            Ver
                                        </button>
                                        <button @click="confirmDelete(image.devImg_id, 'image')" class="px-3 py-2 bg-red-600 text-white rounded text-xs font-medium">
                                            Eliminar
                                        </button>
                                    </div>
                                </div>
                                <div class="p-3">
                                    <p class="font-medium text-gray-900">{{ image.devImg_title }}</p>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center py-6">
                            <p class="text-gray-500">No hay imágenes agregadas</p>
                        </div>
                    </div>
                </div>

                <!-- Botón de guardar cambios -->
                <div class="mt-8 flex justify-end">
                    <button 
                        @click="handleSave"
                        :disabled="form.processing"
                        class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:bg-gray-400 font-semibold"
                    >
                        {{ form.processing ? 'Guardando...' : 'Guardar Cambios' }}
                    </button>
                </div>

                <!-- Modales -->
                <FileModal 
                    :show="showFileModal"
                    :development="development"
                    :documentTypes="documentTypes"
                    @close="showFileModal = false"
                    @saved="handleFilesSaved"
                />

                <ImageModal 
                    :show="showImageModal"
                    :development="development"
                    @close="showImageModal = false"
                    @saved="handleImagesSaved"
                />


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
            </div>
        </div>
    </AppLayout>
</template>
