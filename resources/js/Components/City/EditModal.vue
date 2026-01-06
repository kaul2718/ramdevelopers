<script setup>
import DialogModal from '@/Components/DialogModal.vue'
import CityForm from './Form.vue'

defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    city: {
        type: Object,
        default: () => ({})
    },
    countries: {
        type: Array,
        default: () => []
    }
})

const emit = defineEmits(['close'])

const handleSuccess = () => {
    emit('close')
}
</script>

<template>
    <DialogModal :show="show" @close="emit('close')">
        <template #content>
            <CityForm :updating="true" :city="city" :countries="countries" @success="handleSuccess" @cancel="emit('close')" />
        </template>
    </DialogModal>
</template>
