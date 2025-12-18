<template>
    <AppLayout title="Detalles del Lead">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detalles del Lead #{{ lead.lead_id }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- Header con botones -->
                    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">{{ lead.lead_client_name }}</h3>
                        <div class="flex gap-2">
                            <Link
                                :href="route('lead.edit', lead.lead_id)"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium"
                            >
                                Editar
                            </Link>
                            <Link
                                href="/lead"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium"
                            >
                                Volver
                            </Link>
                        </div>
                    </div>

                    <!-- Contenido -->
                    <div class="p-6 space-y-6">
                        <!-- Información Personal -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 mb-4">Información Personal</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Email</p>
                                    <p class="text-gray-900 font-medium">{{ lead.lead_client_email || 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Teléfono</p>
                                    <p class="text-gray-900 font-medium">{{ lead.lead_client_phone || 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Idioma</p>
                                    <p class="text-gray-900 font-medium">{{ lead.lead_language || 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Creado</p>
                                    <p class="text-gray-900 font-medium">{{ formatDate(lead.created_at) }}</p>
                                </div>
                            </div>
                        </div>

                        <hr />

                        <!-- Información de Relaciones -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 mb-4">Información de Relaciones</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">País</p>
                                    <p class="text-gray-900 font-medium">{{ lead.country?.ctry_name || 'Sin asignar' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Desarrollo</p>
                                    <p class="text-gray-900 font-medium">{{ lead.development?.devt_title || 'Sin asignar' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Fuente</p>
                                    <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700">
                                        {{ lead.source?.leadSou_name || 'N/A' }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Estado</p>
                                    <span class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-700">
                                        {{ lead.status?.leadSta_name || 'N/A' }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Usuario Responsable</p>
                                    <p class="text-gray-900 font-medium">{{ lead.user?.name || 'Sin asignar' }}</p>
                                </div>
                            </div>
                        </div>

                        <hr />

                        <!-- Mensaje -->
                        <div v-if="lead.lead_message">
                            <h4 class="text-md font-semibold text-gray-900 mb-4">Mensaje</h4>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <p class="text-gray-700 whitespace-pre-line">{{ lead.lead_message }}</p>
                            </div>
                        </div>

                        <!-- Notas del Lead -->
                        <div v-if="lead.notes && lead.notes.length > 0">
                            <h4 class="text-md font-semibold text-gray-900 mb-4">Notas</h4>
                            <div class="space-y-3">
                                <div v-for="note in lead.notes" :key="note.leadNote_id" class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <p class="text-sm text-gray-600">{{ formatDate(note.created_at) }}</p>
                                    <p class="text-gray-700 mt-2">{{ note.leadNote_text }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    lead: Object,
});

const formatDate = (date) => {
    if (!date) return 'N/A';
    return new Date(date).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>
