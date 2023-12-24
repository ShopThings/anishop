<template>
  <base-button
      class="!absolute top-0 left-0 -translate-y-1/4 -translate-x-1/4 bg-rose-500 !p-1 z-[1]"
      @click="emit('click')"
  >
    <TrashIcon class="h-5 w-5"/>
  </base-button>
</template>

<script setup>
import {TrashIcon} from "@heroicons/vue/24/outline/index.js";
import BaseButton from "../base/BaseButton.vue";

const emit = defineEmits(['click'])
</script>

<style scoped>

</style>
