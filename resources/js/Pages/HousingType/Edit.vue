<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import HousingTypeForm from '@/Components/HousingType/Form.vue';

const props = defineProps({
    housing_type: {
        type: Object,
        required: true
    }
})

const form = useForm({
    houTyp_name: props.housing_type?.houTyp_name || '',
    houTyp_code: props.housing_type?.houTyp_code || '',
    houTyp_description: props.housing_type?.houTyp_description || '',
    houTyp_active: props.housing_type?.houTyp_active ?? true,
})
</script>

<template>
    <AppLayout title="Editar Tipo de Vivienda">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Tipo de Vivienda
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <HousingTypeForm 
                            :updating="true" 
                            :form="form"
                            @submit="form.put(route('housingtype.update', props.housing_type.houTyp_id))" 
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
