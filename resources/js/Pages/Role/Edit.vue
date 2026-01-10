<script>
export default {
    name: 'RoleEdit'
}
</script>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { watchEffect } from 'vue'

const page = usePage();

const props = defineProps({
    role: {
        type: Object,
        required: true
    },
    permissions: {
        type: Array,
        required: true
    },
    rolePermissions: {
        type: Array,
        required: true
    }
})

const isDevAdmin = () => {
    const user = page.props.auth?.user;
    if (!user) return false;
    return user.roles && user.roles.some(role => role.name === 'DevAdmin');
};

const form = useForm({
    name: '',
    permissions: []
})

// Initialize form when props are available to avoid runtime errors
watchEffect(() => {
    form.name = props.role?.name ?? '';
    form.permissions = props.rolePermissions ?? [];
})
</script>

<template>
    <AppLayout title="Editar Rol">
        <template #header>
            <h1 class="font-semibold text-2xl text-gray-800">Editar Rol</h1>
        </template>

        <!-- Verificar acceso DevAdmin -->
        <div v-if="!isDevAdmin()" class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-800">⚠️ No tienes permiso para acceder a esta sección. Solo DevAdmin puede editar
                        roles.
                    </p>
                </div>
            </div>
        </div>

        <!-- Contenido para DevAdmin -->
        <div v-else class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Encabezado -->
                    <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                        <h2 class="text-lg font-semibold text-white">{{ props.role?.name }}</h2>
                        <p class="text-indigo-100 text-sm mt-1">Actualiza los datos del rol</p>
                    </div>

                    <!-- Formulario -->
                    <form @submit.prevent="form.put(route('roles.update', props.role?.id))" class="p-6">
                        <!-- Nombre del Rol -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
                                Nombre del Rol <span class="text-red-500">*</span>
                            </label>
                            <input id="name" v-model="form.name" type="text"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors"
                                required />
                            <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
                        </div>

                        <!-- Permisos -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-900 mb-3">
                                Permisos Asignados <span class="text-gray-500 text-xs font-normal">({{
                                    form.permissions.length
                                    }} seleccionados)</span>
                            </label>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div v-if="props.permissions && props.permissions.length > 0"
                                    class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <label v-for="p in props.permissions" :key="p.id"
                                        class="flex items-start gap-3 p-2 hover:bg-white rounded-lg transition-colors cursor-pointer">
                                        <input type="checkbox" :value="p.id" v-model="form.permissions"
                                            class="mt-1 w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer" />
                                        <span class="text-sm text-gray-700">{{ p.name }}</span>
                                    </label>
                                </div>
                                <p v-else class="text-gray-500 text-sm italic">No hay permisos disponibles</p>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                            <a :href="route('roles.index')"
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
