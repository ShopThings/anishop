<template>
  <base-carousel
    v-slot="{slide, index}"
    v-model="relatedProducts"
    v-model:current="currentSlide"
    :has-navigation="carouselSettings.hasNavigation"
    :navigation-display="carouselSettings.navigationDisplay"
    :has-pagination="carouselSettings.hasPagination"
    :free-mode="carouselSettings.freeMode"
    :breakpoints="carouselSettings.breakpoints"
  >
    <slot name="slide" :slide="slide" :index="index">
      <div class="w-full h-full">
        <product-card :product="slide"/>
      </div>
    </slot>
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
  hasNavigation: true,
  navigationDisplay: 'floating-sides',
  hasPagination: false,
  freeMode: true,
  breakpoints: {
    0: {
      slidesPerView: 1,
    },
    450: {
      slidesPerView: 1.5,
    },
    576: {
      slidesPerView: 2,
    },
    768: {
      slidesPerView: 2.85,
    },
    991: {
      slidesPerView: 3.35,
      spaceBetween: 20,
    },
    1280: {
      slidesPerView: 4.15,
    },
  },
}
</script>
