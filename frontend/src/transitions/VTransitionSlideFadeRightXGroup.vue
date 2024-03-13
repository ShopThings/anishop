<template>
  <TransitionGroup name="slide-fade-right-x-group">
    <slot></slot>
  </TransitionGroup>
</template>

<style scoped>
.slide-fade-right-x-group-enter-active {
  transition: all 0.2s ease-out;
  transition-delay: .02s;
}

.slide-fade-right-x-group-leave-active {
  position: absolute;
  transition: all 0.2s cubic-bezier(1, 0.5, 0.8, 1);
  transition-delay: .02s;
}

.slide-fade-right-x-group-enter-from,
.slide-fade-right-x-group-leave-to {
  transform: translateX(-12px);
  opacity: 0;
}
</style>
