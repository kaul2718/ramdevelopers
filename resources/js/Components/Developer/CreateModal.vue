<script setup>
import DialogModal from '@/Components/DialogModal.vue';
import { ref, watch } from 'vue';
import axios from 'axios';
import { useNotificationStore } from '@/stores/notificationStore';

const props = defineProps({
    show: {
        type: Boolean,
        required: true
    },
    countries: {
        type: Array,
        default: () => []
    },
    users: {
        type: Array,
        default: () => []
    }
});

const emit = defineEmits(['close']);

const notificationStore = useNotificationStore();

const form = ref({
    devr_commercial_name: '',
    devr_legal_name: '',
    devr_email_contact: '',
    devr_phone_contact: '',
    devr_website: '',
    ctry_id: '',
    user_id: '',
    devr_active: true
});

const isSubmitting = ref(false);
const errors = ref({});

const validateForm = () => {
    errors.value = {};

    if (!form.value.devr_commercial_name?.trim()) {
        errors.value.devr_commercial_name = 'El nombre comercial es requerido';
    }
    if (!form.value.devr_legal_name?.trim()) {
        errors.value.devr_legal_name = 'El nombre legal es requerido';
    }
    if (!form.value.devr_email_contact?.trim()) {
        errors.value.devr_email_contact = 'El email es requerido';
    }
    if (!form.value.ctry_id) {
        errors.value.ctry_id = 'El país es requerido';
    }
    if (!form.value.user_id) {
        errors.value.user_id = 'El usuario es requerido';
    }

    return Object.keys(errors.value).length === 0;
};

const handleSubmit = async () => {
    if (!validateForm()) {
        notificationStore.error('Por favor completa todos los campos requeridos');
        return;
    }

    isSubmitting.value = true;
    const formData = new FormData();

    if (form.value.devr_commercial_name) formData.append('devr_commercial_name', form.value.devr_commercial_name);
    if (form.value.devr_legal_name) formData.append('devr_legal_name', form.value.devr_legal_name);
    if (form.value.devr_email_contact) formData.append('devr_email_contact', form.value.devr_email_contact);
    if (form.value.devr_phone_contact) formData.append('devr_phone_contact', form.value.devr_phone_contact);
    if (form.value.devr_website) formData.append('devr_website', form.value.devr_website);
    if (form.value.ctry_id) formData.append('ctry_id', form.value.ctry_id);
    if (form.value.user_id) formData.append('user_id', form.value.user_id);
    formData.append('devr_active', form.value.devr_active ? 1 : 0);

    try {
        await axios.post(route('developers.store'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        });

        sessionStorage.setItem('showCreateDeveloperNotification', 'true');
        closeModal();
        window.location.href = route('developers.index');
    } catch (error) {
        console.error('Error creating developer:', error);
        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors;
            const errorMessages = Object.values(errors.value).flat().join(', ');
            notificationStore.error(errorMessages);
        } else {
            notificationStore.error('Error al crear el desarrollador');
        }
    } finally {
        isSubmitting.value = false;
    }
};

const closeModal = () => {
    // Resetear el formulario
    form.value = {
        devr_commercial_name: '',
        devr_legal_name: '',
        devr_email_contact: '',
        devr_phone_contact: '',
        devr_website: '',
        ctry_id: '',
        user_id: '',
        devr_active: true
    };
    emit('close');
};

