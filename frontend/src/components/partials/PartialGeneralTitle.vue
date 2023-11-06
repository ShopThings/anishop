<template>

    <div
        v-if="type === 'side'"
        :class="containerClass"
    >
        <h1 class="flex flex-col gap-2 text-center">
            <span class="font-iranyekan-bold">{{ title }}</span>
            <span class="h-0.5 w-12 bg-primary rounded-full mx-auto"></span>
        </h1>
    </div>

    <div
        v-else
        class="flex gap-3 items-center justify-between"
        :class="containerClass"
    >
        <h1 class="border-b-2 border-primary inline-block pb-2 font-iranyekan-bold">
            <template v-if="slots['title']">
                <slot name="title"></slot>
            </template>
            <template v-else>
                {{ title }}
            </template>
        </h1>
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
    type: {
        type: String,
        default: 'general',
        validator: (value) => {
            return ['general', 'side'].indexOf(value) !== -1
        },
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
