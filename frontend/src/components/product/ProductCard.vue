<template>
  <VTransitionSlideFadeUpY mode="out-in">
    <div
      v-if="product && product?.slug"
      :class="containerClass"
      class="min-h-[400px] w-full h-full p-3 border bg-white"
    >
      <router-link
        :to="{name: 'product.detail', params: {slug: product.slug}}"
        class="group block"
        target="_blank"
      >
        <base-lazy-image
          :alt="product.title"
          :lazy-src="product.image.path"
          :is-local="false"
          class="w-full h-48 bg-white rounded-lg transition group-hover:scale-95"
        />
      </router-link>

      <div class="mt-3 flex flex-col justify-center">
        <router-link
          :title="product.title"
          :to="{name: 'product.detail', params: {slug: product.slug}}"
          class="text-sm leading-loose h-[52px] ellipsis-2 hover:text-indigo-600 transition"
          target="_blank"
        >
          <h1>
            {{ product.title }}
          </h1>
        </router-link>

        <div class="mt-2">
          <base-carousel
            v-slot="{slide, index}"
            v-model="productProperties"
            v-model:current="currentProductItem"
            :breakpoints="productItemsCarouselSetting.breakpoints"
            :has-navigation="productItemsCarouselSetting.hasNavigation"
            :has-pagination="productItemsCarouselSetting.hasPagination"
            :navigation-display="productItemsCarouselSetting.navigationDisplay"
            :navigation-size="productItemsCarouselSetting.navigationSize"
          >
            <div class="w-full h-full flex flex-col justify-evenly">
              <div
                v-if="slide.is_special"
                class="flex items-center justify-center my-3"
              >
                <div class="grow h-0.5 bg-red-400 rounded-full"></div>
                <span class="mx-2 text-xs text-red-600 shrink-0">فروش ویژه</span>
                <div class="grow h-0.5 bg-red-400 rounded-full"></div>
              </div>

              <div class="max-h-[64px] overflow-hidden">
                <div
                  v-if="slide.show_coming_soon"
                  class="bg-cyan-500 py-2 px-4 rounded text-xs text-white text-center"
                >
                  این محصول به زودی موجود می‌شود
                </div>
                <div
                  v-else-if="slide.show_call_for_more"
                  class="bg-amber-500 py-2 px-4 rounded text-xs text-white text-center"
                >
                  برای اطلاعات بیشتر تماس بگیرید
                </div>
                <div
                  v-else-if="!slide.is_available || slide.stock_count <= 0"
                  class="bg-rose-500 py-2 px-4 rounded text-xs text-white text-center"
                >
                  اتمام موجودی
                </div>
                <div v-else>
                  <div
                    v-if="getDiscountTimer(slide)"
                    class="mb-3"
                  >
                    <partial-countdown-show
                      :duration="getDiscountTimer(slide)"
                      :hide-after-end="true"
                      type="minimal"
                    />
                  </div>

                  <div class="flex items-center justify-between">
                    <template
                      v-if="getPercentageOfPortion(getBuyablePrice(slide), slide.price) > 0"
                    >
                      <div class="rounded-lg bg-rose-500 text-white text-sm py-1 px-2 my-1">
                        <span class="text-xs">%</span>
                        {{ getPercentageOfPortion(getBuyablePrice(slide), slide.price) }}
                      </div>
                    </template>

                    <div class="flex flex-col mr-3">
                      <div class="my-1 text-lg">
                        <template v-if="getBuyablePrice(slide) === 0">
                          <span class="font-iranyekan-bold mx-1">رایگان</span>
                        </template>
                        <template v-else>
                          <span class="font-iranyekan-bold mx-1">
                            {{ numberFormat(getBuyablePrice(slide)) }}
                          </span>
                          <span class="text-xs text-gray-400">تومان</span>
                        </template>
                      </div>

                      <template v-if="getBuyablePrice(slide) < slide.price && +slide.price !== 0">
                        <div class="relative ml-3 my-1 text-sm">
                        <span
                          class="absolute top-1/2 -translate-y-1/2 left-0 h-[1px] w-full bg-slate-400 -rotate-3"></span>
                          <div class="text-slate-400 text-center">
                            {{ numberFormat(slide.price) }}
                            <span class="text-xs text-gray-400">تومان</span>
                          </div>
                        </div>
                      </template>
                    </div>
                  </div>
                </div>
              </div>

              <ul class="flex flex-wrap items-start flex-col gap-2 mt-2 max-h-[85px]">
                <li
                  v-if="slide.color_name"
                  class="flex items-center"
                >
                  <span class="text-gray-400 ml-2 text-xs">رنگ:</span>
                  <span
                    v-tooltip.top="'' + slide.color_name + ''"
                    :style="'background-color:' + slide.color_hex"
                    class="rounded-full border shadow w-5 h-5 cursor-pointer inline-block"
                  ></span>
                  <span class="text-xs mr-1 truncate">{{ slide.color_name }}</span>
                </li>
                <li
                  v-if="slide.size"
                  class="flex items-center"
                >
                  <span class="text-gray-400 ml-2 text-xs">سایز:</span>
                  <span class="rounded-lg px-3 border-2 transition">{{ slide.size }}</span>
                </li>
                <li
                  v-if="slide.guarantee"
                  class="flex items-center"
                >
                  <CheckBadgeIcon class="text-emerald-500 w-5 h-5 ml-1"/>
                  <span class="text-xs truncate">{{ slide.guarantee }}</span>
                </li>
              </ul>

              <div
                v-if="slide.updated_at"
                class="flex gap-1.5 items-center mt-2"
              >
                <span class="text-slate-400 text-xs">آخرین بروزرسانی:</span>
                <span class="text-amber-500 text-sm">{{ slide.updated_at }}</span>
              </div>
            </div>
          </base-carousel>
        </div>
      </div>
    </div>
    <template v-else>
      <loader-card/>
    </template>
  </VTransitionSlideFadeUpY>
