<template>
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mt-6">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-medium leading-6 text-gray-900">
                Captadores del Proyecto
            </h3>
            <Link
                :href="`/development/${development.devt_id}/captors`"
                class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
            >
                Gestionar
            </Link>
        </div>

        <div v-if="captors.length === 0" class="px-4 py-5 sm:p-6">
            <p class="text-center text-gray-500">No hay captadores asignados a este desarrollo.</p>
        </div>

        <ul v-else class="divide-y divide-gray-200">
            <li v-for="captor in captors" :key="captor.user_id" class="px-4 py-4 sm:px-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">
                            {{ captor.user?.name }} {{ captor.user?.lastname }}
                        </p>
                        <p class="text-sm text-gray-500">{{ captor.user?.email }}</p>
                    </div>
                    <div v-if="captor.devtUsr_is_main" class="ml-3">
                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            Principal
                        </span>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    development: Object,
    captors: {
        type: Array,
        default: () => [],
    },
})
</script>
