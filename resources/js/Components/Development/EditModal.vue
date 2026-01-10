<script setup>
    import DialogModal from '@/Components/DialogModal.vue'
    import DevelopmentForm from '@/Components/Development/Form.vue'

    const props = defineProps({
        show: Boolean,
        development: { type: Object, default: null },
        developers: { type: Array, default: () => [] },
        countries: { type: Array, default: () => [] },
        cities: { type: Array, default: () => [] },
        approvalStatuses: { type: Array, default: () => [] },
        businessStates: { type: Array, default: () => [] },
        commercialStatuses: { type: Array, default: () => [] },
        housingTypes: { type: Array, default: () => [] }
    })

    const emit = defineEmits(['close', 'saved'])

    const handleSaved = (development) => {
        emit('saved', development)
    }
</script>

<template>
    <DialogModal :show="show" @close="emit('close')">
        <template #content>
            <DevelopmentForm
                :updating="true"
                :development="development"
                :developers="developers"
                :countries="countries"
                :cities="cities"
                :approvalStatuses="approvalStatuses"
                :businessStates="businessStates"
                :commercialStatuses="commercialStatuses"
                :housingTypes="housingTypes"
                @saved="handleSaved"
                @cancel="emit('close')"
            />
        </template>
    </DialogModal>
</template>
