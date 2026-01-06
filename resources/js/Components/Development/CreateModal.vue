<script setup>
import DialogModal from '@/Components/DialogModal.vue';
import { ref } from 'vue';
import axios from 'axios';
import { useNotificationStore } from '@/stores/notificationStore';

const notificationStore = useNotificationStore();

const props = defineProps({
    show: {
        type: Boolean,
        required: true
    },
    developers: {
        type: Array,
        required: true
    },
    countries: {
        type: Array,
        required: true
    },
    cities: {
        type: Array,
        required: true
    },
    approvalStatuses: {
        type: Array,
        required: true
    },
    businessStates: {
        type: Array,
        required: true
    },
    commercialStatuses: {
        type: Array,
        required: true
    }
});

const emit = defineEmits(['close', 'saved']);

const form = ref({
    devr_id: '',
    ctry_id: '',
    city_id: '',
    apvSta_id: '',
    busiSta_id: '',
    commSta_id: '',
    devt_title: '',
    devt_slug: '',
    devt_address: '',
    devt_short_description: '',
    devt_long_description: '',
    devt_price_from: '',
    devt_price_to: '',
    devt_delivery_year: '',
    devt_estimated_profit: '',
    devt_is_featured: false,
});

const isSubmitting = ref(false);

const getCitiesForCountry = () => {
    if (!form.value.ctry_id) return [];
    const country = props.countries.find(c => c.ctry_id == form.value.ctry_id);
    return country?.cities || [];
};

const closeModal = () => {
    form.value = {
        devr_id: '',
        ctry_id: '',
        city_id: '',
        apvSta_id: '',
        busiSta_id: '',
        commSta_id: '',
        devt_title: '',
        devt_slug: '',
        devt_address: '',
        devt_short_description: '',
        devt_long_description: '',
        devt_price_from: '',
        devt_price_to: '',
        devt_delivery_year: '',
        devt_estimated_profit: '',
        devt_is_featured: false,
    };
    emit('close');
};

