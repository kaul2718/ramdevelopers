<script setup>
    import DialogModal from '@/Components/DialogModal.vue';
    import Form from '@/Components/Lead/Form.vue';
    import { router } from '@inertiajs/vue3';

    const props = defineProps({
        show: {
            type: Boolean,
            required: true
        },
        countries: {
            type: Array,
            default: () => []
        },
        developments: {
            type: Array,
            default: () => []
        },
        sources: {
            type: Array,
            default: () => []
        },
        statuses: {
            type: Array,
            default: () => []
        },
        users: {
            type: Array,
            default: () => []
        }
    });

    const emit = defineEmits(['close', 'saved']);

    const closeModal = () => {
        emit('close');
    };

    const handleSuccess = () => {
        router.visit(route('lead.index'));
    };
</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="2xl">
        <template #content>
            <Form
                :countries="countries"
                :developments="developments"
                :sources="sources"
                :statuses="statuses"
                :users="users"
                @success="handleSuccess"
                @cancel="closeModal"
            />
        </template>
    </DialogModal>
</template>
