<script>
export default {
    name: 'AdminIndex'
}
</script>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'
</script>

<template>
    <AppLayout title="Admin">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">Panel de Administración</h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-white border-border-gray-200 rounded-lg">
                    <!-- USUARIOS -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Usuarios</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            <Link :href="route('users.index')"
                                class="block p-4 bg-white rounded-lg shadow hover:shadow-md transition">
                                <div class="flex items-start gap-4">
                                    <div class="p-2 bg-indigo-50 rounded-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium">Usuarios</h3>
                                        <p class="text-sm text-gray-500">Ingresar · Listar · Gestionar usuarios</p>
                                    </div>
                                </div>
                            </Link>
                        </div>
                    </div>

                    <!-- UBICACIÓN -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Ubicación</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            <Link :href="route('countries.index')"
                                class="block p-4 bg-white rounded-lg shadow hover:shadow-md transition">
                                <div class="flex items-start gap-4">
                                    <div class="p-2 bg-blue-50 rounded-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20H7m6-4v4m0-11v3m0-5v2" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium">Países</h3>
                                        <p class="text-sm text-gray-500">Gestionar países</p>
                                    </div>
                                </div>
                            </Link>

                            <Link :href="route('cities.index')"
                                class="block p-4 bg-white rounded-lg shadow hover:shadow-md transition">
                                <div class="flex items-start gap-4">
                                    <div class="p-2 bg-orange-50 rounded-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-600" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M17 21v-8H7v8" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium">Ciudades</h3>
                                        <p class="text-sm text-gray-500">[Dependiente de País] Gestionar ciudades</p>
                                    </div>
                                </div>
                            </Link>
                        </div>
                    </div>

                    <!-- DEVELOPMENTS -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Developments (Proyectos)</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            <Link :href="route('documenttype.index')"
                                class="block p-4 bg-white rounded-lg shadow hover:shadow-md transition">
                                <div class="flex items-start gap-4">
                                    <div class="p-2 bg-red-50 rounded-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium">Tipos Documentos</h3>
                                        <p class="text-sm text-gray-500">Gestionar tipos de documentos</p>
                                    </div>
                                </div>
                            </Link>

                            <Link :href="route('businessstate.index')"
                                class="block p-4 bg-white rounded-lg shadow hover:shadow-md transition">
                                <div class="flex items-start gap-4">
                                    <div class="p-2 bg-teal-50 rounded-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-teal-600" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium">Estado General - Negocio</h3>
                                        <p class="text-sm text-gray-500">Estados de desarrollo del proyecto</p>
                                    </div>
                                </div>
                            </Link>

                            <Link :href="route('approvalstatus.index')"
                                class="block p-4 bg-white rounded-lg shadow hover:shadow-md transition">
                                <div class="flex items-start gap-4">
                                    <div class="p-2 bg-pink-50 rounded-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-pink-600" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium">Estado General - Aprobación</h3>
                                        <p class="text-sm text-gray-500">Aprobación del development</p>
                                    </div>
                                </div>
                            </Link>

                            <Link :href="route('commercialstatus.index')"
                                class="block p-4 bg-white rounded-lg shadow hover:shadow-md transition">
                                <div class="flex items-start gap-4">
                                    <div class="p-2 bg-violet-50 rounded-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-violet-600" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium">Estado Comercial - Promoción</h3>
                                        <p class="text-sm text-gray-500">Estado de venta/comercialización</p>
                                    </div>
                                </div>
                            </Link>
                        </div>
                    </div>

                    <!-- LEADS -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Leads (Gestión de Prospectos)</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            <Link :href="route('leadstatus.index')"
                                class="block p-4 bg-white rounded-lg shadow hover:shadow-md transition">
                                <div class="flex items-start gap-4">
                                    <div class="p-2 bg-cyan-50 rounded-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-cyan-600" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium">Estado General</h3>
                                        <p class="text-sm text-gray-500">Estados del prospecto</p>
                                    </div>
                                </div>
                            </Link>

                            <Link :href="route('leadsources.index')"
                                class="block p-4 bg-white rounded-lg shadow hover:shadow-md transition">
                                <div class="flex items-start gap-4">
                                    <div class="p-2 bg-lime-50 rounded-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-lime-600" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium">Origen</h3>
                                        <p class="text-sm text-gray-500">[Dependiente de Estado Lead] De dónde viene el prospecto</p>
                                    </div>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
