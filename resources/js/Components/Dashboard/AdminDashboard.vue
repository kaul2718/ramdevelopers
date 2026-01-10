<script setup>
    import Charts from '@/Components/Dashboard/Charts.vue';
    import TopUsersTable from '@/Components/Dashboard/TopUsersTable.vue';

    defineProps({
        stats: Object,
        usersByCountry: Array,
        leadsByStatus: Array,
        leadsBySource: Array,
        usersByProfile: Array,
        topUsersByDevelopments: Array,
        userLeadsByStatus: Array,
    });
</script>

<template>
    <div class="space-y-8">
        <!-- Header de Bienvenida -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Control Total del Sistema</h3>
                    <p class="text-gray-600 text-sm mt-1">Gestión completa de usuarios, desarrolladores y proyectos</p>
                </div>
                <div class="text-4xl">📊</div>
            </div>
        </div>

        <!-- Estadísticas Clave -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-blue-50 rounded-lg p-6 border-l-4 border-blue-500 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold text-gray-600">Total Usuarios</p>
                    <span class="text-2xl">👥</span>
                </div>
                <p class="text-3xl font-bold text-blue-600">{{ stats?.totalUsers || 0 }}</p>
            </div>

            <div class="bg-green-50 rounded-lg p-6 border-l-4 border-green-500 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold text-gray-600">Desarrolladores</p>
                    <span class="text-2xl">💻</span>
                </div>
                <p class="text-3xl font-bold text-green-600">{{ stats?.activeDevelopers || 0 }}</p>
            </div>

            <div class="bg-purple-50 rounded-lg p-6 border-l-4 border-purple-500 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold text-gray-600">Total Leads</p>
                    <span class="text-2xl">📋</span>
                </div>
                <p class="text-3xl font-bold text-purple-600">{{ stats?.totalLeads || 0 }}</p>
            </div>

            <div class="bg-amber-50 rounded-lg p-6 border-l-4 border-amber-500 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold text-gray-600">Proyectos</p>
                    <span class="text-2xl">🏗️</span>
                </div>
                <p class="text-3xl font-bold text-amber-600">{{ stats?.totalDevelopments || 0 }}</p>
            </div>
        </div>

        <!-- Gráficas Administrativas -->
        <Charts
            :is-admin="true"
            :users-by-country="usersByCountry"
            :leads-by-status="leadsByStatus"
            :leads-by-source="leadsBySource"
            :users-by-profile="usersByProfile"
            :user-leads-by-status="userLeadsByStatus"
        />

        <!-- Top Usuarios -->
        <TopUsersTable :users="topUsersByDevelopments" />
    </div>
</template>
