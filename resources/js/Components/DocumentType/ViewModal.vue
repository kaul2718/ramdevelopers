<script setup>
import DialogModal from '@/Components/DialogModal.vue';
import { ref, watch, computed } from 'vue';
import axios from 'axios';
import { useNotificationStore } from '@/stores/notificationStore';

const props = defineProps({
    show: {
        type: Boolean,
        required: true
    },
    documentType: {
        type: Object,
        default: null
    },
    editMode: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['close']);

const notificationStore = useNotificationStore();

const isEditing = ref(false);
const isSubmitting = ref(false);
const errors = ref({});

const form = ref({
    docTyp_name: '',
    docTyp_code: '',
    docTyp_description: '',
    docTyp_isInternal: false,
    docTyp_active: false
});

const originalForm = ref({
    docTyp_name: '',
    docTyp_code: '',
    docTyp_description: '',
    docTyp_isInternal: false,
    docTyp_active: false
});

const hasChanges = computed(() => {
    return form.value.docTyp_name !== originalForm.value.docTyp_name ||
           form.value.docTyp_code !== originalForm.value.docTyp_code ||
           form.value.docTyp_description !== originalForm.value.docTyp_description ||
           form.value.docTyp_isInternal !== originalForm.value.docTyp_isInternal ||
           form.value.docTyp_active !== originalForm.value.docTyp_active;
});

watch(() => props.show, (newVal) => {
    if (newVal && props.documentType) {
        const formData = {
            docTyp_name: props.documentType.docTyp_name || '',
            docTyp_code: props.documentType.docTyp_code || '',
            docTyp_description: props.documentType.docTyp_description || '',
            docTyp_isInternal: props.documentType.docTyp_isInternal ? 1 : 0,
            docTyp_active: props.documentType.docTyp_active ? 1 : 0
        };
        
        form.value = formData;
        originalForm.value = JSON.parse(JSON.stringify(formData));
        
        if (props.editMode) {
            isEditing.value = true;
        } else {
            isEditing.value = false;
        }
    }
});

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

const toggleEdit = () => {
    if (isEditing.value) {
        isEditing.value = false;
    } else {
        if (props.documentType) {
            const formData = {
                docTyp_name: props.documentType.docTyp_name || '',
                docTyp_code: props.documentType.docTyp_code || '',
                docTyp_description: props.documentType.docTyp_description || '',
                docTyp_isInternal: props.documentType.docTyp_isInternal ? 1 : 0,
                docTyp_active: props.documentType.docTyp_active ? 1 : 0
            };
            
            form.value = formData;
            originalForm.value = JSON.parse(JSON.stringify(formData));
        }
        isEditing.value = true;
    }
};

const handleSubmit = async () => {
    if (!validateForm()) {
        return;
    }

    if (!hasChanges.value) {
        notificationStore.error('No has realizado cambios');
        return;
    }
    
    isSubmitting.value = true;
    const formData = new FormData();
    
    if (form.value.docTyp_name) formData.append('docTyp_name', form.value.docTyp_name);
    if (form.value.docTyp_code) formData.append('docTyp_code', form.value.docTyp_code);
    if (form.value.docTyp_description) formData.append('docTyp_description', form.value.docTyp_description);
    formData.append('docTyp_isInternal', form.value.docTyp_isInternal || 0);
    formData.append('docTyp_active', form.value.docTyp_active || 0);
    formData.append('_method', 'PUT');
    
    try {
        await axios.post(route('documenttype.update', props.documentType.docTyp_id), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        });
        
        // Guardar flag en sessionStorage para mostrar notificación después del refresco
        sessionStorage.setItem('showUpdateDocumentTypeNotification', 'true');
        
        // Cerrar modal y refrescar
        closeModal();
        window.location.href = route('documenttype.index');
    } catch (error) {
        console.error('Error updating document type:', error);
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors;
            const errorMessages = Object.values(errors.value).flat().join(', ');
            notificationStore.error(errorMessages);
        } else {
            notificationStore.error('Error al actualizar el tipo de documento');
        }
    } finally {
        isSubmitting.value = false;
    }
};

