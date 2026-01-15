<script setup>
import FeatureCard from '@/Components/Landing/FeatureCard.vue';
</script>

<style scoped>
</style>

<template>
    <div id="features" class="w-full max-w-[1200px] px-6 py-24">
        <div class="flex flex-col gap-16 @container">
            <div class="flex flex-col gap-4 text-center items-center">
                <h2 class="text-white text-4xl font-black leading-tight @[480px]:text-5xl max-w-[720px]">
                    ¿Por qué los desarrolladores líderes nos eligen?
                </h2>
                <div class="h-1.5 w-24 bg-primary rounded-full"></div>
                <p class="text-gray-400 text-lg font-normal leading-normal max-w-[640px]">
                    Construido para transparencia y excelencia profesional en desarrollo de propiedades.
                    Asegura tus inversiones con datos en los que puedas confiar.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <FeatureCard icon="verified_user" title="Proyectos Verificados"
                    description="Cada listado se somete a una rigurosa verificación de 50 puntos incluyendo auditorías legales y antecedentes de desarrolladores." />
                <FeatureCard icon="groups" title="Asesores Profesionales"
                    description="Acceso directo a veteranos de la industria para crecimiento estratégico y consultoría personalizada en cada etapa." />
                <FeatureCard icon="visibility" title="Transparencia Total"
                    description="Feeds de datos en tiempo real y canales de comunicación cifrados proporcionan claridad absoluta para todos los interesados." />
            </div>
        </div>
    </div>
</template>

