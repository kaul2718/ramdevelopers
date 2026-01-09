<template>
    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
        <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
            <span class="w-1 h-6 bg-gradient-to-b from-indigo-500 to-indigo-600 rounded-full mr-3"></span>
            Top 10 Usuarios por Captaciones de Proyectos
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Posición</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">País</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Cantidad de Proyectos</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="(user, index) in users" :key="index" class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white font-bold text-sm">
                                {{ index + 1 }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ user.name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ user.country }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-gradient-to-r from-green-100 to-green-50 text-green-700">
                                {{ user.development_count }}
                            </span>
                        </td>
                    </tr>
                    <tr v-if="!users || users.length === 0">
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500 text-sm">No hay datos disponibles</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
defineProps({
    users: {
        type: Array,
        required: true
    }
});
</script>
