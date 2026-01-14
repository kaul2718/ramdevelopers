<script setup>
import DialogModal from '@/Components/DialogModal.vue'

const props = defineProps({
    show: Boolean,
    city: Object,
    countries: {
        type: Array,
        default: () => []
    }
})

const emit = defineEmits(['close'])

const closeModal = () => {
    emit('close')
}
</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="2xl">
        <template #content v-if="city">
            <div class="space-y-6">
                <!-- Título -->
                <div class="pb-4 border-b">
                    <h3 class="modal-title">Información de la Ciudad</h3>
                </div>

                <!-- Información -->
                <div>
                    <h4 class="modal-section-title">Detalles</h4>

                    <div class="modal-grid">
                        <div>
                            <p class="modal-value">Nombre</p>
                            <p class="modal-label">{{ city.city_name }}</p>
                        </div>

                        <div>
                            <p class="modal-value">Región</p>
                            <p class="modal-label">{{ city.city_region || 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="modal-value">País</p>
                            <p
                                class="modal-label inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-sm font-medium text-blue-700">
                                {{ countries.find(c => c.ctry_id == city.ctry_id)?.ctry_name || 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <p class="modal-value">Estado</p>

                            <p v-if="city.city_active"
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
