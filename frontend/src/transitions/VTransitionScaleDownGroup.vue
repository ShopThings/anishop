<template>
    <TransitionGroup name="scale-down-group">
        <slot></slot>
    </TransitionGroup>
</template>

<script setup>
</script>

<style scoped>
.scale-down-group-enter-active,
.scale-down-group-leave-active {
    transition: transform 0.2s ease-in-out;
}

.scale-down-group-enter-from {
    opacity: 0;
}

.scale-down-group-leave-to {
    opacity: 0;
}

.scale-down-group-enter-from,
.scale-down-group-leave-to {
    transform: scale(1.1);
}

.scale-down-group-leave-active {
    position: absolute;
}
</style>
