<template>
    <TransitionGroup name="fade-group">
        <slot></slot>
    </TransitionGroup>
</template>

<script setup>
</script>

<style scoped>
.fade-group-enter-active,
.fade-group-leave-active {
    transition: opacity 0.3s ease;
    transition-delay: .05s;
}

.fade-group-enter-from,
.fade-group-leave-to {
    opacity: 0;
}

.fade-group-leave-active {
    position: absolute;
}
</style>
