<template>
  <div class="w-full rounded-lg">
    <base-carousel
        v-slot="{slide}"
        v-model="slides"
        v-model:current="currentSlide"
        :class-name="carouselSettings.className"
        :effect="carouselSettings.effect"
        :has-navigation="carouselSettings.hasNavigation"
        :navigation-display="carouselSettings.navigationDisplay"
        :has-pagination="carouselSettings.hasPagination"
        :breakpoints="carouselSettings.breakpoints"
        :autoplay="carouselSettings.autoplay"
        :wrap-around="carouselSettings.wrapAround"
    >
      <router-link
          :to="slide.link"
          class="w-full h-full rounded-lg"
      >
        <img
            :src="slide.image.path"
            alt=""
            class="w-full h-60 md:h-72 lg:h-80 xl:h-96 object-cover rounded-lg"
        >
      </router-link>
    </base-carousel>
  </div>
</template>

<script setup>
import {ref} from "vue";
import BaseCarousel from "../base/BaseCarousel.vue";

const carouselSettings = {
  className: 'main-slider',
  wrapAround: true,
  autoplay: 10000,
  effect: 'coverflow',
  hasNavigation: true,
  navigationDisplay: 'floating-sides',
  hasPagination: true,
  breakpoints: {},
}

const currentSlide = ref(0)
const slides = ref([
  {
    image: {
      path: '/src/assets/banners/b1.jpg',
    },
    link: '#',
  },
  {
    image: {
      path: '/src/assets/banners/b2.jpg',
    },
    link: '#',
  },
  {
    image: {
      path: '/src/assets/banners/b3.jpg',
    },
    link: '#',
  },
  {
    image: {
      path: '/src/assets/banners/b4.jpg',
    },
    link: '#',
  },
  {
    image: {
      path: '/src/assets/banners/b5.jpg',
    },
    link: '#',
  },
  {
    image: {
      path: '/src/assets/banners/b6.jpg',
    },
    link: '#',
  },
])
</script>

<style scoped>

</style>