const closeModal = () => {
    isEditing.value = false;
    emit('close');
};
</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="2xl">
        <template #title>
            <h2 class="modal-title text-xl">
                {{ isEditing ? 'Editar Tipo de Documento' : 'Información del Tipo de Documento' }}
            </h2>
        </template>

        <template #content>
            <!-- Vista -->
            <div v-if="!isEditing" class="space-y-4">
                <div class="modal-grid">
                    <div>
                        <p class="modal-label">Nombre</p>
                        <p class="modal-value">{{ documentType?.docTyp_name }}</p>
                    </div>
                    <div>
                        <p class="modal-label">Código</p>
                        <p class="modal-value">{{ documentType?.docTyp_code }}</p>
                    </div>
                </div>
                <div>
                    <p class="modal-label">Descripción</p>
                    <p class="modal-value">{{ documentType?.docTyp_description || 'N/A' }}</p>
                </div>
                <div class="modal-grid">
                    <div>
                        <p class="modal-label">Es Interno</p>
                        <div :class="[
                            'py-1.5 px-2.5 rounded-full flex items-center gap-2 w-fit',
                            documentType?.docTyp_isInternal ? 'bg-blue-50 text-blue-600' : 'bg-gray-50 text-gray-600'
                        ]">
                            <svg width="6" height="6" viewBox="0 0 6 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="3" cy="3" r="3" :fill="documentType?.docTyp_isInternal ? '#2563eb' : '#9ca3af'" />
                            </svg>
                            <span class="font-semibold text-xs">
                                {{ documentType?.docTyp_isInternal ? 'Sí' : 'No' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="modal-label">Estado</p>
                    <div :class="[
                        'py-1.5 px-2.5 rounded-full flex items-center gap-2 w-fit',
                        documentType?.docTyp_active ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600'
                    ]">
                        <svg width="6" height="6" viewBox="0 0 6 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="3" cy="3" r="3" :fill="documentType?.docTyp_active ? '#059669' : '#dc2626'" />
                        </svg>
                        <span class="font-semibold text-xs">
                            {{ documentType?.docTyp_active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Edición -->
            <div v-else class="space-y-6">
                <div>
                    <label for="docTyp_name" class="block text-sm font-medium text-gray-700">
                        Nombre del Tipo <span class="text-red-600">*</span>
                    </label>
                    <input 
                        id="docTyp_name" 
                        v-model="form.docTyp_name" 
                        type="text" 
                        class="mt-1 block w-full rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500"
                        :class="errors.docTyp_name ? 'border border-red-500' : 'border border-gray-300 focus:border-indigo-500'">
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
                        :class="errors.docTyp_code ? 'border border-red-500' : 'border border-gray-300 focus:border-indigo-500'">
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
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </textarea>
                </div>

                <div>
                    <label for="docTyp_isInternal" class="block text-sm font-medium text-gray-700">Es Interno</label>
                    <select 
                        id="docTyp_isInternal" 
                        v-model="form.docTyp_isInternal"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option :value="1">Sí</option>
                        <option :value="0">No</option>
                    </select>
                </div>

                <div>
                    <label for="docTyp_active" class="block text-sm font-medium text-gray-700">Estado</label>
                    <select 
                        id="docTyp_active" 
                        v-model="form.docTyp_active"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option :value="1">Activo</option>
                        <option :value="0">Inactivo</option>
                    </select>
                </div>
            </div>
        </template>

        <template #footer>
            <button
                @click="closeModal"
                class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                {{ isEditing ? 'Cancelar' : 'Cerrar' }}
            </button>
            <button
                v-if="!isEditing"
                @click="toggleEdit"
                class="px-4 py-2 text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition ml-3">
                Editar
            </button>
            <button
                v-else
                @click="handleSubmit"
                :disabled="!hasChanges || isSubmitting"
                class="px-4 py-2 text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition ml-3 disabled:opacity-50">
                {{ isSubmitting ? 'Guardando...' : 'Guardar Cambios' }}
            </button>
        </template>
    </DialogModal>
</template>
