<template>
    <base-carousel
        v-slot="{slide, index}"
        v-model="relatedProducts"
        v-model:current="currentSlide"
        :snap-align="carouselSettings.snapAlign"
        :has-navigation="carouselSettings.hasNavigation"
        :navigation-position="carouselSettings.navigationPosition"
        :navigation-display="carouselSettings.navigationDisplay"
        :always-show-navigation-buttons="carouselSettings.alwaysShowNavigationButtons"
        :has-pagination="carouselSettings.hasPagination"
        :breakpoints="carouselSettings.breakpoints"
    >
        <product-card :product="slide"/>
    </base-carousel>
</template>

<script setup>
import {ref} from "vue";
import BaseCarousel from "./../base/BaseCarousel.vue";
import ProductCard from "./ProductCard.vue";

const props = defineProps({
    products: {
        type: Object,
        required: true,
    },
})

const relatedProducts = ref(props.products)
const currentSlide = ref(0)

const carouselSettings = {
    snapAlign: 'start',
    hasNavigation: true,
    navigationPosition: 'right',
    navigationDisplay: 'floating-sides',
    alwaysShowNavigationButtons: false,
    hasPagination: true,
    breakpoints: {
        0: {
            itemsToShow: 1,
        },
        450: {
            itemsToShow: 1.5,
        },
        576: {
            itemsToShow: 2,
            itemsToScroll: 2,
        },
        768: {
            itemsToShow: 2.85,
            itemsToScroll: 2,
        },
        991: {
            itemsToShow: 3.35,
            itemsToScroll: 3,
        },
        1280: {
            itemsToShow: 4.15,
            itemsToScroll: 4,
        },
    },
}
</script>

<style scoped>

</style>
