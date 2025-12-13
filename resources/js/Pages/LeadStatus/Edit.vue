<!-- filepath: resources/js/Pages/LeadStatus/Edit.vue -->
<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import LeadStatusForm from '@/Components/LeadStatus/Form.vue';

const props = defineProps({
    lead_status: {
        type: Object,
        required: true
    }
})

const form = useForm({
    leadSta_name: props.lead_status?.leadSta_name || '',
    leadSta_code: props.lead_status?.leadSta_code || '',
    leadSta_description: props.lead_status?.leadSta_description || '',
    leadSta_active: props.lead_status?.leadSta_active ?? true,
})
</script>

<template>
    <AppLayout title="Editar Estado de Lead">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Estado de Lead
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <LeadStatusForm 
                            :updating="true" 
                            :form="form"
                            @submit="form.put(route('leadstatus.update', props.lead_status.leadSta_id))" 
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>