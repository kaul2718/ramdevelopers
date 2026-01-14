<script setup>
import DialogModal from '@/Components/DialogModal.vue'

defineProps({
    show: Boolean,
    developer: Object
})

const emit = defineEmits(['close'])
</script>

<template>
    <DialogModal :show="show" @close="emit('close')" max-width="2xl">
        <template #content v-if="developer">
            <div class="space-y-6">
                <!-- Título -->
                <div class="pb-4 border-b">
                    <h3 class="modal-title">Información del Desarrollador</h3>
                </div>
                <!-- Información -->
                <div>
                    <h4 class="modal-section-title">Detalles</h4>
                    <div class="modal-grid">
                        <div>
                            <p class="modal-value">Nombre Comercial</p>
                            <p class="modal-label">{{ developer.devr_commercial_name }}</p>
                        </div>
                        <div>
                            <p class="modal-value">Nombre Legal</p>
                            <p class="modal-label">{{ developer.devr_legal_name }}</p>
                        </div>
                        <div>
                            <p class="modal-value">Email de Contacto</p>
                            <p class="modal-label">{{ developer.devr_email_contact }}</p>
                        </div>
                        <div>
                            <p class="modal-value">Teléfono de Contacto</p>
                            <p class="modal-label">{{ developer.devr_phone_contact || 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="modal-value">Sitio Web</p>
                            <p class="modal-label">{{ developer.devr_website || 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="modal-value">País</p>
                            <p
                                v-if="developer.country"
                                class="modal-label inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-sm font-medium text-blue-700">
                                {{ developer.country.ctry_name }}
                            </p>
                            <p v-else class="modal-label">N/A</p>
                        </div>
                        <div>
                            <p class="modal-value">Usuario Asociado</p>
                            <p
                                v-if="developer.user"
                                class="modal-label inline-flex items-center rounded-md bg-purple-50 px-2 py-1 text-sm font-medium text-purple-700">
                                {{ developer.user.name }} {{ developer.user.lastname }}
                            </p>
                            <p v-else class="modal-label">N/A</p>
                        </div>
                        <div>
                            <p class="modal-value">Estado</p>
                            <p v-if="developer.devr_active"
                                class="modal-label inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-sm font-medium text-green-700">
                                ✓ Activo
                            </p>
                            <p v-else
                                class="modal-label inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-sm font-medium text-gray-700">
                                ✗ Inactivo
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </DialogModal>
</template>
