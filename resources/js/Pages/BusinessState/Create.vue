<script>
export default {
    name: 'BusinessStateCreate'
}
</script>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import BusinessStateForm from '@/Components/BusinessState/Form.vue'

const form = useForm({
    busiSta_name: '',
    busiSta_code: '',
    busiSta_description: '',
    busiSta_active: true
})

const submit = () => {
    form.post(route('businessstate.store'))
}
</script>

<template>
    <AppLayout title="Crear Estado del Proyecto">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Estado del Proyecto
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <BusinessStateForm :form="form" @submit="submit" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>