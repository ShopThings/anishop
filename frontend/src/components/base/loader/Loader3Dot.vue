<template>
  <div
      class="loader"
      :style="'--bg-color:' + bgColor"
  ></div>
</template>

<script setup>
import {isValidColorHex} from "@/validation/index.js";

defineProps({
  bgColor: {
    type: String,
    default: '#96affa',
    validator: (value) => {
      return isValidColorHex(value)
    },
  },
})
</script>

<style scoped>
.loader {
  width: 60px;
  aspect-ratio: 2;
  --bg-color: #000;
  --_g: no-repeat radial-gradient(circle closest-side, var(--bg-color) 90%, transparent);
  background: var(--_g) 0 50%, var(--_g) 50% 50%, var(--_g) 100% 50%;
  background-size: calc(100% / 3) 50%;
  animation: l3 1s infinite linear;
}

@keyframes l3 {
  20% {
    background-position: 0 0, 50% 50%, 100% 50%
  }
  40% {
    background-position: 0 100%, 50% 0, 100% 50%
  }
  60% {
    background-position: 0 50%, 50% 100%, 100% 0
  }
  80% {
    background-position: 0 50%, 50% 50%, 100% 100%
  }
}
</style>
