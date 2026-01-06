<script setup>
    import { computed, onMounted, onUnmounted, ref, watch } from 'vue';

    const props = defineProps({
        show: {
            type: Boolean,
            default: false,
        },
        maxWidth: {
            type: String,
            default: '2xl',
        },
        closeable: {
            type: Boolean,
            default: true,
        },
    });

    const emit = defineEmits(['close']);
    const dialog = ref();
    const showSlot = ref(props.show);

    watch(() => props.show, () => {
        if (props.show) {
            document.body.style.overflow = 'hidden';
            showSlot.value = true;
            dialog.value?.showModal();
        } else {
            document.body.style.overflow = null;
            showSlot.value = false;
            setTimeout(() => {
                dialog.value?.close();
            }, 200);
        }
    });

    const close = () => {
        if (props.closeable) {
            emit('close');
        }
    };

    const closeOnEscape = (e) => {
        if (e.key === 'Escape') {
            e.preventDefault();

            if (props.show) {
                close();
            }
        }
    };

    onMounted(() => document.addEventListener('keydown', closeOnEscape));

    onUnmounted(() => {
        document.removeEventListener('keydown', closeOnEscape);
        document.body.style.overflow = null;
    });

    const maxWidthClass = computed(() => {
        return {
            'sm': 'sm:max-w-sm',
            'md': 'sm:max-w-md',
            'lg': 'sm:max-w-lg',
            'xl': 'sm:max-w-xl',
            '2xl': 'sm:max-w-2xl',
        }[props.maxWidth];
    });
</script>

<template>
    <dialog ref="dialog">
        <div class="modal">
            <transition enter-active-class="ease-out duration-300" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="ease-in duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div class="bg--modal" v-show="show" @click="close" />
            </transition>

            <transition enter-active-class="ease-out duration-300" enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to-class="opacity-100 translate-y-0 sm:scale-100" leave-active-class="ease-in duration-200" leave-from-class="opacity-100 translate-y-0 sm:scale-100" leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div v-show="show" class="caja--modal">
                    <div class="contenido--modal">
                        <slot v-if="showSlot"/>
                    </div>
                </div>
            </transition>
        </div>
    </dialog>
</template>
