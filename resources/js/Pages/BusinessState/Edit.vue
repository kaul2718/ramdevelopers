<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import BusinessStateForm from '@/Components/BusinessState/Form.vue';

const props = defineProps({
    business_state: {
        type: Object,
        required: true
    }
})

const form = useForm({
    busiSta_name: props.business_state?.busiSta_name || '',
    busiSta_code: props.business_state?.busiSta_code || '',
    busiSta_description: props.business_state?.busiSta_description || '',
    busiSta_active: props.business_state?.busiSta_active ?? true,
})
</script>

<template>
    <AppLayout title="Editar Estado del Proyecto">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Estado del Proyecto
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <BusinessStateForm 
                            :updating="true" 
                            :form="form"
                            @submit="form.put(route('businessstate.update', props.business_state.busiSta_id))" 
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>