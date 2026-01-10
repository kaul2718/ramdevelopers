<script setup>
    import DialogModal from '@/Components/DialogModal.vue'
    import DocumentTypeForm from '@/Components/DocumentType/Form.vue'
    import { router } from '@inertiajs/vue3'

    const props = defineProps({
        show: Boolean
    })

    const emit = defineEmits(['close'])

    const closeModal = () => {
        emit('close')
    }

    const handleSuccess = () => {
        closeModal()
        router.visit(route('documenttype.index'))
    }
</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="2xl">
        <template #content>
            <DocumentTypeForm :updating="false" @success="handleSuccess" @cancel="closeModal" />
        </template>
    </DialogModal>
</template>
