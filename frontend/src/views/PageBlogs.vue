<template>
  <div
      ref="bgMovement"
      :style="'--ty:' + (mainPageContainer?.offsetHeight ?? 3000) + 'px'"
      class="animated-parts"
  >
    <div
        v-for="i in 12"
        :key="i"
        :class="getAnimatedPartsColor()[(i - 1) % 12]"
        :style="[
            '--time:' + ((Math.random() * 15) + 10) + 's',
            '--amount:' + ((Math.random() * 5) + 2),
            '--animate-time:' + ((Math.random() * 15) + 15) + 's',
        ]"
        class="tk-blob"
    >
      <template v-if="i % 6 === 0">
        <svg viewBox="0 0 317.5 353.7" xmlns="http://www.w3.org/2000/svg">
          <path
              d="M291.8 55.3c30.4 39.9 30.7 102 17 160.4-13.8 58.3-41.6 112.9-84 130.9s-99.3-.6-137-30C50.2 287.1 32 246.9 17 200.5 2.1 154.1-9.6 101.4 11.5 63.6 32.6 25.8 86.6 2.8 143.8.2c57.2-2.6 117.6 15.2 148 55.1z"></path>
        </svg>
      </template>
      <template v-else-if="i % 5 === 0">
        <svg viewBox="0 0 418.7 325.5" xmlns="http://www.w3.org/2000/svg">
          <path
              d="M363.8 52.4c42.8 41.3 68 108.8 47.9 153.5-20.1 44.8-85.6 66.7-143 87.4-57.4 20.8-106.8 40.3-156.7 28.7C62 310.4 11.5 267.7 1.8 217.7c-9.7-49.9 21.5-107 61.5-147.6C103.2 29.5 152 5.3 206.4.8c54.5-4.6 114.7 10.4 157.4 51.6z"></path>
        </svg>
      </template>
      <template v-else-if="i % 4 === 0">
        <svg viewBox="0 0 747.2 726.7" xmlns="http://www.w3.org/2000/svg">
          <path
              d="M539.8 137.6c98.3 69 183.5 124 203 198.4 19.3 74.4-27.1 168.2-93.8 245-66.8 76.8-153.8 136.6-254.2 144.9-100.6 8.2-214.7-35.1-292.7-122.5S-18.1 384.1 7.4 259.8C33 135.6 126.3 19 228.5 2.2c102.1-16.8 213.2 66.3 311.3 135.4z"></path>
        </svg>
      </template>
      <template v-else-if="i % 3 === 0">
        <svg viewBox="0 0 274 303.2" xmlns="http://www.w3.org/2000/svg">
          <path
              d="M260.2 41.4c20 29.2 14.6 74.5 7.2 124.4-7.3 49.9-16.6 104.5-49.2 126-32.5 21.6-88.4 10.2-132-15.2s-75-64.7-83.6-107.8C-6.1 125.7 8 79 36.3 47.8 64.5 16.7 107 1.3 150.9.1c43.9-1.1 89.3 12 109.3 41.3z"></path>
        </svg>
      </template>
      <template v-else>
        <svg viewBox="0 0 747.2 726.7" xmlns="http://www.w3.org/2000/svg">
          <path
              d="M539.8 137.6c98.3 69 183.5 124 203 198.4 19.3 74.4-27.1 168.2-93.8 245-66.8 76.8-153.8 136.6-254.2 144.9-100.6 8.2-214.7-35.1-292.7-122.5S-18.1 384.1 7.4 259.8C33 135.6 126.3 19 228.5 2.2c102.1-16.8 213.2 66.3 311.3 135.4z"></path>
        </svg>
      </template>
    </div>
  </div>

  <div ref="mainPageContainer">
    <app-navigation-header
        :title="getPageTitle"
        container-class="text-center"
    />

    <div class="p-3 grid grid-cols-3 gap-6">
      <div class="col-span-3 lg:col-span-2">
        <div
            v-if="slidesLoading || !slides?.length"
            class="rounded-lg h-[30rem] w-full animate-pulse bg-blue-200 flex items-center justify-center relative"
        >
          <PhotoIcon class="size-24 md:size-28 text-blue-400"/>

          <div
              class="absolute z-[1] bg-gradient-to-t from-indigo-950/50 bottom-0 w-full h-full rounded-b-lg pt-6 px-6 pb-9 flex items-end"
          >
            <div class="w-2/3 h-4 bg-slate-200 rounded"></div>
            <div
                class="absolute rounded-tr-lg rounded-bl-lg w-24 h-7 bg-indigo-600 left-0 bottom-0"
            ></div>
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
            :navigation-position="carouselSettings.navigationPosition"
            :wrap-around="carouselSettings.wrapAround"
        >
          <div class="bg-white rounded-lg relative">
            <base-lazy-image
                :alt="slide.title"
                :lazy-src="slide.path"
                :size="FileSizes.LARGE"
                class="rounded-lg h-[30rem] w-full !object-cover"
            />

            <div
                class="absolute z-[1] bg-gradient-to-t from-slate-950/70 bottom-0 w-full h-full rounded-b-lg pt-6 px-6 pb-16 text-shadow flex items-end">
              <div>
                <router-link
                    :to="{name: 'blog.search', query: {category: slide.category.id}}"
                    class="text-xs rounded-full py-1.5 px-4 inline-block mb-3 bg-indigo-600 text-white hover:bg-indigo-500 transition"
                >
                  {{ slide.category.name }}
                </router-link>
                <h1 class="w-full text-right">

                  <router-link
                      :to="{name: 'blog.detail', params: {slug: slide.slug}}"
                      class="text-xl text-white leading-relaxed hover:text-opacity-80 transition"
                  >
                    {{ slide.title }}
                  </router-link>
                </h1>
              </div>
            </div>
          </div>
        </base-carousel>
      </div>

      <div class="col-span-3 lg:col-span-1 flex flex-col sm:flex-row lg:flex-col gap-6">
        <div
            v-for="i in 2"
            v-if="sliderSideImagesLoading || !sliderSideImages?.length"
            :key="i"
            class="relative w-full animate-pulse"
        >
          <div
              class="rounded-lg h-[calc(15rem-12px)] w-full bg-blue-200 flex items-center justify-center"
          >
            <PhotoIcon class="size-16 md:size-20 text-blue-400"/>
          </div>
          <div
              class="absolute z-[1] bg-gradient-to-t from-indigo-950/50 bottom-0 w-full h-full rounded-b-lg pt-6 px-6 pb-9 flex items-end"
          >
            <div class="w-2/3 h-4 bg-slate-200 rounded"></div>
            <div
                class="absolute rounded-tr-lg rounded-bl-lg w-16 h-7 bg-indigo-600 left-0 bottom-0"
            ></div>
          </div>
        </div>

        <template v-else>
          <div
              v-for="item in sliderSideImages"
              :key="item.id"
              class="relative w-full"
          >
            <base-lazy-image
                :alt="item.title"
                :lazy-src="item.image.path"
                :size="FileSizes.LARGE"
                class="rounded-lg h-[calc(15rem-12px)] w-full !object-cover"
            />

            <div
                class="absolute z-[1] bg-gradient-to-t from-slate-950/70 bottom-0 w-full h-full rounded-b-lg pt-6 px-6 pb-9 text-shadow flex items-end">
              <h1 class="w-full text-right">
                <router-link
                    :to="{name: 'blog.detail', params: {slug: item.slug}}"
                    class="text-lg text-white leading-relaxed hover:text-opacity-80 transition"
                >
                  {{ item.title }}
                </router-link>

                <router-link
                    :to="{name: 'blog.search', query: {category: item.category.id}}"
                    class="text-xs rounded-tr-lg rounded-bl-lg py-1.5 px-4 bg-indigo-600 text-white absolute left-0 bottom-0 hover:bg-indigo-500 transition"
                >
                  {{ item.category.name }}
                </router-link>
              </h1>
            </div>
          </div>
        </template>
      </div>
    </div>

    <div class="mb-6 p-3">
      <div class="flex flex-col lg:flex-row gap-3">
        <div class="grow">
          <partial-general-title
              container-class="mb-2 p-2"
              title="جدیدترین نوشته‌ها"
          />

          <div
              v-if="latestBlogsLoading || !latestBlogs?.length"
              class="flex flex-wrap items-start"
          >
            <div
                v-for="i in 3"
                :key="i"
                class="w-full md:w-1/2 xl:w-1/3 p-3"
            >
              <loader-card-blog/>
            </div>
          </div>
          <template v-else>
            <div class="flex flex-wrap items-start">
              <div
                  v-for="blog in latestBlogs"
                  :key="blog.id"
                  class="w-full md:w-1/2 xl:w-1/3 p-3"
              >
                <blog-card
                    :blog="blog"
                    container-class="border-0 rounded-lg supports-[backdrop-filter]:backdrop-blur-sm supports-[backdrop-filter]:bg-opacity-80"
                    type="vertical"
                />
              </div>
            </div>

            <div class="mt-5 text-left p-3 flex items-center">
              <div class="h-0.5 rounded-full grow bg-slate-200"></div>
              <base-button
                  :to="{name: 'blog.search'}"
                  class="border-2 border-slate-200 px-5 !text-black bg-white bg-opacity-50 text-sm flex items-center gap-3 group shrink-0"
                  type="link"
              >
                <ArrowLongRightIcon class="w-6 h-6 group-hover:translate-x-1 transition"/>
                <span>مشاهده تمامی نوشته‌ها</span>
              </base-button>
            </div>
          </template>
        </div>

        <div
            v-if="hasPopularCategories || hasMostViewsBlogs"
            class="shrink-0 lg:w-80 flex flex-col gap-6"
        >
          <partial-card
              v-if="hasPopularCategories"
              class="border-0 flex flex-col supports-[backdrop-filter]:backdrop-blur-sm supports-[backdrop-filter]:bg-opacity-80"
          >
            <template #body>
              <partial-general-title
                  title="دسته‌بندی‌های پرطرفدار"
                  type="side"
              />

              <app-side-categories-blog @loaded="(hasData) => {hasPopularCategories = hasData}"/>
            </template>
          </partial-card>

          <partial-card
              v-if="hasMostViewsBlogs"
              class="border-0 flex flex-col supports-[backdrop-filter]:backdrop-blur-sm supports-[backdrop-filter]:bg-opacity-80"
          >
            <template #body>
              <partial-general-title
                  title="پربازدیدترین نوشته‌ها"
                  type="side"
              />

              <blog-side-most-viewed @loaded="(hasData) => {hasMostViewsBlogs = hasData}"/>
            </template>
          </partial-card>
        </div>
      </div>
    </div>

    <app-newsletter/>
  </div>
