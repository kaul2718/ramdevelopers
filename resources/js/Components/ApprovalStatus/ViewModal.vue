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
    approvalStatus: {
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
    apvSta_name: '',
    apvSta_code: '',
    apvSta_description: '',
    apvSta_active: true
});

const originalForm = ref({
    apvSta_name: '',
    apvSta_code: '',
    apvSta_description: '',
    apvSta_active: true
});

const hasChanges = computed(() => {
    return (
        form.value.apvSta_name !== originalForm.value.apvSta_name ||
        form.value.apvSta_code !== originalForm.value.apvSta_code ||
        form.value.apvSta_description !== originalForm.value.apvSta_description ||
        form.value.apvSta_active !== originalForm.value.apvSta_active
    );
});

const validateForm = () => {
    errors.value = {};
    
    if (!form.value.apvSta_name?.trim()) {
        errors.value.apvSta_name = 'El nombre es requerido';
    }
    if (!form.value.apvSta_code?.trim()) {
        errors.value.apvSta_code = 'El código es requerido';
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

    formData.append('apvSta_name', form.value.apvSta_name);
    formData.append('apvSta_code', form.value.apvSta_code);
    formData.append('apvSta_description', form.value.apvSta_description);
    formData.append('apvSta_active', form.value.apvSta_active ? 1 : 0);
    formData.append('_method', 'PUT');

    try {
        const response = await axios.post(route('approvalstatus.update', props.approvalStatus.apvSta_id), formData);
        
        // Store flag in sessionStorage
        sessionStorage.setItem('showUpdateApprovalStatusNotification', 'true');
        
        // Redirect to index
        window.location.href = route('approvalstatus.index');
    } catch (error) {
        console.error('Error updating approval status:', error);
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors;
            const errorMessages = Object.values(errors.value).flat().join(', ');
            notificationStore.error(errorMessages);
        } else {
            notificationStore.error('Error al actualizar el estado de aprobación');
        }
        isSubmitting.value = false;
    }
};

const closeApprovalStatusModal = () => {
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
    if (props.approvalStatus) {
        form.value = {
            apvSta_name: props.approvalStatus.apvSta_name,
            apvSta_code: props.approvalStatus.apvSta_code,
            apvSta_description: props.approvalStatus.apvSta_description,
            apvSta_active: props.approvalStatus.apvSta_active
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

watch(() => props.approvalStatus, () => {
    if (props.show) {
        loadFormData();
    }
});

watch(() => props.editMode, (newVal) => {
    isEditing.value = newVal;
});
</script>

<template>
    <DialogModal :show="show" @close="emit('close')">
        <template #title>
            <h2 class="modal-title text-xl">
                {{ isEditing ? 'Editar Estado de Aprobación' : 'Ver Estado de Aprobación' }}
            </h2>
        </template>

        <template #content>
            <div v-if="!isEditing" class="space-y-4">
                <!-- Nombre -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <p class="px-3 py-2 text-gray-900">{{ approvalStatus?.apvSta_name }}</p>
                </div>

                <!-- Código -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Código</label>
                    <p class="px-3 py-2 text-gray-900">{{ approvalStatus?.apvSta_code }}</p>
                </div>

                <!-- Descripción -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <p class="px-3 py-2 text-gray-900">{{ approvalStatus?.apvSta_description || 'N/A' }}</p>
                </div>

                <!-- Estado -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <div class="inline-block">
                        <span v-if="approvalStatus?.apvSta_active" class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-sm font-semibold">
                            Activo
                        </span>
                        <span v-else class="px-3 py-1 rounded-full bg-red-50 text-red-600 text-sm font-semibold">
                            Inactivo
                        </span>
                    </div>
                </div>
            </div>

            <form v-else @submit.prevent="handleSubmit" class="space-y-4">
                <!-- Nombre -->
                <div>
                    <label for="apvSta_name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre <span class="text-red-600">*</span>
                    </label>
                    <input
                        v-model="form.apvSta_name"
                        type="text"
                        id="apvSta_name"
                        class="w-full px-3 py-2 rounded-md text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-indigo-500"
                        :class="errors.apvSta_name ? 'border border-red-500' : 'border border-gray-300 focus:border-indigo-500'"
                        placeholder="Ej: Pendiente"
                    />
                    <p v-if="errors.apvSta_name" class="mt-1 text-sm text-red-600">
                        {{ errors.apvSta_name }}
                    </p>
                </div>

                <!-- Código -->
                <div>
                    <label for="apvSta_code" class="block text-sm font-medium text-gray-700 mb-1">
                        Código <span class="text-red-600">*</span>
                    </label>
                    <input
                        v-model="form.apvSta_code"
                        type="text"
                        id="apvSta_code"
                        class="w-full px-3 py-2 rounded-md text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-indigo-500"
                        :class="errors.apvSta_code ? 'border border-red-500' : 'border border-gray-300 focus:border-indigo-500'"
                        placeholder="Ej: PEND"
                    />
                    <p v-if="errors.apvSta_code" class="mt-1 text-sm text-red-600">
                        {{ errors.apvSta_code }}
                    </p>
                </div>

                <!-- Descripción -->
                <div>
                    <label for="apvSta_description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea
                        v-model="form.apvSta_description"
                        id="apvSta_description"
                        rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Descripción del estado..."
                    ></textarea>
                </div>

                <!-- Activo -->
                <div>
                    <label for="apvSta_active" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select
                        v-model="form.apvSta_active"
                        id="apvSta_active"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    >
                        <option :value="true">Activo</option>
                        <option :value="false">Inactivo</option>
                    </select>
                </div>
            </form>
        </template>

        <template #footer>
            <button
                @click="closeApprovalStatusModal"
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
