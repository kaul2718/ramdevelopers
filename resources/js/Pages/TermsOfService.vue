<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';

defineProps({
    terms: String,
});
</script>

<template>
    <Head title="Terms of Service" />

    <div class="font-sans text-gray-900 antialiased">
        <div style="background-color: var(--fondo); padding: 2em;">
            <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
                <AuthenticationCardLogo />

                <div class="w-full sm:max-w-2xl mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg prose" v-html="terms" />
            </div>
        </div>
    </div>
</template>
