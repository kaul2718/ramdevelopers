<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import LeadSourceForm from '@/Components/LeadSource/Form.vue';

const props = defineProps({
    lead_source: {
        type: Object,
        required: true
    }
})

const form = useForm({
    leadSou_name: props.lead_source?.leadSou_name || '',
    leadSou_code: props.lead_source?.leadSou_code || '',
    leadSou_description: props.lead_source?.leadSou_description || '',
    leadSou_active: props.lead_source?.leadSou_active ?? true,
})
</script>

<template>
    <AppLayout title="Editar Fuente de Lead">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Fuente de Lead
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <LeadSourceForm 
                            :updating="true" 
                            :form="form"
                            @submit="form.put(route('leadsources.update', props.lead_source.leadSou_id))" 
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
