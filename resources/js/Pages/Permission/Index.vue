<script>
    export default {
        name: 'PermissionIndex'
    }
</script>

<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import { Link, router, usePage } from '@inertiajs/vue3'

    const page = usePage();

    defineProps({
        permissions: {
            type: Object,
            required: true
        }
    })

    const isDevAdmin = () => {
        const user = page.props.auth?.user;
        if (!user) return false;
        return user.roles && user.roles.some(role => role.name === 'DevAdmin');
    };

    const deletePermission = (id) => {
        if (confirm("¿Seguro que deseas eliminar este permiso?")) {
            router.delete(route("permissions.destroy", id));
        }
    };
</script>

<template>
    <AppLayout title="Permisos">
        <template #header>
            <div class="flex items-center justify-between">
                <h1 class="font-semibold text-2xl text-gray-800">Gestión de Permisos</h1>
            </div>
        </template>

        <!-- Verificar acceso DevAdmin -->
        <div v-if="!isDevAdmin()" class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-800">⚠️ No tienes permiso para acceder a esta sección. Solo DevAdmin puede gestionar permisos.</p>
                </div>
            </div>
        </div>

        <!-- Contenido para DevAdmin -->
        <div v-else class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Encabezado con botón crear -->
                <div class="mb-6">
                    <div class="flex justify-between items-center bg-white rounded-lg shadow p-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Permisos del Sistema</h2>
                            <p class="text-sm text-gray-500 mt-1">Administra los permisos disponibles en el sistema</p>
                        </div>
                        <Link 
                            :href="route('permissions.create')"
                            class="inline-flex items-center gap-2 text-white bg-indigo-600 hover:bg-indigo-700 py-2 px-4 rounded-lg font-medium transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Crear Permiso
                        </Link>
                    </div>
                </div>

                <!-- Tabla de permisos -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div v-if="permissions.data && permissions.data.length > 0">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">ID</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Nombre del Permiso</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Guard</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr v-for="item in permissions.data" :key="item.id" class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800">
                                                #{{ item.id }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                            {{ item.name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="inline-flex items-center rounded-md bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">
                                                {{ item.guard_name }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center gap-2">
                                                <Link
                                                    :href="route('permissions.edit', item.id)"
                                                    class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 px-3 py-2 rounded-lg transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 9.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                                                    </svg>
                                                    Editar
                                                </Link>
                                                <button
                                                    @click="deletePermission(item.id)"
                                                    class="inline-flex items-center gap-1 text-red-600 hover:text-red-800 hover:bg-red-50 px-3 py-2 rounded-lg transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Eliminar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="flex items-center justify-between border-t border-gray-200 px-6 py-4 sm:px-6">
                            <div class="flex flex-1 justify-between sm:hidden">
                                <Link v-if="permissions.prev_page_url" :href="permissions.prev_page_url" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Anterior</Link>
                                <Link v-if="permissions.next_page_url" :href="permissions.next_page_url" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Siguiente</Link>
                            </div>

                            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700">Mostrando <span class="font-semibold">{{ permissions.from }}</span> a <span class="font-semibold">{{ permissions.to }}</span> de <span class="font-semibold">{{ permissions.total }}</span> permisos</p>
                                </div>

                                <div>
                                    <nav aria-label="Paginación" class="isolate inline-flex -space-x-px rounded-md shadow-sm">
                                        <Link v-if="permissions.prev_page_url" :href="permissions.prev_page_url" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"><svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="w-5 h-5"><path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" /></svg></Link>

                                        <Link v-for="page in permissions.last_page" :key="page" :href="`?page=${page}`" :class="[
                                            'relative inline-flex items-center px-4 py-2 text-sm font-semibold focus:z-20 focus:outline-offset-0',
                                            page === permissions.current_page ? 'z-10 text-white bg-indigo-600 focus-visible:outline-2 focus-visible:outline-indigo-600' : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50'
                                        ]">{{ page }}</Link>

                                        <Link v-if="permissions.next_page_url" :href="permissions.next_page_url" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"><svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="w-5 h-5"><path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" /></svg></Link>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="p-8 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-12 h-12 text-gray-400 mx-auto mb-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m-3.75-6h.008v.008H9v-.008zm0 3h.008v.008H9V15zm0 3h.008v.008H9V18z" />
                        </svg>
                        <p class="text-gray-500">No hay permisos disponibles</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
