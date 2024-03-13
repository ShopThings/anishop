<template>
  <div>
    <label
        :class="klass"
        class="text-xs py-1 px-2.5 mt-1 inline-block rounded-sm"
    >
      <slot v-if="slot['text']" name="text"></slot>
      <template v-else>{{ text }}</template>
    </label>
  </div>
</template>

<script setup>
import {useSlots} from "vue";

defineProps({
  text: String,
  klass: {
    type: String,
    default: 'text-black bg-sky-100 border-r-4 border-sky-500',
  },
})

const slot = useSlots()
</script>
