<script>
export default {
    name: 'DevelopmentImageCreate'
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

defineProps({
    development: {
        type: Object,
        required: true
    }
})

const images = ref([])
const fileInput = ref(null)
const imagePreview = ref(null)

const form = useForm({
    development_id: route().params.development_id,
    devImg_title: '',
    file: null,
    devImg_is_cover: false,
})

const handleImageSelect = (event) => {
    const selectedFiles = event.target.files
    if (selectedFiles.length > 0) {
        // Procesar múltiples imágenes
        for (let i = 0; i < selectedFiles.length; i++) {
            const file = selectedFiles[i]
            
            // Crear preview para cada imagen
            const reader = new FileReader()
            reader.onload = (e) => {
                images.value.push({
                    devImg_title: file.name.split('.')[0],
                    file: file,
                    devImg_is_cover: false,
                    preview: e.target.result
                })
            }
            reader.readAsDataURL(file)
        }
        fileInput.value.value = ''
        form.devImg_title = ''
        form.file = null
        form.devImg_is_cover = false
        imagePreview.value = null
    }
}

const addImage = () => {
    console.log('addImage called - Solo usa la selección múltiple automática')
}

const removeImage = (index) => {
    images.value.splice(index, 1)
}

const submit = () => {
    if (images.value.length > 0) {
        const formData = new FormData()
        formData.append('development_id', form.development_id)
        
        images.value.forEach((img, index) => {
            formData.append(`images[${index}][devImg_title]`, img.devImg_title)
            formData.append(`images[${index}][devImg_is_cover]`, img.devImg_is_cover ? 1 : 0)
            formData.append(`images[${index}][file]`, img.file)
        })

        axios.post(route('developmentimages.store'), formData)
            .then(() => {
                window.location.href = route('development.index')
            })
            .catch(error => {
                console.error('Error:', error)
                alert('Error al subir imágenes: ' + (error.response?.data?.message || error.message))
            })
    }
}

const finish = () => {
    window.location.href = route('development.index')
}
</script>

<template>
    <AppLayout>
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">Agregar Imágenes al Desarrollo</h1>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-white border-border-gray-200 rounded-lg shadow">
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded">
                        <p class="text-sm text-blue-800">
                            <strong>Desarrollo:</strong> {{ development.devt_title }}
                        </p>
                    </div>

                    <FormSection @submitted="addImage">
                        <template #title>
                            Subir Imagen
                        </template>

                        <template #description>
                            Sube imágenes de la galería del desarrollo. Puedes agregar múltiples imágenes.
                        </template>

                        <template #form>
                            <div class="col-span-6 sm:col-span-6">
                                <InputLabel for="file" value="Selecciona las Imágenes"></InputLabel>
                                <input 
                                    ref="fileInput"
                                    id="file" 
                                    type="file" 
                                    accept="image/*"
                                    multiple
                                    @change="handleImageSelect"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <p class="text-xs text-gray-500 mt-2">Formatos soportados: JPG, PNG, WEBP, GIF. Puedes seleccionar múltiples imágenes de una vez</p>
                                <InputError :message="$page.props.errors.file" class="mt-2" />
                            </div>
                        </template>

                        <template #actions>
                            <PrimaryButton v-if="images.length === 0" disabled class="opacity-25">
                                Selecciona imágenes arriba
                            </PrimaryButton>
                        </template>
                    </FormSection>

                    <!-- Lista de imágenes agregadas -->
                    <div v-if="images.length > 0" class="mt-8">
                        <h3 class="font-semibold text-lg mb-4">Imágenes a Subir ({{ images.length }})</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div v-for="(img, index) in images" :key="index" class="border rounded-lg p-4">
                                <img :src="img.preview" :alt="img.devImg_title" class="w-full h-40 object-cover rounded mb-3">
                                
                                <div class="space-y-3">
                                    <!-- Campo de título editable -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Título de la Imagen</label>
                                        <input 
                                            v-model="img.devImg_title"
                                            type="text"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="Ej: Fachada principal">
                                    </div>

                                    <!-- Checkbox para portada -->
                                    <div class="flex items-center">
                                        <input 
                                            v-model="img.devImg_is_cover"
                                            type="checkbox"
                                            :id="`cover-${index}`"
                                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label :for="`cover-${index}`" class="ml-2 block text-sm text-gray-700">
                                            Usar como portada
                                        </label>
                                    </div>

                                    <!-- Botón eliminar -->
                                    <button 
                                        type="button"
                                        @click="removeImage(index)"
                                        class="w-full px-3 py-2 bg-red-50 text-red-700 rounded hover:bg-red-100 text-sm font-medium border border-red-200">
                                        Eliminar imagen
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción final -->
                    <div class="mt-8 flex gap-4">
                        <button 
                            type="button"
                            @click="submit"
                            :disabled="images.length === 0 || form.processing"
                            :class="{ 'opacity-50 cursor-not-allowed': images.length === 0 || form.processing }"
                            class="px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            Subir Imágenes
                        </button>
                        <button 
                            type="button"
                            @click="finish"
                            class="px-6 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                            Terminar (sin imágenes)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

