<template>
    <img
        v-lazy="{ src: lazySrc, loading: '/image-placeholder.jpg' }"
        :alt="alt"
        class="app-image"
    >
</template>

<script setup>
defineProps({
    lazySrc: String,
    alt: {
        type: Stirng,
        default: '',
    },
})
</script>

<style scoped>
.app-image {
    max-width: 100%;
    max-height: 100%;
    width: auto;
    height: auto;
    vertical-align: middle;
}

.app-image[lazy=loading] {
    width: 100%;
    height: 300px;
}
</style>
