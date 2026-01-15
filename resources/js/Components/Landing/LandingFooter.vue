<script setup>
</script>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>


<template>
    <div class="w-full bg-slate-900/50 mt-20 border-t border-white/10 flex justify-center py-20">
        <div class="w-full max-w-[1200px] px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div class="col-span-1 md:col-span-2 flex flex-col gap-6">
                    <!-- LOGO -->
                    <div class="flex items-center gap-3">
                        <img src="/images/logo2.png" alt="Logo"
                            class="h-10 w-36 sm:h-12 sm:w-44 md:h-14 md:w-52 lg:h-16 lg:w-60 object-contain drop-shadow-md" />
                    </div>
                    
                    <p class="text-gray-400 max-w-sm">
                        La plataforma líder para el desarrollo inmobiliario institucional. Construido por
                        desarrolladores, para desarrolladores.
                    </p>
                    <div class="flex gap-4">
                        <div
                            class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:text-primary hover:border-primary transition-all cursor-pointer">
                            <span class="material-symbols-outlined">share</span>
                        </div>
                        <div
                            class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:text-primary hover:border-primary transition-all cursor-pointer">
                            <span class="material-symbols-outlined">language</span>
                        </div>
                        <div
                            class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:text-primary hover:border-primary transition-all cursor-pointer">
                            <span class="material-symbols-outlined">mail</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-6">
                    <h4 class="text-white font-bold text-lg">Productos</h4>
                    <nav class="flex flex-col gap-4 text-gray-400 text-sm">
                        <a class="hover:text-white transition-colors" href="#developments">Proyectos</a>
                        <a class="hover:text-white transition-colors" href="#features">Características</a>
                        <a class="hover:text-white transition-colors" href="#advisors">Asesores Estratégicos</a>
                        <a class="hover:text-white transition-colors" href="#pricing">Planes de Precios</a>
                    </nav>
                </div>
                <div class="flex flex-col gap-6">
                    <h4 class="text-white font-bold text-lg">Boletín</h4>
                    <p class="text-gray-400 text-sm">Obtenga las últimas novedades sobre desarrollo inmobiliario
                        semanalmente.</p>
                    <div class="flex gap-2">
                        <input
                            class="bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-sm focus:ring-1 focus:ring-primary focus:outline-none w-full"
                            placeholder="Email" type="email" />
                        <button
                            class="bg-primary text-[#191e2b] px-4 py-2 rounded-lg font-bold text-sm hover:shadow-lg transition-all">
                            Unirse
                        </button>
                    </div>
                </div>
            </div>
            <div
                class="mt-20 pt-10 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-6 text-gray-500 text-xs">
                <p>© 2026 Ram Developers. Todos los derechos reservados.</p>
                <div class="flex gap-8">
                    <a class="hover:text-white" href="#">Política de Privacidad</a>
                    <a class="hover:text-white" href="#">Términos de Servicio</a>
                    <a class="hover:text-white" href="#">Configuración de Cookies</a>
                </div>
            </div>
        </div>
    </div>
</template>
