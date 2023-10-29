<template>
    <div
        class="flex gap-3 items-center justify-between"
        :class="containerClass"
    >
        <h2 class="border-b-2 border-primary inline-block pb-2">
            <template v-if="slots['title']">
                <slot name="title"></slot>
            </template>
            <template v-else>
                {{ title }}
            </template>
        </h2>
        <div v-if="slots['extra']">
            <slot name="extra"></slot>
        </div>
    </div>
</template>

<script setup>
import {useSlots} from "vue";

defineProps({
    containerClass: {
        type: String,
        default: 'mb-2 mt-6 p-2',
    },
    title: {
        type: String,
        required: true,
    },
})
const slots = useSlots()
</script>

<style scoped>

</style>
