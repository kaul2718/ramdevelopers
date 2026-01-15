<template>
  <div class="min-h-screen bg-background-dark dark">
    <Head title="EstateSync - Modern Real Estate CRM" />
    <slot />
  </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
</script>

<style scoped>
:global(body) {
  @apply bg-background-dark text-white;
  font-family: 'Inter', sans-serif;
}

:global(.dark) {
  color-scheme: dark;
}
</style>
