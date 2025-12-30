<script setup>
import DialogModal from '@/Components/DialogModal.vue';
import { ref, watch } from 'vue';
import axios from 'axios';
import Form from './Form.vue';
import { useNotificationStore } from '@/stores/notificationStore';

const notificationStore = useNotificationStore();

const props = defineProps({
    show: {
        type: Boolean,
        required: true
    }
});

const emit = defineEmits(['close']);

const form = ref({
    leadSta_code: '',
    leadSta_name: '',
    leadSta_description: '',
    leadSta_active: true
});

const isSubmitting = ref(false);
const errors = ref({});

const validateForm = () => {
    errors.value = {};
    
    if (!form.value.leadSta_name?.trim()) {
        errors.value.leadSta_name = 'El nombre es requerido';
    }
    if (!form.value.leadSta_code?.trim()) {
        errors.value.leadSta_code = 'El código es requerido';
    }
    
    return Object.keys(errors.value).length === 0;
};

const handleSubmit = async () => {
    if (!validateForm()) {
        return;
    }
    
    isSubmitting.value = true;
    const formData = new FormData();

    formData.append('leadSta_code', form.value.leadSta_code);
    formData.append('leadSta_name', form.value.leadSta_name);
    formData.append('leadSta_description', form.value.leadSta_description);
    formData.append('leadSta_active', form.value.leadSta_active ? 1 : 0);

    try {
        await axios.post(route('leadstatus.store'), formData);
        
        sessionStorage.setItem('showCreateLeadStatusNotification', 'true');
        window.location.href = route('leadstatus.index');
    } catch (error) {
        console.error('Error creating lead status:', error);
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors;
            const errorMessages = Object.values(errors.value).flat().join(', ');
            notificationStore.error(errorMessages);
        } else {
            notificationStore.error('Error al crear el estado de lead');
        }
        isSubmitting.value = false;
    }
};

const resetForm = () => {
    form.value = {
        leadSta_code: '',
        leadSta_name: '',
        leadSta_description: '',
        leadSta_active: true
    };
    isSubmitting.value = false;
};

const closeModal = () => {
    resetForm();
    emit('close');
};

watch(() => props.show, (newVal) => {
    if (newVal) {
        resetForm();
    }
});
</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="2xl">
        <template #title>
            <h2 class="text-xl font-semibold">Crear Estado de Lead</h2>
        </template>

        <template #content>
            <Form :form="form" :updating="false" :errors="errors" @submit="handleSubmit" />
        </template>

        <template #footer>
            <button
                @click="closeModal"
                class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
            >
                Cancelar
            </button>
            <button
                @click="handleSubmit"
                :disabled="isSubmitting"
                class="ml-3 px-4 py-2 text-white bg-indigo-600 rounded-md hover:bg-indigo-700 disabled:opacity-50"
            >
                {{ isSubmitting ? 'Creando...' : 'Crear' }}
            </button>
        </template>
    </DialogModal>
</template>
