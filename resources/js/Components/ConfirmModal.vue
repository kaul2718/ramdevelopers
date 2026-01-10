<script setup>
import DialogModal from '@/Components/DialogModal.vue';

defineProps({
    show: {
        type: Boolean,
        required: true
    },
    title: {
        type: String,
        default: 'Confirmar acción'
    },
    message: {
        type: String,
        default: '¿Estás seguro de que deseas continuar?'
    },
    confirmText: {
        type: String,
        default: 'Confirmar'
    },
    cancelText: {
        type: String,
        default: 'Cancelar'
    },
    isDangerous: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['confirm', 'close']);

const handleConfirm = () => {
    emit('confirm');
};

const handleClose = () => {
    emit('close');
};
</script>

<template>
    <DialogModal :show="show" @close="handleClose">
        <template #title>
            <h2 class="modal-title text-xl">{{ title }}</h2>
        </template>

        <template #content>
            <p class="text-gray-700">{{ message }}</p>
        </template>

        <template #footer>
            <button
                @click="handleClose"
                class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                {{ cancelText }}
            </button>
            <button
                @click="handleConfirm"
                :class="[
                    'px-4 py-2 text-white rounded-lg transition ml-3',
                    isDangerous 
                        ? 'bg-red-600 hover:bg-red-700'
                        : 'bg-indigo-600 hover:bg-indigo-700'
                ]">
                {{ confirmText }}
            </button>
        </template>
    </DialogModal>
</template>
