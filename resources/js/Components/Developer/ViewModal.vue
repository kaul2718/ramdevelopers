<script setup>
import DialogModal from '@/Components/DialogModal.vue'

defineProps({
    show: Boolean,
    developer: Object
})

const emit = defineEmits(['close'])
</script>

<template>
    <DialogModal :show="show" @close="emit('close')">
        <template #title>
            Información del Desarrollador
        </template>

        <template #content>
            <div v-if="developer" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nombre Comercial</p>
                        <p class="mt-1 text-sm text-gray-900">{{ developer.devr_commercial_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nombre Legal</p>
                        <p class="mt-1 text-sm text-gray-900">{{ developer.devr_legal_name }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Email de Contacto</p>
                        <p class="mt-1 text-sm text-gray-900">{{ developer.devr_email_contact }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Teléfono de Contacto</p>
                        <p class="mt-1 text-sm text-gray-900">{{ developer.devr_phone_contact || 'N/A' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Sitio Web</p>
                        <p class="mt-1 text-sm text-gray-900">{{ developer.devr_website || 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">País</p>
                        <div class="mt-1 flex items-center gap-2">
                            <span class="inline-block px-2 py-1 text-xs font-medium text-white bg-blue-600 rounded">
                                {{ developer.country?.ctry_name || 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Usuario Asociado</p>
                        <div class="mt-1">
                            <span v-if="developer.user" class="inline-block px-2 py-1 text-xs font-medium text-white bg-purple-600 rounded">
                                {{ developer.user.name }} {{ developer.user.lastname }}
                            </span>
                            <span v-else class="text-sm text-gray-900">N/A</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Estado</p>
                        <div class="mt-1">
                            <span
                                class="inline-block px-2 py-1 text-xs font-medium text-white rounded"
                                :class="developer.devr_active ? 'bg-green-600' : 'bg-red-600'"
                            >
                                {{ developer.devr_active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </DialogModal>
</template>
