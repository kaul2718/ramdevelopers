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
    businessState: {
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
    busiSta_name: '',
    busiSta_code: '',
    busiSta_description: '',
    busiSta_active: true
});

const originalForm = ref({
    busiSta_name: '',
    busiSta_code: '',
    busiSta_description: '',
    busiSta_active: true
});

const hasChanges = computed(() => {
    return (
        form.value.busiSta_name !== originalForm.value.busiSta_name ||
        form.value.busiSta_code !== originalForm.value.busiSta_code ||
        form.value.busiSta_description !== originalForm.value.busiSta_description ||
        form.value.busiSta_active !== originalForm.value.busiSta_active
    );
});

const validateForm = () => {
    errors.value = {};
    
    if (!form.value.busiSta_name?.trim()) {
        errors.value.busiSta_name = 'El nombre es requerido';
    }
    if (!form.value.busiSta_code?.trim()) {
        errors.value.busiSta_code = 'El código es requerido';
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

    formData.append('busiSta_name', form.value.busiSta_name);
    formData.append('busiSta_code', form.value.busiSta_code);
    formData.append('busiSta_description', form.value.busiSta_description);
    formData.append('busiSta_active', form.value.busiSta_active ? 1 : 0);
    formData.append('_method', 'PUT');

    try {
        const response = await axios.post(route('businessstate.update', props.businessState.busiSta_id), formData);
        
        // Store flag in sessionStorage
        sessionStorage.setItem('showUpdateBusinessStateNotification', 'true');
        
        // Redirect to index
        window.location.href = route('businessstate.index');
    } catch (error) {
        console.error('Error updating business state:', error);
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors;
            const errorMessages = Object.values(errors.value).flat().join(', ');
            notificationStore.error(errorMessages);
        } else {
            notificationStore.error('Error al actualizar el estado de negocio');
        }
        isSubmitting.value = false;
    }
};

const closeBusinessStateModal = () => {
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
    if (props.businessState) {
        form.value = {
            busiSta_name: props.businessState.busiSta_name,
            busiSta_code: props.businessState.busiSta_code,
            busiSta_description: props.businessState.busiSta_description,
            busiSta_active: props.businessState.busiSta_active
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

watch(() => props.businessState, () => {
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
                {{ isEditing ? 'Editar Estado de Negocio' : 'Ver Estado de Negocio' }}
            </h2>
        </template>

        <template #content>
            <div v-if="!isEditing" class="space-y-4">
                <!-- Nombre -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <p class="px-3 py-2 text-gray-900">{{ businessState?.busiSta_name }}</p>
                </div>

                <!-- Código -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Código</label>
                    <p class="px-3 py-2 text-gray-900">{{ businessState?.busiSta_code }}</p>
                </div>

                <!-- Descripción -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <p class="px-3 py-2 text-gray-900">{{ businessState?.busiSta_description || 'N/A' }}</p>
                </div>

                <!-- Estado -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <div class="inline-block">
                        <span v-if="businessState?.busiSta_active" class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-sm font-semibold">
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
                    <label for="busiSta_name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre <span class="text-red-600">*</span>
                    </label>
                    <input
                        v-model="form.busiSta_name"
                        type="text"
                        id="busiSta_name"
                        class="w-full px-3 py-2 rounded-md text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-indigo-500"
                        :class="errors.busiSta_name ? 'border border-red-500' : 'border border-gray-300 focus:border-indigo-500'"
                        placeholder="Ej: En desarrollo"
                    />
                    <p v-if="errors.busiSta_name" class="mt-1 text-sm text-red-600">
                        {{ errors.busiSta_name }}
                    </p>
                </div>

                <!-- Código -->
                <div>
                    <label for="busiSta_code" class="block text-sm font-medium text-gray-700 mb-1">
                        Código <span class="text-red-600">*</span>
                    </label>
                    <input
                        v-model="form.busiSta_code"
                        type="text"
                        id="busiSta_code"
                        class="w-full px-3 py-2 rounded-md text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-indigo-500"
                        :class="errors.busiSta_code ? 'border border-red-500' : 'border border-gray-300 focus:border-indigo-500'"
                        placeholder="Ej: DESA"
                    />
                    <p v-if="errors.busiSta_code" class="mt-1 text-sm text-red-600">
                        {{ errors.busiSta_code }}
                    </p>
                </div>

                <!-- Descripción -->
                <div>
                    <label for="busiSta_description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea
                        v-model="form.busiSta_description"
                        id="busiSta_description"
                        rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Descripción del estado..."
                    ></textarea>
                </div>

                <!-- Activo -->
                <div>
                    <label for="busiSta_active" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select
                        v-model="form.busiSta_active"
                        id="busiSta_active"
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
                @click="closeBusinessStateModal"
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
