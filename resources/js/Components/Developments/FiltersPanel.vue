<script setup>
import { ref, watch, computed } from 'vue'

const props = defineProps({
    search: String,
    country: [String, Number],
    city: [String, Number],
    housingType: [String, Number],
    priceFrom: [String, Number],
    priceTo: [String, Number],
    countries: Array,
    cities: Array,
    housingTypes: Array,
})

const emit = defineEmits(['apply', 'update:search', 'update:country', 'update:city', 'update:housingType', 'update:priceFrom', 'update:priceTo'])

const searchQuery = ref(props.search || '')
const selectedCountry = ref(props.country || '')
const selectedCity = ref(props.city || '')
const selectedHousingType = ref(props.housingType || '')
const priceFrom = ref(props.priceFrom || '')
const priceTo = ref(props.priceTo || '')

// Filtrar ciudades según el país seleccionado
const filteredCities = computed(() => {
    if (!selectedCountry.value) {
        return []
    }
    return props.cities.filter(city => city.ctry_id === Number(selectedCountry.value))
})

// Sincronizar cambios externos
watch(() => props.search, (newVal) => searchQuery.value = newVal || '')
watch(() => props.country, (newVal) => selectedCountry.value = newVal || '')
watch(() => props.city, (newVal) => selectedCity.value = newVal || '')
watch(() => props.housingType, (newVal) => selectedHousingType.value = newVal || '')
watch(() => props.priceFrom, (newVal) => priceFrom.value = newVal || '')
watch(() => props.priceTo, (newVal) => priceTo.value = newVal || '')

// Limpiar ciudad cuando cambia el país
watch(selectedCountry, () => {
    selectedCity.value = ''
})

const applyFilters = () => {
    emit('apply', {
        search: searchQuery.value,
        country: selectedCountry.value,
        city: selectedCity.value,
        housingType: selectedHousingType.value,
        priceFrom: priceFrom.value,
        priceTo: priceTo.value,
    })
}
</script>

<template>
    <aside class="w-full lg:w-64 flex-shrink-0">
        <div class="bg-white/5 border border-white/10 rounded-xl p-6 sticky top-24">
            <div class="mb-6">
                <h1 class="text-white text-lg font-bold">Filtrar Resultados</h1>
                <p class="text-slate-400 text-xs">Refina tu búsqueda</p>
            </div>
            <div class="space-y-6">
                <!-- Search -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-slate-300 text-sm font-semibold">
                        <span class="material-symbols-outlined text-[20px]">search</span> Buscar
                    </label>
                    <input v-model="searchQuery" type="text" placeholder="Nombre del proyecto..."
                        class="w-full bg-white/5 border border-white/10 rounded-lg text-sm text-white placeholder-slate-500 focus:ring-primary focus:border-primary" />
                </div>

                <!-- Country -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-slate-300 text-sm font-semibold">
                        <span class="material-symbols-outlined text-[20px]">public</span> País
                    </label>
                    <select v-model="selectedCountry"
                        class="w-full bg-white/5 border border-white/10 rounded-lg text-sm text-white focus:ring-primary focus:border-primary">
                        <option value="" class="bg-[#191e2b] text-white">
                            Todos los países
                        </option>

                        <option v-for="country in countries" :key="country.ctry_id" :value="country.ctry_id"
                            class="bg-[#191e2b] text-white">
                            {{ country.ctry_name }}
                        </option>
                    </select>
                </div>

                <!-- City -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-slate-300 text-sm font-semibold" :class="{ 'opacity-50': !selectedCountry }">
                        <span class="material-symbols-outlined text-[20px]">location_on</span> Ciudad
                    </label>

                    <select v-model="selectedCity" :disabled="!selectedCountry"
                        class="w-full bg-white/5 border border-white/10 rounded-lg text-sm text-white px-3 py-2 focus:ring-1 focus:ring-primary focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed"
                        :class="{ 'opacity-50': !selectedCountry }">
                        <option value="" class="bg-[#191e2b] text-white">
                            {{ selectedCountry ? 'Todas las ciudades' : 'Selecciona un país primero' }}
                        </option>

                        <option v-for="city in filteredCities" :key="city.city_id" :value="city.city_id"
                            class="bg-[#191e2b] text-white">
                            {{ city.city_name }}
                        </option>
                    </select>
                </div>

                <!-- Housing Type -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-slate-300 text-sm font-semibold">
                        <span class="material-symbols-outlined text-[20px]">apartment</span> Tipo
                    </label>

                    <select v-model="selectedHousingType"
                        class="w-full bg-white/5 border border-white/10 rounded-lg text-sm text-white px-3 py-2 focus:ring-1 focus:ring-primary focus:outline-none">
                        <option value="" class="bg-[#191e2b] text-white">
                            Todos los tipos
                        </option>

                        <option v-for="type in housingTypes" :key="type.houTyp_id" :value="type.houTyp_id"
                            class="bg-[#191e2b] text-white">
                            {{ type.houTyp_name }}
                        </option>
                    </select>
                </div>


                <!-- Price Range -->
                <div class="space-y-4">
                    <label class="flex items-center gap-2 text-slate-300 text-sm font-semibold">
                        <span class="material-symbols-outlined text-[20px]">payments</span> Rango de Precio
                    </label>
                    <div class="space-y-2">
                        <input v-model="priceFrom" type="number" placeholder="Desde"
                            class="w-full bg-white/5 border border-white/10 rounded-lg text-sm text-white placeholder-slate-500 focus:ring-primary focus:border-primary" />
                        <input v-model="priceTo" type="number" placeholder="Hasta"
                            class="w-full bg-white/5 border border-white/10 rounded-lg text-sm text-white placeholder-slate-500 focus:ring-primary focus:border-primary" />
                    </div>
                </div>

                <button @click="applyFilters"
                    class="w-full bg-primary hover:bg-primary/90 text-background-dark font-bold py-3 rounded-lg text-sm transition-all">
                    Aplicar Filtros
                </button>
            </div>
        </div>
    </aside>
</template>
