<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ApprovalStatusForm from '@/Components/ApprovalStatus/Form.vue';

const props = defineProps({
    approval_status: {
        type: Object,
        required: true
    }
})

const handleSuccess = () => {
    window.location.href = route('approvalstatus.index');
};

const handleCancel = () => {
    window.location.href = route('approvalstatus.index');
};
</script>

<template>
    <AppLayout title="Editar Estado de Aprobación">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Estado de Aprobación
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <ApprovalStatusForm 
                            :updating="true" 
                            :approval-status="approval_status"
                            @success="handleSuccess"
                            @cancel="handleCancel" 
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
