<template>
    <TransitionGroup name="slide-fade-left-x-group">
        <slot></slot>
    </TransitionGroup>
</template>

<script setup>
</script>

<style scoped>
.slide-fade-left-x-group-enter-active {
    transition: all 0.2s ease-out;
    transition-delay: .02s;
}

.slide-fade-left-x-group-leave-active {
    position: absolute;
    transition: all 0.2s cubic-bezier(1, 0.5, 0.8, 1);
    transition-delay: .02s;
}

.slide-fade-left-x-group-enter-from,
.slide-fade-left-x-group-leave-to {
    transform: translateX(12px);
    opacity: 0;
}
</style>
