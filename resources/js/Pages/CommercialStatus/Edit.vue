<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import CommercialStatusForm from '@/Components/CommercialStatus/Form.vue';

const props = defineProps({
    commercial_status: {
        type: Object,
        required: true
    }
})

const form = useForm({
    commSta_name: props.commercial_status?.commSta_name || '',
    commSta_code: props.commercial_status?.commSta_code || '',
    commSta_description: props.commercial_status?.commSta_description || '',
    commSta_active: props.commercial_status?.commSta_active ?? true,
})
</script>

<template>
    <AppLayout title="Editar Estado Comercial">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Estado Comercial
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <CommercialStatusForm 
                            :updating="true" 
                            :form="form"
                            @submit="form.put(route('commercialstatus.update', props.commercial_status.commSta_id))" 
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
