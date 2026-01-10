<script>
export default {
    name: 'PermissionEdit'
}
</script>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { watch } from 'vue'

const page = usePage();

const props = defineProps({
    permission: {
        type: Object,
        required: true
    }
})

const isDevAdmin = () => {
    const user = page.props.auth?.user;
    if (!user) return false;
    return user.roles && user.roles.some(role => role.name === 'DevAdmin');
};

const form = useForm({
    name: ''
})

// Initialize form with permission data using watch
watch(() => props.permission, (newPermission) => {
    if (newPermission) {
        form.name = newPermission.name
    }
}, { immediate: true })
</script>

<template>
    <AppLayout title="Editar Permiso">
        <template #header>
            <h1 class="font-semibold text-2xl text-gray-800">Editar Permiso</h1>
        </template>

        <!-- Verificar acceso DevAdmin -->
        <div v-if="!isDevAdmin()" class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-800">⚠️ No tienes permiso para acceder a esta sección. Solo DevAdmin puede editar
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
                        <h2 class="text-lg font-semibold text-white">{{ props.permission?.name }}</h2>
                        <p class="text-indigo-100 text-sm mt-1">Actualiza los datos del permiso</p>
                    </div>

                    <!-- Formulario -->
                    <form @submit.prevent="form.put(route('permissions.update', props.permission.id))" class="p-6">
                        <!-- Nombre del Permiso -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
                                Nombre del Permiso <span class="text-red-500">*</span>
                            </label>
                            <input id="name" v-model="form.name" type="text"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors"
                                required />
                            <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
                        </div>

                        <!-- Guard (Solo lectura) -->
                        <div class="mb-6">
                            <label for="guard" class="block text-sm font-semibold text-gray-900 mb-2">
                                Guard
                            </label>
                            <input id="guard" :value="props.permission.guard_name" disabled
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed text-gray-600" />
                            <p class="text-xs text-gray-500 mt-1">El guard no puede ser modificado después de la
                                creación</p>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Actualizar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
