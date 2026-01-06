<script setup>
    import DialogModal from '@/Components/DialogModal.vue'
    import ApprovalStatusForm from './Form.vue'
    import { router } from '@inertiajs/vue3'

    defineProps({
        show: {
            type: Boolean,
            default: false,
        },
    })

    const emit = defineEmits(['close'])

    const handleSuccess = () => {
        emit('close')
        router.visit(route('approvalstatus.index'))
    }
</script>

<template>
    <DialogModal :show="show" @close="emit('close')">
        <template #content>
            <ApprovalStatusForm :updating="false" @success="handleSuccess" @cancel="emit('close')" />
        </template>
    </DialogModal>
</template>
