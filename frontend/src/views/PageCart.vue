<template>
  <app-navigation-header title="سبد خرید"/>

  <div class="px-3 mb-12">
    <div v-if="items?.length" class="flex flex-col lg:flex-row gap-6 cart-info-sticky-container">
      <partial-card class="border-0 p-4 w-full">
        <template #body>
          <div class="relative">
            <VTransitionFade>
              <loader-circle
                v-if="isLoading"
                main-container-klass="absolute w-[calc(100%+1rem)] h-[calc(100%+1rem)] -top-2 -left-2"
              />
            </VTransitionFade>

            <ul class="flex flex-col divide-y divide-slate-200">
              <li
                v-for="item in items"
                :key="item.id"
                class="relative flex flex-col gap-3 py-6 pr-3 pl-10"
              >
                <div class="absolute left-0 top-1.5">
                  <button
                    v-tooltip.right="'حذف از سبد خرید'"
                    class="group p-1 inline-block"
                    type="button"
                    @click="removeItemHandler(item.code)"
                  >
                    <XMarkIcon class="w-6 h-6 text-gray-400 group-hover:text-rose-500 transition"/>
                  </button>
                </div>

                <div class="shrink-0 flex flex-col sm:flex-row gap-3">
                  <div class="shrink-0">
                    <router-link
                      :to="{name: 'product.detail', params: {slug: item.product.slug}}"
                      class="inline-block border rounded-lg"
                    >
                      <base-lazy-image
                        :alt="item.product.title"
                        :lazy-src="item.product.image.path"
                        :is-local="false"
                        class="!w-36 !h-36 sm:!w-28 sm:!h-28 object-contain hover:scale-95 transition rounded-lg"
                      />
                    </router-link>
                  </div>

                  <div class="flex gap-3 flex-col justify-between">
                    <router-link
                      :to="{name: 'product.detail', params: {slug: item.product.slug}}"
                      class="inline-block text-black hover:text-opacity-80 leading-relaxed text-sm"
                    >
                      {{ item.product.title }}
                    </router-link>

                    <base-spinner
                      v-model="item.quantity"
                      :max="Math.min(item.max_cart_count, item.stock_count)"
                      :min="0"
                    >
                      <template #afterValue>
                        <span class="text-sm text-gray-400">{{ item.product.unit_name }}</span>
                      </template>
                    </base-spinner>

                    <div class="flex flex-wrap items-center">
                      <div
                        v-if="+item.price !== +item.actual_price"
                        class="rounded-lg bg-rose-500 text-white py-0.5 px-2 my-1 ml-3 flex items-center justify-center"
                      >
                        <span class="text-xs">%</span>
                        <div class="mr-1 inline-block text-sm">
                          {{ getPercentageOfPortion(+item.price, +item.actual_price) }}
                        </div>
                      </div>
                      <div class="flex flex-wrap items-center gap-3">
                        <div class="text-lg font-iranyekan-bold">
                          {{ numberFormat(item.price) }}
                          <span class="text-xs text-gray-400">تومان</span>
                        </div>

                        <div
                          v-if="+item.price !== +item.actual_price"
                          class="relative text-center"
                        >
                          <span
                            class="absolute top-1/2 -translate-y-1/2 left-0 h-[1px] w-full bg-slate-500 -rotate-3"></span>
                          <div class="text-slate-500 text-sm font-iranyekan-bold">
                            {{ numberFormat(item.actual_price) }}
                            <span class="text-xs text-gray-400">تومان</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div
                  class="shrink-0 text-sm flex flex-col sm:flex-row flex-wrap gap-3 sm:items-center sm:divide-x sm:divide-x-reverse"
                >
                  <div
                    v-if="item.color_name"
                    class="flex items-center"
                  >
                    <span class="text-gray-600 ml-2 text-xs sm:hidden">رنگ:</span>
                    {{ item.color_name }}
                    <span
                      v-tooltip.top="item.color_hex"
                      class="inline-block w-5 h-5 rounded-full border mr-2"
                      :style="'background-color:' + item.color_hex + ';'"
                    ></span>
                  </div>
                  <div
                    v-if="item.size"
                    class="sm:pr-3"
                  >
                    <span class="text-gray-600 ml-2 text-xs sm:hidden">سایز:</span>
                    {{ item.size }}
                  </div>
                  <div
                    v-if="item.guarantee"
                    class="sm:pr-3"
                  >
                    <span class="text-gray-600 ml-2 text-xs sm:hidden">گارانتی:</span>
                    {{ item.guarantee }}
                  </div>
                </div>
              </li>
            </ul>

            <div class="flex flex-col justify-end sm:flex-row gap-3 mt-6">
              <base-button
                class="!text-black border-2 px-6 w-full sm:w-auto hover:!bg-slate-50"
                type="button"
                @click="updateItemsHandler"
              >
                بروزرسانی سبد خرید
              </base-button>
              <base-button
                class="!text-black border-2 border-rose-300 px-6 w-full sm:w-auto hover:!bg-rose-50"
                type="button"
                @click="cancelItemsUpdateHandler"
              >
                لغو تغییرات
              </base-button>
            </div>
          </div>
        </template>
      </partial-card>

      <div class="flex items-center gap-3 my-3 lg:hidden">
        <div class="grow h-0.5 bg-slate-300"></div>
        <div class="shrink-0">
          <ShoppingCartIcon class="w-12 h-12 text-gray-300"/>
        </div>
        <div class="grow h-0.5 bg-slate-300"></div>
      </div>

      <Vue3StickySidebar
          :bottom-spacing="20"
          :min-width="1024"
          :top-spacing="114"
          class="w-full shrink-0 md:w-1/2 lg:w-1/3 md:mr-auto lg:mr-0"
          containerSelector=".cart-info-sticky-container"
          innerWrapperSelector='.sidebar__inner'
      >
        <partial-card class="border-2 border-slate-300 p-8 w-full">
          <template #body>
            <partial-general-title container-class="mb-6" title="خلاصه سفارش"/>

            <ul class="flex flex-col divide-y divide-slate-300">
              <li class="flex flex-wrap justify-between gap-3 py-2 text-sm">
                <div class="font-iranyekan-light leading-relaxed grow">
                  تعداد محصولات
                </div>
                <div class="shrink-0 mr-auto">
                  <div class="flex items-center rounded-full py-0.5 px-3 bg-violet-300">
                    <span class="text-base font-iranyekan-bold">{{ cartStore.count }}</span>
                    <span class="text-xs mr-2">مورد</span>
                  </div>
                </div>
              </li>
              <li class="flex flex-wrap justify-between gap-3 py-2 text-sm">
                <div class="font-iranyekan-light leading-relaxed grow">
                  قیمت محصولات
                </div>
                <div class="shrink-0 mr-auto">
                  <span class="font-iranyekan-bold">{{ numberFormat(cartStore.totalPrice) }}</span>
                  <span class="text-slate-400 text-xs mr-2">تومان</span>
                </div>
              </li>
              <li class="flex flex-wrap justify-between gap-3 py-2 text-sm">
                <div class="font-iranyekan-light leading-relaxed grow">
                  هزینه ارسال
                </div>
                <div class="shrink-0 mr-auto">
                  وابسته به آدرس
                </div>
              </li>

              <li class="flex flex-wrap justify-between gap-3 py-2 text-sm">
                <div class="font-iranyekan-bold leading-relaxed grow">
                  قیمت کل
                </div>
                <div class="shrink-0 mr-auto">
                  <span class="font-iranyekan-bold">{{ numberFormat(cartStore.totalDiscountedPrice) }}</span>
                  <span class="text-slate-400 text-xs mr-2">تومان</span>
                </div>
              </li>
            </ul>

            <base-button
              :disabled="isLoading"
                :to="{name: 'checkout'}"
                class="bg-primary w-full mt-6"
                type="link"
            >
              <VTransitionFade>
                <loader-circle
                  v-if="isLoading"
                  big-circle-color="border-transparent"
                  main-container-klass="absolute w-full h-full top-0 left-0"
                />
              </VTransitionFade>

              <span>بازنگری و پرداخت</span>
            </base-button>
          </template>
        </partial-card>
      </Vue3StickySidebar>
    </div>

    <template v-else>
      <partial-empty-card/>
    </template>
  </div>
