<!-- filepath: resources/js/Pages/LeadStatus/Create.vue -->
<script>
export default {
    name: 'LeadStatusCreate'
}
</script>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import LeadStatusForm from '@/Components/LeadStatus/Form.vue'

const form = useForm({
    leadSta_name: '',
    leadSta_code: '',
    leadSta_description: '',
    leadSta_active: true, 
})
</script>

<template>
    <AppLayout title="Crear Estado de Lead">
        <template #header>
            <h1 
                v-if="$page.props.user.permissions.includes('create leads status')"
                class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Estado de Lead
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <LeadStatusForm 
                            :form="form"
                            @submit="form.post(route('leadstatus.store'))" 
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>