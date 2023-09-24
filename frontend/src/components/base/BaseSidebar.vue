<template>
    <div class="relative h-full">
        <nav class="w-20 sticky top-0 hidden md:block" :class="bg">
            <slot/>
        </nav>
    </div>
</template>

<script setup>
defineProps({
    bg: {
        type: String,
        default: 'bg-indigo-600 text-white'
    },
})
</script>

<style scoped>

</style>
