<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import StatCard from '@/Components/Dashboard/StatCard.vue';
    import MyStats from '@/Components/Dashboard/MyStats.vue';
    import Charts from '@/Components/Dashboard/Charts.vue';
    import TopUsersTable from '@/Components/Dashboard/TopUsersTable.vue';

    defineProps({
        isAdmin: Boolean,
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
    <AppLayout :title="isAdmin ? 'Dashboard Admin' : 'Mi Dashboard'">
        <template #header>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ isAdmin ? 'Dashboard - Administrador' : 'Mi Dashboard' }}
            </h2>
        </template>

        <div class="py-12 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- ESTADÍSTICAS RÁPIDAS (ADMIN) -->
                <div v-if="isAdmin" class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <StatCard
                        label="Total Usuarios"
                        :value="stats?.totalUsers || 0"
                        color="blue"
                        iconPath="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM4 20h16a2 2 0 002-2v-2a2 2 0 00-2-2H4a2 2 0 00-2 2v2a2 2 0 002 2z"
                    />
                    <StatCard
                        label="Promotores"
                        :value="stats?.activeDevelopers || 0"
                        color="green"
                        iconPath="M10 20l4-16m4 4l4 4m0 6H4m16 0a2 2 0 11-4 0 2 2 0 014 0z"
                    />
                    <StatCard
                        label="Total Leads"
                        :value="stats?.totalLeads || 0"
                        color="purple"
                        iconPath="M13 10V3L4 14h7v7l9-11h-7z"
                    />
                    <StatCard
                        label="Proyectos"
                        :value="stats?.totalDevelopments || 0"
                        color="amber"
                        iconPath="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"
                    />
                </div>

                <!-- MIS ESTADÍSTICAS (Usuario Regular) -->
                <MyStats v-if="!isAdmin" :stats="stats" class="mb-8" />

                <!-- GRÁFICAS -->
                <Charts
                    class="mb-8"
                    :is-admin="isAdmin"
                    :users-by-country="usersByCountry"
                    :leads-by-status="leadsByStatus"
                    :leads-by-source="leadsBySource"
                    :users-by-profile="usersByProfile"
                    :user-leads-by-status="userLeadsByStatus"
                />

                <!-- TOP 10 USUARIOS (Admin) -->
                <TopUsersTable v-if="isAdmin" :users="topUsersByDevelopments" />
            </div>
        </div>
    </AppLayout>
</template>
