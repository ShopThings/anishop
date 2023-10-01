<template>
    <div
        class="flex"
        :class="determineClass"
    >
        <button
            class="border rounded-full shadow-lg bg-white bg-opacity-90 hover:bg-opacity-100 transition group p-1 w-9 h-9"
            :class="[
                display === 'floating-sides'
                 ? (
                     hasPagination
                     ? 'absolute right-1 top-1/2 -translate-y-full'
                     : 'absolute right-1 top-1/2 -translate-y-1/2'
                     )
                 : '',
                 !alwaysShowButtons && currentSlide === 0 ? 'hidden' : ''
            ]"
            @click="prevHandler"
        >
            <ChevronRightIcon class="w-6 h-6 text-gray-500 group-hover:text-black transition mx-auto"/>
        </button>
        <button
            class="border rounded-full shadow-lg bg-white bg-opacity-90 hover:bg-opacity-100 transition group p-1 w-9 h-9"
            :class="[
                display === 'floating-sides'
                 ? (
                     hasPagination
                     ? 'absolute left-1 top-1/2 -translate-y-full'
                     : 'absolute left-1 top-1/2 -translate-y-1/2'
                     )
                 : 'mr-2',
                 !alwaysShowButtons && currentSlide === props.slidesCount - 1 ? 'hidden' : ''
            ]"
            @click="nextHandler"
        >
            <ChevronLeftIcon class="w-6 h-6 text-gray-500 group-hover:text-black transition mx-auto"/>
        </button>
    </div>
</template>

<script setup>
import {ChevronLeftIcon, ChevronRightIcon} from "@heroicons/vue/24/outline/index.js"
import {computed, ref, watch} from "vue";
import {useWindowSize} from "@vueuse/core";

const props = defineProps({
    carousel: {
        type: Object,
        required: true,
    },
    slideWidth: Number,
    currentSlide: Number,
    slidesCount: Number,
    itemsToScroll: Number,
    hasPagination: Boolean,
    position: {
        type: String,
        default: 'left',
        validator: (value) => {
            return ['left', 'right'].indexOf(value) !== -1
        },
    },
    display: {
        type: String,
        default: 'floating',
        validator: (value) => {
            return ['solid', 'floating', 'solid-sides', 'solid-center', 'floating-sides'].indexOf(value) !== -1
        },
    },
    alwaysShowButtons: {
        type: Boolean,
        default: true,
    },
    breakpoints: Object,
})

const determineClass = computed(() => {
    let klass = ''

    switch (props.display) {
        case 'solid':
            klass += 'mt-3 '
            if (props.position === 'right') {
                klass += 'justify-start '
            } else {
                klass += 'justify-end '
            }
            break
        case 'floating':
            klass += 'absolute '
            if (props.position === 'right') {
                klass += 'right-5 '
            } else {
                klass += 'left-5 '
            }
            if (props.hasPagination) {
                klass += 'bottom-7 -translate-y-full '
            } else {
                klass += 'bottom-1 -translate-y-1/2 '
            }
            break
        case 'solid-sides':
            klass += 'mt-3 justify-between '
            break
        case 'solid-center':
            klass += 'mt-3 justify-center '
            break
        case 'floating-sides':
            klass += 'absolute top-1/2 left-0 w-full '
            break
    }

    return klass.trim()
})

const determinedItemsToScroll = ref(props.itemsToScroll)
const {width} = useWindowSize()

function doDetermination() {
    determinedItemsToScroll.value = props.itemsToScroll
    for (let o in props.breakpoints) {
        if (props.breakpoints.hasOwnProperty(o)) {
            if (width.value >= o && props.breakpoints[o].itemsToScroll) {
                determinedItemsToScroll.value = props.breakpoints[o].itemsToScroll
            }
        }
    }
}

doDetermination()
watch(width, () => {
    doDetermination()
})

function nextHandler() {
    const next = (props.currentSlide >= props.slidesCount - 1) ? 0 : props.currentSlide + determinedItemsToScroll.value
    props.carousel.slideTo(next)
}

function prevHandler() {
    const prev = (props.currentSlide <= 0) ? props.slidesCount - 1 : props.currentSlide - determinedItemsToScroll.value
    props.carousel.slideTo(prev)
}
</script>

<style scoped>

</style>
