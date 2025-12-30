<script setup>
import DialogModal from '@/Components/DialogModal.vue';
import { ref, watch } from 'vue';
import axios from 'axios';
import { useNotificationStore } from '@/stores/notificationStore';

const props = defineProps({
    show: {
        type: Boolean,
        required: true
    }
});

const emit = defineEmits(['close']);

const notificationStore = useNotificationStore();

const form = ref({
    docTyp_name: '',
    docTyp_code: '',
    docTyp_description: '',
    docTyp_isInternal: false,
    docTyp_active: true
});

const isSubmitting = ref(false);
const errors = ref({});

const validateForm = () => {
    errors.value = {};
    
    if (!form.value.docTyp_name?.trim()) {
        errors.value.docTyp_name = 'El nombre del tipo es requerido';
    }
    if (!form.value.docTyp_code?.trim()) {
        errors.value.docTyp_code = 'El código es requerido';
    }
    
    return Object.keys(errors.value).length === 0;
};

const handleSubmit = async () => {
    if (!validateForm()) {
        return;
    }
    
    isSubmitting.value = true;
    const formData = new FormData();
    
    if (form.value.docTyp_name) formData.append('docTyp_name', form.value.docTyp_name);
    if (form.value.docTyp_code) formData.append('docTyp_code', form.value.docTyp_code);
    if (form.value.docTyp_description) formData.append('docTyp_description', form.value.docTyp_description);
    formData.append('docTyp_isInternal', form.value.docTyp_isInternal ? 1 : 0);
    formData.append('docTyp_active', form.value.docTyp_active ? 1 : 0);
    
    try {
        await axios.post(route('documenttype.store'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        });
        
        // Guardar flag en sessionStorage para mostrar notificación después del refresco
        sessionStorage.setItem('showCreateDocumentTypeNotification', 'true');
        
        // Cerrar modal y refrescar la página
        closeModal();
        window.location.href = route('documenttype.index');
    } catch (error) {
        console.error('Error creating document type:', error);
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors;
            const errorMessages = Object.values(errors.value).flat().join(', ');
            notificationStore.error(errorMessages);
        } else {
            notificationStore.error('Error al crear el tipo de documento');
        }
    } finally {
        isSubmitting.value = false;
    }
};

const closeModal = () => {
    // Resetear el formulario
    form.value = {
        docTyp_name: '',
        docTyp_code: '',
        docTyp_description: '',
        docTyp_isInternal: false,
        docTyp_active: true
    };
    emit('close');
};

watch(() => props.show, (newVal) => {
    if (newVal) {
        // Resetear el formulario cuando se abre el modal
        form.value = {
            docTyp_name: '',
            docTyp_code: '',
            docTyp_description: '',
            docTyp_isInternal: false,
            docTyp_active: true
        };
    }
});
</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="2xl">
        <template #title>
            <h2 class="modal-title text-xl">Crear Nuevo Tipo de Documento</h2>
        </template>

        <template #content>
            <div class="space-y-6">
                <div>
                    <label for="docTyp_name" class="block text-sm font-medium text-gray-700">
                        Nombre del Tipo <span class="text-red-600">*</span>
                    </label>
                    <input 
                        id="docTyp_name" 
                        v-model="form.docTyp_name" 
                        type="text" 
                        class="mt-1 block w-full rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500"
                        :class="errors.docTyp_name ? 'border border-red-500' : 'border border-gray-300 focus:border-indigo-500'"
                        placeholder="Contrato">
                    <p v-if="errors.docTyp_name" class="mt-1 text-sm text-red-600">
                        {{ errors.docTyp_name }}
                    </p>
                </div>

                <div>
                    <label for="docTyp_code" class="block text-sm font-medium text-gray-700">
                        Código <span class="text-red-600">*</span>
                    </label>
                    <input 
                        id="docTyp_code" 
                        v-model="form.docTyp_code" 
                        type="text" 
                        class="mt-1 block w-full rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500"
                        :class="errors.docTyp_code ? 'border border-red-500' : 'border border-gray-300 focus:border-indigo-500'"
                        placeholder="CT">
                    <p v-if="errors.docTyp_code" class="mt-1 text-sm text-red-600">
                        {{ errors.docTyp_code }}
                    </p>
                </div>

                <div>
                    <label for="docTyp_description" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea 
                        id="docTyp_description" 
                        v-model="form.docTyp_description" 
                        rows="3"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Descripción del tipo de documento">
                    </textarea>
                </div>

                <div>
                    <label for="docTyp_isInternal" class="block text-sm font-medium text-gray-700">Es Interno</label>
                    <select 
                        id="docTyp_isInternal" 
                        v-model="form.docTyp_isInternal"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option :value="true">Sí</option>
                        <option :value="false">No</option>
                    </select>
                </div>
            </div>
        </template>

        <template #footer>
            <button
                @click="closeModal"
                class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                Cancelar
            </button>
            <button
                @click="handleSubmit"
                :disabled="isSubmitting"
                class="px-4 py-2 text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition ml-3 disabled:opacity-50">
                {{ isSubmitting ? 'Guardando...' : 'Crear Tipo' }}
            </button>
        </template>
    </DialogModal>
</template>
