<script setup>
import DialogModal from '@/Components/DialogModal.vue'

const props = defineProps({
    show: Boolean,
    documentType: Object
})

const emit = defineEmits(['close'])

const closeModal = () => {
    emit('close')
}
</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="2xl">
        <template #content v-if="documentType">
            <div class="space-y-6">
                <!-- Encabezado -->
                <div class="pb-4 border-b">
                    <h3 class="modal-title">{{ documentType.docTyp_name }}</h3>
                    <p class="modal-subtitle">{{ documentType.docTyp_code }}</p>
                </div>

                <!-- Contenido -->
                <div class="space-y-4">
                    <div>
                        <p class="modal-label">Código</p>
                        <p class="modal-value">{{ documentType.docTyp_code }}</p>
                    </div>

                    <div>
                        <p class="modal-label">Nombre</p>
                        <p class="modal-value">{{ documentType.docTyp_name }}</p>
                    </div>

                    <div>
                        <p class="modal-label">Descripción</p>
                        <p class="modal-value">{{ documentType.docTyp_description || 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="modal-label">Es Interno</p>
                        <div :class="[
                            'py-1.5 px-2.5 rounded-full flex items-center gap-2 w-fit',
                            documentType.docTyp_isInternal ? 'bg-blue-50 text-blue-600' : 'bg-gray-50 text-gray-600'
                        ]">
                            <svg width="6" height="6" viewBox="0 0 6 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="3" cy="3" r="3" :fill="documentType.docTyp_isInternal ? '#2563eb' : '#9ca3af'" />
                            </svg>
                            <span class="font-semibold text-xs">
                                {{ documentType.docTyp_isInternal ? 'Sí' : 'No' }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <p class="modal-label">Estado</p>
                        <div :class="[
                            'py-1.5 px-2.5 rounded-full flex items-center gap-2 w-fit',
                            documentType.docTyp_active ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600'
                        ]">
                            <svg width="6" height="6" viewBox="0 0 6 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="3" cy="3" r="3" :fill="documentType.docTyp_active ? '#059669' : '#dc2626'" />
                            </svg>
                            <span class="font-semibold text-xs">
                                {{ documentType.docTyp_active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </DialogModal>
</template>
