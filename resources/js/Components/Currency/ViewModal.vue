<script setup>
    import DialogModal from '@/Components/DialogModal.vue'

    const props = defineProps({
        show: Boolean,
        currency: Object
    })

    const emit = defineEmits(['close'])

    const closeModal = () => {
        emit('close')
    }
</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="2xl">
        <template #content v-if="currency">
            <div class="space-y-6">
                <!-- Encabezado -->
                <div class="pb-4 border-b">
                    <h3 class="modal-title">{{ currency.curr_name }}</h3>
                    <p class="modal-subtitle">{{ currency.curr_code }}</p>
                    <div class="mt-2">
                        <span v-if="currency.curr_active" 
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
                            <p class="modal-value">{{ currency.curr_code }}</p>
                        </div>
                        <div>
                            <p class="modal-label">Símbolo</p>
                            <p class="modal-value">{{ currency.curr_symbol }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="modal-label">Posición del Símbolo</p>
                            <p class="modal-value">{{ currency.curr_symbol_first ? 'Símbolo al inicio' : 'Símbolo al final' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </DialogModal>
</template>
