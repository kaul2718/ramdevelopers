<script setup>
defineProps({
  icon: {
    type: String,
    required: true,
  },
  title: {
    type: String,
    required: true,
  },
  description: {
    type: String,
    required: true,
  },
});
</script>
<template>
  <div
    class="flex flex-col gap-6 rounded-xl border border-white/10 bg-white/5 p-8 hover:border-primary/50 transition-all group">
    <div
      class="w-14 h-14 rounded-lg bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-[#191e2b] transition-all">
      <span class="material-symbols-outlined" style="font-size: 32px;">{{ icon }}</span>
    </div>
    <div class="flex flex-col gap-3">
      <h3 class="text-white text-xl font-bold leading-tight">{{ title }}</h3>
      <p class="text-gray-400 text-base font-normal leading-relaxed">{{ description }}</p>
    </div>
  </div>
</template>

<style scoped>
.material-symbols-outlined {
  font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>
