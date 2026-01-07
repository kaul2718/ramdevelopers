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
        developments: {
            type: Array,
            default: () => []
        },
        sources: {
            type: Array,
            default: () => []
        },
        statuses: {
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
        lead_client_name: '',
        lead_client_email: '',
        lead_client_phone: '',
        lead_language: '',
        ctry_id: '',
        devt_id: '',
        leadSou_id: '',
        leadSta_id: '',
        user_id: '',
        lead_message: ''
    });

    const isSubmitting = ref(false);
    const errors = ref({});

    const validateForm = () => {
        errors.value = {};
        
        if (!form.value.lead_client_name?.trim()) {
            errors.value.lead_client_name = 'El nombre del cliente es requerido';
        }
        if (!form.value.lead_client_email?.trim()) {
            errors.value.lead_client_email = 'El email es requerido';
        } else if (!isValidEmail(form.value.lead_client_email)) {
            errors.value.lead_client_email = 'El email no es válido';
        }
        if (!form.value.ctry_id) {
            errors.value.ctry_id = 'Debe seleccionar un país';
        }
        
        return Object.keys(errors.value).length === 0;
    };

    const isValidEmail = (email) => {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    };

    const handleSubmit = async () => {
        if (!validateForm()) {
            return;
        }
        
        isSubmitting.value = true;
        const formData = new FormData();
        
        formData.append('lead_client_name', form.value.lead_client_name);
        formData.append('lead_client_email', form.value.lead_client_email);
        if (form.value.lead_client_phone) formData.append('lead_client_phone', form.value.lead_client_phone);
        if (form.value.lead_language) formData.append('lead_language', form.value.lead_language);
        if (form.value.ctry_id) formData.append('ctry_id', form.value.ctry_id);
        if (form.value.devt_id) formData.append('devt_id', form.value.devt_id);
        if (form.value.leadSou_id) formData.append('leadSou_id', form.value.leadSou_id);
        if (form.value.leadSta_id) formData.append('leadSta_id', form.value.leadSta_id);
        if (form.value.user_id) formData.append('user_id', form.value.user_id);
        if (form.value.lead_message) formData.append('lead_message', form.value.lead_message);
        
        try {
            await axios.post(route('lead.store'), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                }
            });
            
            sessionStorage.setItem('showCreateLeadNotification', 'true');
            closeModal();
            window.location.href = route('lead.index');
        } catch (error) {
            console.error('Error creating lead:', error);
            if (error.response?.data?.errors) {
                errors.value = error.response.data.errors;
                const errorMessages = Object.values(errors.value).flat().join(', ');
                notificationStore.error(errorMessages);
            } else {
                notificationStore.error('Error al crear el lead');
            }
        } finally {
            isSubmitting.value = false;
        }
    };

    const closeModal = () => {
        form.value = {
            lead_client_name: '',
            lead_client_email: '',
            lead_client_phone: '',
            lead_language: '',
            ctry_id: '',
            devt_id: '',
            leadSou_id: '',
            leadSta_id: '',
            user_id: '',
            lead_message: ''
        };
        errors.value = {};
        emit('close');
    };

    watch(() => props.show, (newVal) => {
        if (newVal) {
            form.value = {
                lead_client_name: '',
                lead_client_email: '',
                lead_client_phone: '',
                lead_language: '',
                ctry_id: '',
                devt_id: '',
                leadSou_id: '',
                leadSta_id: '',
                user_id: '',
                lead_message: ''
            };
            errors.value = {};
        }
    });
