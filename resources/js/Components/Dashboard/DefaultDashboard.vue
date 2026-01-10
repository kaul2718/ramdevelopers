<script setup>
    import MyStats from '@/Components/Dashboard/MyStats.vue';
    import Charts from '@/Components/Dashboard/Charts.vue';

    defineProps({
        stats: Object,
        userLeadsByStatus: Array,
    });
</script>

<template>
    <div class="space-y-8">
        <MyStats v-if="stats" :stats="stats" />
        <Charts
            :is-admin="false"
            :user-leads-by-status="userLeadsByStatus"
        />
    </div>
</template>
