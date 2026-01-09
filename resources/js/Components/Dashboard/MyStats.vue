<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <StatCard
            label="Mis Promotores"
            :value="stats?.userDevelopers || 0"
            color="green"
            iconPath="M10 20l4-16m4 4l4 4m0 6H4m16 0a2 2 0 11-4 0 2 2 0 014 0z"
        />
        <StatCard
            label="Mis proyectos"
            :value="stats?.userDevelopments || 0"
            color="blue"
            iconPath="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"
        />
    </div>
</template>

<script setup>
import StatCard from './StatCard.vue';

defineProps({
    stats: {
        type: Object,
        required: true
    }
});
</script>
