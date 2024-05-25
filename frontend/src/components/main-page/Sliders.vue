<template>
  <div
      v-if="slidersLoading || !sliders?.length"
      class="px-3 mb-6 lg:mb-8"
  >
    <div class="flex items-center gap-6 overflow-hidden mb-6 lg:mb-8">
      <loader-card
          v-for="i in 5"
          :key="i"
          class="!w-80 rounded-lg shadow-lg"
      />
    </div>
    <div class="grid gap-4 grid-cols-1 md:grid-cols-2">
      <div
          v-for="i in 2"
          :key="i"
          class="flex items-center justify-center bg-slate-200 rounded-lg h-56 animate-pulse"
      >
        <PhotoIcon class="text-slate-400 size-12 md:size-16"/>
      </div>
    </div>
  </div>
  <div
      v-for="slider in sliders"
      v-else
      :key="slider.id"
      class="px-3 mb-6 lg:mb-8"
  >
    <template v-if="slider.place_in.value === SLIDER_PLACES.MAIN_SLIDERS.value">
      <partial-general-title
          :title="slider.title"
          title-size="text-lg"
      >
        <template #extra>
          <router-link
              v-if="slider?.options[SLIDER_OPTIONS.SHOW_ALL_LINK.value] &&
               slider?.options[SLIDER_OPTIONS.SHOW_ALL_LINK.value].trim() !== ''"
              :to="slider.options[SLIDER_OPTIONS.SHOW_ALL_LINK.value]"
              class="text-sm text-blue-600 hover:opacity-80 transition border-2 rounded py-1.5 px-2 bg-white flex gap-2 item-center"
          >
            <span>مشاهده همه</span>
            <ArrowLeftIcon class="h-5 w-5"/>
          </router-link>
        </template>
      </partial-general-title>
      <product-carousel
          v-if="slider.items?.length"
          :products="slider.items"
      />
    </template>

    <template v-else-if="slider.place_in.value === SLIDER_PLACES.MAIN_SLIDER_IMAGES.value">
      <div
          :class="gridImageClass[slider?.options[SLIDER_OPTIONS.BESIDE_IMAGES.value] ?? 1]"
          class="grid gap-4"
      >
        <div
            v-for="(item, idx) in slider.items"
            :key="idx"
        >
          <router-link
              :to="item?.link"
              class="block rounded-lg shadow"
          >
            <img
                :alt="'تصویر ' + idx"
                :src="item?.image.path"
                class="w-full h-auto object-contain rounded-lg"
            />
          </router-link>
        </div>
      </div>
    </template>
  </div>
</template>

<script setup>
import {onMounted, ref} from "vue";
import {ArrowLeftIcon, PhotoIcon} from "@heroicons/vue/24/outline"
import PartialGeneralTitle from "@/components/partials/PartialGeneralTitle.vue";
import ProductCarousel from "@/components/product/ProductCarousel.vue";
import {SLIDER_OPTIONS, SLIDER_PLACES} from "@/composables/constants.js";
import {HomeMainPageAPI} from "@/service/APIHomePages.js";
import LoaderCard from "@/components/base/loader/LoaderCard.vue";

const emit = defineEmits(['loaded'])

const gridImageClass = {
  1: 'grid-cols-1',
  2: 'grid-cols-1 md:grid-cols-2',
  3: 'grid-cols-1 md:grid-cols-3',
  4: 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
}
const sliders = ref(null)
const slidersLoading = ref(true)

onMounted(() => {
  HomeMainPageAPI.fetchSliders({
    success(response) {
      sliders.value = response.data
    },
    error() {
      return false
    },
    finally() {
      slidersLoading.value = false
      emit('loaded', !!sliders.value?.length)
    },
  })
})
</script>
