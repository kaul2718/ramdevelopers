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
    <AppLayout>
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">Panel de Administración</h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-white border-border-gray-200 rounded-lg">
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

                        <Link :href="route('roles.index')"
                            class="block p-4 bg-white rounded-lg shadow hover:shadow-md transition">
                            <div class="flex items-start gap-4">
                                <div class="p-2 bg-green-50 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 6v6l4 2" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-medium">Roles</h3>
                                    <p class="text-sm text-gray-500">Asignar roles y permisos</p>
                                </div>
                            </div>
                        </Link>

                        <Link :href="route('permissions.index')"
                            class="block p-4 bg-white rounded-lg shadow hover:shadow-md transition">
                            <div class="flex items-start gap-4">
                                <div class="p-2 bg-purple-50 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-medium">Permisos</h3>
                                    <p class="text-sm text-gray-500">Crear y gestionar permisos</p>
                                </div>
                            </div>
                        </Link>

                        <Link :href="route('developers.index')"
                            class="block p-4 bg-white rounded-lg shadow hover:shadow-md transition">
                            <div class="flex items-start gap-4">
                                <div class="p-2 bg-yellow-50 rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-600" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 2l3 7h7l-5.5 4 2 7L12 16l-6.5 4 2-7L2 9h7l3-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-medium">Desarrolladores</h3>
                                    <p class="text-sm text-gray-500">Ver y gestionar desarrolladores</p>
                                </div>
                            </div>
                        </Link>

                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
