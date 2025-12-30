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
    ctry_name: '',
    ctry_code: '',
    ctry_active: true
});

const isSubmitting = ref(false);
const errors = ref({});

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

    isSubmitting.value = true;
    const formData = new FormData();
    
    if (form.value.ctry_name) formData.append('ctry_name', form.value.ctry_name);
    if (form.value.ctry_code) formData.append('ctry_code', form.value.ctry_code);
    formData.append('ctry_active', form.value.ctry_active ? 1 : 0);
    
    try {
        await axios.post(route('countries.store'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        });
        
        // Guardar flag en sessionStorage para mostrar notificación después del refresco
        sessionStorage.setItem('showCreateCountryNotification', 'true');
        
        // Cerrar modal y refrescar la página
        closeModal();
        window.location.href = route('countries.index');
    } catch (error) {
        console.error('Error creating country:', error);
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors;
            const errorMessages = Object.values(errors.value).flat().join(', ');
            notificationStore.error(errorMessages);
        } else {
            notificationStore.error('Error al crear el país');
        }
    } finally {
        isSubmitting.value = false;
    }
};

const closeModal = () => {
    // Resetear el formulario
    form.value = {
        ctry_name: '',
        ctry_code: '',
        ctry_active: true
    };
    emit('close');
};

watch(() => props.show, (newVal) => {
    if (newVal) {
        // Resetear el formulario cuando se abre el modal
        form.value = {
            ctry_name: '',
            ctry_code: '',
            ctry_active: true
        };
    }
});
</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="2xl">
        <template #title>
            <h2 class="modal-title text-xl">Crear Nuevo País</h2>
        </template>

        <template #content>
            <div class="space-y-6">
                <div>
                    <label for="ctry_name" class="block text-sm font-medium text-gray-700">Nombre del País <span class="text-red-600">*</span></label>
                    <input 
                        id="ctry_name" 
                        v-model="form.ctry_name" 
                        type="text" 
                        class="mt-1 block w-full border rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        :class="errors.ctry_name ? 'border-red-500' : 'border-gray-300'"
                        placeholder="Colombia">
                    <p v-if="errors.ctry_name" class="mt-1 text-sm text-red-600">{{ errors.ctry_name }}</p>
                </div>

                <div>
                    <label for="ctry_code" class="block text-sm font-medium text-gray-700">Código (ISO) <span class="text-red-600">*</span></label>
                    <input 
                        id="ctry_code" 
                        v-model="form.ctry_code" 
                        type="text" 
                        maxlength="5"
                        class="mt-1 block w-full border rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        :class="errors.ctry_code ? 'border-red-500' : 'border-gray-300'"
                        placeholder="CO">
                    <p v-if="errors.ctry_code" class="mt-1 text-sm text-red-600">{{ errors.ctry_code }}</p>
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
                {{ isSubmitting ? 'Guardando...' : 'Crear País' }}
            </button>
        </template>
    </DialogModal>
</template>
