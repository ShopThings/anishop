<template>
  <Transition name="slide-fade-left-x">
    <slot></slot>
  </Transition>
</template>

<script setup>
</script>

<style scoped>
.slide-fade-left-x-enter-active {
  transition: all 0.2s ease-out;
  transition-delay: .02s;
}

.slide-fade-left-x-leave-active {
  transition: all 0.2s cubic-bezier(1, 0.5, 0.8, 1);
  transition-delay: .02s;
}

.slide-fade-left-x-enter-from,
.slide-fade-left-x-leave-to {
  transform: translateX(12px);
  opacity: 0;
}
</style>
