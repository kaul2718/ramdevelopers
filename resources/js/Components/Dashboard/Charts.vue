<template>
    <div :class="['grid gap-6', isAdmin ? 'grid-cols-1 lg:grid-cols-2' : 'grid-cols-1']">
        <!-- Usuarios por País (Admin) -->
        <div v-if="isAdmin" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <span class="w-1 h-6 bg-gradient-to-b from-blue-500 to-blue-600 rounded-full mr-3"></span>
                Usuarios por País
            </h3>
            <canvas ref="chartUsersCanvas" height="300"></canvas>
        </div>

        <!-- Leads por Fuente (Admin) -->
        <div v-if="isAdmin" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <span class="w-1 h-6 bg-gradient-to-b from-orange-500 to-orange-600 rounded-full mr-3"></span>
                Leads por Fuente
            </h3>
            <canvas ref="chartLeadsSourceCanvas" height="300"></canvas>
        </div>

        <!-- Usuarios por Perfil (Admin) -->
        <div v-if="isAdmin" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <span class="w-1 h-6 bg-gradient-to-b from-purple-500 to-purple-600 rounded-full mr-3"></span>
                Usuarios por Perfil
            </h3>
            <canvas ref="chartUsersProfileCanvas" height="300"></canvas>
        </div>

        <!-- Leads por Estado (Todos) -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow" :class="{ 'lg:col-span-2': !isAdmin }">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <span class="w-1 h-6 bg-gradient-to-b from-green-500 to-green-600 rounded-full mr-3"></span>
                {{ isAdmin ? 'Leads por Estado' : 'Mis Leads por Estado' }}
            </h3>
            <div :class="{ 'flex justify-center': !isAdmin }">
                <div :style="!isAdmin ? 'width: 100%; max-width: 400px;' : ''">
                    <canvas :ref="isAdmin ? 'chartLeadsCanvas' : 'chartUserLeadsCanvas'" height="300"></canvas>
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
