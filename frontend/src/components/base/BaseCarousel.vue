<template>
  <div class="relative">
    <swiper-container
        ref="carousel"
        :class="className"
        init="false"
    >
      <swiper-slide v-for="(slide, idx) in slides" :key="idx">
        <slot :index="idx" :slide="slide"></slot>
      </swiper-slide>
    </swiper-container>

    <partial-carousel-navigation
        v-if="hasNavigation"
        :dir="dir"
        :display="navigationDisplay"
        :next-class-name="className + '-next'"
        :position="navigationPosition"
        :prev-class-name="className + '-prev'"
        :size="navigationSize"
    />
  </div>

  <div
      v-if="useThumbnail"
      class="mt-3"
  >
    <swiper-container
        ref="thumbsSwiper"
        :a11y="a11y"
        :breakpoints="{
            360: {
            slidesPerView: 3.5,
            },
            640: {
            slidesPerView: 5,
            },
            768: {
            slidesPerView: 6,
            },
            1024: {
            slidesPerView: 3.5,
            },
        }"
        :class="className + '-thumbnail'"
        :dir="dir"
        :free-mode="true"
        :initial-slide="currentSlide"
        :loop="false"
        :slides-per-view="4"
        :space-between="10"
        :speed="transition"
        :watch-slides-progress="true"
    >
      <swiper-slide v-for="(slide, idx) in slides" :key="idx">
        <slot :index="idx" :slide="slide" name="thumbSlide"></slot>
      </swiper-slide>
    </swiper-container>
  </div>
</template>

<script setup>
import 'swiper/swiper-bundle.css';

import {computed, onBeforeUnmount, ref, watchEffect} from "vue";
import {watchImmediate} from "@vueuse/core";
import {A11y, Autoplay, EffectCoverflow, EffectFade, EffectFlip, FreeMode, Thumbs,} from 'swiper/modules';
import PartialCarouselNavigation from "@/components/partials/PartialCarouselNavigation.vue";
import isObject from "lodash.isobject";
import uniqueId from "lodash.uniqueid";

const props = defineProps({
  modelValue: {
    type: Array,
    required: true,
  },
  current: {
    type: Number,
    default: 0,
  },
  className: String,
  freeMode: [Boolean, Object],
  effect: {
    type: String,
    default: 'slide',
    validator: (value) => {
      return ['slide', 'fade', 'cube', 'coverflow', 'flip', 'creative'].indexOf(value) !== -1
    },
  },
  hasNavigation: Boolean,
  navigationPosition: String,
  navigationDisplay: String,
  navigationSize: String,
  hasPagination: [Boolean, Object],
  slidesPerView: {
    type: [Function, Number],
    default: 1,
  },
  spaceBetween: {
    type: Number,
    default: 15,
  },
  autoplay: [Number, Object],
  wrapAround: Boolean,
  dir: {
    type: String,
    default: 'rtl',
    validator: (value) => {
      return ['rtl', 'ltr'].indexOf(value) !== -1
    },
  },
  transition: {
    type: Number,
    default: 400,
  },
  direction: {
    type: String,
    default: 'horizontal',
    validator: (value) => {
      return ['horizontal', 'vertical'].indexOf(value) !== -1
    },
  },
  breakpoints: {
    type: Object,
    default: () => {
      return {
        // 640px and up
        640: {
          slidesPerView: 2.5,
        },
        // 1024px and up
        1024: {
          slidesPerView: 3.5,
        },
        // 1280px and up
        1280: {
          slidesPerView: 4.5,
          spaceBetween: 20,
        },
      }
    },
  },
  useObserver: {
    type: Boolean,
    default: true,
  },
  useThumbnail: Boolean,
  nested: Boolean,
})
const emit = defineEmits([
  'update:modelValue',
  'update:current',
  'init',
  'slide-change',
])

const carousel = ref(null)

const slides = computed({
  get() {
    return props.modelValue
  },
  set(value) {
    emit('update:modelValue', value)
  }
})

const currentSlide = computed({
  get() {
    return props.current
  },
  set(value) {
    emit('update:current', value)
  }
})

const className = props.className ?? uniqueId('base-swiper-')

//-------------------------------
// Define Modules & Preparation
//-------------------------------
const modules = [
  A11y,
  EffectFade,
  EffectCoverflow,
  EffectFlip,
]

if (props.useThumbnail) modules.push(Thumbs)
if (props.freeMode) modules.push(FreeMode)
if (props.autoplay) modules.push(Autoplay)

const navigationObject = ref(props.hasNavigation)
const paginationObject = ref(props.hasPagination)
const autoplayObject = ref(props.autoplay)

