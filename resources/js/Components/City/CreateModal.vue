<script setup>
import DialogModal from '@/Components/DialogModal.vue';
import { ref, watch } from 'vue';
import axios from 'axios';
import { useNotificationStore } from '@/stores/notificationStore';

const props = defineProps({
    show: {
        type: Boolean,
        required: true
    },
    countries: {
        type: Array,
        default: () => []
    }
});

const emit = defineEmits(['close']);

const notificationStore = useNotificationStore();

const form = ref({
    city_name: '',
    city_region: '',
    ctry_id: '',
    city_active: true
});

const isSubmitting = ref(false);
const errors = ref({});

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

const handleSubmit = async () => {
    if (!validateForm()) {
        return;
    }
    
    isSubmitting.value = true;
    const formData = new FormData();
    
    if (form.value.city_name) formData.append('city_name', form.value.city_name);
    if (form.value.city_region) formData.append('city_region', form.value.city_region);
    if (form.value.ctry_id) formData.append('ctry_id', form.value.ctry_id);
    formData.append('city_active', form.value.city_active ? 1 : 0);
    
    try {
        await axios.post(route('cities.store'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        });
        
        // Guardar flag en sessionStorage para mostrar notificación después del refresco
        sessionStorage.setItem('showCreateCityNotification', 'true');
        
        // Cerrar modal y refrescar la página
        closeModal();
        window.location.href = route('cities.index');
    } catch (error) {
        console.error('Error creating city:', error);
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors;
            const errorMessages = Object.values(errors.value).flat().join(', ');
            notificationStore.error(errorMessages);
        } else {
            notificationStore.error('Error al crear la ciudad');
        }
    } finally {
        isSubmitting.value = false;
    }
};

const closeModal = () => {
    // Resetear el formulario
    form.value = {
        city_name: '',
        city_region: '',
        ctry_id: '',
        city_active: true
    };
    emit('close');
};

watch(() => props.show, (newVal) => {
    if (newVal) {
        // Resetear el formulario cuando se abre el modal
        form.value = {
            city_name: '',
            city_region: '',
            ctry_id: '',
            city_active: true
        };
    }
});
</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="2xl">
        <template #title>
            <h2 class="modal-title text-xl">Crear Nueva Ciudad</h2>
        </template>

        <template #content>
            <div class="space-y-6">
                <div>
                    <label for="city_name" class="block text-sm font-medium text-gray-700">
                        Nombre de la Ciudad <span class="text-red-600">*</span>
                    </label>
                    <input 
                        id="city_name" 
                        v-model="form.city_name" 
                        type="text" 
                        class="mt-1 block w-full rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500"
                        :class="errors.city_name ? 'border border-red-500' : 'border border-gray-300 focus:border-indigo-500'"
                        placeholder="Bogotá">
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
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Cundinamarca">
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
                {{ isSubmitting ? 'Guardando...' : 'Crear Ciudad' }}
            </button>
        </template>
    </DialogModal>
</template>

