<script>
export default {
    name: 'PermissionEdit'
}
</script>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { watch } from 'vue'

const props = defineProps({
    permission: {
        type: Object,
        required: true
    }
})

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
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">Editar Permiso</h1>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-white border-border-gray-200 rounded-lg">
                    <form @submit.prevent="form.put(route('permissions.update', props.permission.id))">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input v-model="form.name" class="mt-1 block w-full border rounded p-2" />
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Guard</label>
                            <input :value="props.permission.guard_name" disabled class="mt-1 block w-full border rounded p-2 bg-gray-100 cursor-not-allowed" />
                            <p class="text-xs text-gray-500 mt-1">El guard no puede ser modificado</p>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a :href="route('permissions.index')" class="text-gray-500 hover:text-gray-700 py-2 px-4 rounded">Cancelar</a>
                            <button type="submit" class="text-white bg-indigo-500 hover:bg-indigo-700 py-2 px-4 rounded">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
