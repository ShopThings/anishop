<template>
  <div
      :style="{backgroundColor: colorHex, color: getTextColor(colorHex)}"
      class="rounded py-1 px-3 text-white inline-block text-center text-xs"
  >
    {{ text }}
  </div>
</template>

<script setup>
import {getTextColor} from "@/composables/helper.js";

defineProps({
  text: {
    type: String,
    default: 'نامشخص',
  },
  colorHex: {
    type: String,
    default: '#000000',
  },
})
</script>
