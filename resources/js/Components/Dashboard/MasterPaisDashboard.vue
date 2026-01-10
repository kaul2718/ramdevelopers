<script setup>
    import Charts from '@/Components/Dashboard/Charts.vue';

    defineProps({
        stats: Object,
        topAgents: Array,
        topProjects: Array,
        leadsByCity: Array,
        leadsBySource: Array,
        recentActivity: Array,
        leadsByStatus: Array,
    });
</script>

<template>
    <div class="space-y-8">
        <!-- Header de Bienvenida -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-indigo-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Gestión de Master País</h3>
                    <p class="text-gray-600 text-sm mt-1">Control centralizado de proyectos, agentes y leads</p>
                </div>
                <div class="text-4xl">🌍</div>
            </div>
        </div>

        <!-- Estadísticas Clave (4 tarjetas) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-blue-50 rounded-lg p-6 border-l-4 border-blue-500 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold text-gray-600">Proyectos</p>
                    <span class="text-2xl">🏢</span>
                </div>
                <p class="text-3xl font-bold text-blue-600">{{ stats?.totalDevelopments || 0 }}</p>
            </div>

            <div class="bg-green-50 rounded-lg p-6 border-l-4 border-green-500 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold text-gray-600">Total Leads</p>
                    <span class="text-2xl">📋</span>
                </div>
                <p class="text-3xl font-bold text-green-600">{{ stats?.totalLeads || 0 }}</p>
            </div>

            <div class="bg-purple-50 rounded-lg p-6 border-l-4 border-purple-500 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold text-gray-600">Convertidos</p>
                    <span class="text-2xl">✅</span>
                </div>
                <p class="text-3xl font-bold text-purple-600">{{ stats?.convertedLeads || 0 }}</p>
            </div>

            <div class="bg-orange-50 rounded-lg p-6 border-l-4 border-orange-500 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold text-gray-600">Agentes Activos</p>
                    <span class="text-2xl">👥</span>
                </div>
                <p class="text-3xl font-bold text-orange-600">{{ stats?.activeAgents || 0 }}</p>
            </div>
        </div>

        <!-- 1. Top Agentes Inmobiliarios -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <h4 class="font-semibold text-gray-900 mb-4 text-lg">Top Agentes Inmobiliarios</h4>
            <div class="space-y-3">
                <div v-for="(agent, index) in topAgents" :key="agent.name" class="flex items-center justify-between p-3 bg-gray-50 rounded hover:bg-blue-50 transition">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-500 text-white text-xs font-bold">{{ index + 1 }}</span>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ agent.name }}</p>
                            <p class="text-xs text-gray-500">{{ agent.conversion_rate }}% conversión</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-blue-100 text-blue-700">
                            {{ agent.total_leads }} leads
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-700">
                            {{ agent.converted_leads }} ✓
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Proyectos Más Activos -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <h4 class="font-semibold text-gray-900 mb-4 text-lg">Proyectos Más Activos</h4>
            <div class="space-y-3">
                <div v-for="(project, index) in topProjects" :key="index" class="flex items-center justify-between p-3 bg-gray-50 rounded hover:bg-green-50 transition">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-green-500 text-white text-xs font-bold">{{ index + 1 }}</span>
                        <span class="text-gray-700 font-medium">{{ project.name }}</span>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-700">
                        {{ project.lead_count }} leads
                    </span>
                </div>
            </div>
        </div>

        <!-- 3. Distribución por Ciudades -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-cyan-500">
            <h4 class="font-semibold text-gray-900 mb-4 text-lg">Leads por Ciudad</h4>
            <div class="space-y-3">
                <div v-for="city in leadsByCity" :key="city.city_name" class="flex items-center justify-between p-3 bg-gray-50 rounded">
                    <span class="text-gray-700 font-medium">{{ city.city_name }}</span>
                    <div class="flex items-center gap-3">
                        <div class="w-40 bg-gray-200 rounded-full h-2">
                            <div class="bg-cyan-500 h-2 rounded-full" :style="{width: (city.total / (stats?.totalLeads || 1) * 100) + '%'}"></div>
                        </div>
                        <span class="font-bold text-gray-900 w-8 text-right">{{ city.total }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Leads por Fuente -->
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

        <!-- Resumen por Estado de Leads -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <h4 class="font-semibold text-gray-900 mb-4 text-lg">Resumen por Estado</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div v-for="status in leadsByStatus" :key="status.leadSta_name" class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4 border border-gray-200">
                    <p class="text-sm text-gray-600 mb-2">{{ status.leadSta_name }}</p>
                    <p class="text-3xl font-bold text-gray-800">{{ status.total }}</p>
                </div>
            </div>
        </div>

        <!-- 7. Actividad Reciente -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-pink-500">
            <h4 class="font-semibold text-gray-900 mb-4 text-lg">Actividad Reciente del País</h4>
            <div class="space-y-3 max-h-80 overflow-y-auto">
                <div v-for="activity in recentActivity" :key="activity.id" class="flex items-start justify-between p-3 bg-gray-50 rounded hover:bg-pink-50 transition border-l-3 border-pink-300">
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900">{{ activity.name }}</p>
                        <p class="text-xs text-gray-600 mt-1">Agente: <span class="font-medium">{{ activity.agent }}</span></p>
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

        <!-- Gráfico de Leads por Estado -->
        <Charts
            :is-admin="false"
            :user-leads-by-status="leadsByStatus"
        />
    </div>
</template>