</template>

<script setup>
import {ref} from "vue";
import {ShoppingCartIcon, XMarkIcon,} from "@heroicons/vue/24/outline/index.js";
import AppNavigationHeader from "@/components/AppNavigationHeader.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import Vue3StickySidebar from "vue3-sticky-sidebar";
import PartialGeneralTitle from "@/components/partials/PartialGeneralTitle.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import PartialEmptyCard from "@/components/partials/pages/PartialEmptyCard.vue";
import {useConfirmToast} from "@/composables/toast-helper.js";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import BaseSpinner from "@/components/base/BaseSpinner.vue";
import {getPercentageOfPortion, numberFormat} from "@/composables/helper.js";
import {useCartStore} from "@/store/StoreUserCart.js";

const cartStore = useCartStore()

const isLoading = ref(false)
const items = cartStore.getCartItems

function removeItemHandler(code) {
  if (isLoading.value) return

  useConfirmToast(() => {
    isLoading.value = true

    cartStore.removeItem(code)

    setTimeout(() => {
      isLoading.value = false
    }, 800)
  }, 'حذف محصول از سبد خرید؟')
}

function updateItemsHandler() {
  if (!cartStore.getCartItems?.length || isLoading.value) return

  let codesQuantities = {}
  for (let i of cartStore.getCartItems) {
    if (i?.code && i?.quantity) {
      codesQuantities[i.code] = i.quantity
    }
  }

  if (!Object.keys(codesQuantities).length) return

  isLoading.value = true
  cartStore.addAllItems(codesQuantities, {
    finally() {
      isLoading.value = false
    },
  })
}

function cancelItemsUpdateHandler() {
  if (!cartStore.getCartItems?.length || isLoading.value) return

  if (cartStore.isShoppingCartActivated) {
    cartStore.changeToShoppingCart()
  } else if (cartStore.isWishlistCartActivated) {
    cartStore.changeToWishlistCart()
  }
}
</script>
