<template>
  <Transition name="scale-up">
    <slot></slot>
  </Transition>
</template>

<style scoped>
.scale-up-enter-active,
.scale-up-leave-active {
  transition: transform 0.2s ease-in-out;
}

.scale-up-enter-from {
  opacity: 0;
}

.scale-up-leave-to {
  opacity: 0;
}

.scale-up-enter-from,
.scale-up-leave-to {
  transform: scale(.9);
}
</style>
