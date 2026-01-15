<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import LandingHeader from '@/Components/Landing/LandingHeader.vue';
import HeroSection from '@/Components/Landing/HeroSection.vue';
import TrustedBySection from '@/Components/Landing/TrustedBySection.vue';
import FeaturesSection from '@/Components/Landing/FeaturesSection.vue';
import ProjectsGrid from '@/Components/Landing/ProjectsGrid.vue';
import LandingFooter from '@/Components/Landing/LandingFooter.vue';

defineOptions({
    layout: false,
});

const developments = ref([]);
const developers = ref([]);

onMounted(async () => {
    try {
        const [devResponse, devrsResponse] = await Promise.all([
            fetch('/api/developments/featured'),
            fetch('/api/developers/featured')
        ]);
        
        if (devResponse.ok) {
            developments.value = await devResponse.json();
        }
        if (devrsResponse.ok) {
            developers.value = await devrsResponse.json();
        }
    } catch (error) {
        console.error('Error fetching landing data:', error);
    }
});
</script>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>

<template>
    <div class="relative flex min-h-screen w-full flex-col bg-background-dark group/design-root overflow-x-hidden"
        :class="{ dark: true }">

        <Head title="EstateSync - Modern Real Estate CRM" />
        <!-- Navigation Bar -->
        <LandingHeader />
        <div class="layout-container flex h-full grow flex-col">
            <main class="flex flex-col items-center">
                <!-- Hero Section -->
                <HeroSection :developers="developers" />
                <!-- Trusted By Section -->
                <TrustedBySection />
                <!-- Features Section -->
                <FeaturesSection />
                <!-- Projects Grid -->
                <ProjectsGrid :developments="developments" />
                <!-- Footer -->
                <LandingFooter />
            </main>
        </div>
    </div>
</template>


