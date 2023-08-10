<template>
    <TransitionGroup name="scale-up-group">
        <slot></slot>
    </TransitionGroup>
</template>

<script setup>
</script>

<style scoped>
.scale-up-group-enter-active,
.scale-up-group-leave-active {
    transition: transform 0.2s ease-in-out;
}

.scale-up-group-enter-from {
    opacity: 0;
}

.scale-up-group-leave-to {
    opacity: 0;
}

.scale-up-group-enter-from,
.scale-up-group-leave-to {
    transform: scale(.9);
}

.scale-up-group-leave-active {
    position: absolute;
}
</style>
