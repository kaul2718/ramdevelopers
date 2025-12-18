<script setup>
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import LeadForm from '@/Components/Lead/Form.vue'

const props = defineProps({
    lead: Object,
    developments: Array,
    countries: Array,
    sources: Array,
    statuses: Array,
    users: Array,
})

const form = useForm({
    lead_client_name: props.lead?.lead_client_name || '',
    lead_client_email: props.lead?.lead_client_email || '',
    lead_client_phone: props.lead?.lead_client_phone || '',
    lead_message: props.lead?.lead_message || '',
    lead_language: props.lead?.lead_language || '',
    devt_id: props.lead?.devt_id || null,
    ctry_id: props.lead?.ctry_id || null,
    user_id: props.lead?.user_id || null,
    leadSou_id: props.lead?.leadSou_id || null,
    leadSta_id: props.lead?.leadSta_id || null,
})
</script>

<template>
    <AppLayout title="Editar Lead">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Lead #{{ lead.lead_id }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <LeadForm 
                            :form="form"
                            :lead="lead"
                            :developments="developments"
                            :countries="countries"
                            :sources="sources"
                            :statuses="statuses"
                            :users="users"
                            :updating="true"
                            @submit="form.put(route('lead.update', lead.lead_id))"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
