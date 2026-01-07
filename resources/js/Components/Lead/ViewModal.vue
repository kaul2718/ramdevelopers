<script setup>
    import DialogModal from '@/Components/DialogModal.vue';
    import { ref, watch } from 'vue';

    const props = defineProps({
        show: {
            type: Boolean,
            required: true
        },
        lead: {
            type: Object,
            default: null
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

    const emit = defineEmits(['close', 'edit']);

    const closeModal = () => {
        emit('close');
    };

    const openEdit = () => {
        emit('edit');
    };
</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="2xl">
        <template #title>
            <h2 class="modal-title text-xl">Información del Lead</h2>
        </template>

        <template #content>
            <div class="space-y-4">
                <div class="modal-grid">
                    <div>
                        <p class="modal-label">Cliente</p>
                        <p class="modal-value">{{ lead?.lead_client_name }}</p>
                    </div>
                    <div>
                        <p class="modal-label">Email</p>
                        <p class="modal-value">{{ lead?.lead_client_email }}</p>
                    </div>
                </div>
                <div class="modal-grid">
                    <div>
                        <p class="modal-label">Teléfono</p>
                        <p class="modal-value">{{ lead?.lead_client_phone || 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="modal-label">Idioma</p>
                        <p class="modal-value">{{ lead?.lead_language || 'N/A' }}</p>
                    </div>
                </div>
                <div class="modal-grid">
                    <div>
                        <p class="modal-label">País</p>
                        <p class="modal-value">{{ countries.find(c => c.ctry_id == lead?.ctry_id)?.ctry_name || 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="modal-label">Desarrollo</p>
                        <p class="modal-value">{{ developments.find(d => d.devt_id == lead?.devt_id)?.devt_title || 'N/A' }}</p>
                    </div>
                </div>
                <div class="modal-grid">
                    <div>
                        <p class="modal-label">Fuente</p>
                        <p class="modal-value">{{ sources.find(s => s.leadSou_id == lead?.leadSou_id)?.leadSou_name || 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="modal-label">Estado</p>
                        <p class="modal-value">{{ statuses.find(s => s.leadSta_id == lead?.leadSta_id)?.leadSta_name || 'N/A' }}</p>
                    </div>
                </div>
                <div>
                    <p class="modal-label">Usuario Responsable</p>
                    <p class="modal-value">{{ users.find(u => u.id == lead?.user_id) ? `${users.find(u => u.id == lead?.user_id).name} ${users.find(u => u.id == lead?.user_id).lastname}` : 'N/A' }}</p>
                </div>
                <div v-if="lead?.lead_message">
                    <p class="modal-label">Mensaje</p>
                    <p class="modal-value">{{ lead.lead_message }}</p>
                </div>
            </div>
        </template>        
    </DialogModal>
</template>
