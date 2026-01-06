<script setup>
import DialogModal from '@/Components/DialogModal.vue'
import CommercialStatusForm from '@/Components/CommercialStatus/Form.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    show: Boolean,
    commercialStatus: Object
})

const emit = defineEmits(['close'])

const closeModal = () => {
    emit('close')
}

const handleSuccess = () => {
    closeModal()
    router.visit(route('commercialstatus.index'))
}
</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="2xl">
        <template #content>
            <CommercialStatusForm :updating="true" :commercial-status="commercialStatus" @success="handleSuccess" @cancel="closeModal" />
        </template>
    </DialogModal>
</template>
