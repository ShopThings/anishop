<template>
  <button
      class="group"
      type="button"
      @click="() => {emit('click')}"
  >
    <XMarkIcon class="h-6 w-6 group-hover:rotate-90 group-hover:text-rose-500 transition"/>
  </button>
</template>

<script setup>
import {XMarkIcon} from '@heroicons/vue/24/solid'

const emit = defineEmits(['click'])
</script>
