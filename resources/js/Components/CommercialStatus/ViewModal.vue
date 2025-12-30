<script setup>
import DialogModal from '@/Components/DialogModal.vue';
import Form from '@/Components/CommercialStatus/Form.vue';
import { ref, watch, computed } from 'vue';
import axios from 'axios';
import { useNotificationStore } from '@/stores/notificationStore';

const props = defineProps({
    show: {
        type: Boolean,
        required: true
    },
    commercialStatus: {
        type: Object,
        default: null
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
    commSta_name: '',
    commSta_code: '',
    commSta_description: '',
    commSta_active: false
});

const originalForm = ref({
    commSta_name: '',
    commSta_code: '',
    commSta_description: '',
    commSta_active: false
});

const hasChanges = computed(() => {
    return form.value.commSta_name !== originalForm.value.commSta_name ||
           form.value.commSta_code !== originalForm.value.commSta_code ||
           form.value.commSta_description !== originalForm.value.commSta_description ||
           form.value.commSta_active !== originalForm.value.commSta_active;
});

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

watch(() => props.show, (newVal) => {
    if (newVal && props.commercialStatus) {
        const formData = {
            commSta_name: props.commercialStatus.commSta_name || '',
            commSta_code: props.commercialStatus.commSta_code || '',
            commSta_description: props.commercialStatus.commSta_description || '',
            commSta_active: props.commercialStatus.commSta_active
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

const toggleEdit = () => {
    if (isEditing.value) {
        isEditing.value = false;
    } else {
        if (props.commercialStatus) {
            const formData = {
                commSta_name: props.commercialStatus.commSta_name || '',
                commSta_code: props.commercialStatus.commSta_code || '',
                commSta_description: props.commercialStatus.commSta_description || '',
                commSta_active: props.commercialStatus.commSta_active
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

    formData.append('commSta_name', form.value.commSta_name);
    formData.append('commSta_code', form.value.commSta_code);
    formData.append('commSta_description', form.value.commSta_description);
    formData.append('commSta_active', form.value.commSta_active ? 1 : 0);
    formData.append('_method', 'PUT');

    try {
        await axios.post(route('commercialstatus.update', props.commercialStatus.commSta_id), formData);
        
        sessionStorage.setItem('showUpdateCommercialStatusNotification', 'true');
        window.location.href = route('commercialstatus.index');
    } catch (error) {
        console.error('Error updating commercial status:', error);
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors;
            const errorMessages = Object.values(errors.value).flat().join(', ');
            notificationStore.error(errorMessages);
        } else {
            notificationStore.error('Error al actualizar el estado comercial');
        }
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
                {{ isEditing ? 'Editar Estado Comercial' : 'Información del Estado Comercial' }}
            </h2>
        </template>

        <template #content>
            <!-- Vista -->
            <div v-if="!isEditing" class="space-y-4">
                <div class="modal-grid">
                    <div>
                        <p class="modal-label">Nombre</p>
                        <p class="modal-value">{{ commercialStatus?.commSta_name }}</p>
                    </div>
                    <div>
                        <p class="modal-label">Código</p>
                        <p class="modal-value">{{ commercialStatus?.commSta_code }}</p>
                    </div>
                </div>
                <div>
                    <p class="modal-label">Descripción</p>
                    <p class="modal-value">{{ commercialStatus?.commSta_description || 'N/A' }}</p>
                </div>
                <div>
                    <p class="modal-label">Estado</p>
                    <div :class="[
                        'py-1.5 px-2.5 rounded-full flex items-center gap-2 w-fit',
                        commercialStatus?.commSta_active ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600'
                    ]">
                        <svg width="6" height="6" viewBox="0 0 6 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="3" cy="3" r="3" :fill="commercialStatus?.commSta_active ? '#059669' : '#dc2626'" />
                        </svg>
                        <span class="font-semibold text-xs">
                            {{ commercialStatus?.commSta_active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Edición -->
            <div v-else>
                <Form :form="form" :edit-mode="true" :errors="errors" @submit="handleSubmit" />
            </div>
        </template>

        <template #footer>
            <div v-if="!isEditing" class="flex gap-3">
                <button
                    @click="closeModal"
                    class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                    Cerrar
                </button>
                <button
                    @click="toggleEdit"
                    v-if="$page.props.user.permissions.includes('update commercial status')"
                    class="px-4 py-2 text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition">
                    Editar
                </button>
            </div>
            <div v-else class="flex gap-3">
                <button
                    @click="toggleEdit"
                    class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                    Cancelar
                </button>
                <button
                    @click="handleSubmit"
                    :disabled="!hasChanges || isSubmitting"
                    class="px-4 py-2 text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition disabled:opacity-50">
                    {{ isSubmitting ? 'Guardando...' : 'Guardar Cambios' }}
                </button>
            </div>
        </template>
    </DialogModal>
</template>
