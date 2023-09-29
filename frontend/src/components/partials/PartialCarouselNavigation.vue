<template>
    <div
        class="absolute flex left-5"
        :class="[
            hasPagination ? 'bottom-7 -translate-y-full' : 'bottom-1 -translate-y-1/2',
        ]"
    >
        <button
            class="border rounded-full shadow-lg bg-white bg-opacity-90 hover:bg-opacity-100 transition group p-1 w-9 h-9"
            @click="prevHandler"
        >
            <ChevronRightIcon class="w-6 h-6 text-gray-500 group-hover:text-black transition mx-auto"/>
        </button>
        <button
            class="border rounded-full shadow-lg bg-white bg-opacity-90 hover:bg-opacity-100 transition mr-2 group p-1 w-9 h-9"
            @click="nextHandler"
        >
            <ChevronLeftIcon class="w-6 h-6 text-gray-500 group-hover:text-black transition mx-auto"/>
        </button>
    </div>
</template>

<script setup>
import {ChevronLeftIcon, ChevronRightIcon} from "@heroicons/vue/24/outline/index.js"

const props = defineProps({
    carousel: {
        type: Object,
        required: true,
    },
    slideWidth: Number,
    currentSlide: Number,
    slidesCount: Number,
    hasPagination: Boolean,
})

function nextHandler() {
    const next = (props.currentSlide >= props.slidesCount - 1) ? 0 : props.currentSlide + 1
    props.carousel.slideTo(next)
}

function prevHandler() {
    const prev = (props.currentSlide <= 0) ? props.slidesCount - 1 : props.currentSlide - 1
    props.carousel.slideTo(prev)
}
</script>

<style scoped>

</style>
