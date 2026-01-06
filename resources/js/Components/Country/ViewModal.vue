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
    <DialogModal :show="show" @close="emit('close')">
        <template #title>
            <h2 class="text-lg font-medium text-gray-900">Información del País</h2>
        </template>

        <template #content>
            <div v-if="country" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nombre del País</label>
                    <p class="mt-1 text-sm text-gray-900">{{ country.ctry_name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Código (ISO)</label>
                    <p class="mt-1 text-sm text-gray-900">{{ country.ctry_code }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Estado</label>
                    <div class="mt-1">
                        <div :class="['py-1.5 px-2.5 rounded-full flex justify-center w-24 items-center gap-2', country.ctry_active ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600']">
                            <svg width="6" height="6" viewBox="0 0 6 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="3" cy="3" r="3" :fill="country.ctry_active ? '#059669' : '#dc2626'" />
                            </svg>
                            <span class="font-semibold text-xs">{{ country.ctry_active ? 'Activo' : 'Inactivo' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template #footer>
            <button
                @click="emit('close')"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Cerrar
            </button>
        </template>
    </DialogModal>
</template>
