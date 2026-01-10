<script setup>
    import { Link } from '@inertiajs/vue3'

    defineProps({
    objPage: {
        type: Object,
        required: true,
    },
    })
</script>

<template>
  <div class="caja--paginacion" v-if="objPage.total > 0">
    <p>
      Mostrando
      <span class="font-medium">{{ objPage.from }}</span>
      a
      <span class="font-medium">{{ objPage.to }}</span>
      de
      <span class="font-medium">{{ objPage.total }}</span>
      resultados
    </p>

    <nav aria-label="Pagination">
      <!-- Previous -->
      <Link v-if="objPage.prev_page_url" :href="objPage.prev_page_url" class="btn--prevnext">
        <svg viewBox="0 0 20 20" fill="currentColor" class="size-5">
          <path d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" fill-rule="evenodd"/>
        </svg>
      </Link>

      <!-- Pages -->
      <Link v-for="page in objPage.last_page" :key="page" :href="`?page=${page}`" :class="['btn--numpag', page === objPage.current_page && 'btn--pagact']">
        {{ page }}
      </Link>

      <!-- Next -->
      <Link v-if="objPage.next_page_url" :href="objPage.next_page_url" class="btn--prevnext">
        <svg viewBox="0 0 20 20" fill="currentColor" class="size-5">
          <path d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd"/>
        </svg>
      </Link>
    </nav>
  </div>
</template>
