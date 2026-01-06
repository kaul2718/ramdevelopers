<script setup>
    import Modal from './Modal.vue';

    const emit = defineEmits(['close']);

    defineProps({
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

    const close = () => {
        emit('close');
    };
</script>

<template>
    <Modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
        <div>
            <div>
                <slot name="title" />
            </div>

            <div>
                <slot name="content" />
            </div>
        </div>

        <div>
            <slot name="footer" />
        </div>
    </Modal>
</template>
