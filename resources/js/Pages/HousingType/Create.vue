<script>
export default {
    name: 'HousingTypeCreate'
}
</script>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import HousingTypeForm from '@/Components/HousingType/Form.vue'

const form = useForm({
    houTyp_name: '',
    houTyp_code: '',
    houTyp_description: '',
    houTyp_active: true
})

const submit = () => {
    form.post(route('housingtype.store'))
}
</script>

<template>
    <AppLayout title="Crear Tipo de Vivienda">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Tipo de Vivienda
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <HousingTypeForm :form="form" @submit="submit" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
