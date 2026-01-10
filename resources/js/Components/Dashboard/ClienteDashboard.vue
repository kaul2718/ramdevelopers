<script setup>
    import StatCard from '@/Components/Dashboard/StatCard.vue';
    import MyStats from '@/Components/Dashboard/MyStats.vue';

    defineProps({
        stats: Object,
    });
</script>

<template>
    <div class="space-y-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Portal de Cliente</h3>
                    <p class="text-gray-600 text-sm mt-1">Consulta tus leads y proyectos de interés</p>
                </div>
                <div class="text-4xl">🏠</div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <StatCard
                label="Mis Leads"
                :value="stats?.totalLeads || 0"
                color="blue"
                iconPath="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z"
            />
            <StatCard
                label="Proyectos de Interés"
                :value="stats?.totalDevelopments || 0"
                color="green"
                iconPath="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"
            />
        </div>

        <MyStats v-if="stats" :stats="stats" />
    </div>
</template>
