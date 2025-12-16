<script>
export default {
    name: 'RoleCreate'
}
</script>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'

defineProps({
    permissions: {
        type: Array,
        required: true
    }
})

const form = useForm({
    name: '',
    permissions: []
})
</script>

<template>
    <AppLayout>
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">Crear Rol</h1>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                    <div class="p-6 bg-white border-border-gray-200 rounded-lg">
                    <form @submit.prevent="form.post(route('roles.store'))">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input v-model="form.name" class="mt-1 block w-full border rounded p-2" />
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Permisos</label>
                            <div class="grid grid-cols-2 gap-2 mt-2">
                                <label v-for="p in permissions" :key="p.id" class="flex items-center gap-2">
                                    <input type="checkbox" :value="p.id" v-model="form.permissions" />
                                    <span class="text-sm">{{ p.name }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="text-white bg-indigo-500 hover:bg-indigo-700 py-2 px-4 rounded">Crear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
