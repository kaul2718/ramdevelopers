<script setup>
import DialogModal from '@/Components/DialogModal.vue'
import DeveloperForm from '@/Components/Developer/Form.vue'
import { router } from '@inertiajs/vue3'

defineProps({
    show: Boolean,
    countries: { type: Array, default: () => [] },
    users: { type: Array, default: () => [] }
})

const emit = defineEmits(['close'])

const handleSuccess = () => {
    emit('close')
    router.get(route('developers.index'))
}
</script>


<template>
    <DialogModal :show="show" @close="emit('close')">
        <template #content>
            <DeveloperForm
                :updating="false"
                :countries="countries"
                :users="users"
                @success="handleSuccess"
                @cancel="emit('close')"
            />
        </template>
    </DialogModal>
</template>
