<script>
export default {
    name: 'DevelopmentCreate'
}
</script>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Form from '@/Components/Development/Form.vue'

defineProps({
    developers: {
        type: Array,
        required: true
    },
    countries: {
        type: Array,
        required: true
    },
    cities: {
        type: Array,
        required: true
    },
    approvalStatuses: {
        type: Array,
        required: true
    },
    businessStates: {
        type: Array,
        required: true
    },
    commercialStatuses: {
        type: Array,
        required: true
    }
})

const form = useForm({
    devr_id: '',
    ctry_id: '',
    city_id: '',
    apvSta_id: '',
    busiSta_id: '',
    commSta_id: '',
    devt_title: '',
    devt_slug: '',
    devt_address: '',
    devt_short_description: '',
    devt_long_description: '',
    devt_price_from: '',
    devt_price_to: '',
    devt_delivery_year: '',
    devt_estimated_profit: '',
    devt_is_featured: false,
})
</script>

<template>
    <AppLayout title="Crear Desarrollo">
        <template #header>
            <h1 
                v-if="$page.props.user.permissions.includes('create development')"
                class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Desarrollo
            </h1>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <Form 
                            :form="form"
                            :developers="developers"
                            :countries="countries"
                            :cities="cities"
                            :approvalStatuses="approvalStatuses"
                            :businessStates="businessStates"
                            :commercialStatuses="commercialStatuses"
                            :updating="false"
                            @submit="form.post(route('development.store'))" 
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
