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
    developer: {
        type: Object,
        default: null
    },
    countries: {
        type: Array,
        default: () => []
    },
    users: {
        type: Array,
        default: () => []
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
    devr_commercial_name: '',
    devr_legal_name: '',
    devr_email_contact: '',
    devr_phone_contact: '',
    devr_website: '',
    ctry_id: '',
    user_id: '',
    devr_active: false
});

const originalForm = ref({
    devr_commercial_name: '',
    devr_legal_name: '',
    devr_email_contact: '',
    devr_phone_contact: '',
    devr_website: '',
    ctry_id: '',
    user_id: '',
    devr_active: false
});

const hasChanges = computed(() => {
    return form.value.devr_commercial_name !== originalForm.value.devr_commercial_name ||
           form.value.devr_legal_name !== originalForm.value.devr_legal_name ||
           form.value.devr_email_contact !== originalForm.value.devr_email_contact ||
           form.value.devr_phone_contact !== originalForm.value.devr_phone_contact ||
           form.value.devr_website !== originalForm.value.devr_website ||
           form.value.ctry_id !== originalForm.value.ctry_id ||
           form.value.user_id !== originalForm.value.user_id ||
           form.value.devr_active !== originalForm.value.devr_active;
});

watch(() => props.show, (newVal) => {
    if (newVal && props.developer) {
        const formData = {
            devr_commercial_name: props.developer.devr_commercial_name || '',
            devr_legal_name: props.developer.devr_legal_name || '',
            devr_email_contact: props.developer.devr_email_contact || '',
            devr_phone_contact: props.developer.devr_phone_contact || '',
            devr_website: props.developer.devr_website || '',
            ctry_id: props.developer.ctry_id || '',
            user_id: props.developer.user_id || '',
            devr_active: props.developer.devr_active ? 1 : 0
        };
        
        form.value = formData;
        originalForm.value = JSON.parse(JSON.stringify(formData));
        
        if (props.editMode) {
            isEditing.value = true;
        } else {
            isEditing.value = false;
        }
    } else if (!newVal) {
        // Limpiar datos cuando se cierra el modal
        form.value = {
            devr_commercial_name: '',
            devr_legal_name: '',
            devr_email_contact: '',
            devr_phone_contact: '',
            devr_website: '',
            ctry_id: '',
            user_id: '',
            devr_active: false
        };
        originalForm.value = {
            devr_commercial_name: '',
            devr_legal_name: '',
            devr_email_contact: '',
            devr_phone_contact: '',
            devr_website: '',
            ctry_id: '',
            user_id: '',
            devr_active: false
        };
        isEditing.value = false;
    }
});

const toggleEdit = () => {
    if (isEditing.value) {
        isEditing.value = false;
    } else {
        if (props.developer) {
            const formData = {
                devr_commercial_name: props.developer.devr_commercial_name || '',
                devr_legal_name: props.developer.devr_legal_name || '',
                devr_email_contact: props.developer.devr_email_contact || '',
                devr_phone_contact: props.developer.devr_phone_contact || '',
                devr_website: props.developer.devr_website || '',
                ctry_id: props.developer.ctry_id || '',
                user_id: props.developer.user_id || '',
                devr_active: props.developer.devr_active ? 1 : 0
            };
            
            form.value = formData;
            originalForm.value = JSON.parse(JSON.stringify(formData));
        }
        isEditing.value = true;
    }
};

const validateForm = () => {
    errors.value = {};
    
    if (!form.value.devr_commercial_name?.trim()) {
        errors.value.devr_commercial_name = 'El nombre comercial es requerido';
    }
    if (!form.value.devr_legal_name?.trim()) {
        errors.value.devr_legal_name = 'El nombre legal es requerido';
    }
    if (!form.value.devr_email_contact?.trim()) {
        errors.value.devr_email_contact = 'El email es requerido';
    }
    if (!form.value.ctry_id) {
        errors.value.ctry_id = 'El país es requerido';
    }
    if (!form.value.user_id) {
        errors.value.user_id = 'El usuario es requerido';
    }
    
    return Object.keys(errors.value).length === 0;
};

