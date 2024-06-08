<template>
  <div
    v-if="brandsLoading || !brands?.length"
    class="flex items-center overflow-hidden divide-x divide-x-reverse divide-slate-100"
  >
    <div
      v-for="i in 8"
      :key="i"
      class="min-w-48 h-36 p-3 flex items-center justify-center bg-white animate-pulse"
    >
      <PhotoIcon class="size-10 text-orange-200"/>
    </div>
  </div>
  <template v-else>
    <partial-general-title
      container-class="mb-5 mt-6 p-2"
      title="محبوب‌ترین برندها"
      title-size="text-xl"
      type="side"
    />

    <base-carousel
      v-slot="slide"
      v-model="brands"
      v-model:current="currentSlide"
      :breakpoints="carouselSettings.breakpoints"
      :class-name="carouselSettings.className"
      :free-mode="carouselSettings.freeMode"
      :has-navigation="carouselSettings.hasNavigation"
      :has-pagination="carouselSettings.hasPagination"
      :navigation-display="carouselSettings.navigationDisplay"
      :space-between="carouselSettings.spaceBetween"
      :wrap-around="carouselSettings.wrapAround"
    >
      <div class="p-3 h-36 flex items-center justify-center bg-white">
        <router-link :to="{name: 'search', query: {brand: slide.slide.id}}">
          <base-lazy-image
            v-if="slide.slide.image?.path"
            :alt="slide?.name"
            :is-local="false"
            :lazy-src="slide.slide.image?.path"
          />
          <img
            v-else
            :alt="slide?.name"
            src="/image-placeholder.jpg"
          >
        </router-link>
      </div>
    </base-carousel>
  </template>
</template>

<script setup>
import {onMounted, ref} from "vue";
import {PhotoIcon} from "@heroicons/vue/24/outline/index.js"
import PartialGeneralTitle from "@/components/partials/PartialGeneralTitle.vue";
import BaseCarousel from "@/components/base/BaseCarousel.vue";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import {HomeMainPageAPI} from "@/service/APIHomePages.js";

const emit = defineEmits(['loaded'])

const brands = ref(null)
const brandsLoading = ref(true)

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
      slidesPerView: 1.15,
    },
    300: {
      slidesPerView: 2.5,
    },
    400: {
      slidesPerView: 3.4,
    },
    576: {
      slidesPerView: 4.25,
    },
    768: {
      slidesPerView: 5.5,
    },
    991: {
      slidesPerView: 6.5,
    },
    1280: {
      slidesPerView: 7.25,
    },
  },
}

onMounted(() => {
  HomeMainPageAPI.fetchSliderPopularBrands({
    success(response) {
      brands.value = response.data
    },
    error() {
      return false
    },
    finally() {
      brandsLoading.value = false
      emit('loaded', !!brands.value?.length)
    },
  })
})
</script>
