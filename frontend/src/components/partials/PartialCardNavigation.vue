<template>
    <router-link
        :to="to"
        :class="[
            'flex items-center justify-between py-3 px-6 rounded-lg w-full h-auto shadow min-h-[5rem]',
            'hover:scale-105 transition focus:scale-105',
            bgColor,
        ]"
    >
        <slot></slot>
    </router-link>
</template>

<script setup>
defineProps({
    to: {
        type: [Object, String],
        required: true,
    },
    bgColor: {
        type: String,
        default: '',
    },
})
</script>

<style scoped>

</style>
