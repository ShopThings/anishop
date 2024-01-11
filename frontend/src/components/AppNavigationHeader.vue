<template>
  <div
    :class="containerClass"
    class="px-3 py-6"
  >
    <h1 v-if="title && title.length" class="text-2xl font-iranyekan-bold">
      {{ title }}
    </h1>
  </div>
</template>

<script setup>
defineProps({
  containerClass: String,
  title: String,
})
</script>