const handleSubmit = async () => {
    isSubmitting.value = true;
    try {
        const response = await axios.post(route('development.store'), form.value);
        
        if (response.data && response.data.development) {
            notificationStore.success('Desarrollo creado correctamente');
            
            // Emitir el desarrollo recibido
            const development = response.data.development;
            emit('saved', development);
            
            // Cerrar el modal después de emitir
            closeModal();
        }
    } catch (error) {
        console.error('Error:', error);
        notificationStore.error(
            'Error al crear el desarrollo: ' + (error.response?.data?.message || error.message)
        );
    } finally {
        isSubmitting.value = false;
    }
};
</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="xl">
        <template #title>
            <h2 class="text-lg font-semibold">
                Crear Nuevo Desarrollo
            </h2>
        </template>

        <template #content>
            <div class="max-h-96 overflow-y-auto">
                <form @submit.prevent="handleSubmit" class="space-y-6">
                    <!-- Desarrollador -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Desarrollador *</label>
                        <select 
                            v-model="form.devr_id"
                            class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                            <option value="" disabled>Selecciona un desarrollador</option>
                            <option v-for="developer in developers" :key="developer.devr_id" :value="developer.devr_id">
                                {{ developer.devr_commercial_name }}
                            </option>
                        </select>
                    </div>

                    <!-- País -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">País *</label>
                        <select 
                            v-model="form.ctry_id"
                            class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                            <option value="" disabled>Selecciona un país</option>
                            <option v-for="country in countries" :key="country.ctry_id" :value="country.ctry_id">
                                {{ country.ctry_name }}
                            </option>
                        </select>
                    </div>

                    <!-- Ciudad -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ciudad *</label>
                        <select 
                            v-model="form.city_id"
                            :disabled="!form.ctry_id"
                            class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-indigo-300 focus:ring focus:ring-indigo-200 disabled:opacity-50">
                            <option value="" disabled>{{ form.ctry_id ? 'Selecciona una ciudad' : 'Primero selecciona un país' }}</option>
                            <option v-for="city in getCitiesForCountry()" :key="city.city_id" :value="city.city_id">
                                {{ city.city_name }}
                            </option>
                        </select>
                    </div>

                    <!-- Estados -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Estado Aprobación *</label>
                            <select 
                                v-model="form.apvSta_id"
                                class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                                <option value="" disabled>Selecciona</option>
                                <option v-for="status in approvalStatuses" :key="status.apvSta_id" :value="status.apvSta_id">
                                    {{ status.apvSta_name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Estado del Proyecto *</label>
                            <select 
                                v-model="form.busiSta_id"
                                class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                                <option value="" disabled>Selecciona</option>
                                <option v-for="state in businessStates" :key="state.busiSta_id" :value="state.busiSta_id">
                                    {{ state.busiSta_name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo del Proyecto *</label>
                        <select 
                            v-model="form.commSta_id"
                            class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-indigo-300 focus:ring focus:ring-indigo-200">
                            <option value="" disabled>Selecciona</option>
                            <option v-for="status in commercialStatuses" :key="status.commSta_id" :value="status.commSta_id">
                                {{ status.commSta_name }}
                            </option>
                        </select>
                    </div>

                    <!-- Título y Slug -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                            <input 
                                v-model="form.devt_title" 
                                type="text" 
                                class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-indigo-300 focus:ring focus:ring-indigo-200"
                                placeholder="Ej: Proyecto Residencial Downtown">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Slug *</label>
                            <input 
                                v-model="form.devt_slug" 
                                type="text" 
                                class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-indigo-300 focus:ring focus:ring-indigo-200"
                                placeholder="Ej: proyecto-residencial-downtown">
                        </div>
                    </div>

                    <!-- Dirección -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dirección *</label>
                        <input 
                            v-model="form.devt_address" 
                            type="text" 
                            class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-indigo-300 focus:ring focus:ring-indigo-200"
                            placeholder="Ej: Calle Principal 123, Zona 10">
                    </div>

                    <!-- Descripciones -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Resumen *</label>
                            <textarea 
                                v-model="form.devt_short_description" 
                                class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-indigo-300 focus:ring focus:ring-indigo-200"
                                placeholder="Resumen breve"
                                rows="2">
                            </textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción  *</label>
                            <textarea 
                                v-model="form.devt_long_description" 
                                class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-indigo-300 focus:ring focus:ring-indigo-200"
                                placeholder="Descripción detallada"
                                rows="3">
                            </textarea>
                        </div>
                    </div>

                    <!-- Precios y Año -->
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Año Entrega</label>
                            <input 
                                v-model="form.devt_delivery_year" 
                                type="number" 
                                min="2020"
                                class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-indigo-300 focus:ring focus:ring-indigo-200"
                                placeholder="2025">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Precio Desde</label>
                            <input 
                                v-model="form.devt_price_from" 
                                type="number" 
                                min="0"
                                step="0.01"
                                class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-indigo-300 focus:ring focus:ring-indigo-200"
                                placeholder="50000">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Precio Hasta</label>
                            <input 
                                v-model="form.devt_price_to" 
                                type="number" 
                                min="0"
                                step="0.01"
                                class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-indigo-300 focus:ring focus:ring-indigo-200"
                                placeholder="150000">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Honorarios</label>
                        <input 
                            v-model="form.devt_estimated_profit" 
                            type="number" 
                            min="0"
                            step="0.01"
                            class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-indigo-300 focus:ring focus:ring-indigo-200"
                            placeholder="20000">
                    </div>

                    <div>
                        <label class="flex items-center">
                            <input 
                                v-model="form.devt_is_featured" 
                                type="checkbox" 
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">Destacado</span>
                        </label>
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-2 justify-end pt-4 border-t">
                        <button 
                            type="button"
                            @click="closeModal"
                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                            Cancelar
                        </button>
                        <button 
                            type="submit"
                            :disabled="isSubmitting"
                            class="px-4 py-2 text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg disabled:opacity-50">
                            {{ isSubmitting ? 'Guardando...' : 'Guardar' }}
                        </button>
                    </div>
                </form>
            </div>
        </template>
    </DialogModal>
</template>
