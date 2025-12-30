<!-- filepath: resources/js/Pages/User/Show.vue -->
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3'

defineProps({
    user: {
        type: Object,
        required: true
    }
})
</script>

<template>
    <AppLayout>
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">Información del Usuario</h1>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <!-- Card Principal -->
                <div class="bg-white rounded-lg shadow">
                    <!-- Header con foto -->
                    <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-8">
                        <div class="flex items-center gap-6">
                            <!-- Foto de Perfil -->
                            <div>
                                <img v-if="user.profile_photo_url" 
                                    :src="user.profile_photo_url" 
                                    :alt="user.name" 
                                    class="w-24 h-24 rounded-full object-cover border-4 border-white">
                                <div v-else 
                                    class="w-24 h-24 rounded-full bg-gray-300 flex items-center justify-center text-3xl font-semibold text-gray-700 border-4 border-white">
                                    {{ user.name.charAt(0) }}
                                </div>
                            </div>
                            <!-- Nombre y Estado -->
                            <div class="text-white">
                                <h2 class="text-3xl font-bold">{{ user.name }} {{ user.lastname }}</h2>
                                <p class="text-indigo-100 mt-1">{{ user.email }}</p>
                                <div class="mt-3">
                                    <span v-if="user.usr_active" 
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
                    </div>

                    <!-- Contenido -->
                    <div class="px-6 py-8">
                        <!-- Información Personal -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                Información Personal
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Nombre</p>
                                    <p class="text-lg text-gray-900 mt-1">{{ user.name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Apellido</p>
                                    <p class="text-lg text-gray-900 mt-1">{{ user.lastname }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Correo Electrónico</p>
                                    <p class="text-lg text-gray-900 mt-1">
                                        <a :href="`mailto:${user.email}`" class="text-indigo-600 hover:text-indigo-700">
                                            {{ user.email }}
                                        </a>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Teléfono</p>
                                    <p class="text-lg text-gray-900 mt-1">{{ user.phone || 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Idiomas</p>
                                    <p class="text-lg text-gray-900 mt-1">{{ user.idiomas || 'No especificado' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">País</p>
                                    <p class="text-lg text-gray-900 mt-1">
                                        <span v-if="user.country" 
                                            class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-sm font-medium text-blue-700">
                                            {{ user.country.ctry_name }}
                                        </span>
                                        <span v-else class="text-gray-400">N/A</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Roles -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                Roles Asignados
                            </h3>
                            <div v-if="user.roles && user.roles.length > 0" class="flex flex-wrap gap-2">
                                <span v-for="role in user.roles" 
                                    :key="role.id" 
                                    class="inline-flex items-center rounded-full bg-purple-100 px-4 py-2 text-sm font-medium text-purple-800">
                                    {{ role.name }}
                                </span>
                            </div>
                            <p v-else class="text-gray-500">Sin roles asignados</p>
                        </div>

                        <!-- Fechas -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                                Información de Sistema
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Creado el</p>
                                    <p class="text-lg text-gray-900 mt-1">{{ new Date(user.created_at).toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' }) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Actualizado el</p>
                                    <p class="text-lg text-gray-900 mt-1">{{ new Date(user.updated_at).toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' }) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                        <Link 
                            :href="route('users.index')"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            ← Volver al listado
                        </Link>
                        <Link 
                            v-if="$page.props.user.permissions.includes('update users')"
                            :href="route('users.edit', user.id)"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                            ✏️ Editar Usuario
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
