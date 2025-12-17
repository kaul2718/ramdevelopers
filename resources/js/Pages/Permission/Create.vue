<script>
export default {
    name: 'PermissionCreate'
}
</script>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    guard_name: 'web'
})
</script>

<template>
    <AppLayout title="Crear Permiso">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">Crear Permiso</h1>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-white border-border-gray-200 rounded-lg">
                    <form @submit.prevent="form.post(route('permissions.store'))">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input v-model="form.name" class="mt-1 block w-full border rounded p-2" />
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Guard</label>
                            <select v-model="form.guard_name" class="mt-1 block w-full border rounded p-2">
                                <option value="web">web</option>
                                <option value="sanctum">sanctum</option>
                            </select>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a :href="route('permissions.index')" class="text-gray-500 hover:text-gray-700 py-2 px-4 rounded">Cancelar</a>
                            <button type="submit" class="text-white bg-indigo-500 hover:bg-indigo-700 py-2 px-4 rounded">Crear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