</template>

<script setup>
import {computed, inject, onMounted, ref} from "vue";
import {ArrowLongRightIcon, PhotoIcon} from "@heroicons/vue/24/outline/index.js";
import BaseCarousel from "@/components/base/BaseCarousel.vue";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import PartialGeneralTitle from "@/components/partials/PartialGeneralTitle.vue";
import BlogCard from "@/components/blog/BlogCard.vue";
import AppNavigationHeader from "@/components/AppNavigationHeader.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import {useResizeObserver} from "@vueuse/core";
import AppSideCategoriesBlog from "@/components/AppSideCategoriesBlog.vue";
import AppNewsletter from "@/components/AppNewsletter.vue";
import {HomeBlogAPI, HomeBlogMainPage} from "@/service/APIHomePages.js";
import {BLOG_ORDER_TYPES} from "@/composables/constants.js";
import LoaderCardBlog from "@/components/base/loader/LoaderCardBlog.vue";
import {FileSizes} from "@/composables/file-list.js";
import BlogSideMostViewed from "@/components/blog/BlogSideMostViewed.vue";

const homeSettingStore = inject('homeSettingStore')

const getPageTitle = computed(() => {
  return 'بلاگ ' + homeSettingStore.getTitle
})

const bgMovement = ref(null)
const mainPageContainer = ref(null)

