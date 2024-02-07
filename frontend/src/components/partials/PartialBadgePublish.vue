<template>
  <span v-if="publish" :class="containerClass"
        class="text-sm bg-emerald-200 text-emerald-700 whitespace-nowrap">{{ publishText }}</span>
  <span v-else :class="containerClass"
        class="text-sm bg-rose-200 text-rose-700 whitespace-nowrap">{{ unpublishText }}</span>
</template>

<script setup>
defineProps({
  publish: Boolean,
  publishText: {
    type: String,
    default: 'منتشر شده',
  },
  unpublishText: {
    type: String,
    default: 'عدم انتشار',
  },
  containerClass: {
    type: String,
    default: 'rounded-lg px-2',
  },
})
</script>
