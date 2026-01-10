<script setup>
    import DialogModal from '@/Components/DialogModal.vue'
    import CityForm from './Form.vue'
    import { router } from '@inertiajs/vue3'

    defineProps({
        show: {
            type: Boolean,
            default: false,
        },
        countries: {
            type: Array,
            default: () => []
        }
    })

    const emit = defineEmits(['close'])

    const handleSuccess = () => {
        emit('close')
        router.get(route('cities.index'))

    }
</script>

<template>
    <DialogModal :show="show" @close="emit('close')">
        <template #content>
            <CityForm :updating="false" :countries="countries" @success="handleSuccess" @cancel="emit('close')" />
        </template>
    </DialogModal>
</template>
