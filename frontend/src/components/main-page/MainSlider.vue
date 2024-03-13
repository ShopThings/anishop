<template>
  <div
      v-if="slidesLoading || !slides?.length"
      class="w-full h-full rounded-lg animate-pulse"
  >
    <div
        class="w-full h-60 md:h-72 lg:h-80 xl:h-96 rounded-lg flex items-center justify-center bg-slate-200"
    >
      <PhotoIcon class="size-12 md:size-16 text-slate-400"/>
    </div>
  </div>
  <base-carousel
      v-else
      v-slot="{slide, index}"
      v-model="slides"
      v-model:current="currentSlide"
      :autoplay="carouselSettings.autoplay"
      :breakpoints="carouselSettings.breakpoints"
      :class-name="carouselSettings.className"
      :effect="carouselSettings.effect"
      :has-navigation="carouselSettings.hasNavigation"
      :has-pagination="carouselSettings.hasPagination"
      :navigation-display="carouselSettings.navigationDisplay"
      :wrap-around="carouselSettings.wrapAround"
  >
    <router-link
        :to="slide.link"
        class="w-full h-full rounded-lg"
        target="_blank"
    >
      <img
          :alt="'اسلاید ' + index"
          :src="slide.image.path"
          class="w-full h-60 md:h-72 lg:h-80 xl:h-96 object-cover rounded-lg"
      >
    </router-link>
  </base-carousel>
</template>

<script setup>
import {onMounted, ref} from "vue";
import {PhotoIcon} from "@heroicons/vue/24/outline/index.js"
import BaseCarousel from "@/components/base/BaseCarousel.vue";
import {HomeMainPageAPI} from "@/service/APIHomePages.js";

const emit = defineEmits(['loaded'])

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
const slides = ref(null)
const slidesLoading = ref(true)

onMounted(() => {
  HomeMainPageAPI.fetchSliderMain({
    success(response) {
      slides.value = response.data
    },
    error() {
      return false
    },
    finally() {
      slidesLoading.value = false
      emit('loaded', !!slides.value?.length)
    },
  })
})
</script>
