<template>

    <div
        v-if="type === 'side'"
        :class="containerClass"
    >
        <h1 class="text-center font-iranyekan-bold">
            <template v-if="slots['title']">
                <slot name="title"></slot>
            </template>
            <template v-else>
                <span :class="titleSize" class="font-iranyekan-bold">{{ title }}</span>
            </template>

            <span :class="lineClass" class="h-[3px] w-12 rounded-full mx-auto block mt-2.5"></span>
        </h1>
    </div>

    <div
        v-else
        class="flex gap-3 items-center justify-between"
        :class="containerClass"
    >
        <h1
            :class="titleSize"
            class="border-b-[3px] border-primary inline-block pb-2 font-iranyekan-bold"
        >
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
    lineClass: {
        type: String,
        default: 'bg-primary',
    },
    type: {
        type: String,
        default: 'general',
        validator: (value) => {
            return ['general', 'side'].indexOf(value) !== -1
        },
    },
    title: String,
    titleSize: String,
})
const slots = useSlots()
</script>

<style scoped>

</style>