const handleSubmit = async () => {
    if (!validateForm()) {
        notificationStore.error('Por favor completa todos los campos requeridos');
        return;
    }

    if (!hasChanges.value) {
        notificationStore.error('No has realizado cambios');
        return;
    }
    
    isSubmitting.value = true;
    const formData = new FormData();
    
    if (form.value.devr_commercial_name) formData.append('devr_commercial_name', form.value.devr_commercial_name);
    if (form.value.devr_legal_name) formData.append('devr_legal_name', form.value.devr_legal_name);
    if (form.value.devr_email_contact) formData.append('devr_email_contact', form.value.devr_email_contact);
    if (form.value.devr_phone_contact) formData.append('devr_phone_contact', form.value.devr_phone_contact);
    if (form.value.devr_website) formData.append('devr_website', form.value.devr_website);
    if (form.value.ctry_id) formData.append('ctry_id', form.value.ctry_id);
    if (form.value.user_id) formData.append('user_id', form.value.user_id);
    formData.append('devr_active', form.value.devr_active || 0);
    formData.append('_method', 'PUT');
    
    try {
        await axios.post(route('developers.update', props.developer.devr_id), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        });
        
        sessionStorage.setItem('showUpdateDeveloperNotification', 'true');
        closeModal();
        window.location.href = route('developers.index');
    } catch (error) {
        console.error('Error updating developer:', error);
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors;
            const errorMessages = Object.values(errors.value).flat().join(', ');
            notificationStore.error(errorMessages);
        } else {
            notificationStore.error('Error al actualizar el desarrollador');
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
                {{ isEditing ? 'Editar Desarrollador' : 'Información del Desarrollador' }}
            </h2>
        </template>

        <template #content>
            <!-- Vista -->
            <div v-if="!isEditing" class="space-y-4">
                <div class="modal-grid">
                    <div>
                        <p class="modal-label">Nombre Comercial</p>
                        <p class="modal-value">{{ developer?.devr_commercial_name }}</p>
                    </div>
                    <div>
                        <p class="modal-label">Nombre Legal</p>
                        <p class="modal-value">{{ developer?.devr_legal_name }}</p>
                    </div>
                </div>
                <div class="modal-grid">
                    <div>
                        <p class="modal-label">Email</p>
                        <p class="modal-value">{{ developer?.devr_email_contact }}</p>
                    </div>
                    <div>
                        <p class="modal-label">Teléfono</p>
                        <p class="modal-value">{{ developer?.devr_phone_contact || 'N/A' }}</p>
                    </div>
                </div>
                <div class="modal-grid">
                    <div>
                        <p class="modal-label">Sitio Web</p>
                        <p class="modal-value">{{ developer?.devr_website || 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="modal-label">País</p>
                        <p class="modal-value">{{ countries.find(c => c.ctry_id == developer?.ctry_id)?.ctry_name || 'N/A' }}</p>
                    </div>
                </div>
                <div class="modal-grid">
                    <div>
                        <p class="modal-label">Usuario</p>
                        <p class="modal-value">{{ users.find(u => u.id == developer?.user_id)?.name || 'N/A' }}</p>
                    </div>
                </div>
                <div>
                    <p class="modal-label">Estado</p>
                    <div :class="[
                        'py-1.5 px-2.5 rounded-full flex items-center gap-2 w-fit',
                        developer?.devr_active ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600'
                    ]">
                        <svg width="6" height="6" viewBox="0 0 6 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="3" cy="3" r="3" :fill="developer?.devr_active ? '#059669' : '#dc2626'" />
                        </svg>
                        <span class="font-semibold text-xs">
                            {{ developer?.devr_active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Edición -->
            <div v-else class="space-y-6">
                <div>
                    <label for="devr_commercial_name" class="block text-sm font-medium text-gray-700">Nombre Comercial <span class="text-red-600">*</span></label>
                    <input 
                        id="devr_commercial_name" 
                        v-model="form.devr_commercial_name" 
                        type="text" 
                        class="mt-1 block w-full border rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        :class="errors.devr_commercial_name ? 'border-red-500' : 'border-gray-300'">
                    <p v-if="errors.devr_commercial_name" class="mt-1 text-sm text-red-600">{{ errors.devr_commercial_name }}</p>
                </div>

                <div>
                    <label for="devr_legal_name" class="block text-sm font-medium text-gray-700">Nombre Legal <span class="text-red-600">*</span></label>
                    <input 
                        id="devr_legal_name" 
                        v-model="form.devr_legal_name" 
                        type="text" 
                        class="mt-1 block w-full border rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        :class="errors.devr_legal_name ? 'border-red-500' : 'border-gray-300'">
                    <p v-if="errors.devr_legal_name" class="mt-1 text-sm text-red-600">{{ errors.devr_legal_name }}</p>
                </div>

                <div>
                    <label for="devr_email_contact" class="block text-sm font-medium text-gray-700">Email de Contacto <span class="text-red-600">*</span></label>
                    <input 
                        id="devr_email_contact" 
                        v-model="form.devr_email_contact" 
                        type="email" 
                        class="mt-1 block w-full border rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        :class="errors.devr_email_contact ? 'border-red-500' : 'border-gray-300'">
                    <p v-if="errors.devr_email_contact" class="mt-1 text-sm text-red-600">{{ errors.devr_email_contact }}</p>
                </div>

                <div>
                    <label for="devr_phone_contact" class="block text-sm font-medium text-gray-700">Teléfono de Contacto</label>
                    <input 
                        id="devr_phone_contact" 
                        v-model="form.devr_phone_contact" 
                        type="text" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label for="devr_website" class="block text-sm font-medium text-gray-700">Sitio Web</label>
                    <input 
                        id="devr_website" 
                        v-model="form.devr_website" 
                        type="url" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label for="ctry_id" class="block text-sm font-medium text-gray-700">País <span class="text-red-600">*</span></label>
                    <select 
                        id="ctry_id" 
                        v-model="form.ctry_id"
                        class="mt-1 block w-full border rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        :class="errors.ctry_id ? 'border-red-500' : 'border-gray-300'">
                        <option value="">Seleccionar país</option>
                        <option v-for="country in countries" :key="country.ctry_id" :value="country.ctry_id">
                            {{ country.ctry_name }}
                        </option>
                    </select>
                    <p v-if="errors.ctry_id" class="mt-1 text-sm text-red-600">{{ errors.ctry_id }}</p>
                </div>

                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700">Usuario Asociado <span class="text-red-600">*</span></label>
                    <select 
                        id="user_id" 
                        v-model="form.user_id"
                        class="mt-1 block w-full border rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        :class="errors.user_id ? 'border-red-500' : 'border-gray-300'">
                        <option value="">Seleccionar usuario</option>
                        <option v-for="user in users" :key="user.id" :value="user.id">
                            {{ user.name }}
                        </option>
                    </select>
                    <p v-if="errors.user_id" class="mt-1 text-sm text-red-600">{{ errors.user_id }}</p>
                </div>

                <div>
                    <label for="devr_active" class="block text-sm font-medium text-gray-700">Estado</label>
                    <select 
                        id="devr_active" 
                        v-model="form.devr_active"
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
