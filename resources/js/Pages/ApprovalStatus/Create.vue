<script>
export default {
    name: 'ApprovalStatusCreate'
}
</script>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ApprovalStatusForm from '@/Components/ApprovalStatus/Form.vue'
import { useRouter } from 'vue-router';

const router = useRouter();

const handleSuccess = () => {
    window.location.href = route('approvalstatus.index');
};

const handleCancel = () => {
    window.location.href = route('approvalstatus.index');
};
</script>

<template>
    <AppLayout title="Crear Estado de Aprobación">
        <template #header>
            <h1 
                v-if="$page.props.user.permissions.includes('create approval status')"
                class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Estado de Aprobación
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <ApprovalStatusForm 
                            @success="handleSuccess"
                            @cancel="handleCancel" 
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>