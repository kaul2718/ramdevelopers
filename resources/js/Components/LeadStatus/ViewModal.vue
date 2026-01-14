<script setup>
import DialogModal from '@/Components/DialogModal.vue'

const props = defineProps({
    show: Boolean,
    leadStatus: Object
})

const emit = defineEmits(['close'])

const closeModal = () => {
    emit('close')
}
</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="2xl">
        <template #content v-if="leadStatus">
            <div class="space-y-6">

                <!-- Título -->
                <div class="pb-4 border-b">
                    <h3 class="modal-title">Información del Estado del Lead</h3>
                </div>

                <!-- Información -->
                <div>
                    <h4 class="modal-section-title">Detalles</h4>
                    <div class="modal-grid">
                        <div>
                            <p class="modal-value">Código</p>
                            <p class="modal-label">{{ leadStatus.leadSta_code }}</p>
                        </div>
                        <div>
                            <p class="modal-value">Nombre</p>
                            <p class="modal-label">{{ leadStatus.leadSta_name }}</p>
                        </div>
                        <div>
                            <p class="modal-value">Estado</p>
                            <p v-if="leadStatus.leadSta_active"
                                class="modal-label inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-sm font-medium text-green-700">
                                ✓ Activo
                            </p>
                            <p v-else
                                class="modal-label inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-sm font-medium text-gray-700">
                                ✗ Inactivo
                            </p>
                        </div>
                        <div>
                            <p class="modal-value">Descripción</p>
                            <p class="modal-label">
                                {{ leadStatus.leadSta_description || 'No especificada' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </DialogModal>
</template>
