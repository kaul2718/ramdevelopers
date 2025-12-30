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
    apvSta_name: '',
    apvSta_code: '',
    apvSta_description: '',
    apvSta_active: true
});

const isSubmitting = ref(false);
const errors = ref({});

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

const handleSubmit = async () => {
    if (!validateForm()) {
        return;
    }
    
    isSubmitting.value = true;
    const formData = new FormData();

    formData.append('apvSta_name', form.value.apvSta_name);
    formData.append('apvSta_code', form.value.apvSta_code);
    formData.append('apvSta_description', form.value.apvSta_description);
    formData.append('apvSta_active', form.value.apvSta_active ? 1 : 0);

    try {
        const response = await axios.post(route('approvalstatus.store'), formData);
        
        // Store flag in sessionStorage
        sessionStorage.setItem('showCreateApprovalStatusNotification', 'true');
        
        // Redirect to index
        window.location.href = route('approvalstatus.index');
    } catch (error) {
        console.error('Error creating approval status:', error);
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors;
            const errorMessages = Object.values(errors.value).flat().join(', ');
            notificationStore.error(errorMessages);
        } else {
            notificationStore.error('Error al crear el estado de aprobación');
        }
        isSubmitting.value = false;
    }
};

const resetForm = () => {
    form.value = {
        apvSta_name: '',
        apvSta_code: '',
        apvSta_description: '',
        apvSta_active: true
    };
    isSubmitting.value = false;
};

watch(() => props.show, (newVal) => {
    if (newVal) {
        resetForm();
    }
});
</script>

<template>
    <DialogModal :show="show" @close="emit('close')">
        <template #title>
            <h2 class="modal-title text-xl">Crear Estado de Aprobación</h2>
        </template>

        <template #content>
            <form @submit.prevent="handleSubmit" class="space-y-4">
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

                <!-- Botones -->
                <div class="flex justify-end gap-3 mt-6">
                    <button
                        type="button"
                        @click="emit('close')"
                        class="px-4 py-2 rounded-md bg-gray-200 text-gray-800 hover:bg-gray-300"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        :disabled="isSubmitting"
                        class="px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 disabled:opacity-50"
                    >
                        {{ isSubmitting ? 'Guardando...' : 'Guardar' }}
                    </button>
                </div>
            </form>
        </template>
    </DialogModal>
</template>
