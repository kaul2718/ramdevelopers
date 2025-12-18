<script>
export default {
    name: 'DevelopmentFileCreate'
}
</script>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'
import FormSection from '@/Components/FormSection.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import axios from 'axios'
import { ref } from 'vue'

const props = defineProps({
    development: {
        type: Object,
        required: true
    },
    documentTypes: {
        type: Array,
        required: true
    }
})

const files = ref([])
const fileInput = ref(null)

const form = useForm({
    development_id: route().params.development_id,
    docTyp_id: '',
    devFile_name: '',
    file: null,
})

const handleFileSelect = (event) => {
    const selectedFiles = event.target.files
    if (selectedFiles.length > 0) {
        // Procesar múltiples archivos
        for (let i = 0; i < selectedFiles.length; i++) {
            const file = selectedFiles[i]
            // Agregar cada archivo directamente sin esperar a formulario
            files.value.push({
                docTyp_id: form.docTyp_id,
                devFile_name: form.devFile_name || file.name.split('.')[0],
                file: file,
                docTyp_name: form.docTyp_id ? props.documentTypes.find(dt => dt.docTyp_id == form.docTyp_id)?.docTyp_name : 'Sin tipo'
            })
        }
        fileInput.value.value = ''
        form.docTyp_id = ''
        form.devFile_name = ''
        form.file = null
    }
}

const addFile = () => {
    console.log('addFile called - Solo usa la selección múltiple automática')
}

const removeFile = (index) => {
    files.value.splice(index, 1)
}

const submit = () => {
    if (files.value.length > 0) {
        const formData = new FormData()
        formData.append('development_id', form.development_id)
        
        files.value.forEach((f, index) => {
            formData.append(`files[${index}][docTyp_id]`, f.docTyp_id)
            formData.append(`files[${index}][devFile_name]`, f.devFile_name)
            formData.append(`files[${index}][file]`, f.file)
        })

        axios.post(route('developmentfile.store'), formData)
            .then(() => {
                window.location.href = route('developmentimages.create', { development_id: form.development_id })
            })
            .catch(error => {
                console.error('Error:', error)
                alert('Error al subir archivos: ' + (error.response?.data?.message || error.message))
            })
    }
}

const skip = () => {
    window.location.href = route('developmentimages.create', { development_id: form.development_id })
}
</script>

<template>
    <AppLayout>
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">Agregar Archivos al Desarrollo</h1>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-white border-border-gray-200 rounded-lg shadow">
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded">
                        <p class="text-sm text-blue-800">
                            <strong>Desarrollo:</strong> {{ development.devt_title }}
                        </p>
                    </div>

                    <FormSection @submitted="addFile">
                        <template #title>
                            Subir Archivo
                        </template>

                        <template #description>
                            Sube archivos y documentos relacionados con el desarrollo. Puedes agregar múltiples archivos.
                        </template>

                        <template #form>
                            <div class="col-span-6 sm:col-span-3">
                                <InputLabel for="docTyp_id" value="Tipo de Documento"></InputLabel>
                                <select 
                                    id="docTyp_id" 
                                    v-model="form.docTyp_id"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="" disabled>Selecciona un tipo de documento</option>
                                    <option v-for="type in documentTypes" :key="type.docTyp_id" :value="type.docTyp_id">
                                        {{ type.docTyp_name }}
                                    </option>
                                </select>
                                <InputError :message="$page.props.errors.docTyp_id" class="mt-2" />
                            </div>

                            <div class="col-span-6 sm:col-span-6">
                                <InputLabel for="file" value="Selecciona los Archivos"></InputLabel>
                                <input 
                                    ref="fileInput"
                                    id="file" 
                                    type="file" 
                                    multiple
                                    @change="handleFileSelect"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <p class="text-xs text-gray-500 mt-2">Formatos soportados: PDF, DOC, DOCX, XLS, XLSX, ZIP, etc. Puedes seleccionar múltiples archivos de una vez</p>
                                <InputError :message="$page.props.errors.file" class="mt-2" />
                            </div>
                        </template>

                        <template #actions>
                            <PrimaryButton v-if="files.length === 0" disabled class="opacity-25">
                                Selecciona archivos arriba
                            </PrimaryButton>
                        </template>
                    </FormSection>

                    <!-- Lista de archivos agregados -->
                    <div v-if="files.length > 0" class="mt-8">
                        <h3 class="font-semibold text-lg mb-4">Archivos a Subir ({{ files.length }})</h3>
                        <div class="space-y-3">
                            <div v-for="(f, index) in files" :key="index" class="border rounded-lg p-4 flex items-center justify-between bg-gray-50">
                                <div class="flex items-center space-x-3 flex-1">
                                    <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"/>
                                    </svg>
                                    <div>
                                        <p class="font-medium text-sm">{{ f.devFile_name }}</p>
                                        <p class="text-xs text-gray-500">{{ f.docTyp_name }} • {{ (f.file.size / 1024 / 1024).toFixed(2) }}MB</p>
                                    </div>
                                </div>
                                <button 
                                    type="button"
                                    @click="removeFile(index)"
                                    class="px-3 py-1 bg-red-200 text-red-800 rounded text-sm hover:bg-red-300">
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción final -->
                    <div class="mt-8 flex gap-4">
                        <button 
                            type="button"
                            @click="submit"
                            :disabled="files.length === 0 || form.processing"
                            :class="{ 'opacity-50 cursor-not-allowed': files.length === 0 || form.processing }"
                            class="px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            Subir Archivos
                        </button>
                        <button 
                            type="button"
                            @click="skip"
                            class="px-6 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                            Saltar (sin archivos)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

