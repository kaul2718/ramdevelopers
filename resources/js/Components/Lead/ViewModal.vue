<script setup>
import DialogModal from '@/Components/DialogModal.vue'

defineProps({
    show: Boolean,
    lead: Object,
    countries: Array,
    developments: Array,
    sources: Array,
    statuses: Array,
    users: Array
})

const emit = defineEmits(['close', 'edit'])

const closeModal = () => emit('close')
</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="2xl">
        <template #content v-if="lead">
            <div class="space-y-6">

                <!-- Título -->
                <div class="pb-4 border-b">
                    <h3 class="modal-title">Información del Lead</h3>
                </div>

                <!-- Información -->
                <div>
                    <h4 class="modal-section-title">Detalles</h4>
                    <div class="modal-grid">
                        <div>
                            <p class="modal-value">Cliente</p>
                            <p class="modal-label">{{ lead.lead_client_name }}</p>
                        </div>
                        <div>
                            <p class="modal-value">Email</p>
                            <p class="modal-label">{{ lead.lead_client_email }}</p>
                        </div>
                        <div>
                            <p class="modal-value">Teléfono</p>
                            <p class="modal-label">{{ lead.lead_client_phone || 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="modal-value">Idioma</p>
                            <p class="modal-label">{{ lead.lead_language || 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="modal-value">País</p>
                            <p class="modal-label">
                                {{ countries.find(c => c.ctry_id == lead.ctry_id)?.ctry_name || 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="modal-value">Desarrollo</p>
                            <p class="modal-label">
                                {{ developments.find(d => d.devt_id == lead.devt_id)?.devt_title || 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="modal-value">Fuente</p>
                            <p class="modal-label">
                                {{ sources.find(s => s.leadSou_id == lead.leadSou_id)?.leadSou_name || 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="modal-value">Estado</p>
                            <p class="modal-label">
                                {{ statuses.find(s => s.leadSta_id == lead.leadSta_id)?.leadSta_name || 'N/A' }}
                            </p>
                        </div>
                        <div >
                            <p class="modal-value">Usuario Responsable</p>
                            <p class="modal-label">
                                {{
                                    users.find(u => u.id == lead.user_id)
                                        ? `${users.find(u => u.id == lead.user_id).name} ${users.find(u => u.id == lead.user_id).lastname}`
                                        : 'N/A'
                                }}
                            </p>
                        </div>
                        <div v-if="lead.lead_message" >
                            <p class="modal-value">Mensaje</p>
                            <p class="modal-label">{{ lead.lead_message }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </DialogModal>
</template>