watchImmediate([
  () => props.hasNavigation,
  () => props.hasPagination,
  () => props.autoplay,
], ([nav, page, autoplay]) => {
  if (nav === true) {
    navigationObject.value = {
      nextEl: '.' + className + '-next',
      prevEl: '.' + className + '-prev',
    }
  } else {
    navigationObject.value = nav
  }

  if (page === true) {
    paginationObject.value = {
      clickable: true,
      // dynamicBullets: true,
    }
  } else {
    paginationObject.value = page
  }

  if (autoplay && !isObject(autoplay)) {
    autoplayObject.value = {
      delay: autoplay,
      pauseOnMouseEnter: true,
    }
  } else {
    autoplayObject.value = autoplay
  }
})

//-------------------------------

const a11y = {
  firstSlideMessage: 'اسلاید اول',
  prevSlideMessage: 'اسلاید قبلی',
  nextSlideMessage: 'اسلاید بعدی',
  lastSlideMessage: 'اسلاید آخر',
  paginationBulletMessage: 'رفتن به اسلاید {{index}}',
}

//-------------------------------
// Thumbnail Support
//-------------------------------
const thumbsSwiper = ref(null)
const thumbObject = ref({})

watchImmediate([() => props.useThumbnail, thumbsSwiper], () => {
  if (props.useThumbnail && thumbsSwiper.value)
    thumbObject.value = {swiper: thumbsSwiper.value?.swiper}
})

//-------------------------------

watchEffect(() => {
  if (carousel.value) {
    if (!carousel.value.initialized) {
      Object.assign(carousel.value, {
        initialSlide: currentSlide.value,
        navigation: navigationObject.value,
        pagination: paginationObject.value,
        freeMode: props.freeMode,
        effect: props.effect,
        speed: props.transition,
        loop: props.wrapAround,
        autoplay: autoplayObject.value,
        breakpoints: props.breakpoints,
        observer: props.useObserver,
        dir: props.dir,
        thumbs: thumbObject.value,
        direction: props.direction,
        spaceBetween: props.spaceBetween,
        nested: props.nested,
        a11y,
        modules,
        on: {
          swiper: () => {
            emit('init')
          },
          slideChange: () => {
            emit('slide-change', carousel.value?.swiper?.realIndex || 0)
          }
        },
      })

      carousel.value.initialize()
    } else {
      carousel.value.initialSlide = currentSlide.value
      carousel.value.navigation = navigationObject.value
      carousel.value.freeMode = props.freeMode
      carousel.value.effect = props.effect
      carousel.value.speed = props.transition
      carousel.value.loop = props.wrapAround
      carousel.value.autoplay = autoplayObject.value
      carousel.value.breakpoints = props.breakpoints
      carousel.value.observer = props.useObserver
      carousel.value.dir = props.dir
      carousel.value.thumbs = thumbObject.value
      carousel.value.direction = props.direction
      carousel.value.spaceBetween = props.spaceBetween
      carousel.value.nested = props.nested
    }
  }
})

onBeforeUnmount(() => {
  // Destroy Swiper instance to prevent memory leaks
  if (carousel.value?.swiper) {
    carousel.value.swiper.destroy();
  }
})

defineExpose({
  carousel: carousel.value,
  thumbnail: thumbsSwiper.value,
})
</script>

<style>
:root {
  --swiper-theme-color: #3057D3;

  /* pagination settings */
  --swiper-pagination-color: var(--swiper-theme-color);
  --swiper-pagination-left: auto;
  --swiper-pagination-right: 8px;
  --swiper-pagination-bottom: 8px;
  --swiper-pagination-top: auto;
  --swiper-pagination-fraction-color: inherit;
  --swiper-pagination-progressbar-bg-color: rgba(0, 0, 0, 0.25);
  --swiper-pagination-progressbar-size: 4px;
  --swiper-pagination-bullet-size: 8px;
  --swiper-pagination-bullet-width: 12px;
  --swiper-pagination-bullet-height: 12px;
  --swiper-pagination-bullet-inactive-color: #000;
  --swiper-pagination-bullet-inactive-opacity: 0.35;
  --swiper-pagination-bullet-opacity: 1;
  --swiper-pagination-bullet-horizontal-gap: 4px;
  --swiper-pagination-bullet-vertical-gap: 6px;
}

.swiper-slide-thumb-active {
  border: 2px solid #2563eb;
  border-radius: .6rem;
}

swiper-container {
  display: grid;
}

.swiper-wrapper {
  min-width: 0;
}
</style>
