<script setup>
import DialogModal from '@/Components/DialogModal.vue';
import Form from '@/Components/CommercialStatus/Form.vue';
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
    commSta_name: '',
    commSta_code: '',
    commSta_description: '',
    commSta_active: true
});

const isSubmitting = ref(false);
const errors = ref({});

const validateForm = () => {
    errors.value = {};
    
    if (!form.value.commSta_name?.trim()) {
        errors.value.commSta_name = 'El nombre es requerido';
    }
    if (!form.value.commSta_code?.trim()) {
        errors.value.commSta_code = 'El código es requerido';
    }
    
    return Object.keys(errors.value).length === 0;
};

const handleSubmit = async () => {
    if (!validateForm()) {
        return;
    }
    
    isSubmitting.value = true;
    const formData = new FormData();
    
    if (form.value.commSta_name) formData.append('commSta_name', form.value.commSta_name);
    if (form.value.commSta_code) formData.append('commSta_code', form.value.commSta_code);
    if (form.value.commSta_description) formData.append('commSta_description', form.value.commSta_description);
    formData.append('commSta_active', form.value.commSta_active ? 1 : 0);
    
    try {
        await axios.post(route('commercialstatus.store'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        });
        
        // Guardar flag en sessionStorage para mostrar notificación después del refresco
        sessionStorage.setItem('showCreateCommercialStatusNotification', 'true');
        
        // Cerrar modal y refrescar la página
        closeModal();
        window.location.href = route('commercialstatus.index');
    } catch (error) {
        console.error('Error creating commercial status:', error);
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors;
            const errorMessages = Object.values(errors.value).flat().join(', ');
            notificationStore.error(errorMessages);
        } else {
            notificationStore.error('Error al crear el estado comercial');
        }
    } finally {
        isSubmitting.value = false;
    }
};

const closeModal = () => {
    // Resetear el formulario
    form.value = {
        commSta_name: '',
        commSta_code: '',
        commSta_description: '',
        commSta_active: true
    };
    emit('close');
};

watch(() => props.show, (newVal) => {
    if (newVal) {
        // Resetear el formulario cuando se abre el modal
        form.value = {
            commSta_name: '',
            commSta_code: '',
            commSta_description: '',
            commSta_active: true
        };
    }
});
</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="2xl">
        <template #title>
            <h2 class="modal-title text-xl">Crear Nuevo Estado Comercial</h2>
        </template>

        <template #content>
            <Form :form="form" :edit-mode="false" :errors="errors" @submit="handleSubmit" />
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
                {{ isSubmitting ? 'Guardando...' : 'Crear Estado' }}
            </button>
        </template>
    </DialogModal>
</template>
