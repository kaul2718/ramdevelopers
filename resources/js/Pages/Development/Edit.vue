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
import PrimaryButton from '@/Components/PrimaryButton.vue';
import HeaderBody from '@/Components/HeaderBody.vue';
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
    devt_estimated_profit_is_percentage: false,
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
    form.devt_estimated_profit_is_percentage = Boolean(newDevelopment.devt_estimated_profit_is_percentage)
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
    // Validar honorarios antes de guardar
    if (form.devt_estimated_profit_is_percentage && form.devt_estimated_profit !== '') {
        const profit = parseFloat(form.devt_estimated_profit)
        if (isNaN(profit) || profit < 1 || profit > 100) {
            notificationStore.error('Si es porcentaje, el valor debe estar entre 1 y 100')
            return
        }
    }

    try {
        await form.put(route('development.update', props.development.devt_id))
        notificationStore.success('Desarrollo actualizado correctamente')
        setTimeout(() => {
            router.reload()
        }, 1000)
    } catch (error) {
        notificationStore.error('Error al actualizar el desarrollo')
    }
};

const openFile = (file) => {
    window.open(`/storage/${file.devFile_url}`, '_blank');
};


</script>

<template>
    <AppLayout title="Editar Desarrollo">
        <Form :form="form" :developers="developers" :countries="countries" :cities="cities"
            :approvalStatuses="approvalStatuses" :businessStates="businessStates"
            :commercialStatuses="commercialStatuses" :housingTypes="housingTypes" :currencies="currencies"
            :updating="true" :show-submit-button="false"
            @submit="form.put(route('development.update', props.development.devt_id))" />

        <!-- Sección para administrar archivos -->
        <div class="bloque--horizontal">
            <h2></h2>
            <HeaderBody titulo-body="Documentos adjuntos" permisos="create users" nombre-btn="Agregar Archivo"
                @create="showFileModal = true" />
            <div v-if="development.files && development.files.length > 0" class="caja--tabla" style="margin-top: -2em">
                <table>
                    <thead>
                        <tr>
                            <th>Nombre archivo</th>
                            <th>Tipo</th>
                            <th>Borrar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="file in development.files" :key="file.devFile_id" @click="openFile(file)">
                            <td>{{ file.devFile_name }}</td>
                            <td>{{ file.docTyp_id?.docTyp_name || 'Documento' }}</td>
                            <td class="botonera--tabla">
                                <button @click.stop="confirmDelete(file.devFile_id, 'file')" class="btn--tipo2"
                                    title="Eliminar campo">
                                    <svg class="size-5" viewBox="0 0 20 20" fill="CurrentColor"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M4.00031 5.49999V4.69999H3.20031V5.49999H4.00031ZM16.0003 5.49999H16.8003V4.69999H16.0003V5.49999ZM17.5003 5.49999L17.5003 6.29999C17.9421 6.29999 18.3003 5.94183 18.3003 5.5C18.3003 5.05817 17.9421 4.7 17.5003 4.69999L17.5003 5.49999ZM9.30029 9.24997C9.30029 8.80814 8.94212 8.44997 8.50029 8.44997C8.05847 8.44997 7.70029 8.80814 7.70029 9.24997H9.30029ZM7.70029 13.75C7.70029 14.1918 8.05847 14.55 8.50029 14.55C8.94212 14.55 9.30029 14.1918 9.30029 13.75H7.70029ZM12.3004 9.24997C12.3004 8.80814 11.9422 8.44997 11.5004 8.44997C11.0585 8.44997 10.7004 8.80814 10.7004 9.24997H12.3004ZM10.7004 13.75C10.7004 14.1918 11.0585 14.55 11.5004 14.55C11.9422 14.55 12.3004 14.1918 12.3004 13.75H10.7004ZM4.00031 6.29999H16.0003V4.69999H4.00031V6.29999ZM15.2003 5.49999V12.5H16.8003V5.49999H15.2003ZM11.0003 16.7H9.00031V18.3H11.0003V16.7ZM4.80031 12.5V5.49999H3.20031V12.5H4.80031ZM9.00031 16.7C7.79918 16.7 6.97882 16.6983 6.36373 16.6156C5.77165 16.536 5.49093 16.3948 5.29823 16.2021L4.16686 17.3334C4.70639 17.873 5.38104 18.0979 6.15053 18.2013C6.89702 18.3017 7.84442 18.3 9.00031 18.3V16.7ZM3.20031 12.5C3.20031 13.6559 3.19861 14.6033 3.29897 15.3498C3.40243 16.1193 3.62733 16.4279 4.16686 17.3334L5.29823 16.2021C5.10553 16.0094 4.96431 15.7286 4.88471 15.1366C4.80201 14.5215 4.80031 13.7011 4.80031 12.5H3.20031ZM15.2003 12.5C15.2003 13.7011 15.1986 14.5215 15.1159 15.1366C15.0363 15.7286 14.8951 16.0094 14.7024 16.2021L15.8338 17.3334C16.3733 16.7939 16.5982 16.1193 16.7016 15.3498C16.802 14.6033 16.8003 13.6559 16.8003 12.5H15.2003ZM11.0003 18.3C12.1562 18.3 13.1036 18.3017 13.8501 18.2013C14.6196 18.0979 15.2942 17.873 15.8338 17.3334L14.7024 16.2021C14.5097 16.3948 14.229 16.536 13.6369 16.6156C13.0218 16.6983 12.2014 16.7 11.0003 16.7V18.3ZM2.50031 4.69999C2.22572 4.7 2.04405 4.7 1.94475 4.7C1.89511 4.7 1.86604 4.7 1.85624 4.7C1.85471 4.7 1.85206 4.7 1.851 4.7C1.05253 5.50059 1.85233 6.3 1.85256 6.3C1.85273 6.3 1.85297 6.3 1.85327 6.3C1.85385 6.3 1.85472 6.3 1.85587 6.3C1.86047 6.3 1.86972 6.3 1.88345 6.3C1.99328 6.3 2.39045 6.3 2.9906 6.3C4.19091 6.3 6.2032 6.3 8.35279 6.3C10.5024 6.3 12.7893 6.3 14.5387 6.3C15.4135 6.3 16.1539 6.3 16.6756 6.3C16.9364 6.3 17.1426 6.29999 17.2836 6.29999C17.3541 6.29999 17.4083 6.29999 17.4448 6.29999C17.4631 6.29999 17.477 6.29999 17.4863 6.29999C17.4909 6.29999 17.4944 6.29999 17.4968 6.29999C17.498 6.29999 17.4988 6.29999 17.4994 6.29999C17.4997 6.29999 17.4999 6.29999 17.5001 6.29999C17.5002 6.29999 17.5003 6.29999 17.5003 5.49999C17.5003 4.69999 17.5002 4.69999 17.5001 4.69999C17.4999 4.69999 17.4997 4.69999 17.4994 4.69999C17.4988 4.69999 17.498 4.69999 17.4968 4.69999C17.4944 4.69999 17.4909 4.69999 17.4863 4.69999C17.477 4.69999 17.4631 4.69999 17.4448 4.69999C17.4083 4.69999 17.3541 4.69999 17.2836 4.69999C17.1426 4.7 16.9364 4.7 16.6756 4.7C16.1539 4.7 15.4135 4.7 14.5387 4.7C12.7893 4.7 10.5024 4.7 8.35279 4.7C6.2032 4.7 4.19091 4.7 2.9906 4.7C2.39044 4.7 1.99329 4.7 1.88347 4.7C1.86974 4.7 1.86051 4.7 1.85594 4.7C1.8548 4.7 1.85396 4.7 1.85342 4.7C1.85315 4.7 1.85298 4.7 1.85288 4.7C1.85284 4.7 2.65253 5.49941 1.85408 6.3C1.85314 6.3 1.85296 6.3 1.85632 6.3C1.86608 6.3 1.89511 6.3 1.94477 6.3C2.04406 6.3 2.22573 6.3 2.50031 6.29999L2.50031 4.69999ZM7.05028 5.49994V4.16661H5.45028V5.49994H7.05028ZM7.91695 3.29994H12.0836V1.69994H7.91695V3.29994ZM12.9503 4.16661V5.49994H14.5503V4.16661H12.9503ZM12.0836 3.29994C12.5623 3.29994 12.9503 3.68796 12.9503 4.16661H14.5503C14.5503 2.8043 13.4459 1.69994 12.0836 1.69994V3.29994ZM7.05028 4.16661C7.05028 3.68796 7.4383 3.29994 7.91695 3.29994V1.69994C6.55465 1.69994 5.45028 2.8043 5.45028 4.16661H7.05028ZM2.50031 6.29999C4.70481 6.29998 6.40335 6.29998 8.1253 6.29997C9.84725 6.29996 11.5458 6.29995 13.7503 6.29994L13.7503 4.69994C11.5458 4.69995 9.84724 4.69996 8.12529 4.69997C6.40335 4.69998 4.7048 4.699982.50031 4.69999L2.50031 6.29999ZM13.7503 6.29994L17.5003 6.29999L17.5003 4.69999L13.7503 4.69994L13.7503 6.29994ZM7.70029 9.24997V13.75H9.30029V9.24997H7.70029ZM10.7004 9.24997V13.75H12.3004V9.24997H10.7004Z">
                                        </path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-else class="text-center py-6">
                <p class="text-gray-500">No hay archivos agregados</p>
            </div>
        </div>

        <!-- Sección para administrar imágenes -->
        <div class="bloque--horizontal">
            <h2></h2>
            <HeaderBody titulo-body="Galería de Imágenes" permisos="create users" nombre-btn="Agregar Imagen"
                @create="showImageModal = true" />
            <div v-if="development.images && development.images.length > 0"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div v-for="image in development.images" :key="image.devImg_id"
                    class="border rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="relative group">
                        <img :src="`/storage/${image.devImg_url}`" :alt="image.devImg_title"
                            class="w-full h-48 object-cover">
                        <div v-if="image.devImg_is_cover"
                            class="absolute top-2 right-2 bg-yellow-400 text-yellow-900 px-2 py-1 rounded text-xs font-semibold">
                            ★ Portada
                        </div>
                        <div
                            class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                            <button @click="openImagePreview(image)"
                                class="px-3 py-2 bg-blue-600 text-white rounded text-xs font-medium">
                                Ver
                            </button>
                            <button @click="confirmDelete(image.devImg_id, 'image')"
                                class="px-3 py-2 bg-red-600 text-white rounded text-xs font-medium">
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

        <!-- Botón de guardar cambios -->
        <div class="mt-8 flex justify-end">
            <PrimaryButton @click="handleSave" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                <template #texto--boton>{{ form.processing ? 'Guardando...' : 'Guardar' }}</template>
                <template #icono--boton>
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </template>
            </PrimaryButton>
        </div>

        <!-- Modales -->
        <FileModal :show="showFileModal" :development="development" :documentTypes="documentTypes"
            @close="showFileModal = false" @saved="handleFilesSaved" />

        <ImageModal :show="showImageModal" :development="development" @close="showImageModal = false"
            @saved="handleImagesSaved" />


        <!-- Modal de preview de imagen -->
        <div v-if="showImagePreview" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50">
            <div class="relative max-w-4xl max-h-[90vh] bg-white rounded-lg overflow-hidden">
                <!-- Botón cerrar -->
                <button @click="closeImagePreview"
                    class="absolute top-4 right-4 text-gray-700 hover:text-gray-900 bg-white rounded-full p-2 shadow-lg z-10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>

                <!-- Imagen -->
                <img v-if="selectedImage" :src="`/storage/${selectedImage.devImg_url}`"
                    :alt="selectedImage.devImg_title" class="w-full h-full object-contain">

                <!-- Título y info -->
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
                    <p class="text-white font-semibold">{{ selectedImage?.devImg_title }}</p>
                    <div v-if="selectedImage?.devImg_is_cover"
                        class="mt-2 inline-block bg-yellow-400 text-yellow-900 px-2 py-1 rounded text-xs font-semibold">
                        ★ Portada
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
