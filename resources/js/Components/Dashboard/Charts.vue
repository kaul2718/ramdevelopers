<template>
    <div :class="['grid gap-6', isAdmin ? 'grid-cols-1 lg:grid-cols-2' : 'grid-cols-1']">
        <!-- Usuarios por País (Admin) -->
        <div v-if="isAdmin" class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-all duration-300 border border-gray-100">
            <div class="flex items-center mb-6">
                <div class="w-1.5 h-7 bg-gradient-to-b from-blue-500 to-blue-600 rounded-full mr-4"></div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Usuarios por País</h3>
                    <p class="text-xs text-gray-500">Distribución geográfica</p>
                </div>
            </div>
            <canvas ref="chartUsersCanvas" height="280"></canvas>
        </div>

        <!-- Leads por Fuente (Admin) -->
        <div v-if="isAdmin" class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-all duration-300 border border-gray-100">
            <div class="flex items-center mb-6">
                <div class="w-1.5 h-7 bg-gradient-to-b from-orange-500 to-orange-600 rounded-full mr-4"></div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Leads por Fuente</h3>
                    <p class="text-xs text-gray-500">Origen de los leads</p>
                </div>
            </div>
            <canvas ref="chartLeadsSourceCanvas" height="280"></canvas>
        </div>

        <!-- Usuarios por Perfil (Admin) -->
        <div v-if="isAdmin" class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-all duration-300 border border-gray-100">
            <div class="flex items-center mb-6">
                <div class="w-1.5 h-7 bg-gradient-to-b from-purple-500 to-purple-600 rounded-full mr-4"></div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Usuarios por Rol</h3>
                    <p class="text-xs text-gray-500">Distribución de perfiles</p>
                </div>
            </div>
            <canvas ref="chartUsersProfileCanvas" height="280"></canvas>
        </div>

        <!-- Leads por Estado (Todos) -->
        <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-all duration-300 border border-gray-100" :class="{ 'lg:col-span-2': !isAdmin }">
            <div class="flex items-center mb-6">
                <div class="w-1.5 h-7 bg-gradient-to-b from-green-500 to-green-600 rounded-full mr-4"></div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">{{ isAdmin ? 'Leads por Estado' : 'Mis Leads por Estado' }}</h3>
                    <p class="text-xs text-gray-500">{{ isAdmin ? 'Estado general de leads' : 'Tu estado de leads' }}</p>
                </div>
            </div>
            <div :class="{ 'flex justify-center': !isAdmin }">
                <div :style="!isAdmin ? 'width: 100%; max-width: 400px;' : ''">
                    <canvas :ref="isAdmin ? 'chartLeadsCanvas' : 'chartUserLeadsCanvas'" height="280"></canvas>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useDashboardCharts } from '@/Composables/useDashboardCharts';

const props = defineProps({
    isAdmin: Boolean,
    usersByCountry: Array,
    leadsByStatus: Array,
    leadsBySource: Array,
    usersByProfile: Array,
    userLeadsByStatus: Array
});

const {
    chartUsersCanvas,
    chartLeadsCanvas,
    chartLeadsSourceCanvas,
    chartUsersProfileCanvas,
    chartUserLeadsCanvas,
    initUsersByCountryChart,
    initLeadsBySourceChart,
    initUsersByProfileChart,
    initLeadsByStatusChart,
    initUserLeadsByStatusChart
} = useDashboardCharts();

onMounted(() => {
    if (props.isAdmin) {
        initUsersByCountryChart(props.usersByCountry);
        initLeadsBySourceChart(props.leadsBySource);
        initUsersByProfileChart(props.usersByProfile);
        initLeadsByStatusChart(props.leadsByStatus);
    } else {
        initUserLeadsByStatusChart(props.userLeadsByStatus);
    }
});
</script>
