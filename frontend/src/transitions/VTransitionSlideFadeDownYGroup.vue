<template>
    <TransitionGroup name="slide-fade-down-y-group">
        <slot></slot>
    </TransitionGroup>
</template>

<script setup>
</script>

<style scoped>
.slide-fade-down-y-group-enter-active {
    transition: all 0.2s ease-out;
    transition-delay: .02s;
}

.slide-fade-down-y-group-leave-active {
    position: absolute;
    transition: all 0.2s cubic-bezier(1, 0.5, 0.8, 1);
    transition-delay: .02s;
}

.slide-fade-down-y-group-enter-from,
.slide-fade-down-y-group-leave-to {
    transform: translateY(-12px);
    opacity: 0;
}
</style>
