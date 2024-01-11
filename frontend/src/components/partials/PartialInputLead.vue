<template>
  <div>
    <label
      class="text-xs py-1 px-2 mt-1 inline-block rounded-md"
      :class="klass"
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
    default: 'text-white bg-blue-500',
  },
})

const slot = useSlots()
</script>
