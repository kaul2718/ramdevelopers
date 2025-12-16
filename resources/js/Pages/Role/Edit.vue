<script>
export default {
    name: 'RoleEdit'
}
</script>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { watchEffect } from 'vue'

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
    <AppLayout>
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">Editar Rol</h1>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 bg-white border-border-gray-200 rounded-lg">
                    <form @submit.prevent="form.put(route('roles.update', props.role?.id))">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input v-model="form.name" class="mt-1 block w-full border rounded p-2" />
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Permisos</label>
                            <div class="grid grid-cols-2 gap-2 mt-2">
                                <label v-for="p in props.permissions" :key="p.id" class="flex items-center gap-2">
                                    <input type="checkbox" :value="p.id" v-model="form.permissions" />
                                    <span class="text-sm">{{ p.name }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
