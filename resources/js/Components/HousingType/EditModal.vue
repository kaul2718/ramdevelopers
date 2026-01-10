<script setup>
    import DialogModal from '@/Components/DialogModal.vue'
    import HousingTypeForm from '@/Components/HousingType/Form.vue'
    import { router } from '@inertiajs/vue3'

    const props = defineProps({
        show: Boolean,
        housingType: Object
    })

    const emit = defineEmits(['close'])

    const closeModal = () => {
        emit('close')
    }

    const handleSuccess = () => {
        closeModal()
        router.visit(route('housingtype.index'))
    }
</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="2xl">
        <template #content>
            <HousingTypeForm :updating="true" :housing-type="housingType" @success="handleSuccess" @cancel="closeModal" />
        </template>
    </DialogModal>
</template>
