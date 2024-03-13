<template>
  <TransitionGroup name="bounce-group">
    <slot></slot>
  </TransitionGroup>
</template>

<style scoped>
.bounce-group-enter-active {
  animation: bounce-in 0.3s;
}

.bounce-group-leave-active {
  animation: bounce-in 0.3s reverse;
}

.bounce-group-leave-active {
  position: absolute;
}

@keyframes bounce-in {
  0% {
    transform: scale(0);
  }
  50% {
    transform: scale(1.2);
  }
  100% {
    transform: scale(1);
  }
}
</style>
