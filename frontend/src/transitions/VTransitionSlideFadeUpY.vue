<template>
  <Transition name="slide-fade-up-y">
    <slot></slot>
  </Transition>
</template>

<style scoped>
.slide-fade-up-y-enter-active {
  transition: all 0.2s ease-out;
  transition-delay: .02s;
}

.slide-fade-up-y-leave-active {
  transition: all 0.2s cubic-bezier(1, 0.5, 0.8, 1);
  transition-delay: .02s;
}

.slide-fade-up-y-enter-from,
.slide-fade-up-y-leave-to {
  transform: translateY(12px);
  opacity: 0;
}
</style>
