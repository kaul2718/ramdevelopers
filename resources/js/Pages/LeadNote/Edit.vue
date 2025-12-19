<script setup>
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import LeadNoteForm from '@/Components/LeadNote/Form.vue'

const props = defineProps({
    leadNote: Object,
    leads: Array,
    users: Array,
})

const form = useForm({
    leadNot_title: props.leadNote?.leadNot_title || '',
    leadNot_description: props.leadNote?.leadNot_description || '',
    leadNot_active: props.leadNote?.leadNot_active ?? true,
    lead_id: props.leadNote?.lead_id || null,
    user_id: props.leadNote?.user_id || null,
})
</script>

<template>
    <AppLayout title="Editar Nota de Lead">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Nota #{{ leadNote.leadNot_id }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <LeadNoteForm 
                            :form="form"
                            :leadNote="leadNote"
                            :leads="leads"
                            :users="users"
                            :updating="true"
                            @submit="form.put(route('leadnote.update', leadNote.leadNot_id))"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
