<template>
  <div
      :style="{backgroundColor: bgColor, color: getTextColor(bgColor)}"
      class="rounded py-1 px-3 text-white inline-block text-center text-xs"
  >
    {{ status.text }}
  </div>
</template>

<script setup>
import {computed} from "vue";
import {getTextColor} from "@/composables/helper.js";
import {COMMENT_SEEN_STATUSES} from "@/composables/constants.js";

const props = defineProps({
  status: Object,
})

const bgColor = computed(() => {
  for (let s in COMMENT_SEEN_STATUSES) {
    if (
        COMMENT_SEEN_STATUSES.hasOwnProperty(s) &&
        COMMENT_SEEN_STATUSES[s].value === props.status.value
    ) {
      COMMENT_SEEN_STATUSES[s].color_hex
    }
  }
})
</script>