watch(() => props.show, (newVal) => {
    if (newVal) {
        // Resetear el formulario cuando se abre el modal
        form.value = {
            devr_commercial_name: '',
            devr_legal_name: '',
            devr_email_contact: '',
            devr_phone_contact: '',
            devr_website: '',
            ctry_id: '',
            user_id: '',
            devr_active: true
        };
    }
});
</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="2xl">
        <template #title>
            <h2 class="modal-title text-xl">Crear Nuevo Desarrollador</h2>
        </template>

        <template #content>
            <div class="space-y-6">
                <div>
                    <label for="devr_commercial_name" class="block text-sm font-medium text-gray-700">Nombre Comercial
                        <span class="text-red-600">*</span></label>
                    <input id="devr_commercial_name" v-model="form.devr_commercial_name" type="text"
                        class="mt-1 block w-full border rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        :class="errors.devr_commercial_name ? 'border-red-500' : 'border-gray-300'"
                        placeholder="TechDev Solutions">
                    <p v-if="errors.devr_commercial_name" class="mt-1 text-sm text-red-600">{{
                        errors.devr_commercial_name }}</p>
                </div>

                <div>
                    <label for="devr_legal_name" class="block text-sm font-medium text-gray-700">Nombre Legal <span
                            class="text-red-600">*</span></label>
                    <input id="devr_legal_name" v-model="form.devr_legal_name" type="text"
                        class="mt-1 block w-full border rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        :class="errors.devr_legal_name ? 'border-red-500' : 'border-gray-300'"
                        placeholder="TechDev Solutions S.A.">
                    <p v-if="errors.devr_legal_name" class="mt-1 text-sm text-red-600">{{ errors.devr_legal_name }}</p>
                </div>

                <div>
                    <label for="devr_email_contact" class="block text-sm font-medium text-gray-700">Email de Contacto
                        <span class="text-red-600">*</span></label>
                    <input id="devr_email_contact" v-model="form.devr_email_contact" type="email"
                        class="mt-1 block w-full border rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        :class="errors.devr_email_contact ? 'border-red-500' : 'border-gray-300'"
                        placeholder="contacto@ejemplo.com">
                    <p v-if="errors.devr_email_contact" class="mt-1 text-sm text-red-600">{{ errors.devr_email_contact
                        }}</p>
                </div>

                <div>
                    <label for="devr_phone_contact" class="block text-sm font-medium text-gray-700">Teléfono de
                        Contacto</label>
                    <input id="devr_phone_contact" v-model="form.devr_phone_contact" type="text"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="+1 (555) 123-4567">
                </div>

                <div>
                    <label for="devr_website" class="block text-sm font-medium text-gray-700">Sitio Web
                        (Opcional)</label>
                    <input id="devr_website" v-model="form.devr_website" type="url"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="https://www.ejemplo.com">
                </div>

                <div>
                    <label for="ctry_id" class="block text-sm font-medium text-gray-700">País <span
                            class="text-red-600">*</span></label>
                    <select id="ctry_id" v-model="form.ctry_id"
                        class="mt-1 block w-full border rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        :class="errors.ctry_id ? 'border-red-500' : 'border-gray-300'">
                        <option value="">Seleccionar país</option>
                        <option v-for="country in countries" :key="country.ctry_id" :value="country.ctry_id">
                            {{ country.ctry_name }}
                        </option>
                    </select>
                    <p v-if="errors.ctry_id" class="mt-1 text-sm text-red-600">{{ errors.ctry_id }}</p>
                </div>

                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700">Usuario Asociado <span
                            class="text-red-600">*</span></label>
                    <select id="user_id" v-model="form.user_id"
                        class="mt-1 block w-full border rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        :class="errors.user_id ? 'border-red-500' : 'border-gray-300'">
                        <option value="">Seleccionar usuario</option>
                        <option v-for="user in users" :key="user.id" :value="user.id">
                            {{ user.name }} {{ user.lastname }}

                        </option>
                    </select>
                    <p v-if="errors.user_id" class="mt-1 text-sm text-red-600">{{ errors.user_id }}</p>
                </div>
            </div>
        </template>

        <template #footer>
            <button @click="closeModal"
                class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                Cancelar
            </button>
            <button @click="handleSubmit" :disabled="isSubmitting"
                class="px-4 py-2 text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition ml-3 disabled:opacity-50">
                {{ isSubmitting ? 'Guardando...' : 'Crear Desarrollador' }}
            </button>
        </template>
    </DialogModal>
</template>
