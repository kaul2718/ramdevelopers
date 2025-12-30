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
    leadSou_code: '',
    leadSou_name: '',
    leadSou_description: '',
    leadSou_active: true
});

const isSubmitting = ref(false);
const errors = ref({});

const validateForm = () => {
    errors.value = {};
    
    if (!form.value.leadSou_name?.trim()) {
        errors.value.leadSou_name = 'El nombre es requerido';
    }
    if (!form.value.leadSou_code?.trim()) {
        errors.value.leadSou_code = 'El código es requerido';
    }
    
    return Object.keys(errors.value).length === 0;
};

const handleSubmit = async () => {
    if (!validateForm()) {
        return;
    }
    
    isSubmitting.value = true;
    const formData = new FormData();

    formData.append('leadSou_code', form.value.leadSou_code);
    formData.append('leadSou_name', form.value.leadSou_name);
    formData.append('leadSou_description', form.value.leadSou_description);
    formData.append('leadSou_active', form.value.leadSou_active ? 1 : 0);

    try {
        await axios.post(route('leadsources.store'), formData);
        
        sessionStorage.setItem('showCreateLeadSourceNotification', 'true');
        window.location.href = route('leadsources.index');
    } catch (error) {
        console.error('Error creating lead source:', error);
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors;
            const errorMessages = Object.values(errors.value).flat().join(', ');
            notificationStore.error(errorMessages);
        } else {
            notificationStore.error('Error al crear la fuente de lead');
        }
        isSubmitting.value = false;
    }
};

const resetForm = () => {
    form.value = {
        leadSou_code: '',
        leadSou_name: '',
        leadSou_description: '',
        leadSou_active: true
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
            <h2 class="text-xl font-semibold">Crear Fuente de Lead</h2>
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
