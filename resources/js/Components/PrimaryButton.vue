<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});

</script>

<template>
    <button :type="type" class="btn--enviar--form">
        <spam><slot name="texto--boton" /></spam>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="size-5">
            <slot name="icono--boton" />
        </svg>
    </button>
</template>
