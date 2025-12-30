<script setup>
import DialogModal from '@/Components/DialogModal.vue';
import { ref, watch, computed } from 'vue';
import axios from 'axios';
import Form from './Form.vue';
import { useNotificationStore } from '@/stores/notificationStore';

const notificationStore = useNotificationStore();

const props = defineProps({
    show: {
        type: Boolean,
        required: true
    },
    leadStatus: {
        type: Object,
        default: null
    },
    editMode: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['close']);

const isEditing = ref(false);
const isSubmitting = ref(false);
const errors = ref({});

const form = ref({
    leadSta_code: '',
    leadSta_name: '',
    leadSta_description: '',
    leadSta_active: true
});

const originalForm = ref({
    leadSta_code: '',
    leadSta_name: '',
    leadSta_description: '',
    leadSta_active: true
});

const hasChanges = computed(() => {
    return (
        form.value.leadSta_code !== originalForm.value.leadSta_code ||
        form.value.leadSta_name !== originalForm.value.leadSta_name ||
        form.value.leadSta_description !== originalForm.value.leadSta_description ||
        form.value.leadSta_active !== originalForm.value.leadSta_active
    );
});

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

const handleSave = async () => {
    if (!validateForm()) {
        return;
    }
    
    if (!hasChanges.value) {
        notificationStore.error('No has realizado cambios');
        return;
    }
    
    isSubmitting.value = true;
    const formData = new FormData();

    formData.append('leadSta_code', form.value.leadSta_code);
    formData.append('leadSta_name', form.value.leadSta_name);
    formData.append('leadSta_description', form.value.leadSta_description);
    formData.append('leadSta_active', form.value.leadSta_active ? 1 : 0);
    formData.append('_method', 'PUT');

    try {
        await axios.post(route('leadstatus.update', props.leadStatus.leadSta_id), formData);
        
        sessionStorage.setItem('showUpdateLeadStatusNotification', 'true');
        window.location.href = route('leadstatus.index');
    } catch (error) {
        console.error('Error updating lead status:', error);
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors;
            const errorMessages = Object.values(errors.value).flat().join(', ');
            notificationStore.error(errorMessages);
        } else {
            notificationStore.error('Error al actualizar el estado de lead');
        }
        isSubmitting.value = false;
    }
};

const closeLeadStatusModal = () => {
    isEditing.value = false;
    emit('close');
};

const toggleEdit = () => {
    isEditing.value = true;
};

const handleSubmit = async () => {
    await handleSave();
};

const loadFormData = () => {
    if (props.leadStatus) {
        form.value = {
            leadSta_code: props.leadStatus.leadSta_code,
            leadSta_name: props.leadStatus.leadSta_name,
            leadSta_description: props.leadStatus.leadSta_description,
            leadSta_active: props.leadStatus.leadSta_active
        };
        originalForm.value = JSON.parse(JSON.stringify(form.value));
        isEditing.value = props.editMode;
    }
};

watch(() => props.show, (newVal) => {
    if (newVal) {
        loadFormData();
    }
});

watch(() => props.leadStatus, () => {
    if (props.show) {
        loadFormData();
    }
});

watch(() => props.editMode, (newVal) => {
    isEditing.value = newVal;
});
</script>

<template>
    <DialogModal :show="show" @close="emit('close')" max-width="2xl">
        <template #title>
            <h2 class="text-xl font-semibold">
                {{ isEditing ? 'Editar Estado de Lead' : 'Ver Estado de Lead' }}
            </h2>
        </template>

        <template #content>
            <div v-if="!isEditing" class="space-y-4">
                <!-- Código -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Código</label>
                    <p class="px-3 py-2 text-gray-900">{{ leadStatus?.leadSta_code }}</p>
                </div>

                <!-- Nombre -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <p class="px-3 py-2 text-gray-900">{{ leadStatus?.leadSta_name }}</p>
                </div>

                <!-- Descripción -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <p class="px-3 py-2 text-gray-900">{{ leadStatus?.leadSta_description }}</p>
                </div>

                <!-- Estado -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <div class="inline-block">
                        <span v-if="leadStatus?.leadSta_active" class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">Activo</span>
                        <span v-else class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm">Inactivo</span>
                    </div>
                </div>
            </div>

            <Form v-else :form="form" :updating="true" :errors="errors" @submit="handleSubmit" />
        </template>

        <template #footer>
            <button
                @click="closeLeadStatusModal"
                class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
            >
                {{ isEditing ? 'Cancelar' : 'Cerrar' }}
            </button>
            <button
                v-if="!isEditing"
                @click="toggleEdit"
                class="ml-3 px-4 py-2 text-white bg-indigo-600 rounded-md hover:bg-indigo-700"
            >
                Editar
            </button>
            <button
                v-else
                @click="handleSubmit"
                :disabled="!hasChanges || isSubmitting"
                class="ml-3 px-4 py-2 text-white bg-indigo-600 rounded-md hover:bg-indigo-700 disabled:opacity-50"
            >
                {{ isSubmitting ? 'Guardando...' : 'Guardar Cambios' }}
            </button>
        </template>
    </DialogModal>
</template>
