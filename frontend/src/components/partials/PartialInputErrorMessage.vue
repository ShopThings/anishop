<template>
  <VTransitionSlideFadeLeftX>
    <div v-if="errorMessage" class="flex items-center p-1 text-rose-500">
      <MinusIcon class="h-4 w-4 ml-1"/>
      <span class="block text-right text-sm">{{ errorMessage }}</span>
    </div>
  </VTransitionSlideFadeLeftX>
</template>

<script setup>
import {MinusIcon} from "@heroicons/vue/24/outline/index.js";
import VTransitionSlideFadeLeftX from "../../transitions/VTransitionSlideFadeLeftX.vue";

defineProps({
  errorMessage: String,
})
</script>
