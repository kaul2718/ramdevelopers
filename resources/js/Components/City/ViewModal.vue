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
    city: {
        type: Object,
        default: null
    },
    countries: {
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
    city_name: '',
    city_region: '',
    ctry_id: '',
    city_active: false
});

const originalForm = ref({
    city_name: '',
    city_region: '',
    ctry_id: '',
    city_active: false
});

const hasChanges = computed(() => {
    return form.value.city_name !== originalForm.value.city_name ||
           form.value.city_region !== originalForm.value.city_region ||
           form.value.ctry_id !== originalForm.value.ctry_id ||
           form.value.city_active !== originalForm.value.city_active;
});

watch(() => props.show, (newVal) => {
    if (newVal && props.city) {
        const formData = {
            city_name: props.city.city_name || '',
            city_region: props.city.city_region || '',
            ctry_id: props.city.ctry_id || '',
            city_active: props.city.city_active ? 1 : 0
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
    
    if (!form.value.city_name?.trim()) {
        errors.value.city_name = 'El nombre de la ciudad es requerido';
    }
    if (!form.value.ctry_id) {
        errors.value.ctry_id = 'Debe seleccionar un país';
    }
    
    return Object.keys(errors.value).length === 0;
};

const toggleEdit = () => {
    if (isEditing.value) {
        isEditing.value = false;
    } else {
        if (props.city) {
            const formData = {
                city_name: props.city.city_name || '',
                city_region: props.city.city_region || '',
                ctry_id: props.city.ctry_id || '',
                city_active: props.city.city_active ? 1 : 0
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
    
    if (form.value.city_name) formData.append('city_name', form.value.city_name);
    if (form.value.city_region) formData.append('city_region', form.value.city_region);
    if (form.value.ctry_id) formData.append('ctry_id', form.value.ctry_id);
    formData.append('city_active', form.value.city_active || 0);
    formData.append('_method', 'PUT');
    
    try {
        await axios.post(route('cities.update', props.city.city_id), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        });
        
        // Guardar flag en sessionStorage para mostrar notificación después del refresco
        sessionStorage.setItem('showUpdateCityNotification', 'true');
        
        // Cerrar modal y refrescar
        closeModal();
        window.location.href = route('cities.index');
    } catch (error) {
        console.error('Error updating city:', error);
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors;
            const errorMessages = Object.values(errors.value).flat().join(', ');
            notificationStore.error(errorMessages);
        } else {
            notificationStore.error('Error al actualizar la ciudad');
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
                {{ isEditing ? 'Editar Ciudad' : 'Información de la Ciudad' }}
            </h2>
        </template>

        <template #content>
            <!-- Vista -->
            <div v-if="!isEditing" class="space-y-4">
                <div class="modal-grid">
                    <div>
                        <p class="modal-label">Nombre</p>
                        <p class="modal-value">{{ city?.city_name }}</p>
                    </div>
                    <div>
                        <p class="modal-label">Región</p>
                        <p class="modal-value">{{ city?.city_region || 'N/A' }}</p>
                    </div>
                </div>
                <div class="modal-grid">
                    <div>
                        <p class="modal-label">País</p>
                        <p class="modal-value">{{ countries.find(c => c.ctry_id == city?.ctry_id)?.ctry_name || 'N/A' }}</p>
                    </div>
                </div>
                <div>
                    <p class="modal-label">Estado</p>
                    <div :class="[
                        'py-1.5 px-2.5 rounded-full flex items-center gap-2 w-fit',
                        city?.city_active ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600'
                    ]">
                        <svg width="6" height="6" viewBox="0 0 6 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="3" cy="3" r="3" :fill="city?.city_active ? '#059669' : '#dc2626'" />
                        </svg>
                        <span class="font-semibold text-xs">
                            {{ city?.city_active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Edición -->
            <div v-else class="space-y-6">
                <div>
                    <label for="city_name" class="block text-sm font-medium text-gray-700">
                        Nombre de la Ciudad <span class="text-red-600">*</span>
                    </label>
                    <input 
                        id="city_name" 
                        v-model="form.city_name" 
                        type="text" 
                        class="mt-1 block w-full rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500"
                        :class="errors.city_name ? 'border border-red-500' : 'border border-gray-300 focus:border-indigo-500'">
                    <p v-if="errors.city_name" class="mt-1 text-sm text-red-600">
                        {{ errors.city_name }}
                    </p>
                </div>

                <div>
                    <label for="city_region" class="block text-sm font-medium text-gray-700">Región</label>
                    <input 
                        id="city_region" 
                        v-model="form.city_region" 
                        type="text" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label for="ctry_id" class="block text-sm font-medium text-gray-700">
                        País <span class="text-red-600">*</span>
                    </label>
                    <select 
                        id="ctry_id" 
                        v-model="form.ctry_id"
                        class="mt-1 block w-full rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500"
                        :class="errors.ctry_id ? 'border border-red-500' : 'border border-gray-300 focus:border-indigo-500'">
                        <option value="">Seleccionar país</option>
                        <option v-for="country in countries" :key="country.ctry_id" :value="country.ctry_id">
                            {{ country.ctry_name }}
                        </option>
                    </select>
                    <p v-if="errors.ctry_id" class="mt-1 text-sm text-red-600">
                        {{ errors.ctry_id }}
                    </p>
                </div>

                <div>
                    <label for="city_active" class="block text-sm font-medium text-gray-700">Estado</label>
                    <select 
                        id="city_active" 
                        v-model="form.city_active"
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
