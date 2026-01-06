<script setup>
import DialogModal from '@/Components/DialogModal.vue'

const props = defineProps({
    show: Boolean,
    leadSource: Object
})

const emit = defineEmits(['close'])

const closeModal = () => {
    emit('close')
}
</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="2xl">
        <template #content v-if="leadSource">
            <div class="space-y-6">
                <!-- Encabezado -->
                <div class="pb-4 border-b">
                    <h3 class="modal-title">{{ leadSource.leadSou_name }}</h3>
                    <p class="modal-subtitle">{{ leadSource.leadSou_code }}</p>
                    <div class="mt-2">
                        <span v-if="leadSource.leadSou_active" 
                            class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800">
                            ✓ Activo
                        </span>
                        <span v-else 
                            class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-800">
                            ✗ Inactivo
                        </span>
                    </div>
                </div>

                <!-- Información -->
                <div>
                    <h4 class="modal-section-title">Información</h4>
                    <div class="modal-grid">
                        <div>
                            <p class="modal-label">Código</p>
                            <p class="modal-value">{{ leadSource.leadSou_code }}</p>
                        </div>
                        <div>
                            <p class="modal-label">Nombre</p>
                            <p class="modal-value">{{ leadSource.leadSou_name }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="modal-label">Descripción</p>
                            <p class="modal-value">{{ leadSource.leadSou_description || 'No especificada' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </DialogModal>
</template>
