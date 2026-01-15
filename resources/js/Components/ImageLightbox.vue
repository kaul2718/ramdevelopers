<script setup>
import { computed } from 'vue'

const props = defineProps({
    images: {
        type: Array,
        required: true
    },
    currentIndex: {
        type: Number,
        default: 0
    }
})

const emit = defineEmits(['close', 'update:current-index'])

const currentImage = computed(() => {
    return props.images[props.currentIndex]
})

const nextImage = () => {
    const nextIndex = (props.currentIndex + 1) % props.images.length
    emit('update:current-index', nextIndex)
}

const prevImage = () => {
    const prevIndex = (props.currentIndex - 1 + props.images.length) % props.images.length
    emit('update:current-index', prevIndex)
}

const handleKeydown = (event) => {
    if (event.key === 'ArrowRight') nextImage()
    if (event.key === 'ArrowLeft') prevImage()
    if (event.key === 'Escape') emit('close')
}

const handleBackdropClick = (event) => {
    if (event.target === event.currentTarget) {
        emit('close')
    }
}
</script>

<template>
    <div 
        class="fixed inset-0 z-50 bg-black/95 backdrop-blur-sm flex items-center justify-center p-4"
        @click="handleBackdropClick"
        @keydown="handleKeydown"
        tabindex="0"
    >
        <!-- Close button -->
        <button 
            @click="emit('close')"
            class="absolute top-4 right-4 bg-white/10 hover:bg-white/20 text-white rounded-full p-3 transition z-10"
        >
            <span class="material-symbols-outlined">close</span>
        </button>

        <!-- Main image container -->
        <div class="relative w-full h-full flex items-center justify-center max-w-6xl">
            <!-- Current image -->
            <img 
                v-if="currentImage"
                :src="currentImage.devImg_url" 
                :alt="`Imagen ${currentIndex + 1}`"
                class="max-w-full max-h-full object-contain rounded-lg"
            />

            <!-- Navigation buttons -->
            <div v-if="images.length > 1" class="absolute inset-0 flex items-center justify-between pointer-events-none">
                <button 
                    @click="prevImage"
                    class="pointer-events-auto ml-4 bg-white/20 hover:bg-white/30 text-white rounded-full p-3 transition backdrop-blur-sm"
                >
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <button 
                    @click="nextImage"
                    class="pointer-events-auto mr-4 bg-white/20 hover:bg-white/30 text-white rounded-full p-3 transition backdrop-blur-sm"
                >
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>
        </div>

        <!-- Image counter and thumbnails -->
        <div class="absolute bottom-4 left-0 right-0 flex flex-col items-center gap-4">
            <!-- Counter -->
            <div class="bg-black/60 text-white px-4 py-2 rounded-full text-sm font-medium backdrop-blur-sm">
                {{ currentIndex + 1 }} / {{ images.length }}
            </div>

            <!-- Thumbnail strip -->
            <div v-if="images.length > 1" class="flex gap-2 overflow-x-auto px-4 pb-2 max-w-4xl">
                <button 
                    v-for="(image, index) in images"
                    :key="index"
                    @click="emit('update:current-index', index)"
                    class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 transition"
                    :class="index === currentIndex ? 'border-primary' : 'border-white/20 hover:border-white/40'"
                >
                    <img :src="image.devImg_url" :alt="`Thumbnail ${index + 1}`" class="w-full h-full object-cover"/>
                </button>
            </div>
        </div>

        <!-- Info text -->
        <div class="absolute top-4 left-4 text-white/70 text-sm backdrop-blur-sm bg-black/40 px-3 py-2 rounded">
            Usa las flechas del teclado o haz clic para navegar • Presiona ESC para cerrar
        </div>
    </div>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
</style>
