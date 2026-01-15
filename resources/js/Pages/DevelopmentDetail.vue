<script setup>
import { Head, Link } from '@inertiajs/vue3';
import LandingHeader from '@/Components/Landing/LandingHeader.vue';
import LandingFooter from '@/Components/Landing/LandingFooter.vue';
import DevelopmentHero from '@/Components/Developments/DevelopmentHero.vue';
import { ref, computed } from 'vue'

const props = defineProps({
    development: Object,
});

const currentImageIndex = ref(0)
const formData = ref({
    name: '',
    email: '',
    message: ''
})


const nextImage = () => {
    if (props.development?.images) {
        currentImageIndex.value = (currentImageIndex.value + 1) % props.development.images.length
    }
}

const prevImage = () => {
    if (props.development?.images) {
        currentImageIndex.value = (currentImageIndex.value - 1 + props.development.images.length) % props.development.images.length
    }
}

const formatPrice = (price) => {
    if (!price) return 'N/A'
    return new Intl.NumberFormat('es-ES', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 0,
    }).format(price)
}

const handleSubmitRequest = () => {
    console.log('Request form submitted:', formData.value)
    // TODO: Send to backend
    formData.value = { name: '', email: '', message: '' }
}
</script>

<template>
    <div class="relative flex min-h-screen w-full flex-col bg-background-dark group/design-root overflow-x-hidden" :class="{ dark: true }">
        <Head :title="`${development?.devt_title || 'Proyecto'} | EstateSync`" />
        
        <!-- Navigation -->
        <LandingHeader />

        <main class="flex-1 w-full">
            <!-- Breadcrumbs -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <nav class="flex items-center gap-2 text-sm text-slate-400">
                    <Link href="/" class="hover:text-primary transition-colors">Inicio</Link>
                    <span class="material-symbols-outlined text-xs">chevron_right</span>
                    <Link href="/developments" class="hover:text-primary transition-colors">Proyectos</Link>
                    <span class="material-symbols-outlined text-xs">chevron_right</span>
                    <span class="text-slate-200 font-medium">{{ development?.devt_title }}</span>
                </nav>

                <!-- Hero Section -->
                <DevelopmentHero 
                    :development="development" 
                    :current-image-index="currentImageIndex"
                    @next-image="nextImage"
                    @prev-image="prevImage"
                />
            </div>

            <!-- Main Content -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    <!-- Left Column: Content -->
                    <div class="lg:col-span-2 space-y-12">
                        <!-- Description -->
                        <section>
                            <h3 class="text-2xl font-bold text-white mb-4">Sobre el Proyecto</h3>
                            <p class="text-slate-300 leading-relaxed text-lg whitespace-pre-wrap">{{ development?.devt_short_description }}</p>
                            <p class="text-slate-400 leading-relaxed text-lg mt-4 whitespace-pre-wrap">{{ development?.devt_long_description }}</p>
                        </section>

                        <!-- Specifications -->
                        <section class="bg-white/5 border border-white/10 rounded-xl p-8">
                            <h4 class="text-lg font-bold text-white mb-6">Especificaciones del Proyecto</h4>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-y-8 gap-x-4">
                                <div class="flex flex-col gap-1 border-l-2 border-primary/20 pl-4">
                                    <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Rango de Precio</span>
                                    <span class="text-lg font-bold text-white">{{ formatPrice(development?.devt_price_from) }} - {{ formatPrice(development?.devt_price_to) }}</span>
                                </div>
                                <div v-if="development?.devt_delivery_year" class="flex flex-col gap-1 border-l-2 border-primary/20 pl-4">
                                    <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Entrega</span>
                                    <span class="text-lg font-bold text-white">{{ development.devt_delivery_year }}</span>
                                </div>
                                <div v-if="development?.devt_bedrooms" class="flex flex-col gap-1 border-l-2 border-primary/20 pl-4">
                                    <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Recámaras</span>
                                    <span class="text-lg font-bold text-white">{{ development.devt_bedrooms }}</span>
                                </div>
                                <div v-if="development?.country" class="flex flex-col gap-1 border-l-2 border-primary/20 pl-4">
                                    <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider">País</span>
                                    <span class="text-lg font-bold text-white">{{ development.country.ctry_name }}</span>
                                </div>
                                <div v-if="development?.city" class="flex flex-col gap-1 border-l-2 border-primary/20 pl-4">
                                    <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Ciudad</span>
                                    <span class="text-lg font-bold text-white">{{ development.city.city_name }}</span>
                                </div>
                                <div v-if="development?.developer" class="flex flex-col gap-1 border-l-2 border-primary/20 pl-4">
                                    <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Desarrollador</span>
                                    <span class="text-lg font-bold text-white">{{ development.developer.dev_name }}</span>
                                </div>
                            </div>
                        </section>

                        <!-- Amenities -->
                        <section v-if="development?.devt_bedrooms || development?.devt_parking_spaces || development?.devt_swimming_pools || development?.devt_elevators || development?.devt_green_zones || development?.devt_storage_rooms">
                            <h3 class="text-2xl font-bold text-white mb-6">Amenidades Premium</h3>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
                                <div v-if="development?.devt_bedrooms" class="flex flex-col items-center p-6 bg-white/5 border border-white/10 rounded-xl text-center hover:border-primary/50 transition-colors">
                                    <span class="material-symbols-outlined text-primary text-4xl mb-3">bed</span>
                                    <span class="font-medium text-white text-sm">{{ development.devt_bedrooms }} Recámaras</span>
                                </div>
                                <div v-if="development?.devt_parking_spaces" class="flex flex-col items-center p-6 bg-white/5 border border-white/10 rounded-xl text-center hover:border-primary/50 transition-colors">
                                    <span class="material-symbols-outlined text-primary text-4xl mb-3">local_parking</span>
                                    <span class="font-medium text-white text-sm">{{ development.devt_parking_spaces }} Estacionamientos</span>
                                </div>
                                <div v-if="development?.devt_swimming_pools" class="flex flex-col items-center p-6 bg-white/5 border border-white/10 rounded-xl text-center hover:border-primary/50 transition-colors">
                                    <span class="material-symbols-outlined text-primary text-4xl mb-3">pool</span>
                                    <span class="font-medium text-white text-sm">{{ development.devt_swimming_pools }} Piscinas</span>
                                </div>
                                <div v-if="development?.devt_elevators" class="flex flex-col items-center p-6 bg-white/5 border border-white/10 rounded-xl text-center hover:border-primary/50 transition-colors">
                                    <span class="material-symbols-outlined text-primary text-4xl mb-3">elevator</span>
                                    <span class="font-medium text-white text-sm">{{ development.devt_elevators }} Elevadores</span>
                                </div>
                                <div v-if="development?.devt_green_zones" class="flex flex-col items-center p-6 bg-white/5 border border-white/10 rounded-xl text-center hover:border-primary/50 transition-colors">
                                    <span class="material-symbols-outlined text-primary text-4xl mb-3">park</span>
                                    <span class="font-medium text-white text-sm">Zonas Verdes</span>
                                </div>
                                <div v-if="development?.devt_storage_rooms" class="flex flex-col items-center p-6 bg-white/5 border border-white/10 rounded-xl text-center hover:border-primary/50 transition-colors">
                                    <span class="material-symbols-outlined text-primary text-4xl mb-3">storage</span>
                                    <span class="font-medium text-white text-sm">{{ development.devt_storage_rooms }} Trasteros</span>
                                </div>
                                <div v-if="development?.devt_terraces" class="flex flex-col items-center p-6 bg-white/5 border border-white/10 rounded-xl text-center hover:border-primary/50 transition-colors">
                                    <span class="material-symbols-outlined text-primary text-4xl mb-3">balcony</span>
                                    <span class="font-medium text-white text-sm">{{ development.devt_terraces }} Terrazas</span>
                                </div>
                                <div v-if="development?.devt_golf_courses" class="flex flex-col items-center p-6 bg-white/5 border border-white/10 rounded-xl text-center hover:border-primary/50 transition-colors">
                                    <span class="material-symbols-outlined text-primary text-4xl mb-3">golf_course</span>
                                    <span class="font-medium text-white text-sm">Campos de Golf</span>
                                </div>
                            </div>
                        </section>

                        <!-- Gallery -->
                        <section v-if="development?.images && development.images.length > 0">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-2xl font-bold text-white">Galería de Imágenes</h3>
                                <span class="text-slate-400 text-sm">{{ development.images.length }} fotos</span>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div v-for="(image, index) in development.images.slice(0, 6)" :key="index" class="h-48 rounded-lg overflow-hidden cursor-pointer" @click="currentImageIndex = index">
                                    <img :src="image.devImg_url" :alt="`Imagen ${index + 1}`" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                                </div>
                            </div>
                        </section>

                        <!-- Documents -->
                        <section v-if="development?.files && development.files.length > 0">
                            <h3 class="text-2xl font-bold text-white mb-6">Documentos y Descargas</h3>
                            <div class="space-y-3">
                                <div v-for="file in development.files" :key="file.devFile_id" class="flex items-center justify-between p-4 bg-white/5 border border-white/10 rounded-lg hover:border-primary/50 transition-colors group">
                                    <div class="flex items-center gap-4">
                                        <div class="p-2 bg-primary/10 rounded">
                                            <span class="material-symbols-outlined text-primary">description</span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-white">{{ file.devFile_name }}</p>
                                            <p class="text-xs text-slate-400">{{ file.documentType?.docTyp_name || 'Documento' }}</p>
                                        </div>
                                    </div>
                                    <a :href="`/storage/${file.devFile_url}`" download class="text-slate-400 group-hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined">download</span>
                                    </a>
                                </div>
                            </div>
                        </section>
                    </div>

                    <!-- Right Column: Sidebar -->
                    <aside class="lg:relative">
                        <div class="sticky top-24 space-y-6">
                            <!-- Pricing & Request Card -->
                            <div class="bg-white/5 border border-white/10 rounded-2xl p-8 backdrop-blur-sm">
                                <div class="mb-8">
                                    <p class="text-slate-400 text-sm font-medium">Comenzando desde</p>
                                    <h4 class="text-3xl font-black text-primary">{{ formatPrice(development?.devt_price_from) }}</h4>
                                </div>
                                <div class="space-y-4">
                                    <h5 class="font-bold text-lg text-white">Solicitar Información</h5>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Nombre Completo</label>
                                        <input v-model="formData.name" type="text" placeholder="Juan García" class="w-full bg-white/5 border border-white/10 text-white placeholder-slate-500 rounded-lg p-3 focus:ring-primary focus:border-primary focus:outline-none transition"/>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Correo Electrónico</label>
                                        <input v-model="formData.email" type="email" placeholder="juan@example.com" class="w-full bg-white/5 border border-white/10 text-white placeholder-slate-500 rounded-lg p-3 focus:ring-primary focus:border-primary focus:outline-none transition"/>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Mensaje (Opcional)</label>
                                        <textarea v-model="formData.message" placeholder="Me interesa conocer más sobre este proyecto..." rows="3" class="w-full bg-white/5 border border-white/10 text-white placeholder-slate-500 rounded-lg p-3 focus:ring-primary focus:border-primary focus:outline-none transition resize-none"></textarea>
                                    </div>
                                    <button @click="handleSubmitRequest" class="w-full bg-primary hover:bg-primary/90 text-background-dark font-bold py-4 rounded-xl transition-all shadow-lg shadow-primary/25 mt-4">
                                        Solicitar Detalles
                                    </button>
                                    <p class="text-[10px] text-center text-slate-400">Al hacer clic en Solicitar Detalles, aceptas nuestros Términos de Uso y Política de Privacidad.</p>
                                </div>
                            </div>

                            <!-- Developer Info -->
                            <div v-if="development?.developer" class="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm">
                                <h5 class="font-bold text-white mb-4">Desarrollador</h5>
                                <div>
                                    <p class="font-bold text-white">{{ development.developer.dev_name }}</p>
                                    <p class="text-sm text-slate-400 mt-2">{{ development.developer.dev_description }}</p>
                                </div>
                            </div>

                            <!-- Status -->
                            <div v-if="development?.businessStatus || development?.commercialStatus" class="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm">
                                <h5 class="font-bold text-white mb-4">Estado del Proyecto</h5>
                                <div class="space-y-3">
                                    <div v-if="development?.businessStatus" class="flex flex-col gap-1">
                                        <span class="text-xs font-semibold text-slate-400 uppercase">Estado Empresarial</span>
                                        <span class="text-sm text-white font-medium">{{ development.businessStatus.bstatus_name }}</span>
                                    </div>
                                    <div v-if="development?.commercialStatus" class="flex flex-col gap-1">
                                        <span class="text-xs font-semibold text-slate-400 uppercase">Estado Comercial</span>
                                        <span class="text-sm text-white font-medium">{{ development.commercialStatus.cstatus_name }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <LandingFooter />
    </div>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>
