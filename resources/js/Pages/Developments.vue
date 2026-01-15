<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import LandingHeader from '@/Components/Landing/LandingHeader.vue';
import LandingFooter from '@/Components/Landing/LandingFooter.vue';
import DevelopmentCard from '@/Components/Developments/DevelopmentCard.vue';
import FiltersPanel from '@/Components/Developments/FiltersPanel.vue';

defineOptions({
    layout: false,
});

const props = defineProps({
    developments: Array,
    pagination: Object,
    countries: Array,
    cities: Array,
    housingTypes: Array,
    filters: Object,
});


const applyFilters = (filterData) => {
    router.get(route('developments.public'), {
        search: filterData.search || undefined,
        country: filterData.country || undefined,
        city: filterData.city || undefined,
        housing_type: filterData.housingType || undefined,
        price_from: filterData.priceFrom || undefined,
        price_to: filterData.priceTo || undefined,
    }, { preserveScroll: true });
};

const goToPage = (page) => {
    router.get(route('developments.public'), {
        search: props.filters.search,
        country: props.filters.country,
        city: props.filters.city,
        housing_type: props.filters.housing_type,
        price_from: props.filters.price_from,
        price_to: props.filters.price_to,
        page: page
    }, { preserveScroll: true });
};
</script>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>

<template>
    <div class="relative flex min-h-screen w-full flex-col bg-background-dark group/design-root overflow-x-hidden"
        :class="{ dark: true }">

        <Head title="Proyectos" />

        <!-- Navigation Bar -->
        <LandingHeader />

        <main class="flex-1 w-full">
            <!-- Breadcrumbs -->
            <div class="max-w-[1200px] mx-auto px-6 py-8">
                <div class="flex items-center gap-2">
                    <Link href="/" class="text-primary/70 hover:text-primary text-sm font-medium">Inicio</Link>
                    <span class="material-symbols-outlined text-sm text-slate-400">chevron_right</span>
                    <span class="text-slate-400 text-sm font-medium">Proyectos</span>
                </div>
            </div>

            <div class="max-w-[1440px] mx-auto px-6 flex flex-col lg:flex-row gap-8 pb-20">
                <!-- Sidebar Filters -->
                <FiltersPanel :search="props.filters?.search" :country="props.filters?.country"
                    :city="props.filters?.city" :housing-type="props.filters?.housing_type"
                    :price-from="props.filters?.price_from" :price-to="props.filters?.price_to" :countries="countries"
                    :cities="cities" :housing-types="housingTypes" @apply="applyFilters" />

                <!-- Main Content Area -->
                <div class="flex-1">
                    <!-- Page Heading -->
                    <div class="mb-8">
                        <h2 class="text-white text-3xl font-black tracking-tight">Proyectos Disponibles</h2>
                        <p class="text-slate-400 mt-1">{{ pagination.total }} proyectos encontrados</p>
                    </div>

                    <!-- Grid -->
                    <div v-if="developments.length > 0" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        <DevelopmentCard v-for="development in developments" :key="development.devt_id"
                            :development="development" />
                    </div>

                    <!-- Empty State -->
                    <div v-else class="flex flex-col items-center justify-center py-16 text-center">
                        <span class="material-symbols-outlined text-6xl text-slate-600 mb-4">search_off</span>
                        <h3 class="text-white text-xl font-bold mb-2">No hay proyectos</h3>
                        <p class="text-slate-400">Intenta ajustar tus filtros de búsqueda</p>
                    </div>

                    <!-- Pagination -->
                    <div v-if="pagination.last_page > 1" class="mt-12 flex justify-center items-center gap-2">
                        <button v-if="pagination.current_page > 1" @click="goToPage(pagination.current_page - 1)"
                            class="size-10 rounded-lg border border-white/10 flex items-center justify-center text-slate-400 hover:border-primary hover:text-primary transition-all">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </button>

                        <template v-for="page in Array.from({ length: pagination.last_page }, (_, i) => i + 1)">
                            <button
                                v-if="page <= 3 || page === pagination.last_page || (page >= pagination.current_page - 1 && page <= pagination.current_page + 1)"
                                :key="page" @click="goToPage(page)" :class="[
                                    'size-10 rounded-lg font-bold text-sm transition-all',
                                    page === pagination.current_page
                                        ? 'bg-primary text-background-dark'
                                        : 'border border-white/10 text-slate-400 hover:border-primary hover:text-primary'
                                ]">
                                {{ page }}
                            </button>
                            <span v-else-if="page === 4 && pagination.last_page > 5" :key="`dots-${page}`"
                                class="px-2 text-slate-400">...</span>
                        </template>

                        <button v-if="pagination.current_page < pagination.last_page"
                            @click="goToPage(pagination.current_page + 1)"
                            class="size-10 rounded-lg border border-white/10 flex items-center justify-center text-slate-400 hover:border-primary hover:text-primary transition-all">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </button>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <LandingFooter />
    </div>
</template>
