<template>
  <Transition name="scale-down">
    <slot></slot>
  </Transition>
</template>

<style scoped>
.scale-down-enter-active,
.scale-down-leave-active {
  transition: transform 0.2s ease-in-out;
}

.scale-down-enter-from {
  opacity: 0;
}

.scale-down-leave-to {
  opacity: 0;
}

.scale-down-enter-from,
.scale-down-leave-to {
  transform: scale(1.1);
}
</style>
