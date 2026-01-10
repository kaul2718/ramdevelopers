<script setup>
    import Charts from '@/Components/Dashboard/Charts.vue';
    import MyStats from '@/Components/Dashboard/MyStats.vue';

    defineProps({
        stats: Object,
        recentActivity: Array,
        leadsBySource: Array,
        topProjectsByLeads: Array,
        monthlyComparison: Object,
        quickSummary: Array,
        userLeadsByStatus: Array,
    });
</script>

<template>
    <div class="space-y-8">
        <!-- Header de Bienvenida -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Tu Desempeño de Ventas</h3>
                    <p class="text-gray-600 text-sm mt-1">Seguimiento de leads y proyectos activos</p>
                </div>
                <div class="text-4xl">📈</div>
            </div>
        </div>

        <!-- Estadísticas Clave -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-blue-50 rounded-lg p-6 border-l-4 border-blue-500 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold text-gray-600">Mis Proyectos</p>
                    <span class="text-2xl">🏢</span>
                </div>
                <p class="text-3xl font-bold text-blue-600">{{ stats?.totalDevelopments || 0 }}</p>
            </div>

            <div class="bg-green-50 rounded-lg p-6 border-l-4 border-green-500 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold text-gray-600">Mis Leads</p>
                    <span class="text-2xl">📋</span>
                </div>
                <p class="text-3xl font-bold text-green-600">{{ stats?.totalLeads || 0 }}</p>
            </div>

            <div class="bg-purple-50 rounded-lg p-6 border-l-4 border-purple-500 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold text-gray-600">Convertidos</p>
                    <span class="text-2xl">✅</span>
                </div>
                <p class="text-3xl font-bold text-purple-600">{{ stats?.activeLeads || 0 }}</p>
            </div>

            <div class="bg-amber-50 rounded-lg p-6 border-l-4 border-amber-500 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold text-gray-600">Tasa Conversión</p>
                    <span class="text-2xl">📊</span>
                </div>
                <p class="text-3xl font-bold text-amber-600">{{ stats?.conversionRate || 0 }}%</p>
            </div>
        </div>

        <!-- 5. Desempeño del Período -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-indigo-500">
            <h4 class="font-semibold text-gray-900 mb-6 text-lg">Desempeño del Mes</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-2">Este Mes</p>
                    <p class="text-3xl font-bold text-blue-600">{{ monthlyComparison?.thisMonth || 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">leads creados</p>
                </div>
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-2">Mes Anterior</p>
                    <p class="text-3xl font-bold text-gray-600">{{ monthlyComparison?.lastMonth || 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">leads creados</p>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-2">Crecimiento</p>
                    <p class="text-3xl font-bold" :class="monthlyComparison?.growth >= 0 ? 'text-green-600' : 'text-red-600'">
                        {{ monthlyComparison?.growth >= 0 ? '+' : '' }}{{ monthlyComparison?.growth || 0 }}%
                    </p>
                    <p class="text-xs text-gray-500 mt-1">vs mes anterior</p>
                </div>
            </div>
        </div>

        <!-- 3. Leads por Fuente -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
            <h4 class="font-semibold text-gray-900 mb-4 text-lg">Leads por Fuente</h4>
            <div class="space-y-3">
                <div v-for="source in leadsBySource" :key="source.leadSou_name" class="flex items-center justify-between p-3 bg-gray-50 rounded">
                    <span class="text-gray-700 font-medium">{{ source.leadSou_name }}</span>
                    <div class="flex items-center gap-3">
                        <div class="w-40 bg-gray-200 rounded-full h-2">
                            <div class="bg-orange-500 h-2 rounded-full" :style="{width: (source.total / (stats?.totalLeads || 1) * 100) + '%'}"></div>
                        </div>
                        <span class="font-bold text-gray-900 w-8 text-right">{{ source.total }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Top Proyectos por Leads -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-cyan-500">
            <h4 class="font-semibold text-gray-900 mb-4 text-lg">Proyectos Más Activos</h4>
            <div class="space-y-3">
                <div v-for="(project, index) in topProjectsByLeads" :key="index" class="flex items-center justify-between p-3 bg-gray-50 rounded hover:bg-cyan-50 transition">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-cyan-500 text-white text-xs font-bold">{{ index + 1 }}</span>
                        <span class="text-gray-700 font-medium">{{ project.name }}</span>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-cyan-100 text-cyan-700">
                        {{ project.lead_count }} leads
                    </span>
                </div>
            </div>
        </div>

        <!-- 8. Resumen Rápido - Leads por Estado -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <h4 class="font-semibold text-gray-900 mb-4 text-lg">Resumen de Leads por Estado</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div v-for="summary in quickSummary" :key="summary.status_name" class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4 border border-gray-200">
                    <p class="text-sm text-gray-600 mb-2">{{ summary.status_name }}</p>
                    <p class="text-3xl font-bold text-gray-800">{{ summary.total }}</p>
                </div>
            </div>
        </div>

        <!-- 2. Actividad Reciente -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-pink-500">
            <h4 class="font-semibold text-gray-900 mb-4 text-lg">Actividad Reciente</h4>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                <div v-for="activity in recentActivity" :key="activity.id" class="flex items-start justify-between p-3 bg-gray-50 rounded hover:bg-pink-50 transition border-l-3 border-pink-300">
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900">{{ activity.name }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded">{{ activity.source }}</span>
                            <span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded">{{ activity.status }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ activity.date }}</p>
                    </div>
                </div>
                <div v-if="!recentActivity || recentActivity.length === 0" class="text-center py-8 text-gray-500">
                    <p>Sin actividad reciente</p>
                </div>
            </div>
        </div>


        <Charts
            :is-admin="false"
            :user-leads-by-status="userLeadsByStatus"
        />
    </div>
</template>
