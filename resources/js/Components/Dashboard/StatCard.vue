<template>
    <div class="bg-gradient-to-br rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform"
         :class="gradientClass">
        <div class="flex justify-between items-start">
            <div>
                <p class="opacity-90 text-sm font-medium">{{ label }}</p>
                <p class="text-4xl font-bold mt-2">{{ value }}</p>
            </div>
            <div class="rounded-lg p-3" :class="iconBackgroundClass">
                <component :is="icon" class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="iconPath" />
                </component>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    label: {
        type: String,
        required: true
    },
    value: {
        type: [String, Number],
        required: true
    },
    color: {
        type: String,
        default: 'blue',
        validator: (value) => ['blue', 'green', 'purple', 'amber', 'orange'].includes(value)
    },
    iconPath: {
        type: String,
        required: true
    }
});

const gradientClass = computed(() => {
    const gradients = {
        blue: 'from-blue-500 to-blue-600',
        green: 'from-green-500 to-green-600',
        purple: 'from-purple-500 to-purple-600',
        amber: 'from-amber-500 to-amber-600',
        orange: 'from-orange-500 to-orange-600'
    };
    return gradients[props.color];
});

const iconBackgroundClass = computed(() => {
    const backgrounds = {
        blue: 'bg-blue-400 bg-opacity-30',
        green: 'bg-green-400 bg-opacity-30',
        purple: 'bg-purple-400 bg-opacity-30',
        amber: 'bg-amber-400 bg-opacity-30',
        orange: 'bg-orange-400 bg-opacity-30'
    };
    return backgrounds[props.color];
});
</script>
