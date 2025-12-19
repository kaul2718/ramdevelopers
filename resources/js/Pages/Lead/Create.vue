<script setup>
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import LeadForm from '@/Components/Lead/Form.vue'

defineProps({
    developments: Array,
    countries: Array,
    sources: Array,
    statuses: Array,
    users: Array,
})

const form = useForm({
    lead_client_name: '',
    lead_client_email: '',
    lead_client_phone: '',
    lead_message: '',
    lead_language: '',
    devt_id: null,
    ctry_id: null,
    user_id: null,
    leadSou_id: null,
    leadSta_id: null,
})
</script>

<template>
    <AppLayout title="Crear Lead">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Crear Nuevo Lead</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <LeadForm 
                            :form="form"
                            :developments="developments"
                            :countries="countries"
                            :sources="sources"
                            :statuses="statuses"
                            :users="users"
                            @submit="form.post(route('lead.store'))"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