useResizeObserver(mainPageContainer, () => {
  if (bgMovement.value) {
    bgMovement.value.style.height = mainPageContainer.value.offsetHeight + 'px'
  }
})

function shuffleArray(array) {
  // Create a copy of the original array
  const shuffledArray = array.slice();

  // Random sort function
  const randomSort = () => Math.random() - 0.5;

  // Use the random sort function to shuffle the array
  shuffledArray.sort(randomSort);

  return shuffledArray;
}

function getAnimatedPartsColor() {
  return shuffleArray(['fill-amber-200', 'fill-pink-200', 'fill-sky-200', 'fill-teal-200', 'fill-blue-200', 'fill-indigo-200', 'fill-purple-200', 'fill-violet-200', 'fill-orange-200', 'fill-fuchsia-200', 'fill-red-200', 'fill-yellow-200', 'fill-rose-200'])
}

//----------------------------
// Top Slider
//----------------------------
const slides = ref([])
const slidesLoading = ref(true)
const currentSlide = ref(0)

const carouselSettings = {
  className: 'blog-slider',
  effect: 'coverflow',
  wrapAround: true,
  autoplay: 10000,
  hasNavigation: true,
  navigationPosition: 'right',
  navigationDisplay: 'floating-sides',
  hasPagination: true,
  breakpoints: {},
}

const sliderSideImagesLoading = ref(true)
const sliderSideImages = ref(null)

//----------------------------
const latestBlogs = ref(null)
const latestBlogsLoading = ref(null)

const hasMostViewsBlogs = ref(true)
const hasPopularCategories = ref(true)

onMounted(() => {
  HomeBlogMainPage.fetchSliderMain({
    success(response) {
      slides.value = response.data
      slidesLoading.value = false
    },
  })

  HomeBlogMainPage.fetchSliderMainSideImages({
    success(response) {
      sliderSideImages.value = response.data
      sliderSideImagesLoading.value = false
    },
  })

  HomeBlogAPI.fetchAll({
    order: BLOG_ORDER_TYPES.NEWEST.value,
  }, {
    success(response) {
      latestBlogs.value = response.data
      latestBlogsLoading.value = false
    },
  })
})
</script>

