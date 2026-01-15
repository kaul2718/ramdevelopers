<script setup>
import { computed } from 'vue'

const props = defineProps({
    development: {
        type: Object,
        required: true
    },
    currentImageIndex: {
        type: Number,
        default: 0
    }
})

const emit = defineEmits(['next-image', 'prev-image', 'select-image'])

const currentImage = computed(() => {
    if (props.development?.images && props.development.images.length > 0) {
        return props.development.images[props.currentImageIndex]?.devImg_url
    }
    return null
})

const handleNextImage = () => {
    emit('next-image')
}

const handlePrevImage = () => {
    emit('prev-image')
}
</script>

<template>
    <section v-if="development?.images && development.images.length > 0" class="relative rounded-2xl overflow-hidden mb-8 shadow-xl">
        <div class="h-40 sm:h-48 md:h-60 w-full bg-cover bg-center transition-transform duration-700 hover:scale-105 relative">
            <!-- Background Image -->
            <img v-if="currentImage" :src="currentImage" :alt="development.devt_title" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-background-dark via-transparent to-transparent"></div>
            
            <!-- Navigation buttons -->
            <button v-if="development.images.length > 1" @click="handlePrevImage" class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white rounded-full p-2 transition backdrop-blur-sm">
                <span class="material-symbols-outlined">chevron_left</span>
            </button>
            <button v-if="development.images.length > 1" @click="handleNextImage" class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white rounded-full p-2 transition backdrop-blur-sm">
                <span class="material-symbols-outlined">chevron_right</span>
            </button>

            <!-- Image counter -->
            <div class="absolute bottom-4 right-4 bg-black/60 text-white px-3 py-1 rounded-full text-sm font-medium backdrop-blur-sm">
                {{ currentImageIndex + 1 }} / {{ development.images.length }}
            </div>

            <!-- Title overlay -->
            <div class="absolute bottom-0 left-0 p-8 w-full">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <div v-if="development?.commercialStatus" class="inline-flex items-center px-3 py-1 rounded-full bg-primary text-background-dark text-xs font-bold uppercase tracking-widest mb-4">
                            {{ development.commercialStatus.cstatus_name }}
                        </div>
                        <h2 class="text-4xl md:text-5xl font-black text-white leading-tight">{{ development?.devt_title }}</h2>
                        <div class="flex items-center text-white/90 mt-2 gap-2">
                            <span class="material-symbols-outlined text-xl">location_on</span>
                            <p class="text-lg">{{ development?.devt_address }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>
