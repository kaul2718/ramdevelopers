<script>
export default {
    name: 'ApprovalStatusCreate'
}
</script>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ApprovalStatusForm from '@/Components/ApprovalStatus/Form.vue'

const form = useForm({
    apvSta_name: '',
    apvSta_code: '',
    apvSta_description: '',
    apvSta_active: true, 
})

</script>

<template>
    <AppLayout title="Crear Estado Aprobación">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight"
                v-if="$page.props.user.permissions.includes('create approval status')"> Crear Estado Aprobación</h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <ApprovalStatusForm :form="form" @submit="form.post(route('approvalstatus.store'))" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>