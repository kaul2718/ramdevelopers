<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import Form from '@/Components/Currency/Form.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    currency: {
        type: Object,
        required: true
    }
})
</script>

<template>
    <AppLayout title="Editar Moneda">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">Editar Moneda</h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <Form 
                            :currency="currency"
                            :updating="true"
                            @saved="$inertia.visit(route('currency.index'))" 
                        />
                    </div>
                </div>

                <div class="mt-4">
                    <Link :href="route('currency.index')" class="text-indigo-600 hover:text-indigo-900">
                        ← Volver a Monedas
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
