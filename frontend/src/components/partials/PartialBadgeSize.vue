<template>
  <span class="border-2 border-indigo-500 bg-indigo-50 rounded-lg py-1 px-3">{{ value.title }}</span>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true,
  },
})
</script>
