<script setup>
import DialogModal from '@/Components/DialogModal.vue'
import CountryForm from './Form.vue'
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
    router.get(route('countries.index'))

}
</script>

<template>
    <DialogModal :show="show" @close="emit('close')">
        <template #content>
            <CountryForm :updating="false" @success="handleSuccess" @cancel="emit('close')" />
        </template>
    </DialogModal>
</template>
