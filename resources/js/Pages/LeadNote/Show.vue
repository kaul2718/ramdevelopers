<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { formatDate } from '@/Helpers/dateHelper'

defineProps({
    leadNote: Object,
})
</script>

<template>
    <AppLayout title="Detalle de Nota de Lead">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nota de Lead #{{ leadNote.leadNot_id }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header Actions -->
                <div class="mb-6 flex gap-3">
                    <Link :href="route('leadnote.index')" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                        Volver
                    </Link>
                    <Link :href="route('leadnote.edit', leadNote.leadNot_id)" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                        Editar
                    </Link>
                </div>

                <!-- Note Details -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="grid grid-cols-6 gap-6">
                            <!-- Título -->
                            <div class="col-span-6 sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-700">Título</label>
                                <p class="mt-2 text-sm text-gray-900">{{ leadNote.leadNot_title || '-' }}</p>
                            </div>

                            <!-- Estado -->
                            <div class="col-span-6 sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-700">Estado</label>
                                <span v-if="leadNote.leadNot_active" class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    Activo
                                </span>
                                <span v-else class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    Inactivo
                                </span>
                            </div>

                            <!-- Lead -->
                            <div class="col-span-6 sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-700">Lead</label>
                                <Link :href="route('lead.show', leadNote.lead?.lead_id)" class="mt-2 text-sm text-blue-600 hover:text-blue-900">
                                    {{ leadNote.lead?.lead_client_name }}
                                </Link>
                            </div>

                            <!-- Usuario -->
                            <div class="col-span-6 sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-700">Usuario</label>
                                <p class="mt-2 text-sm text-gray-900">{{ leadNote.user?.name }} {{ leadNote.user?.lastname }}</p>
                            </div>

                            <!-- Creado -->
                            <div class="col-span-6 sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-700">Creado</label>
                                <p class="mt-2 text-sm text-gray-900">{{ formatDate(leadNote.created_at) }}</p>
                            </div>

                            <!-- Descripción -->
                            <div class="col-span-6">
                                <label class="block text-sm font-medium text-gray-700">Descripción</label>
                                <p class="mt-2 text-sm text-gray-900 whitespace-pre-wrap">{{ leadNote.leadNot_description || '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
