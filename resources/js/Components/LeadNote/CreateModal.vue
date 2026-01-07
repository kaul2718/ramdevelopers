<script setup>
import DialogModal from '@/Components/DialogModal.vue';
import Form from '@/Components/LeadNote/Form.vue';

const props = defineProps({
    show: {
        type: Boolean,
        required: true
    },
    lead: {
        type: Object,
        default: null
    }
});

const emit = defineEmits(['close', 'saved']);

const closeModal = () => {
    emit('close');
};

const handleSuccess = () => {
    closeModal();
    emit('saved', 'create');
};
</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="2xl">
        <template #content>
            <Form 
                :lead="lead" 
                :updating="false"
                @success="handleSuccess"
                @cancel="closeModal"
            />
        </template>
    </DialogModal>
</template>
