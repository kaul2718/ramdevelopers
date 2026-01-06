<script setup>
    import DialogModal from '@/Components/DialogModal.vue'
    import CountryForm from './Form.vue'

    defineProps({
        show: {
            type: Boolean,
            default: false,
        },
    })

    const emit = defineEmits(['close'])

    const handleSuccess = () => {
        emit('close')
    }
</script>

<template>
    <DialogModal :show="show" @close="emit('close')">
        <template #content>
            <CountryForm :updating="false" @success="handleSuccess" @cancel="emit('close')" />
        </template>
    </DialogModal>
</template>
