<template>
  <label
    :class="[extraClass, space]"
    :for="id"
    class="text-right text-black flex justify-between items-center gap-1.5"
  >
    <span class="text-sm w-full">
        <slot name="label">{{ title }}</slot>
    </span>
    <span v-if="isOptional" class="text-sm text-gray-400">(اختیاری)</span>
  </label>
</template>

<script setup>
defineProps({
  title: String,
  id: String,
  isOptional: Boolean,
  space: {
    type: [Object, String],
    default: 'mb-1'
  },
  extraClass: [String, Array],
})
</script>
