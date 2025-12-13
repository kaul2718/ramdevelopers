<!-- filepath: resources/js/Pages/DocumentType/Edit.vue -->
<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import DocumentTypeForm from '@/Components/DocumentType/Form.vue';

const props = defineProps({
    document_type: {
        type: Object,
        required: true
    }
})

const form = useForm({
    docTyp_name: props.document_type?.docTyp_name || '',
    docTyp_code: props.document_type?.docTyp_code || '',
    docTyp_description: props.document_type?.docTyp_description || '',
    docTyp_isInternal: props.document_type?.docTyp_isInternal ?? false,
    docTyp_active: props.document_type?.docTyp_active ?? true,
})
</script>

<template>
    <AppLayout title="Editar Tipo de Documento">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Tipo de Documento
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <DocumentTypeForm 
                            :updating="true" 
                            :form="form"
                            @submit="form.put(route('documenttype.update', props.document_type.docTyp_id))" 
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>