</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="2xl">
        <template #title>
            <h2 class="modal-title text-xl">Crear Nuevo Lead</h2>
        </template>

        <template #content>
            <div class="space-y-6">
                <div>
                    <label for="lead_client_name" class="block text-sm font-medium text-gray-700">
                        Nombre del Cliente <span class="text-red-600">*</span>
                    </label>
                    <input 
                        id="lead_client_name" 
                        v-model="form.lead_client_name" 
                        type="text" 
                        class="mt-1 block w-full rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500"
                        :class="errors.lead_client_name ? 'border border-red-500' : 'border border-gray-300 focus:border-indigo-500'"
                        placeholder="Juan Pérez">
                    <p v-if="errors.lead_client_name" class="mt-1 text-sm text-red-600">
                        {{ errors.lead_client_name }}
                    </p>
                </div>

                <div>
                    <label for="lead_client_email" class="block text-sm font-medium text-gray-700">
                        Email <span class="text-red-600">*</span>
                    </label>
                    <input 
                        id="lead_client_email" 
                        v-model="form.lead_client_email" 
                        type="email" 
                        class="mt-1 block w-full rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500"
                        :class="errors.lead_client_email ? 'border border-red-500' : 'border border-gray-300 focus:border-indigo-500'"
                        placeholder="juan@example.com">
                    <p v-if="errors.lead_client_email" class="mt-1 text-sm text-red-600">
                        {{ errors.lead_client_email }}
                    </p>
                </div>

                <div>
                    <label for="lead_client_phone" class="block text-sm font-medium text-gray-700">
                        Teléfono
                    </label>
                    <input 
                        id="lead_client_phone" 
                        v-model="form.lead_client_phone" 
                        type="tel" 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="+34 123 456 789">
                </div>

                <div>
                    <label for="lead_language" class="block text-sm font-medium text-gray-700">
                        Idioma
                    </label>
                    <select 
                        id="lead_language" 
                        v-model="form.lead_language"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Selecciona un idioma</option>
                        <option value="Español">Español</option>
                        <option value="Inglés">Inglés</option>
                        <option value="Francés">Francés</option>
                    </select>
                </div>

                <div>
                    <label for="ctry_id" class="block text-sm font-medium text-gray-700">
                        País <span class="text-red-600">*</span>
                    </label>
                    <select 
                        id="ctry_id" 
                        v-model="form.ctry_id"
                        class="mt-1 block w-full rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500"
                        :class="errors.ctry_id ? 'border border-red-500' : 'border border-gray-300 focus:border-indigo-500'">
                        <option value="">Seleccionar país</option>
                        <option v-for="country in countries" :key="country.ctry_id" :value="country.ctry_id">
                            {{ country.ctry_name }}
                        </option>
                    </select>
                    <p v-if="errors.ctry_id" class="mt-1 text-sm text-red-600">
                        {{ errors.ctry_id }}
                    </p>
                </div>

                <div>
                    <label for="devt_id" class="block text-sm font-medium text-gray-700">Desarrollo</label>
                    <select 
                        id="devt_id" 
                        v-model="form.devt_id"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Seleccionar desarrollo</option>
                        <option v-for="development in developments" :key="development.devt_id" :value="development.devt_id">
                            {{ development.devt_title }}
                        </option>
                    </select>
                </div>

                <div>
                    <label for="leadSou_id" class="block text-sm font-medium text-gray-700">Fuente</label>
                    <select 
                        id="leadSou_id" 
                        v-model="form.leadSou_id"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Seleccionar fuente</option>
                        <option v-for="source in sources" :key="source.leadSou_id" :value="source.leadSou_id">
                            {{ source.leadSou_name }}
                        </option>
                    </select>
                </div>

                <div>
                    <label for="leadSta_id" class="block text-sm font-medium text-gray-700">Estado</label>
                    <select 
                        id="leadSta_id" 
                        v-model="form.leadSta_id"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Seleccionar estado</option>
                        <option v-for="status in statuses" :key="status.leadSta_id" :value="status.leadSta_id">
                            {{ status.leadSta_name }}
                        </option>
                    </select>
                </div>

                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700">Usuario Responsable</label>
                    <select 
                        id="user_id" 
                        v-model="form.user_id"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Seleccionar usuario</option>
                        <option v-for="user in users" :key="user.id" :value="user.id">
                            {{ user.name }} {{ user.lastname }}
                        </option>
                    </select>
                </div>

                <div>
                    <label for="lead_message" class="block text-sm font-medium text-gray-700">
                        Mensaje
                    </label>
                    <textarea 
                        id="lead_message" 
                        v-model="form.lead_message" 
                        rows="5"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Mensaje adicional...">
                    </textarea>
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
                :disabled="isSubmitting"
                class="px-4 py-2 text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition ml-3 disabled:opacity-50">
                {{ isSubmitting ? 'Guardando...' : 'Crear Lead' }}
            </button>
        </template>
    </DialogModal>
</template>
