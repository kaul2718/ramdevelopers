<!-- filepath: resources/js/Pages/Developer/Show.vue -->
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3'

defineProps({
    developer: {
        type: Object,
        required: true
    }
})
</script>

<template>
    <AppLayout>
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">Información del Desarrollador</h1>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <!-- Card Principal -->
                <div class="bg-white rounded-lg shadow">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-8">
                        <div class="text-white">
                            <h2 class="text-3xl font-bold">{{ developer.devr_commercial_name }}</h2>
                            <p class="text-indigo-100 mt-1">{{ developer.devr_email_contact }}</p>
                            <div class="mt-3">
                                <span v-if="developer.devr_active" 
                                    class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-800">
                                    ✓ Activo
                                </span>
                                <span v-else 
                                    class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-800">
                                    ✗ Inactivo
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Contenido -->
                    <div class="px-6 py-8">
                        <!-- Información de Contacto -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                Información de Contacto
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Nombre Comercial</p>
                                    <p class="text-lg text-gray-900 mt-1">{{ developer.devr_commercial_name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Nombre Legal</p>
                                    <p class="text-lg text-gray-900 mt-1">{{ developer.devr_legal_name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Email</p>
                                    <p class="text-lg text-gray-900 mt-1">
                                        <a :href="`mailto:${developer.devr_email_contact}`" class="text-indigo-600 hover:text-indigo-700">
                                            {{ developer.devr_email_contact }}
                                        </a>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Teléfono</p>
                                    <p class="text-lg text-gray-900 mt-1">{{ developer.devr_phone_contact || 'N/A' }}</p>
                                </div>
                                <div v-if="developer.devr_website">
                                    <p class="text-sm font-medium text-gray-500">Sitio Web</p>
                                    <p class="text-lg text-gray-900 mt-1">
                                        <a :href="developer.devr_website" target="_blank" class="text-indigo-600 hover:text-indigo-700">
                                            {{ developer.devr_website }}
                                        </a>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">País</p>
                                    <p class="text-lg text-gray-900 mt-1">
                                        <span v-if="developer.country" 
                                            class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-sm font-medium text-blue-700">
                                            {{ developer.country.ctry_name }}
                                        </span>
                                        <span v-else class="text-gray-400">N/A</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Usuario Asociado -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                Usuario Asociado
                            </h3>
                            <div v-if="developer.user">
                                <p class="text-lg text-gray-900">
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-sm font-medium text-green-700">
                                        {{ developer.user.name }}
                                    </span>
                                </p>
                            </div>
                            <p v-else class="text-gray-500">Sin usuario asociado</p>
                        </div>

                        <!-- Fechas -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                Información de Sistema
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Creado el</p>
                                    <p class="text-lg text-gray-900 mt-1">{{ new Date(developer.created_at).toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' }) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Actualizado el</p>
                                    <p class="text-lg text-gray-900 mt-1">{{ new Date(developer.updated_at).toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' }) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                        <Link 
                            :href="route('developers.index')"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            ← Volver al listado
                        </Link>
                        <Link 
                            v-if="$page.props.user.permissions.includes('update developer')"
                            :href="route('developers.edit', developer.devr_id)"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                            ✏️ Editar Desarrollador
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
