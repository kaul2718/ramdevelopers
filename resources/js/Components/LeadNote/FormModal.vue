<script setup>
import DialogModal from '@/Components/DialogModal.vue';
import { ref, watch, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { useNotificationStore } from '@/stores/notificationStore';

const page = usePage();
const props = defineProps({
    show: {
        type: Boolean,
        required: true
    },
    note: {
        type: Object,
        default: null
    },
    lead: {
        type: Object,
        default: null
    },
    mode: {
        type: String,
        enum: ['create', 'edit'],
        default: 'create'
    }
});

const emit = defineEmits(['close', 'saved']);

const notificationStore = useNotificationStore();

const isSubmitting = ref(false);
const errors = ref({});

const form = ref({
    leadNot_title: '',
    leadNot_description: '',
    leadNot_active: true
});

const originalForm = ref({
    leadNot_title: '',
    leadNot_description: '',
    leadNot_active: true
});

const hasChanges = computed(() => {
    return form.value.leadNot_title !== originalForm.value.leadNot_title ||
           form.value.leadNot_description !== originalForm.value.leadNot_description ||
           form.value.leadNot_active !== originalForm.value.leadNot_active;
});

const validateForm = () => {
    errors.value = {};
    
    if (!form.value.leadNot_title?.trim()) {
        errors.value.leadNot_title = 'El título es requerido';
    }
    if (!form.value.leadNot_description?.trim()) {
        errors.value.leadNot_description = 'La descripción es requerida';
    }
    
    return Object.keys(errors.value).length === 0;
};

const handleSubmit = async () => {
    if (!validateForm()) {
        return;
    }

    if (props.mode === 'edit' && !hasChanges.value) {
        notificationStore.error('No has realizado cambios');
        return;
    }
    
    if (!props.lead?.lead_id) {
        notificationStore.error('Error: Lead no identificado');
        return;
    }
    
    isSubmitting.value = true;
    const formData = new FormData();
    
    formData.append('leadNot_title', form.value.leadNot_title);
    formData.append('leadNot_description', form.value.leadNot_description);
    formData.append('leadNot_active', form.value.leadNot_active ? 1 : 0);
    formData.append('lead_id', props.lead.lead_id);
    formData.append('user_id', page.props.auth.user.id);
    
    if (props.mode === 'edit') {
        formData.append('_method', 'PUT');
    }
    
    try {
        const route_name = props.mode === 'create' ? 'leadnote.store' : 'leadnote.update';
        const route_param = props.mode === 'create' ? [] : [props.note.leadNot_id];
        
        await axios.post(route(route_name, route_param), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        });
        
        closeModal();
        emit('saved', props.mode);
    } catch (error) {
        console.error('Error:', error);
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors;
            const errorMessages = Object.values(errors.value).flat().join(', ');
            notificationStore.error(errorMessages);
        } else {
            notificationStore.error(
                props.mode === 'create' 
                    ? 'Error al crear la nota' 
                    : 'Error al actualizar la nota'
            );
        }
    } finally {
        isSubmitting.value = false;
    }
};

const closeModal = () => {
    form.value = {
        leadNot_title: '',
        leadNot_description: '',
        leadNot_active: true
    };
    errors.value = {};
    emit('close');
};

watch(() => props.show, (newVal) => {
    if (newVal) {
        if (props.mode === 'edit' && props.note) {
            const formData = {
                leadNot_title: props.note.leadNot_title || '',
                leadNot_description: props.note.leadNot_description || '',
                leadNot_active: props.note.leadNot_active || false
            };
            form.value = formData;
            originalForm.value = JSON.parse(JSON.stringify(formData));
        } else {
            form.value = {
                leadNot_title: '',
                leadNot_description: '',
                leadNot_active: true
            };
            originalForm.value = {
                leadNot_title: '',
                leadNot_description: '',
                leadNot_active: true
            };
        }
        errors.value = {};
    }
});
</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="2xl">
        <template #title>
            <h2 class="modal-title text-xl">
                {{ mode === 'create' ? 'Agregar Nueva Nota' : 'Editar Nota' }}
            </h2>
        </template>

        <template #content>
            <div class="space-y-6">
                <div>
                    <label for="leadNot_title" class="block text-sm font-medium text-gray-700">
                        Título <span class="text-red-600">*</span>
                    </label>
                    <input 
                        id="leadNot_title" 
                        v-model="form.leadNot_title" 
                        type="text" 
                        class="mt-1 block w-full rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500"
                        :class="errors.leadNot_title ? 'border border-red-500' : 'border border-gray-300 focus:border-indigo-500'"
                        placeholder="Título de la nota">
                    <p v-if="errors.leadNot_title" class="mt-1 text-sm text-red-600">
                        {{ errors.leadNot_title }}
                    </p>
                </div>

                <div>
                    <label for="leadNot_description" class="block text-sm font-medium text-gray-700">
                        Descripción <span class="text-red-600">*</span>
                    </label>
                    <textarea 
                        id="leadNot_description" 
                        v-model="form.leadNot_description" 
                        rows="5"
                        class="mt-1 block w-full rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500"
                        :class="errors.leadNot_description ? 'border border-red-500' : 'border border-gray-300 focus:border-indigo-500'"
                        placeholder="Descripción de la nota">
                    </textarea>
                    <p v-if="errors.leadNot_description" class="mt-1 text-sm text-red-600">
                        {{ errors.leadNot_description }}
                    </p>
                </div>

                <div v-if="mode === 'edit'">
                    <label for="leadNot_active" class="block text-sm font-medium text-gray-700">
                        Estado
                    </label>
                    <select 
                        id="leadNot_active" 
                        v-model="form.leadNot_active" 
                        class="mt-1 block w-full rounded-md shadow-sm py-2 px-3 border border-gray-300 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500">
                        <option :value="true">Activa</option>
                        <option :value="false">Inactiva</option>
                    </select>
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
                :disabled="isSubmitting || (mode === 'edit' && !hasChanges)"
                class="px-4 py-2 text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition ml-3 disabled:opacity-50 disabled:cursor-not-allowed">
                {{ isSubmitting ? 'Guardando...' : (mode === 'create' ? 'Crear Nota' : 'Guardar Cambios') }}
            </button>
        </template>
    </DialogModal>
</template>
