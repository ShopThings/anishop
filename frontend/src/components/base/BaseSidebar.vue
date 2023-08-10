<template>
    <div
         class="w-72 fixed invisible -right-full h-screen z-10 lg:w-80 lg:top-0 lg:relative lg:block">
        <nav :class="bg"
             class="w-full">
            <slot/>
        </nav>
    </div>
</template>

<script setup>
defineProps({
    bg: {
        type: String,
        default: 'bg-indigo-600'
    }
})
</script>

<style scoped>

</style>
