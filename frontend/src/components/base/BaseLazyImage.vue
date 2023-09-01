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
        type: String,
        default: '',
    },
})
</script>

<style scoped>
.app-image {
    max-width: 100%;
    max-height: 100%;
    vertical-align: middle;
    object-fit: contain;
}

.app-image[lazy=loading] {
    width: 100%;
}
</style>
