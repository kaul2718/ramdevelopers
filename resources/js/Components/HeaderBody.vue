<script setup>
    import { computed } from 'vue'
    import { usePage } from '@inertiajs/vue3'
    import PrimaryButton from '@/Components/PrimaryButton.vue'

    const emit = defineEmits(['create'])

    const props = defineProps({
    tituloBody: {
        type: String,
        required: true,
    },
    permisos: {
        type: String,
        required: true,
    },
    nombreBtn: {
        type: String,
        required: true,
    },
    })

    const page = usePage()

    const tienePermiso = computed(() =>
    page.props.user.permissions.includes(props.permisos)
    )
</script>

<template>
  <div class="cabecera--body">
    <h2>{{ props.tituloBody }}</h2>

    <PrimaryButton v-if="tienePermiso" @click="emit('create')">
      <template #texto--boton>{{ props.nombreBtn }}</template>

      <template #icono--boton>
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
      </template>
    </PrimaryButton>
  </div>
</template>
