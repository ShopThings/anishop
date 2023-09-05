<template>
    <div>
        <label
            class="text-xs py-1 px-2 mt-1 inline-block rounded-md"
            :class="klass"
        >
            {{ text }}
        </label>
    </div>
</template>

<script setup>
defineProps({
    text: String,
    klass: {
        type: String,
        default: 'text-white bg-blue-500',
    },
})
</script>

<style scoped>

</style>
