<script>
export default {
    name: 'PermissionCreate'
}
</script>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, usePage } from '@inertiajs/vue3'

const page = usePage();

const isDevAdmin = () => {
    const user = page.props.auth?.user;
    if (!user) return false;
    return user.roles && user.roles.some(role => role.name === 'DevAdmin');
};

const form = useForm({
    name: '',
    guard_name: 'web'
})
</script>

<template>
    <AppLayout title="Crear Permiso">
        <template #header>
            <h1 class="font-semibold text-2xl text-gray-800">Crear Nuevo Permiso</h1>
        </template>

        <!-- Verificar acceso DevAdmin -->
        <div v-if="!isDevAdmin()" class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-800">⚠️ No tienes permiso para acceder a esta sección. Solo DevAdmin puede crear
                        permisos.</p>
                </div>
            </div>
        </div>

        <!-- Contenido para DevAdmin -->
        <div v-else class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Encabezado -->
                    <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                        <h2 class="text-lg font-semibold text-white">Información del Permiso</h2>
                        <p class="text-indigo-100 text-sm mt-1">Completa los datos para crear un nuevo permiso del
                            sistema</p>
                    </div>

                    <!-- Formulario -->
                    <form @submit.prevent="form.post(route('permissions.store'))" class="p-6">
                        <!-- Nombre del Permiso -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
                                Nombre del Permiso <span class="text-red-500">*</span>
                            </label>
                            <input id="name" v-model="form.name" type="text" placeholder="Ej: create users"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors"
                                required />
                            <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
                        </div>

                        <!-- Guard -->
                        <div class="mb-6">
                            <label for="guard" class="block text-sm font-semibold text-gray-900 mb-2">
                                Guard <span class="text-red-500">*</span>
                            </label>
                            <select id="guard" v-model="form.guard_name"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors"
                                required>
                                <option value="web">web</option>
                                <option value="sanctum">sanctum</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">El guard define el contexto en el que se aplica el
                                permiso</p>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                            <a :href="route('permissions.index')"
                                class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Cancelar
                            </a>
                            <button type="submit" :disabled="form.processing"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:bg-gray-400 disabled:cursor-not-allowed font-medium transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Crear Permiso
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
