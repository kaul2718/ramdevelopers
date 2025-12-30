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
    country: {
        type: Object,
        default: null
    },
    isEditingInitial: {
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
    ctry_name: '',
    ctry_code: '',
    ctry_active: false
});

const originalForm = ref({
    ctry_name: '',
    ctry_code: '',
    ctry_active: false
});

const hasChanges = computed(() => {
    return form.value.ctry_name !== originalForm.value.ctry_name ||
           form.value.ctry_code !== originalForm.value.ctry_code ||
           form.value.ctry_active !== originalForm.value.ctry_active;
});

watch(() => props.show, (newVal) => {
    if (newVal && props.country) {
        const formData = {
            ctry_name: props.country.ctry_name || '',
            ctry_code: props.country.ctry_code || '',
            ctry_active: props.country.ctry_active ? 1 : 0
        };
        
        form.value = formData;
        originalForm.value = JSON.parse(JSON.stringify(formData));
        
        if (props.isEditingInitial) {
            isEditing.value = true;
        } else {
            isEditing.value = false;
        }
    }
});

const toggleEdit = () => {
    if (isEditing.value) {
        isEditing.value = false;
    } else {
        if (props.country) {
            const formData = {
                ctry_name: props.country.ctry_name || '',
                ctry_code: props.country.ctry_code || '',
                ctry_active: props.country.ctry_active ? 1 : 0
            };
            
            form.value = formData;
            originalForm.value = JSON.parse(JSON.stringify(formData));
        }
        isEditing.value = true;
    }
};

const validateForm = () => {
    errors.value = {};
    
    if (!form.value.ctry_name?.trim()) {
        errors.value.ctry_name = 'El nombre del país es requerido';
    }
    if (!form.value.ctry_code?.trim()) {
        errors.value.ctry_code = 'El código ISO es requerido';
    } else if (form.value.ctry_code.length !== 2) {
        errors.value.ctry_code = 'El código ISO debe tener 2 caracteres';
    }
    
    return Object.keys(errors.value).length === 0;
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
    
    if (form.value.ctry_name) formData.append('ctry_name', form.value.ctry_name);
    if (form.value.ctry_code) formData.append('ctry_code', form.value.ctry_code);
    formData.append('ctry_active', form.value.ctry_active || 0);
    formData.append('_method', 'PUT');
    
    try {
        await axios.post(route('countries.update', props.country.ctry_id), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        });
        
        // Guardar flag en sessionStorage para mostrar notificación después del refresco
        sessionStorage.setItem('showUpdateCountryNotification', 'true');
        
        // Cerrar modal y refrescar
        closeModal();
        window.location.href = route('countries.index');
    } catch (error) {
        console.error('Error updating country:', error);
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors;
            const errorMessages = Object.values(errors.value).flat().join(', ');
            notificationStore.error(errorMessages);
        } else {
            notificationStore.error('Error al actualizar el país');
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
                {{ isEditing ? 'Editar País' : 'Información del País' }}
            </h2>
        </template>

        <template #content>
            <!-- Vista -->
            <div v-if="!isEditing" class="space-y-4">
                <div class="modal-grid">
                    <div>
                        <p class="modal-label">Nombre</p>
                        <p class="modal-value">{{ country?.ctry_name }}</p>
                    </div>
                    <div>
                        <p class="modal-label">Código</p>
                        <p class="modal-value">{{ country?.ctry_code }}</p>
                    </div>
                </div>
                <div>
                    <p class="modal-label">Estado</p>
                    <div :class="[
                        'py-1.5 px-2.5 rounded-full flex items-center gap-2 w-fit',
                        country?.ctry_active ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600'
                    ]">
                        <svg width="6" height="6" viewBox="0 0 6 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="3" cy="3" r="3" :fill="country?.ctry_active ? '#059669' : '#dc2626'" />
                        </svg>
                        <span class="font-semibold text-xs">
                            {{ country?.ctry_active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Edición -->
            <div v-else class="space-y-6">
                <div>
                    <label for="ctry_name" class="block text-sm font-medium text-gray-700">
                        Nombre del País <span class="text-red-600">*</span>
                    </label>
                    <input 
                        id="ctry_name" 
                        v-model="form.ctry_name" 
                        type="text" 
                        class="mt-1 block w-full rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500"
                        :class="errors.ctry_name ? 'border border-red-500' : 'border border-gray-300 focus:border-indigo-500'">
                    <p v-if="errors.ctry_name" class="mt-1 text-sm text-red-600">
                        {{ errors.ctry_name }}
                    </p>
                </div>

                <div>
                    <label for="ctry_code" class="block text-sm font-medium text-gray-700">
                        Código (ISO) <span class="text-red-600">*</span>
                    </label>
                    <input 
                        id="ctry_code" 
                        v-model="form.ctry_code" 
                        type="text" 
                        maxlength="5"
                        class="mt-1 block w-full rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500"
                        :class="errors.ctry_code ? 'border border-red-500' : 'border border-gray-300 focus:border-indigo-500'">
                    <p v-if="errors.ctry_code" class="mt-1 text-sm text-red-600">
                        {{ errors.ctry_code }}
                    </p>
                </div>

                <div>
                    <label for="ctry_active" class="block text-sm font-medium text-gray-700">Estado</label>
                    <select 
                        id="ctry_active" 
                        v-model="form.ctry_active"
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
