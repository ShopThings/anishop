<template>
    <base-button class="group flex justify-center items-center">
        <slot name="icon" klass="group-hover:scale-110 group-hover:scale-110 transition-all"></slot>
        <slot></slot>
    </base-button>
</template>

<script setup>
import BaseButton from "./BaseButton.vue";
</script>

<style scoped>

</style>
