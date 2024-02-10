<template>
  <div class="inline-flex items-center gap-2">
    <span
      v-tooltip.top="{message: title}"
      class="inline-block w-5 h-5 rounded-full border"
      :style="'background-color: ' + hex + ';'"
    ></span>
    <span>{{ value.title }}</span>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true,
  },
  hex: {
    type: String,
    required: true,
  },
})
</script>
