<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ApprovalStatusForm from '@/Components/ApprovalStatus/Form.vue';

const props = defineProps({
    approval_status: {
        type: Object,
        required: true
    }
})

console.log('Props recibidas:', props);

const form = useForm({
    apvSta_name: props.approval_status?.apvSta_name || '',
    apvSta_code: props.approval_status?.apvSta_code || '',
    apvSta_description: props.approval_status?.apvSta_description || '',
    apvSta_active: props.approval_status?.apvSta_active ?? true,
})
</script>

<template>
    <AppLayout title="Editar Estado Aprobación">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Estado Aprobación
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <ApprovalStatusForm 
                            :updating="true" 
                            :form="form"
                            @submit="form.put(route('approvalstatus.update', props.approval_status.apvSta_id))" 
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
