<template>
    <ul
        v-if="numberOfBullets > 1"
        class="flex flex-wrap p-3 justify-center items-center"
    >
        <li
            v-for="i in numberOfBullets"
            class=" h-4 rounded-full border hover:bg-blue-100 transition-all mx-0.5 cursor-pointer shadow shadow-slate-400"
            :class="[
                (currentSlide < (i * determinedItemsToScroll) && currentSlide >= ((i - 1) * determinedItemsToScroll))
                ? 'w-8 border-blue-500 bg-blue-300'
                : 'w-4 bg-white'
            ]"
            @click="carousel.slideTo((i - 1) * determinedItemsToScroll)"
        ></li>
    </ul>
</template>

<script setup>
import {ref, watch} from "vue";
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
    breakpoints: Object,
})

const numberOfBullets = ref(props.slidesCount / props.itemsToScroll)
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
    numberOfBullets.value = Math.ceil(props.slidesCount / determinedItemsToScroll.value)
}

doDetermination()
watch(width, () => {
    doDetermination()
})
</script>

<style scoped>

</style>
