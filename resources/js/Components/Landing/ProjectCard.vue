<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  devtSlug: {
    type: String,
    required: true,
  },
  title: {
    type: String,
    required: true,
  },
  priceFrom: {
    type: [Number, String],
    default: null,
  },
  priceTo: {
    type: [Number, String],
    default: null,
  },
  location: {
    type: String,
    required: true,
  },
  image: {
    type: String,
    default: 'https://via.placeholder.com/600x400?text=Development',
  },
  status: {
    type: String,
    required: true,
  },
  type: {
    type: String,
    required: true,
  },
  currency: {
    type: Object,
    default: () => ({ curr_symbol: '$', curr_symbol_first: true, curr_code: 'USD' }),
  },
});

const formatPrice = (price, currency) => {
  if (!price) return 'Consultar';
  const num = Number(price);
  let formatted = '';

  if (num >= 1000000) {
    formatted = `${(num / 1000000).toFixed(2)}M`;
  } else if (num >= 1000) {
    formatted = `${(num / 1000).toFixed(0)}K`;
  } else {
    formatted = num.toLocaleString();
  }

  const symbol = currency?.curr_symbol || '$';
  const symbolFirst = currency?.curr_symbol_first ?? true;

  return symbolFirst ? `${symbol}${formatted}` : `${formatted} ${symbol}`;
};
</script>

<style scoped>
.material-symbols-outlined {
  font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>

<template>
  <div class="flex flex-col gap-5 group cursor-pointer">
    <div class="relative overflow-hidden rounded-xl border border-white/10">
      <div
        class="w-full bg-slate-800 aspect-[16/10] bg-cover bg-center transition-transform duration-500 group-hover:scale-110"
        :style="{ backgroundImage: `url('${image}')` }"></div>
      <div
        class="absolute inset-0 bg-gradient-to-t from-background-dark/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-6">
        <Link
          :href="route('development.public.show', devtSlug)"
          class="w-full bg-white text-[#191e2b] py-2.5 rounded-lg font-bold text-sm hover:bg-gray-100 transition-colors text-center">
          Ver Detalles
        </Link>
      </div>
    </div>
    <div class="flex flex-col gap-2">
      <h3 class="text-white text-xl font-bold group-hover:text-primary transition-colors">{{ title }}</h3>
      <p class="text-gray-400 text-sm flex items-center gap-1">
        <span class="material-symbols-outlined text-sm">location_on</span>
        {{ location }}
      </p>
      <div class="pt-2 border-t border-white/10 flex items-center justify-between">
        <div>
          <p class="text-[10px] uppercase text-slate-500 font-semibold tracking-widest">Desde</p>
          <p class="text-primary font-bold text-lg">{{ formatPrice(priceFrom, currency) }}</p>
          <p v-if="priceTo && priceTo !== priceFrom" class="text-[10px] uppercase text-slate-500 font-semibold tracking-widest mt-2">Hasta</p>
          <p v-if="priceTo && priceTo !== priceFrom" class="text-primary font-bold text-lg">{{ formatPrice(priceTo, currency) }}</p>
        </div>
        <div class="flex flex-col gap-3 text-right">
          <div>
            <span class="text-xs text-gray-500 uppercase block">Estado</span>
            <span class="text-white text-sm font-medium">{{ status }}</span>
          </div>
          <div>
            <span class="text-xs text-gray-500 uppercase block">Tipo</span>
            <span class="text-white text-sm font-medium">{{ type }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
