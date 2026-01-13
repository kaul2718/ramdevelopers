<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import AdminDashboard from '@/Components/Dashboard/AdminDashboard.vue';
    import DevAdminDashboard from '@/Components/Dashboard/DevAdminDashboard.vue';
    import MasterPaisDashboard from '@/Components/Dashboard/MasterPaisDashboard.vue';
    import AgenteInmobiliarioDashboard from '@/Components/Dashboard/AgenteInmobiliarioDashboard.vue';
    import ClienteDashboard from '@/Components/Dashboard/ClienteDashboard.vue';
    import DefaultDashboard from '@/Components/Dashboard/DefaultDashboard.vue';
    import { useUserRole } from '@/Composables/useUserRole.js';
    import { usePage } from '@inertiajs/vue3';

    const page = usePage();
    const { currentRole, getRoleTitle } = useUserRole();

    defineProps({
        isAdmin: Boolean,
        stats: Object,
        usersByCountry: Array,
        leadsByStatus: Array,
        leadsBySource: Array,
        usersByProfile: Array,
        topUsersByDevelopments: Array,
        userLeadsByStatus: Array,
        usersByRole: Array,
        activeUsersNow: Number,
        databaseStats: Object,
        systemAlerts: Array,
        recentActivity: Array,
        topProjectsByLeads: Array,
        monthlyComparison: Object,
        quickSummary: Array,
        topAgents: Array,
        topProjects: Array,
        leadsByCity: Array,
    });
</script>

<template>
    <AppLayout :title="getRoleTitle()">
        <div class="py-12 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- HEADER -->
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                            {{ getRoleTitle() }}
                        </h2>
                        <p class="text-gray-600 text-sm mt-1">Bienvenido, {{ page.props.auth.user.name }} {{ page.props.auth.user.lastname }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">{{ new Date().toLocaleDateString('es-ES', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}</p>
                    </div>
                </div>
                
                <!-- ADMIN DASHBOARD -->
                <AdminDashboard
                    v-if="currentRole === 'Admin'"
                    :stats="stats"
                    :users-by-country="usersByCountry"
                    :leads-by-status="leadsByStatus"
                    :leads-by-source="leadsBySource"
                    :users-by-profile="usersByProfile"
                    :top-users-by-developments="topUsersByDevelopments"
                    :user-leads-by-status="userLeadsByStatus"
                />

                <!-- DEVADMIN DASHBOARD -->
                <DevAdminDashboard
                    v-else-if="currentRole === 'DevAdmin'"
                    :stats="stats"
                    :users-by-role="usersByRole"
                    :active-users-now="activeUsersNow"
                    :database-stats="databaseStats"
                    :system-alerts="systemAlerts"
                />

                <!-- MASTER PAÍS DASHBOARD -->
                <MasterPaisDashboard
                    v-else-if="currentRole === 'Master Pais'"
                    :stats="stats"
                    :top-agents="topAgents"
                    :top-projects="topProjects"
                    :leads-by-city="leadsByCity"
                    :leads-by-source="leadsBySource"
                    :recent-activity="recentActivity"
                    :leads-by-status="leadsByStatus"
                />

                <!-- AGENTE INMOBILIARIO DASHBOARD -->
                <AgenteInmobiliarioDashboard
                    v-else-if="currentRole === 'Agente Inmobiliario'"
                    :stats="stats"
                    :recent-activity="recentActivity"
                    :leads-by-source="leadsBySource"
                    :top-projects-by-leads="topProjectsByLeads"
                    :monthly-comparison="monthlyComparison"
                    :quick-summary="quickSummary"
                    :user-leads-by-status="userLeadsByStatus"
                />

                <!-- CLIENTE DASHBOARD -->
                <ClienteDashboard
                    v-else-if="currentRole === 'Cliente'"
                    :stats="stats"
                />

                <!-- DEFAULT DASHBOARD -->
                <DefaultDashboard
                    v-else
                    :stats="stats"
                    :user-leads-by-status="userLeadsByStatus"
                />

            </div>
        </div>
    </AppLayout>
</template>