</template>

<script setup>
import {ref} from "vue";
import {CheckBadgeIcon} from "@heroicons/vue/24/outline/index.js"
import VTransitionSlideFadeUpY from "@/transitions/VTransitionSlideFadeUpY.vue";
import LoaderCard from "@/components/base/loader/LoaderCard.vue";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import BaseCarousel from "@/components/base/BaseCarousel.vue";
import {getPercentageOfPortion, numberFormat} from "@/composables/helper.js";
import {watchImmediate} from "@vueuse/core";
import PartialCountdownShow from "@/components/partials/PartialCountdownShow.vue";

const props = defineProps({
  containerClass: {
    type: String,
    default: 'rounded-lg shadow-lg',
  },
  product: {
    type: Object,
    required: true,
  },
})

const productProperties = ref([])
const currentProductItem = ref(0)

const productItemsCarouselSetting = {
  hasNavigation: true,
  hasPagination: false,
  navigationDisplay: 'floating',
  navigationSize: 'small',
  breakpoints: {},
}

watchImmediate(() => props.product, () => {
  if (!props?.product?.items) return
  productProperties.value = props?.product?.items
})

//------------------------------------------------------------
// Discount timer operations
//------------------------------------------------------------
function getDiscountTimer(item) {
  const currentDate = new Date()
  const seconds = currentDate.getTime() / 1000

  // Check if product have festival discount
  if (
    (
      !item.festival_discounted_from_in_seconds &&
      item.festival_discounted_until_in_seconds &&
      item.festival_discounted_until_in_seconds >= seconds
    ) ||
    (
      item.festival_discounted_from_in_seconds &&
      !item.festival_discounted_until_in_seconds &&
      item.festival_discounted_from_in_seconds <= seconds
    ) ||
    (
      item.festival_discounted_from_in_seconds &&
      item.festival_discounted_until_in_seconds &&
      item.festival_discounted_until_in_seconds >= seconds &&
      item.festival_discounted_from_in_seconds <= seconds
    )
  ) {
    if (item.festival_discounted_until_in_seconds !== null) {
      return item.festival_discounted_until_in_seconds
    }
    return 0
  }

  // Check if product have general discount
  if (
    (
      !item.discounted_from_in_seconds &&
      item.discounted_until_in_seconds &&
      item.discounted_until_in_seconds >= seconds
    ) ||
    (
      item.discounted_from_in_seconds &&
      !item.discounted_until_in_seconds &&
      item.discounted_from_in_seconds <= seconds
    ) ||
    (
      item.discounted_from_in_seconds &&
      item.discounted_until_in_seconds &&
      item.discounted_until_in_seconds >= seconds &&
      item.discounted_from_in_seconds <= seconds
    )
  ) {
    if (item.discounted_until_in_seconds !== null) {
      return item.discounted_until_in_seconds
    }
    return 0
  }

  return null
}

function getBuyablePrice(item) {
  let price = +item.buyable_price

  if (getDiscountTimer.value !== null) {
    price = +item.price
  }

  return price
}
</script>
