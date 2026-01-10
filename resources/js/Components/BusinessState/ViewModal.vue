<script setup>
    import DialogModal from '@/Components/DialogModal.vue'

    defineProps({
        show: {
            type: Boolean,
            default: false,
        },
        businessState: {
            type: Object,
            default: null,
        },
    })

    const emit = defineEmits(['close'])
</script>

<template>
    <DialogModal :show="show" @close="emit('close')">
        <template #title> Ver Estado del Proyecto </template>
        <template #content>
            <div class="space-y-4">
                <!-- Nombre -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <p class="px-3 py-2 text-gray-900">{{ businessState?.busiSta_name }}</p>
                </div>

                <!-- Código -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Código</label>
                    <p class="px-3 py-2 text-gray-900">{{ businessState?.busiSta_code }}</p>
                </div>

                <!-- Descripción -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <p class="px-3 py-2 text-gray-900">{{ businessState?.busiSta_description || 'N/A' }}</p>
                </div>

                <!-- Estado -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <div class="inline-block">
                        <span
                            v-if="businessState?.busiSta_active"
                            class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-sm font-semibold"
                        >
                            Activo
                        </span>
                        <span v-else class="px-3 py-1 rounded-full bg-red-50 text-red-600 text-sm font-semibold">
                            Inactivo
                        </span>
                    </div>
                </div>
            </div>
        </template>
    </DialogModal>
</template>
