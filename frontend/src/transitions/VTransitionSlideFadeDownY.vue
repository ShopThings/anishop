<template>
    <Transition name="slide-fade-down-y">
        <slot></slot>
    </Transition>
</template>

<script setup>
</script>

<style scoped>
.slide-fade-down-y-enter-active {
    transition: all 0.2s ease-out;
    transition-delay: .02s;
}

.slide-fade-down-y-leave-active {
    transition: all 0.2s cubic-bezier(1, 0.5, 0.8, 1);
    transition-delay: .02s;
}

.slide-fade-down-y-enter-from,
.slide-fade-down-y-leave-to {
    transform: translateY(-12px);
    opacity: 0;
}
</style>
