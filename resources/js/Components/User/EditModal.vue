<script setup>
    import DialogModal from '@/Components/DialogModal.vue'
    import UserForm from '@/Components/User/Form.vue'
    import { router } from '@inertiajs/vue3'

    const props = defineProps({
        show: Boolean,
        user: Object,
        countries: Array,
        roles: Array,
        permissions: Array
    })

    const emit = defineEmits(['close'])

    const closeModal = () => {
        emit('close')
    }

    const handleSuccess = () => {
        closeModal()
        router.visit(route('users.index'))
    }
</script>

<template>
    <DialogModal :show="show" @close="closeModal" max-width="2xl">
        <template #content>
            <UserForm
                :countries="countries"
                :roles="roles"
                :permissions="permissions"
                :updating="true"
                :user="user"
                @success="handleSuccess"
                @cancel="closeModal"
            />
        </template>
    </DialogModal>
</template>
