<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import { ref, onMounted } from 'vue';
    import Chart from 'chart.js/auto';

    const props = defineProps({
        stats: Object,
        usersByCountry: Array,
        leadsByStatus: Array,
    });

    const chartUsers = ref(null);
    const chartLeads = ref(null);
    const chartUsersCanvas = ref(null);
    const chartLeadsCanvas = ref(null);

    onMounted(() => {
        // Gráfica: Usuarios por País
        if (chartUsersCanvas.value) {
            chartUsers.value = new Chart(chartUsersCanvas.value, {
                type: 'bar',
                data: {
                    labels: props.usersByCountry?.map(item => item.ctry_name) || [],
                    datasets: [{
                        label: 'Usuarios por País',
                        data: props.usersByCountry?.map(item => item.total) || [],
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Gráfica: Leads por Estado
        if (chartLeadsCanvas.value) {
            chartLeads.value = new Chart(chartLeadsCanvas.value, {
                type: 'doughnut',
                data: {
                    labels: props.leadsByStatus?.map(item => item.leadSta_name) || [],
                    datasets: [{
                        label: 'Leads por Estado',
                        data: props.leadsByStatus?.map(item => item.total) || [],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true
                }
            });
        }
    });
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Estadísticas Rápidas -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Usuarios -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM4 20h16a2 2 0 002-2v-2a2 2 0 00-2-2H4a2 2 0 00-2 2v2a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-500 text-sm">Total Usuarios</p>
                                <p class="text-2xl font-bold text-gray-800">{{ stats?.totalUsers || 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Desarrolladores -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4m0 6H4m16 0a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-500 text-sm">Desarrolladores</p>
                                <p class="text-2xl font-bold text-gray-800">{{ stats?.activeDevelopers || 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Leads -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-500 text-sm">Total Leads</p>
                                <p class="text-2xl font-bold text-gray-800">{{ stats?.totalLeads || 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Desarrollos -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-500 text-sm">Desarrollos</p>
                                <p class="text-2xl font-bold text-gray-800">{{ stats?.totalDevelopments || 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Países -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-red-100">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20H7m6-4v4m0-11v3m0-5v2" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-500 text-sm">Países</p>
                                <p class="text-2xl font-bold text-gray-800">{{ stats?.totalCountries || 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Ciudades -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-indigo-100">
                                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-500 text-sm">Ciudades</p>
                                <p class="text-2xl font-bold text-gray-800">{{ stats?.totalCities || 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráficas -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Gráfica: Usuarios por País -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Usuarios por País</h3>
                        <canvas ref="chartUsersCanvas" height="300"></canvas>
                    </div>

                    <!-- Gráfica: Leads por Estado -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Leads por Estado</h3>
                        <canvas ref="chartLeadsCanvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
