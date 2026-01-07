<script setup>
import DialogModal from '@/Components/DialogModal.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref } from 'vue';
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
    },
    documentTypes: {
        type: Array,
        required: true
    }
});

const emit = defineEmits(['close', 'saved']);

const files = ref([]);
const fileInput = ref(null);
const isSubmitting = ref(false);
const selectedDocType = ref('');
const selectedFileName = ref('');

const closeModal = () => {
    files.value = [];
    selectedDocType.value = '';
    selectedFileName.value = '';
    emit('close');
};

const handleFileSelect = (event) => {
    if (!selectedDocType.value) {
        notificationStore.error('Por favor selecciona un tipo de documento primero');
        fileInput.value.value = '';
        return;
    }
    
    const selectedFiles = event.target.files;
    if (selectedFiles.length > 0) {
        // Procesar múltiples archivos
        for (let i = 0; i < selectedFiles.length; i++) {
            const file = selectedFiles[i];
            // Agregar cada archivo con el tipo de documento seleccionado
            files.value.push({
                docTyp_id: selectedDocType.value,
                devFile_name: selectedFileName.value || file.name.split('.')[0],
                file: file,
                docTyp_name: selectedDocType.value ? props.documentTypes.find(dt => dt.docTyp_id == selectedDocType.value)?.docTyp_name : 'Sin tipo'
            });
        }
        fileInput.value.value = '';
        // No resetear los valores para mantener la selección
    }
};

const removeFile = (index) => {
    files.value.splice(index, 1);
};

const submit = async () => {
    if (!files.value.length) {
        notificationStore.error('Por favor selecciona al menos un archivo para cargar');
        return;
    }
    
    // Validar que todos los archivos tengan tipo de documento
    const hasInvalidFiles = files.value.some(f => !f.docTyp_id);
    if (hasInvalidFiles) {
        notificationStore.error('Todos los archivos deben tener un tipo de documento asignado');
        return;
    }
    
    if (!props.development) {
        notificationStore.error('Error: No se encontró el desarrollo');
        return;
    }
    
    isSubmitting.value = true;
    const formData = new FormData();
    
    // Obtener el ID del desarrollo correctamente
    const devtId = props.development.devt_id || props.development.id
    
    if (!devtId) {
        notificationStore.error('Error: No se pudo obtener el ID del desarrollo');
        isSubmitting.value = false;
        return;
    }
    
    formData.append('development_id', devtId);
    
    files.value.forEach((f, index) => {
        formData.append(`files[${index}][docTyp_id]`, f.docTyp_id);
        formData.append(`files[${index}][devFile_name]`, f.devFile_name);
        formData.append(`files[${index}][file]`, f.file);
    });

    try {
        await axios.post(route('developmentfile.store'), formData);
        sessionStorage.setItem('showFileUploadNotification', 'true');
        emit('saved');
        closeModal();
    } catch (error) {
        console.error('Error:', error);
        const errorMessage = typeof error.response?.data?.message === 'string'
            ? error.response.data.message
            : Array.isArray(error.response?.data?.errors)
                ? Object.values(error.response.data.errors).flat().join(', ')
                : error.message;
        notificationStore.error('Error al subir archivos: ' + errorMessage);
    } finally {
        isSubmitting.value = false;
    }
};

const skip = () => {
    sessionStorage.setItem('showFileSkipNotification', 'true');
    emit('saved');
    closeModal();
};
</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="2xl">
        <template #title>
            <h2 class="text-lg font-semibold">
                Agregar Archivos
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

                <!-- Formulario de selección -->
                <div class="space-y-3">
                    <div>
                        <InputLabel for="docTyp_id" value="Tipo de Documento" class="text-sm"></InputLabel>
                        <select 
                            id="docTyp_id" 
                            v-model="selectedDocType"
                            class="mt-1 block w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="" disabled>Selecciona un tipo de documento</option>
                            <option v-for="type in documentTypes" :key="type.docTyp_id" :value="type.docTyp_id">
                                {{ type.docTyp_name }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <InputLabel for="devFile_name" value="Nombre del Archivo (Opcional)" class="text-sm"></InputLabel>
                        <input 
                            id="devFile_name"
                            v-model="selectedFileName"
                            type="text"
                            class="mt-1 block w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="Si no especificas, usará el nombre del archivo">
                    </div>

                    <div>
                        <InputLabel for="file" value="Selecciona los Archivos" class="text-sm"></InputLabel>
                        <input 
                            ref="fileInput"
                            id="file" 
                            type="file" 
                            multiple
                            @change="handleFileSelect"
                            class="mt-1 block w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <p class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX, XLS, XLSX, ZIP, etc.</p>
                    </div>
                </div>

                <!-- Lista de archivos agregados -->
                <div v-if="files.length > 0" class="space-y-2 max-h-48 overflow-y-auto border rounded p-3 bg-gray-50">
                    <h3 class="font-semibold text-xs">Archivos a Subir ({{ files.length }})</h3>
                    <div v-for="(f, index) in files" :key="index" class="border rounded p-2 flex items-center justify-between bg-white text-xs gap-2">
                        <div class="flex items-center space-x-2 flex-1 min-w-0">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"/>
                            </svg>
                            <div class="flex-1 min-w-0">
                                <input 
                                    v-model="f.devFile_name"
                                    type="text"
                                    placeholder="Nombre del archivo"
                                    class="w-full text-xs font-medium border border-gray-300 rounded px-2 py-1 mb-1 focus:outline-none focus:ring-1 focus:ring-indigo-300">
                                <p class="text-xs text-gray-500">{{ f.docTyp_name }} • {{ (f.file.size / 1024 / 1024).toFixed(2) }}MB</p>
                            </div>
                        </div>
                        <button 
                            type="button"
                            @click="removeFile(index)"
                            class="px-2 py-1 bg-red-200 text-red-800 rounded text-xs hover:bg-red-300 flex-shrink-0">
                            Eliminar
                        </button>
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
                v-if="files.length > 0"
                @click="submit"
                :disabled="isSubmitting"
                class="px-3 py-2 text-sm text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition ml-2 disabled:opacity-50">
                {{ isSubmitting ? 'Subiendo...' : 'Subir Archivos' }}
            </button>
            <button
                @click="skip"
                class="px-3 py-2 text-sm text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition ml-2">
                Saltar
            </button>
        </template>
    </DialogModal>
</template>
