<template>
  <ul
      v-if="keywords && keywords.length"
      class="flex flex-wrap gap-2.5 min-w-60"
  >
    <li
        v-for="(keyword, idx) in keywords"
        :key="idx"
        class="rounded border-2 bg-violet-50 border-violet-500 text-xs py-0.5 px-2"
    >
      {{ keyword }}
    </li>
  </ul>
  <MinusIcon
      v-else
      class="h-5 w-5 text-rose-500"
  />
</template>

<script setup>
import {MinusIcon} from "@heroicons/vue/24/outline/index.js"

defineProps({
  keywords: {
    type: Array,
    required: true,
  },
})
</script>
