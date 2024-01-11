<template>
  <partial-general-title
    type="side"
    title="محبوب‌ترین برندها"
    title-size="text-xl"
    container-class="mb-5 mt-6 p-2"
  />

  <base-carousel
    v-slot="{slide, index}"
    v-model="brands"
    v-model:current="currentSlide"
    :class-name="carouselSettings.className"
    :space-between="carouselSettings.spaceBetween"
    :wrap-around="carouselSettings.wrapAround"
    :has-navigation="carouselSettings.hasNavigation"
    :navigation-display="carouselSettings.navigationDisplay"
    :has-pagination="carouselSettings.hasPagination"
    :free-mode="carouselSettings.freeMode"
    :breakpoints="carouselSettings.breakpoints"
  >
    <div class="p-3 h-36 flex items-center justify-center bg-white">
      <router-link to="">
        <base-lazy-image
          :lazy-src="slide.image.path"
          :alt="slide?.title"
        />
      </router-link>
    </div>
  </base-carousel>
</template>

<script setup>
import {ref} from "vue";
import PartialGeneralTitle from "../partials/PartialGeneralTitle.vue";
import BaseCarousel from "../base/BaseCarousel.vue";
import BaseLazyImage from "../base/BaseLazyImage.vue";

const brands = ref([
  {
    image: {
      path: '/src/assets/brands/b1.png',
    },
  },
  {
    image: {
      path: '/src/assets/brands/b2.png',
    },
  },
  {
    image: {
      path: '/src/assets/brands/b3.png',
    },
  },
  {
    image: {
      path: '/src/assets/brands/b4.png',
    },
  },
  {
    image: {
      path: '/src/assets/brands/b5.png',
    },
  },
  {
    image: {
      path: '/src/assets/brands/b6.png',
    },
  },
  {
    image: {
      path: '/src/assets/brands/b7.png',
    },
  },
  {
    image: {
      path: '/src/assets/brands/b8.png',
    },
  },
  {
    image: {
      path: '/src/assets/brands/b9.png',
    },
  },
  {
    image: {
      path: '/src/assets/brands/b10.png',
    },
  },
  {
    image: {
      path: '/src/assets/brands/b11.png',
    },
  },
])

const currentSlide = ref(0)

const carouselSettings = {
  className: 'brands-main',
  spaceBetween: 0,
  wrapAround: false,
  hasNavigation: true,
  navigationDisplay: 'floating-sides',
  hasPagination: false,
  freeMode: true,
  breakpoints: {
    0: {
      slidesPerView: 1.25,
    },
    400: {
      slidesPerView: 2,
    },
    576: {
      slidesPerView: 3,
    },
    768: {
      slidesPerView: 4.85,
    },
    991: {
      slidesPerView: 5.35,
    },
    1280: {
      slidesPerView: 6,
    },
  },
}
</script>
