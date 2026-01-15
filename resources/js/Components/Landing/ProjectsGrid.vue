<script setup>
import { Link } from '@inertiajs/vue3'
import ProjectCard from '@/Components/Landing/ProjectCard.vue';

defineProps({
    developments: {
        type: Array,
        default: () => []
    }
});
</script>
<template>
    <div class="w-full flex justify-center">
        <div class="w-full max-w-[1200px] px-6 py-12 flex flex-col gap-12">
            <!-- Section Header -->
            <div class="flex justify-between items-end pb-10 border-b border-white/10">
                <div>
                    <p class="text-primary font-bold text-sm uppercase tracking-widest mb-2">Mercado</p>
                    <h2 class="text-white text-4xl font-bold leading-tight tracking-tight">
                        Proyectos Destacados
                    </h2>
                </div>
                <Link href="/developments" class="flex items-center gap-2 text-primary font-bold hover:underline">
                    Ver todos los proyectos <span class="material-symbols-outlined">arrow_forward</span>
                </Link>
            </div>

            <!-- Featured Projects Grid -->
            <div id="developments">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <ProjectCard v-for="development in developments" :key="development.devt_id"
                        :devt-slug="development.devt_slug"
                        :title="development.devt_title" 
                        :priceFrom="development.devt_price_from"
                        :priceTo="development.devt_price_to"
                        :location="`${development.city?.city_name || 'N/A'}, ${development.country?.ctry_name || 'N/A'}`"
                        :image="development.image || 'https://via.placeholder.com/600x400?text=Development'"
                        :status="development.status || 'Disponible'"
                        :type="development.housingType?.houTyp_name || 'Residencial'" />
                </div>
            </div>
        </div>
    </div>
</template>
