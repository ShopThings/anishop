<template>
  <label
      class="mb-1 text-right text-black flex justify-between items-center"
      :for="id"
  >
        <span class="text-sm">
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
})
</script>

<style scoped>

</style>
