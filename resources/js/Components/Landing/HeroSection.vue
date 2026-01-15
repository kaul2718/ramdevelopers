<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    developers: {
        type: Array,
        default: () => []
    }
});

const scrollToSection = (sectionId) => {
    const element = document.getElementById(sectionId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
};
</script>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>

<template>
    <div class="w-full max-w-[1200px] px-6">
        <div class="@container">
            <div class="flex flex-col gap-10 py-20 md:flex-row md:items-center">
                <div class="flex flex-col gap-8 flex-1 md:max-w-[50%]">
                    <div class="flex flex-col gap-4">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 border border-primary/20 w-fit">
                            <span class="text-primary text-xs font-bold uppercase tracking-wider">
                                Tecnología inmobiliaria de próxima generación
                            </span>
                        </div>
                        <h1 class="text-white text-5xl font-black leading-tight tracking-tight @[480px]:text-6xl">
                            Eleva tu desarrollo inmobiliario con
                            <span class="text-primary">CRM basado en datos</span>
                        </h1>
                        <p class="text-gray-400 text-lg font-normal leading-relaxed max-w-[540px]">
                            El portal todo en uno para que los desarrolladores modernos administren
                            proyectos verificados, se conecten con asesores profesionales y mantener la
                            total transparencia del mercado.
                        </p>
                    </div>
                    <!-- <div class="flex flex-wrap gap-4">
                        <Link :href="route('register')"
                            class="flex min-w-[160px] cursor-pointer items-center justify-center rounded-lg h-14 px-8 bg-primary text-[#0c1a1d] text-base font-bold transition-all hover:shadow-[0_0_20px_rgba(0,195,235,0.4)]">
                            Iniciar Prueba Gratis
                        </Link>
                        <button @click="scrollToSection('developments')"
                            class="flex min-w-[160px] cursor-pointer items-center justify-center rounded-lg h-14 px-8 bg-white/5 text-white text-base font-bold border border-white/10 hover:bg-white/10 transition-all">
                            Ver Demo
                        </button>
                    </div> -->
                    <div class="flex items-center gap-4 pt-4">
                        <div class="flex -space-x-3">
                            <template v-if="developers.length > 0">
                                <div v-for="(developer, index) in developers.slice(0, 3)" :key="developer.devr_id"
                                    class="h-10 w-10 rounded-full border-2 border-background-dark bg-gray-600" :style="developer.profile_photo
                                        ? { backgroundImage: `url('${developer.profile_photo}')`, backgroundSize: 'cover', backgroundPosition: 'center' }
                                        : {}" :title="developer.devr_commercial_name">
                                </div>
                            </template>
                            <template v-else>
                                <div class="h-10 w-10 rounded-full border-2 border-background-dark bg-gray-600"></div>
                                <div class="h-10 w-10 rounded-full border-2 border-background-dark bg-gray-500"></div>
                                <div class="h-10 w-10 rounded-full border-2 border-background-dark bg-gray-400"></div>
                            </template>
                        </div>
                        <p class="text-gray-400 text-sm">
                            Unido por <span class="text-white font-bold">50+</span> desarrolladores líderes
                        </p>
                    </div>
                </div>
                <div class="flex-1 w-full md:max-w-[50%]">
                    <div class="relative">
                        <div
                            class="absolute -inset-1 bg-gradient-to-r from-primary to-blue-600 rounded-2xl blur opacity-25">
                        </div>
                        <div
                            class="relative w-full aspect-[16/12] rounded-2xl overflow-hidden border border-white/10 shadow-2xl">
                            <img src="/images/fondo_landing.jpg" alt="Fondo landing"
                                class="w-full h-full object-cover">                        
                        <div
                            class="absolute bottom-4 left-4 right-4 bg-background-dark/90 backdrop-blur-md p-4 rounded-lg border border-white/10 flex justify-between items-center">
                            <div>
                                <p class="text-xs text-primary font-bold uppercase tracking-widest">Perspectiva
                                    activa</p>
                                <p class="text-white text-sm font-medium">Retorno de la inversión: +12,4 % anual
                                </p>
                            </div>
                            <span class="material-symbols-outlined text-primary">trending_up</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</template>
