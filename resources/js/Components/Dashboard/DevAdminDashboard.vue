<script setup>
    defineProps({
        stats: Object,
        usersByRole: Array,
        activeUsersNow: Number,
        databaseStats: Object,
        systemAlerts: Array,
    });
</script>

<template>
    <div class="space-y-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Sistema en Óptimas Condiciones</h3>
                    <p class="text-gray-600 text-sm mt-1">Administración de roles, permisos y configuración</p>
                </div>
                <div class="text-4xl">⚙️</div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold text-gray-900">Roles del Sistema</h4>
                    <span class="text-2xl">👥</span>
                </div>
                <p class="text-3xl font-bold text-blue-600">5</p>
                <p class="text-sm text-gray-600 mt-2">Admin, DevAdmin, Master País, Agente, Cliente</p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold text-gray-900">Permisos</h4>
                    <span class="text-2xl">🔐</span>
                </div>
                <p class="text-3xl font-bold text-green-600">{{ stats?.totalUsers || '50+' }}</p>
                <p class="text-sm text-gray-600 mt-2">Permisos configurados</p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold text-gray-900">Usuarios Activos</h4>
                    <span class="text-2xl">🟢</span>
                </div>
                <p class="text-3xl font-bold text-green-600">{{ activeUsersNow || 0 }}</p>
                <p class="text-sm text-gray-600 mt-2">Últimos 30 Días</p>
            </div>
        </div>

        <!-- 1. Distribución de Usuarios por Rol -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h4 class="font-semibold text-gray-900 mb-4">Distribución de Usuarios por Rol</h4>
            <div class="space-y-3">
                <div v-for="role in usersByRole" :key="role.role_name" class="flex items-center justify-between p-3 bg-gray-50 rounded">
                    <span class="text-gray-700">{{ role.role_name }}</span>
                    <div class="flex items-center gap-3">
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" :style="{width: (role.total / (stats?.totalUsers || 1) * 100) + '%'}"></div>
                        </div>
                        <span class="font-semibold text-gray-900 w-8 text-right">{{ role.total }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. Estadísticas de Base de Datos -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
                <p class="text-sm text-gray-600">Usuarios</p>
                <p class="text-2xl font-bold text-blue-600">{{ databaseStats?.totalUsers || 0 }}</p>
            </div>
            <div class="bg-green-50 rounded-lg p-4 border-l-4 border-green-500">
                <p class="text-sm text-gray-600">Proyectos</p>
                <p class="text-2xl font-bold text-green-600">{{ databaseStats?.totalDevelopments || 0 }}</p>
            </div>
            <div class="bg-purple-50 rounded-lg p-4 border-l-4 border-purple-500">
                <p class="text-sm text-gray-600">Leads</p>
                <p class="text-2xl font-bold text-purple-600">{{ databaseStats?.totalLeads || 0 }}</p>
            </div>
            <div class="bg-amber-50 rounded-lg p-4 border-l-4 border-amber-500">
                <p class="text-sm text-gray-600">Desarrolladores</p>
                <p class="text-2xl font-bold text-amber-600">{{ databaseStats?.totalDevelopers || 0 }}</p>
            </div>
            <div class="bg-indigo-50 rounded-lg p-4 border-l-4 border-indigo-500">
                <p class="text-sm text-gray-600">Países</p>
                <p class="text-2xl font-bold text-indigo-600">{{ databaseStats?.totalCountries || 0 }}</p>
            </div>
        </div>

        <!-- 7. Alertas del Sistema -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h4 class="font-semibold text-gray-900 mb-4">Estado del Sistema</h4>
            <div class="space-y-3">
                <div 
                    v-for="(alert, index) in systemAlerts" 
                    :key="index"
                    :class="{
                        'bg-green-50 border-l-4 border-green-500': alert.type === 'success',
                        'bg-yellow-50 border-l-4 border-yellow-500': alert.type === 'warning',
                        'bg-blue-50 border-l-4 border-blue-500': alert.type === 'info'
                    }"
                    class="p-4 rounded"
                >
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">{{ alert.icon }}</span>
                        <span :class="{
                            'text-green-700': alert.type === 'success',
                            'text-yellow-700': alert.type === 'warning',
                            'text-blue-700': alert.type === 'info'
                        }">{{ alert.message }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
