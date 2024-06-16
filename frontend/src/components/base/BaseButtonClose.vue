<template>
  <button
    class="group"
    type="button"
    @click="() => {emit('click')}"
  >
    <XMarkIcon
      :class="iconSize"
      class="group-hover:rotate-90 group-hover:text-rose-500 transition"
    />
  </button>
</template>

<script setup>
import {XMarkIcon} from '@heroicons/vue/24/solid'

defineProps({
  iconSize: {
    type: String,
    default: 'size-6',
  },
})
const emit = defineEmits(['click'])
</script>
