<script setup>
import Modal from '@/Components/Modal.vue'
import DialogModal from '@/Components/DialogModal.vue'
import Form from './Form.vue'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['close', 'saved'])

const handleSaved = (currency) => {
    emit('close')
    emit('saved')
    router.reload()
}
</script>

<template>
    <DialogModal :show="show" @close="emit('close')" max-width="2xl">
        <template #content>
            <Form 
                @saved="handleSaved"
                :show-submit-button="true"
            />
        </template>
    </DialogModal>
</template>
