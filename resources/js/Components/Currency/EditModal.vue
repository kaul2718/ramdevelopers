<script setup>
    import DialogModal from '@/Components/DialogModal.vue'
    import Form from './Form.vue'
    import { router } from '@inertiajs/vue3'

    const props = defineProps({
        show: Boolean,
        currency: Object
    })

    const emit = defineEmits(['close'])

    const closeModal = () => {
        emit('close')
    }

    const handleSuccess = () => {
        closeModal()
        router.visit(route('currency.index'))
    }
</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="2xl">
        <template #content>
            <Form
                :currency="currency"
                :updating="true"
                @saved="handleSuccess"
                @cancel="closeModal"
            />
        </template>
    </DialogModal>
</template>
