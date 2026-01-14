<script setup>
    import { computed } from 'vue'

    const props = defineProps({
        show: {
            type: Boolean,
            default: false,
        },
        title: {
            type: String,
            required: true,
        },
        description: {
            type: String,
            default: null,
        },
        modelValue: {
            type: Object,
            required: true,
        },
        closeable: {
            type: Boolean,
            default: true,
        },
    })

    const emit = defineEmits(['close', 'update:show'])

    const closeModal = () => {
        emit('update:show', false)
        emit('close')
    }

    // Cerrar al presionar ESC
    const handleKeydown = (e) => {
        if (e.key === 'Escape' && props.closeable) {
            closeModal()
        }
    }
</script>

<template>
    <!-- Backdrop -->
    <Transition name="fade">
        <div
            v-if="show"
            class="fixed inset-0 z-40 bg-black bg-opacity-50 transition-opacity"
            @click="closeable && closeModal()"
            @keydown="handleKeydown"
        />
    </Transition>

    <!-- Modal -->
    <Transition name="slide-fade">
        <div
            v-if="show"
            class="fixed inset-0 z-50 overflow-y-auto"
            @keydown="handleKeydown"
        >
            <div class="flex items-start justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Centro vertical helper -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel responsivo -->
                <div class="relative inline-block w-full transform bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg md:max-w-xl lg:max-w-2xl rounded-lg">
                    <!-- Header -->
                    <div class="flex items-start justify-between border-b border-gray-200 bg-gray-50 px-4 py-4 sm:px-6">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900">
                                {{ title }}
                            </h3>
                            <p v-if="description" class="mt-1 text-sm text-gray-500">
                                {{ description }}
                            </p>
                        </div>
                        <button
                            v-if="closeable"
                            type="button"
                            @click="closeModal"
                            class="ml-3 inline-flex h-8 w-8 items-center justify-center rounded-md bg-transparent text-gray-400 hover:bg-gray-100 hover:text-gray-600 focus:outline-none transition"
                        >
                            <span class="sr-only">Cerrar</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="px-4 py-6 sm:px-6">
                        <slot>
                            <!-- Contenido por defecto si no se proporciona slot -->
                            <div v-if="modelValue" class="space-y-4">
                                <div v-for="(value, key) in modelValue" :key="key" class="border-b border-gray-100 pb-3 last:border-b-0">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-600">
                                        {{ key }}
                                    </p>
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ value }}
                                    </p>
                                </div>
                            </div>
                        </slot>
                    </div>

                    <!-- Footer/Actions -->
                    <div v-if="$slots.footer" class="border-t border-gray-200 bg-gray-50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                        <slot name="footer" />
                    </div>
                    <div v-else class="border-t border-gray-200 bg-gray-50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6">
                        <button
                            type="button"
                            @click="closeModal"
                            class="w-full sm:w-auto inline-flex justify-center rounded-md bg-gray-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-600 focus:outline-none transition"
                        >
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.slide-fade-enter-active {
    transition: all 0.3s ease;
}

.slide-fade-leave-active {
    transition: all 0.2s ease;
}

.slide-fade-enter-from {
    transform: scale(0.95);
    opacity: 0;
}

.slide-fade-leave-to {
    transform: scale(0.95);
    opacity: 0;
}
</style>
