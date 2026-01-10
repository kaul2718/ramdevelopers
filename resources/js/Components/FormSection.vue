<script setup>
import { computed, useSlots } from 'vue';
import SectionTitle from './SectionTitle.vue';

defineEmits(['submitted']);

const hasActions = computed(() => !! useSlots().actions);
</script>

<template>
    <div class="plantilla--form">
        <SectionTitle>
            <template #buttons>
                <slot name="buttons" />
            </template>
            <template #title>
                <slot name="title" />
            </template>
            <template #description>
                <slot name="description" />
            </template>
        </SectionTitle>

        <div class="contenedor--form">
            <form @submit.prevent="$emit('submitted')">
                <slot name="form" />

                <div v-if="hasActions" class="envio--form">
                    <slot name="actions" />
                </div>
            </form>
        </div>
    </div>
</template>
