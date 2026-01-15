<template>
  <Link :href="route('development.public.show', development.devt_slug)" class="group bg-white/5 border border-white/10 rounded-xl overflow-hidden hover:border-primary/50 shadow-sm hover:shadow-md transition-all block">
    <div class="relative aspect-[16/9] overflow-hidden">
      <div
        class="w-full h-full bg-slate-800 bg-cover bg-center transition-transform duration-500 group-hover:scale-105"
        :style="{ backgroundImage: `url('${development.image || 'https://via.placeholder.com/600x400?text=Development'}')` }"
      ></div>
      <div class="absolute top-3 right-3">
        <span class="bg-primary text-background-dark text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider">
          {{ development.commercialStatus?.commSta_name || 'Disponible' }}
        </span>
      </div>
    </div>
    <div class="p-5 space-y-3">
      <div class="space-y-1">
        <h3 class="text-white font-bold text-lg group-hover:text-primary transition-colors line-clamp-2">
          {{ development.devt_title }}
        </h3>
        <div class="flex items-center gap-1 text-slate-400 text-sm">
          <span class="material-symbols-outlined text-sm">location_on</span>
          <span class="line-clamp-1">
            {{ development.city?.city_name }}, {{ development.country?.ctry_name }}
          </span>
        </div>
      </div>
      <p class="text-slate-400 text-sm line-clamp-2">{{ development.devt_short_description }}</p>
      <div class="pt-2 border-t border-white/10 flex items-center justify-between">
        <div>
          <p class="text-[10px] uppercase text-slate-500 font-semibold tracking-widest">Desde</p>
          <p class="text-primary font-bold text-lg">{{ formatPrice(development.devt_price_from) }}</p>
          <p v-if="development.devt_price_to && development.devt_price_to !== development.devt_price_from" class="text-[10px] uppercase text-slate-500 font-semibold tracking-widest mt-2">Hasta</p>
          <p v-if="development.devt_price_to && development.devt_price_to !== development.devt_price_from" class="text-primary font-bold text-lg">{{ formatPrice(development.devt_price_to) }}</p>
        </div>
        <button class="size-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-background-dark transition-all">
          <span class="material-symbols-outlined">arrow_forward</span>
        </button>
      </div>
    </div>
  </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  development: {
    type: Object,
    required: true,
  },
});


const formatPrice = (price) => {
  if (!price) return 'Consultar';
  const num = Number(price);
  if (num >= 1000000) {
    return `$${(num / 1000000).toFixed(2)}M`;
  }
  if (num >= 1000) {
    return `$${(num / 1000).toFixed(0)}K`;
  }
  return `$${num.toLocaleString()}`;
};
</script>

<style scoped>
.material-symbols-outlined {
  font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}

.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
