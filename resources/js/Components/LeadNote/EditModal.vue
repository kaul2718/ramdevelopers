<script setup>
import DialogModal from '@/Components/DialogModal.vue';
import Form from '@/Components/LeadNote/Form.vue';
import { watch } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        required: true
    },
    note: {
        type: Object,
        default: null
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
    emit('saved', 'edit');
};

</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="2xl">
        <template #content>
            <Form 
                :note="note"
                :lead="lead" 
                :updating="true"
                @success="handleSuccess"
                @cancel="closeModal"
            />
        </template>
    </DialogModal>
</template>
