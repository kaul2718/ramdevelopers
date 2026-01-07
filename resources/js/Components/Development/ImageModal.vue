<script setup>
import DialogModal from '@/Components/DialogModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { ref, computed } from 'vue';
import axios from 'axios';
import { useNotificationStore } from '@/stores/notificationStore';

const notificationStore = useNotificationStore();

const props = defineProps({
    show: {
        type: Boolean,
        required: true
    },
    development: {
        type: Object,
        default: null
    }
});

const emit = defineEmits(['close', 'saved']);

const images = ref([]);
const imageInput = ref(null);
const isSubmitting = ref(false);

// Verificar si ya existe una imagen portada
const hasCoverImage = computed(() => {
    return props.development?.images?.some(img => img.devImg_is_cover) || false;
});

const closeModal = () => {
    images.value = [];
    emit('close');
};

const handleImageSelect = (event) => {
    const selectedFiles = event.target.files;
    if (selectedFiles.length > 0) {
        // Procesar múltiples imágenes
        for (let i = 0; i < selectedFiles.length; i++) {
            const file = selectedFiles[i];
            
            // Crear preview para cada imagen
            const reader = new FileReader();
            reader.onload = (e) => {
                images.value.push({
                    devImg_title: file.name.split('.')[0],
                    file: file,
                    devImg_is_cover: false,
                    preview: e.target.result
                });
            };
            reader.readAsDataURL(file);
        }
        imageInput.value.value = '';
    }
};

const removeImage = (index) => {
    images.value.splice(index, 1);
};

const setCoverImage = (index) => {
    // Si ya existe una portada, mostrar advertencia
    if (hasCoverImage.value) {
        notificationStore.error('Ya existe una imagen marcada como portada. Por favor, elimina la portada actual antes de establecer una nueva.');
        return;
    }
    
    // Desmarcar todas las imágenes
    images.value.forEach((img, i) => {
        img.devImg_is_cover = i === index;
    });
};

const submit = async () => {
    if (!images.value.length) {
        notificationStore.error('Por favor selecciona al menos una imagen');
        return;
    }
    
    if (!props.development) {
        notificationStore.error('Error: No se encontró el desarrollo');
        return;
    }
    
    isSubmitting.value = true;
    const formData = new FormData();
    
    const devtId = props.development.devt_id || props.development.id;
    if (!devtId) {
        notificationStore.error('Error: No se pudo obtener el ID del desarrollo');
        isSubmitting.value = false;
        return;
    }
    
    formData.append('development_id', devtId);
    
    images.value.forEach((img, index) => {
        formData.append(`images[${index}][devImg_title]`, img.devImg_title);
        formData.append(`images[${index}][devImg_is_cover]`, img.devImg_is_cover ? 1 : 0);
        formData.append(`images[${index}][file]`, img.file);
    });

    try {
        await axios.post(route('developmentimages.store'), formData);
        sessionStorage.setItem('showImageUploadNotification', 'true');
        emit('saved');
        closeModal();
    } catch (error) {
        console.error('Error:', error);
        const errorMessage = typeof error.response?.data?.message === 'string'
            ? error.response.data.message
            : Array.isArray(error.response?.data?.errors)
                ? Object.values(error.response.data.errors).flat().join(', ')
                : error.message;
        notificationStore.error('Error al subir imágenes: ' + errorMessage);
    } finally {
        isSubmitting.value = false;
    }
};

const finish = () => {
    sessionStorage.setItem('showImageSkipNotification', 'true');
    emit('saved');
    closeModal();
};
</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="2xl">
        <template #title>
            <h2 class="text-lg font-semibold">
                Agregar Imágenes
            </h2>
        </template>

        <template #content>
            <div class="space-y-4">
                <!-- Info del desarrollo -->
                <div class="p-3 bg-blue-50 border border-blue-200 rounded text-sm">
                    <p class="text-blue-800">
                        <strong>Desarrollo:</strong> {{ development?.devt_title || 'N/A' }}
                    </p>
                </div>

                <!-- Advertencia si ya existe portada -->
                <div v-if="hasCoverImage" class="p-3 bg-yellow-50 border border-yellow-200 rounded text-sm">
                    <p class="text-yellow-800">
                        <strong>⚠️ Nota:</strong> Ya existe una imagen marcada como portada. No puedes agregar otra portada hasta que elimines la actual.
                    </p>
                </div>

                <!-- Selección de imágenes -->
                <div>
                    <InputLabel for="images" value="Selecciona las Imágenes" class="text-sm"></InputLabel>
                    <input 
                        ref="imageInput"
                        id="images" 
                        type="file" 
                        multiple
                        accept="image/*"
                        @change="handleImageSelect"
                        class="mt-1 block w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF, WebP</p>
                </div>

                <!-- Vista previa de imágenes (Grid compacto) -->
                <div v-if="images.length > 0" class="space-y-2 max-h-56 overflow-y-auto border rounded p-3 bg-gray-50">
                    <h3 class="font-semibold text-xs">Imágenes a Subir ({{ images.length }})</h3>
                    <div class="space-y-2">
                        <div v-for="(img, index) in images" :key="index" class="border rounded bg-white p-2 flex gap-2 items-start text-xs">
                            <!-- Preview -->
                            <img 
                                :src="img.preview" 
                                class="w-16 h-16 object-cover rounded flex-shrink-0"
                                :alt="`Preview ${index + 1}`">
                            
                            <!-- Detalles -->
                            <div class="flex-1 min-w-0">
                                <input 
                                    v-model="img.devImg_title"
                                    type="text"
                                    placeholder="Título (Opcional)"
                                    class="w-full text-xs border border-gray-300 rounded px-2 py-1 mb-1 focus:outline-none focus:ring-1 focus:ring-indigo-300">
                                <p class="text-gray-600 text-xs truncate">{{ img.file.name }}</p>
                                <p class="text-gray-500 text-xs">{{ (img.file.size / 1024 / 1024).toFixed(2) }}MB</p>
                            </div>

                            <!-- Controles -->
                            <div class="flex flex-col gap-1 flex-shrink-0">
                                <label class="flex items-center text-xs cursor-pointer hover:bg-blue-100 p-1 rounded" :class="{ 'opacity-50 cursor-not-allowed': hasCoverImage }">
                                    <input 
                                        :checked="img.devImg_is_cover"
                                        @change="setCoverImage(index)"
                                        type="checkbox"
                                        :disabled="hasCoverImage"
                                        class="w-3 h-3 disabled:cursor-not-allowed">
                                    <span class="ml-1 text-blue-600">Portada</span>
                                </label>
                                <button 
                                    type="button"
                                    @click="removeImage(index)"
                                    class="px-1.5 py-0.5 bg-red-200 text-red-800 rounded text-xs hover:bg-red-300">
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template #footer>
            <button
                @click="closeModal"
                class="px-3 py-2 text-sm text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                Cancelar
            </button>
            <button
                v-if="images.length > 0"
                @click="submit"
                :disabled="isSubmitting"
                class="px-3 py-2 text-sm text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition ml-2 disabled:opacity-50">
                {{ isSubmitting ? 'Subiendo...' : 'Subir Imágenes' }}
            </button>
            <button
                @click="finish"
                class="px-3 py-2 text-sm text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition ml-2">
                Finalizar
            </button>
        </template>
    </DialogModal>
</template>
