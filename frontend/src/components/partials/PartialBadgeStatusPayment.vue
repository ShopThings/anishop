<template>
  <div
      class="rounded py-1 px-3 bg-rose-500 text-white inline-block text-center text-xs mix-blend-difference"
      :style="{backgroundColor: colorHex}"
  >
    {{ text }}
  </div>
</template>

<script setup>
defineProps({
  text: {
    type: String,
    required: true,
    default: 'نامشخص',
  },
  colorHex: {
    type: String,
    default: '#000000',
  },
})
</script>

<style scoped>
.mix-blend-difference {
  mix-blend-mode: difference
}
</style>