<style scoped>
.animated-parts {
  --ty: 8000px;
  --animate-time: 20s;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  overflow: hidden;
  z-index: -1;
  inset: 0;
}

.animated-parts > div {
  --time: 30s;
  --amount: 2;
  position: absolute;
  display: block;
  list-style: none;
  width: 100px;
  animation: animate var(--animate-time, 20s) linear infinite;
  top: -150px;
  z-index: -1;
}

.tk-blob {
  animation: blob-turn var(--time, 30s) linear infinite;
  position: relative;
  transform-origin: center;
}

.tk-blob svg {
  animation: blob-skew calc(var(--time, 30s) * 0.5) linear 0s infinite;
  transform-origin: center;
}

.tk-blob svg path {
  animation: blob-scale calc(var(--time, 30s) * 0.5) ease-in-out 0s infinite;
  transform-origin: center;
}

.animated-parts > div:nth-child(1) {
  left: 25%;
  width: 80px;
  height: 80px;
  animation-delay: 0s;
}

.animated-parts > div:nth-child(2) {
  left: 10%;
  width: 30px;
  height: 30px;
  animation-delay: 6s;
}

.animated-parts > div:nth-child(3) {
  left: 70%;
  width: 30px;
  height: 30px;
  animation-delay: 7s;
}

.animated-parts > div:nth-child(4) {
  left: 40%;
  width: 80px;
  height: 80px;
  animation-delay: 18s;
}

.animated-parts > div:nth-child(5) {
  left: 65%;
  width: 30px;
  height: 30px;
  animation-delay: 12s;
}

.animated-parts > div:nth-child(6) {
  left: 75%;
  width: 110px;
  height: 110px;
  animation-delay: 8s;
}

.animated-parts > div:nth-child(7) {
  left: 35%;
  width: 150px;
  height: 150px;
  animation-delay: 20s;
}

.animated-parts > div:nth-child(8) {
  left: 50%;
  width: 35px;
  height: 35px;
  animation-delay: 15s;
}

.animated-parts > div:nth-child(9) {
  left: 20%;
  width: 45px;
  height: 45px;
  animation-delay: 2s;
}

.animated-parts > div:nth-child(10) {
  left: 85%;
  width: 150px;
  height: 150px;
  animation-delay: 0s;
}

@keyframes blob-turn {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

@keyframes blob-skew {
  0% {
    transform: skewY(0deg);
  }
  13% {
    transform: skewY(calc((1.8deg) * var(--amount, 2)));
  }
  18% {
    transform: skewY(calc((2.2deg) * var(--amount, 2)));
  }
  24% {
    transform: skewY(calc((2.48deg) * var(--amount, 2)));
  }
  25% {
    transform: skewY(calc((2.5deg) * var(--amount, 2)));
  }
  26% {
    transform: skewY(calc((2.48deg) * var(--amount, 2)));
  }
  32% {
    transform: skewY(calc((2.2deg) * var(--amount, 2)));
  }
  37% {
    transform: skewY(calc((1.8deg) * var(--amount, 2)));
  }
  50% {
    transform: skewY(0deg);
  }
  63% {
    transform: skewY(calc((-1.8deg) * var(--amount, 2)));
  }
  68% {
    transform: skewY(calc((-2.2deg) * var(--amount, 2)));
  }
  74% {
    transform: skewY(calc((-2.48deg) * var(--amount, 2)));
  }
  75% {
    transform: skewY(calc((-2.5deg) * var(--amount, 2)));
  }
  76% {
    transform: skewY(calc((-2.48deg) * var(--amount, 2)));
  }
  82% {
    transform: skewY(calc((-2.2deg) * var(--amount, 2)));
  }
  87% {
    transform: skewY(calc((-1.8deg) * var(--amount, 2)));
  }
  100% {
    transform: skewY(0deg);
  }
}

@keyframes blob-scale {
  0% {
    transform: scaleX(.9) scaleY(1);
  }
  25% {
    transform: scaleX(.9) scaleY(.9);
  }
  50% {
    transform: scaleX(1) scaleY(.9);
  }
  75% {
    transform: scaleX(.9) scaleY(.9);
  }
  100% {
    transform: scaleX(.9) scaleY(1);
  }
}

@keyframes animate {
  0% {
    transform: translateY(0);
    opacity: 1;
  }

  100% {
    transform: translateY(var(--ty, 3000px));
    opacity: 0;
  }
}
</style>
