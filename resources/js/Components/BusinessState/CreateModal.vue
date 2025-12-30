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
    busiSta_name: '',
    busiSta_code: '',
    busiSta_description: '',
    busiSta_active: true
});

const isSubmitting = ref(false);
const errors = ref({});

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

const handleSubmit = async () => {
    if (!validateForm()) {
        return;
    }
    
    isSubmitting.value = true;
    const formData = new FormData();

    formData.append('busiSta_name', form.value.busiSta_name);
    formData.append('busiSta_code', form.value.busiSta_code);
    formData.append('busiSta_description', form.value.busiSta_description);
    formData.append('busiSta_active', form.value.busiSta_active ? 1 : 0);

    try {
        const response = await axios.post(route('businessstate.store'), formData);
        
        // Store flag in sessionStorage
        sessionStorage.setItem('showCreateBusinessStateNotification', 'true');
        
        // Redirect to index
        window.location.href = route('businessstate.index');
    } catch (error) {
        console.error('Error creating business state:', error);
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors;
            const errorMessages = Object.values(errors.value).flat().join(', ');
            notificationStore.error(errorMessages);
        } else {
            notificationStore.error('Error al crear el estado de negocio');
        }
        isSubmitting.value = false;
    }
};

const resetForm = () => {
    form.value = {
        busiSta_name: '',
        busiSta_code: '',
        busiSta_description: '',
        busiSta_active: true
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
            <h2 class="modal-title text-xl">Crear Estado de Negocio</h2>
        </template>

        <template #content>
            <form @submit.prevent="handleSubmit" class="space-y-4">
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
                        {{ isSubmitting ? 'Guardando...' : 'Crear Estado' }}
                    </button>
                </div>
            </form>
        </template>
    </DialogModal>
</template>
