<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'

const mobileOpen = ref(false)
</script>

<style scoped>
.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: all 0.25s ease;
}
.fade-slide-enter-from,
.fade-slide-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>

<template>
    <div
        class="w-full flex justify-center border-b border-white/10 backdrop-blur-md sticky top-0 z-50 bg-background-dark/80">
        
        <div class="flex flex-col w-full max-w-[1200px]">
            <!-- HEADER -->
            <header class="flex items-center justify-between px-6 h-20">
                
                <!-- LOGO -->
                <div class="flex items-center gap-3">
                    <img
                        src="/images/logo2.png"
                        alt="Logo"
                        class="h-10 w-36 sm:h-12 sm:w-44 md:h-14 md:w-52 lg:h-16 lg:w-60 object-contain drop-shadow-md"
                    />
                </div>

                <!-- DESKTOP NAV -->
                <div class="hidden md:flex items-center gap-10">
                    <nav class="flex items-center gap-8">
                        <Link :href="route('developments.public')"
                            class="text-gray-300 hover:text-primary transition-colors text-sm font-medium">
                            Proyectos
                        </Link>
                        <a href="#features"
                            class="text-gray-300 hover:text-primary transition-colors text-sm font-medium">
                            Promotores
                        </a>
                        <a href="#advisors"
                            class="text-gray-300 hover:text-primary transition-colors text-sm font-medium">
                            Nosotros
                        </a>
                        <a href="#pricing"
                            class="text-gray-400 hover:text-primary transition-colors text-sm font-semibold">
                            Contáctanos
                        </a>
                    </nav>

                    <!-- CTA DESKTOP -->
                    <div class="flex gap-3">
                        <Link
                            :href="route('register')"
                            class="flex min-w-[120px] items-center justify-center rounded-lg h-10 px-5
                                   bg-primary text-[#0c1a1d] text-sm font-bold
                                   hover:brightness-110 transition-all active:scale-95">
                            Registrarse
                        </Link>

                        <Link
                            :href="route('login')"
                            class="flex min-w-[120px] items-center justify-center rounded-lg h-10 px-5
                                   bg-white/10 text-white text-sm font-bold border border-white/10
                                   hover:bg-white/20 transition-all">
                            Iniciar Sesión
                        </Link>
                    </div>
                </div>

                <!-- MOBILE BUTTON -->
                <button
                    @click="mobileOpen = !mobileOpen"
                    class="md:hidden text-white p-2 rounded-lg hover:bg-white/10 transition"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </header>

            <!-- MOBILE MENU -->
            <transition name="fade-slide">
                <div
                    v-if="mobileOpen"
                    class="md:hidden bg-background-dark/95 backdrop-blur-md border-t border-white/10 px-6 py-6 space-y-6"
                >
                    <nav class="flex flex-col gap-4">
                        <Link :href="route('developments.public')" class="text-gray-300 hover:text-primary text-base font-medium">
                            Proyectos
                        </Link>
                        <a href="#features" class="text-gray-300 hover:text-primary text-base font-medium">
                            Promotores
                        </a>
                        <a href="#advisors" class="text-gray-300 hover:text-primary text-base font-medium">
                            Nosotros
                        </a>
                        <a href="#pricing" class="text-gray-300 hover:text-primary text-base font-semibold">
                            Contáctanos
                        </a>
                    </nav>

                    <div class="flex flex-col gap-3">
                        <Link
                            :href="route('register')"
                            class="w-full h-11 rounded-lg bg-primary text-[#0c1a1d]
                                   text-sm font-bold flex items-center justify-center
                                   hover:brightness-110 transition-all">
                            Registrarse
                        </Link>

                        <Link
                            :href="route('login')"
                            class="w-full h-11 rounded-lg bg-white/10 text-white
                                   text-sm font-bold border border-white/10
                                   hover:bg-white/20 transition-all flex items-center justify-center">
                            Iniciar Sesión
                        </Link>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>