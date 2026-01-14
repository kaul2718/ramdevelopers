<script setup>
import DialogModal from '@/Components/DialogModal.vue'

defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    country: {
        type: Object,
        default: null,
    },
})

const emit = defineEmits(['close'])
</script>

<template>
    <DialogModal :show="show" @close="emit('close')" max-width="2xl">
        <template #content>
            <div v-if="country" class="space-y-6">
                <!-- Título -->
                <div class="pb-4 border-b">
                    <h3 class="modal-title">Información del País</h3>
                </div>

                <!-- Información -->
                <div>
                    <h4 class="modal-section-title">Detalles</h4>

                    <div class="modal-grid">
                        <div>
                            <p class="modal-value">Nombre del País</p>
                            <p class="modal-label">{{ country.ctry_name }}</p>
                        </div>

                        <div>
                            <p class="modal-value">Código (ISO)</p>
                            <p class="modal-label">{{ country.ctry_code }}</p>
                        </div>

                        <div>
                            <p class="modal-value">Estado</p>

                            <p v-if="country.ctry_active"
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
