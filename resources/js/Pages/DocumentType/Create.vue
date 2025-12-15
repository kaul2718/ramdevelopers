<!-- filepath: resources/js/Pages/DocumentType/Create.vue -->
<script>
export default {
    name: 'DocumentTypeCreate'
}
</script>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import DocumentTypeForm from '@/Components/DocumentType/Form.vue'

const form = useForm({
    docTyp_name: '',
    docTyp_code: '',
    docTyp_description: '',
    docTyp_isInternal: false,
    docTyp_active: true, 
})
</script>

<template>
    <AppLayout title="Crear Tipo de Documento">
        <template #header>
            <h1 
                v-if="$page.props.user.permissions.includes('create document types')"
                class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Tipo de Documento
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <DocumentTypeForm 
                            :form="form"
                            @submit="form.post(route('documenttype.store'))" 
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>