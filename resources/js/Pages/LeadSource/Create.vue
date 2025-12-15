<script>
export default {
    name: 'LeadSourceCreate'
}
</script>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import LeadSourceForm from '@/Components/LeadSource/Form.vue'

const form = useForm({
    leadSou_name: '',
    leadSou_code: '',
    leadSou_description: '',
    leadSou_active: true, 
})
</script>

<template>
    <AppLayout title="Crear Fuente de Lead">
        <template #header>
            <h1 
                v-if="$page.props.user.permissions.includes('create lead sources')"
                class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Fuente de Lead
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <LeadSourceForm 
                            :form="form"
                            @submit="form.post(route('leadsources.store'))" 
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
