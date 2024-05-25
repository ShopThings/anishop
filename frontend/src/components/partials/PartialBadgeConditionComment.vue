<template>
  <div
      :style="{backgroundColor: bgColor, color: getTextColor(bgColor)}"
      class="rounded py-1 px-3 text-white inline-block text-center text-xs whitespace-nowrap"
  >
    {{ condition?.text ?? 'نامشخص' }}
  </div>
</template>

<script setup>
import {computed} from "vue";
import {getTextColor} from "@/composables/helper.js";
import {COMMENT_STATUSES} from "@/composables/constants.js";

const props = defineProps({
  condition: Object,
})

const bgColor = computed(() => {
  for (let s in COMMENT_STATUSES) {
    if (
        COMMENT_STATUSES.hasOwnProperty(s) &&
        COMMENT_STATUSES[s].value === props.condition?.value
    ) {
      return COMMENT_STATUSES[s].color_hex
    }
  }

  return '#000000'
})
</script>
