<template>
  <app-navigation-header title="جستجوی محصول"/>

  <div class="mb-8 p-3">
    <div class="flex flex-col lg:flex-row-reverse gap-6 sticky-container">
      <div class="grow">
        <base-paginator
            container-class="flex flex-wrap"
            item-container-class="w-full sm:w-1/2 md:w-1/3 lg:w-1/2 xl:w-1/3 ml-[-1px] mt-[-1px]"
            pagination-theme="modern"
            :per-page="16"
            :show-search="true"
            :order="productOrder"
            v-model:items="products"
            :is-local="true"
            :show-pagination-detail="true"
        >
          <template #BeforeItemsPanel="{offset, page, perPage, maxPage, total}">
            <div class="flex flex-wrap items-center justify-between gap-3 pb-3">
              <div class="lg:hidden">
                <base-popover-side
                    panel-class=""
                >
                  <template #button>
                    <base-button
                        type="button"
                        class="!py-1 flex items-center gap-2 border-2 border-blue-600 !text-black hover:bg-white/40"
                    >
                      <FunnelIcon class="w-6 h-6"/>
                      <span class="text-sm font-iranyekan-bold">نمایش فیلترها</span>
                    </base-button>
                  </template>

                  <template #panel="{close}">
                    <div class="mb-3 flex flex-row-reverse items-center p-3">
                      <button @click="close" type="button"
                              class="w-[40px] h-[40px] border-0 py-2 px-2 bg-transparent text-black rounded-lg hover:bg-slate-200 active:bg-slate-300 focus:bg-sky-200 transition-all">
                        <XMarkIcon class="h-6 w-6"/>
                      </button>
                    </div>

                    <div class="h-[calc(100vh-40px-2.25rem)] relative overflow-y-auto">
                      <div class="flex items-center p-3 gap-3 justify-between">
                        <span class="font-iranyekan-bold text-xl">فیلترها</span>
                        <button
                            type="button"
                            class="p-1 text-cyan-600 text-sm hover:text-opacity-80 transition"
                            @click="clearAllFilters"
                        >
                          حذف فیلترها
                        </button>
                      </div>

                      <div
                          class="text-emerald-700 px-3 py-1.5 text-xs leading-relaxed bg-emerald-50">
                        پس از انتخاب فیلترها، از دکمه
                        <span class="text-black">اعمال فیلتر</span>
                        در پایین صفحه برای اعمال فیلترها، استفاده نمایید.
                      </div>

                      <div class="mt-3">
                        <product-search-filters/>
                      </div>

                      <div class="px-3 pb-3 pt-5 bg-white sticky bottom-0 z-[1]">
                        <base-button
                            class="w-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 hover:bg-gradient-to-bl">
                          اعمال فیلترها
                        </base-button>
                      </div>
                    </div>
                  </template>
                </base-popover-side>
              </div>

              <div
                  class="flex flex-wrap items-center justify-end gap-4 px-3 text-slate-400 divide-x-2 divide-x-reverse divide-slate-200 mr-auto">
                <div>
                  <span class="ml-1.5 text-sm font-iranyekan-light">نمایش</span>
                  <span class="font-iranyekan-bold">{{ formatPriceLikeNumber(offset + 1) }}</span>
                  <span class="mx-1.5 text-sm font-iranyekan-light">تا</span>
                  <span class="font-iranyekan-bold">{{
                      formatPriceLikeNumber(offset + Math.min(products.length, perPage))
                    }}</span>
                </div>
                <div class="pr-3">
                  <span class="ml-1.5 text-sm font-iranyekan-light">صفحه</span>
                  <span class="font-iranyekan-bold">{{ formatPriceLikeNumber(page) }}</span>
                  <span class="mx-1.5 text-sm font-iranyekan-light">از</span>
                  <span class="font-iranyekan-bold">{{ formatPriceLikeNumber(maxPage) }}</span>
                </div>
                <div class="pr-3">
                  <span class="font-iranyekan-bold">{{ formatPriceLikeNumber(total) }}</span>
                  <span class="mr-1 text-sm font-iranyekan-light">مورد</span>
                </div>
              </div>
            </div>
          </template>

          <template #empty>
            <partial-empty-rows
                image="/empty-statuses/empty-product.svg"
                image-class="w-60"
                message="هیچ محصولی پیدا نشد!"
            />
          </template>

          <template #item="{item}">
            <product-card
                :product="item"
                container-class="hover:shadow-lg w-full hover:z-[1] hover:relative transition"
            />
          </template>

          <template #loading>
            <div class="border-b">
              <loader-card class="rounded-none"/>
            </div>
          </template>
        </base-paginator>
      </div>

      <Vue3StickySidebar
          class="shrink-0 hidden lg:block lg:w-80"
          containerSelector=".sticky-container"
          innerWrapperSelector='.sidebar__inner'
          :top-spacing="114"
          :bottom-spacing="20"
          :min-width="1024"
      >
        <div class="flex flex-col gap-6">
          <partial-card
              class="border-0 flex flex-col pb-3">
            <template #body>
              <div class="flex items-center p-3 gap-3 justify-between">
                <span class="font-iranyekan-bold text-xl">فیلترها</span>
                <button
                    type="button"
                    class="p-1 text-cyan-600 text-sm hover:text-opacity-80 transition"
                    @click="clearAllFilters"
                >
                  حذف فیلترها
                </button>
              </div>

              <div class="text-emerald-700 px-3 py-1.5 text-xs leading-relaxed bg-emerald-50">
                پس از انتخاب فیلترها، از دکمه
                <span class="text-black">اعمال فیلتر</span>
                در پایین صفحه برای اعمال فیلترها، استفاده نمایید.
              </div>

              <div class="mt-3">
                <product-search-filters/>
              </div>

              <div class="px-3 pb-3 pt-5 bg-white">
                <base-button
                    class="w-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 hover:bg-gradient-to-bl">
                  اعمال فیلترها
                </base-button>
              </div>
            </template>
          </partial-card>
        </div>
      </Vue3StickySidebar>
    </div>
  </div>

  <app-newsletter/>
</template>

<script setup>
import {ref} from "vue";
import {FunnelIcon} from "@heroicons/vue/24/outline/index.js";
import Vue3StickySidebar from "vue3-sticky-sidebar";
import BasePaginator from "../components/base/BasePaginator.vue";
import PartialEmptyRows from "../components/partials/PartialEmptyRows.vue";
import PartialCard from "../components/partials/PartialCard.vue";
import LoaderCard from "../components/base/loader/LoaderCard.vue";
import ProductCard from "../components/product/ProductCard.vue";
import ProductSearchFilters from "../components/product/ProductSearchFilters.vue";
import BaseButton from "../components/base/BaseButton.vue";
import AppNavigationHeader from "../components/AppNavigationHeader.vue";
import AppNewsletter from "../components/AppNewsletter.vue";
import {formatPriceLikeNumber} from "../composables/helper.js";
import BasePopoverSide from "../components/base/BasePopoverSide.vue";
import {XMarkIcon} from "@heroicons/vue/24/solid/index.js";
import {PRODUCT_ORDER_TYPES} from "../composables/constants.js";

function clearAllFilters() {
  // ...
}

//----------------------------
// Search Products
//----------------------------
const productOrder = []

// create orders
let counter = 1
for (const t in PRODUCT_ORDER_TYPES) {
  if (PRODUCT_ORDER_TYPES.hasOwnProperty(t)) {
    productOrder.push({
      id: counter++,
      key: PRODUCT_ORDER_TYPES[t].value,
      text: PRODUCT_ORDER_TYPES[t].text,
    })
  }
}
//

const products = ref([
  {
    id: 1,
    brand_id: 2,
    category_id: 5,
    title: 'گوشی موبایل سامسونگ مدل Galaxy S23 Ultra دو سیم کارت ظرفیت 256 گیگابایت و رم 12 گیگابایت - ویتنام',
    brand: {
      name: 'سامسونگ',
    },
    category: {
      name: 'موبایل',
    },
    image: {
      name: 'samsung_s23_ultra',
      path: '/src/assets/product/g1.jpg',
    },
    festivals: [
      {
        festival_id: 1,
        product_id: 1,
        discount_percentage: 2,
      },
    ],
    products: [
      {
        id: 1,
        color_name: 'مشکی',
        color_hex: '#000000',
        size: null,
        guarantee: null,
        price: 49500000,
        discounted_price: 48500000,
        stock_count: 8,
        max_cart_count: 4,
        is_available: true,
        is_special: false,
        show_coming_soon: false,
        show_call_for_more: false,
      },
    ],
  },
  {
    id: 2,
    brand_id: 2,
    category_id: 5,
    title: 'گوشی موبایل سامسونگ مدل Galaxy S23 Ultra دو سیم کارت ظرفیت 256 گیگابایت و رم 12 گیگابایت - ویتنام',
    brand: {
      name: 'سامسونگ',
    },
    category: {
      name: 'موبایل',
    },
    image: {
      name: 'samsung_s23_ultra',
      path: '/src/assets/product/g2.jpg',
    },
    festivals: [
      {
        festival_id: 1,
        product_id: 1,
        discount_percentage: 2,
      },
    ],
    products: [
      {
        id: 1,
        color_name: 'مشکی',
        color_hex: '#000000',
        size: 'XL',
        guarantee: 'گارانتی ۱۸ ماهه شرکتی',
        price: 49500000,
        discounted_price: 48500000,
        stock_count: 8,
        max_cart_count: 4,
        is_available: true,
        is_special: false,
        show_coming_soon: false,
        show_call_for_more: false,
      },
      {
        id: 2,
        color_name: 'مشکی',
        color_hex: '#000000',
        size: 'XLL',
        guarantee: 'گارانتی ۱۸ ماهه شرکتی',
        price: 49500000,
        discounted_price: 48500000,
        stock_count: 10,
        max_cart_count: 4,
        is_available: true,
        is_special: false,
        show_coming_soon: true,
        show_call_for_more: false,
      },
      {
        id: 3,
        color_name: 'کرم',
        color_hex: '#F3ECE2',
        size: 'L',
        guarantee: 'گارانتی ۱۸ ماهه شرکتی',
        price: 49700000,
        discounted_price: 48700000,
        stock_count: 5,
        max_cart_count: 3,
        is_available: true,
        is_special: true,
        show_coming_soon: false,
        show_call_for_more: false,
      },
      {
        id: 4,
        color_name: 'بنفش',
        color_hex: '#E7DBE5',
        size: 'L',
        guarantee: 'گارانتی ۲۴ ماهه شرکتی',
        price: 49950000,
        discounted_price: 48950000,
        stock_count: 5,
        max_cart_count: 2,
        is_available: true,
        is_special: false,
        show_coming_soon: false,
        show_call_for_more: false,
      },
    ],
  },
  {
    id: 3,
    brand_id: 2,
    category_id: 5,
    title: 'گوشی موبایل سامسونگ مدل Galaxy S23 Ultra دو سیم کارت ظرفیت 256 گیگابایت و رم 12 گیگابایت - ویتنام',
    brand: {
      name: 'سامسونگ',
    },
    category: {
      name: 'موبایل',
    },
    image: {
      name: 'samsung_s23_ultra',
      path: '/src/assets/product/g3.jpg',
    },
    festivals: [
      {
        festival_id: 1,
        product_id: 1,
        discount_percentage: 2,
      },
    ],
    products: [
      {
        id: 1,
        color_name: 'مشکی',
        color_hex: '#000000',
        size: 'XL',
        guarantee: 'گارانتی ۱۸ ماهه شرکتی',
        price: 49500000,
        discounted_price: 48500000,
        stock_count: 8,
        max_cart_count: 4,
        is_available: true,
        is_special: false,
        show_coming_soon: false,
        show_call_for_more: false,
      },
      {
        id: 2,
        color_name: 'مشکی',
        color_hex: '#000000',
        size: 'XLL',
        guarantee: 'گارانتی ۱۸ ماهه شرکتی',
        price: 49500000,
        discounted_price: 48500000,
        stock_count: 10,
        max_cart_count: 4,
        is_available: true,
        is_special: false,
        show_coming_soon: true,
        show_call_for_more: false,
      },
      {
        id: 3,
        color_name: 'کرم',
        color_hex: '#F3ECE2',
        size: 'L',
        guarantee: 'گارانتی ۱۸ ماهه شرکتی',
        price: 49700000,
        discounted_price: 48700000,
        stock_count: 5,
        max_cart_count: 3,
        is_available: true,
        is_special: true,
        show_coming_soon: false,
        show_call_for_more: false,
      },
      {
        id: 4,
        color_name: 'بنفش',
        color_hex: '#E7DBE5',
        size: 'L',
        guarantee: 'گارانتی ۲۴ ماهه شرکتی',
        price: 49950000,
        discounted_price: 48950000,
        stock_count: 5,
        max_cart_count: 2,
        is_available: true,
        is_special: false,
        show_coming_soon: false,
        show_call_for_more: false,
      },
    ],
  },
  {
    id: 4,
    brand_id: 2,
    category_id: 5,
    title: 'گوشی موبایل سامسونگ مدل Galaxy S23 Ultra دو سیم کارت ظرفیت 256 گیگابایت و رم 12 گیگابایت - ویتنام',
    brand: {
      name: 'سامسونگ',
    },
    category: {
      name: 'موبایل',
    },
    image: {
      name: 'samsung_s23_ultra',
      path: '/src/assets/product/g4.jpg',
    },
    festivals: [
      {
        festival_id: 1,
        product_id: 1,
        discount_percentage: 2,
      },
    ],
    products: [
      {
        id: 1,
        color_name: 'مشکی',
        color_hex: '#000000',
        size: null,
        guarantee: null,
        price: 49500000,
        discounted_price: 48500000,
        stock_count: 8,
        max_cart_count: 4,
        is_available: true,
        is_special: false,
        show_coming_soon: false,
        show_call_for_more: false,
      },
    ],
  },
  {
    id: 5,
    brand_id: 2,
    category_id: 5,
    title: 'گوشی موبایل سامسونگ مدل Galaxy S23 Ultra دو سیم کارت ظرفیت 256 گیگابایت و رم 12 گیگابایت - ویتنام',
    brand: {
      name: 'سامسونگ',
    },
    category: {
      name: 'موبایل',
    },
    image: {
      name: 'samsung_s23_ultra',
      path: '/src/assets/product/g5.jpg',
    },
    festivals: [
      {
        festival_id: 1,
        product_id: 1,
        discount_percentage: 2,
      },
    ],
    products: [
      {
        id: 1,
        color_name: null,
        color_hex: null,
        size: 'XL',
        guarantee: null,
        price: 49500000,
        discounted_price: 48500000,
        stock_count: 8,
        max_cart_count: 4,
        is_available: true,
        is_special: false,
        show_coming_soon: false,
        show_call_for_more: true,
      },
      {
        id: 2,
        color_name: 'مشکی',
        color_hex: '#000000',
        size: 'XLL',
        guarantee: 'گارانتی ۱۸ ماهه شرکتی',
        price: 49500000,
        discounted_price: 48500000,
        stock_count: 0,
        max_cart_count: 4,
        is_available: true,
        is_special: false,
        show_coming_soon: false,
        show_call_for_more: false,
      },
      {
        id: 3,
        color_name: 'کرم',
        color_hex: '#F3ECE2',
        size: 'L',
        guarantee: 'گارانتی ۱۸ ماهه شرکتی',
        price: 49700000,
        discounted_price: 48700000,
        stock_count: 5,
        max_cart_count: 3,
        is_available: true,
        is_special: true,
        show_coming_soon: false,
        show_call_for_more: false,
      },
      {
        id: 4,
        color_name: 'بنفش',
        color_hex: '#E7DBE5',
        size: 'L',
        guarantee: 'گارانتی ۲۴ ماهه شرکتی',
        price: 49950000,
        discounted_price: 48950000,
        stock_count: 5,
        max_cart_count: 2,
        is_available: true,
        is_special: false,
        show_coming_soon: false,
        show_call_for_more: false,
      },
    ],
  },
  {
    id: 6,
    brand_id: 2,
    category_id: 5,
    title: 'گوشی موبایل سامسونگ مدل Galaxy S23 Ultra دو سیم کارت ظرفیت 256 گیگابایت و رم 12 گیگابایت - ویتنام',
    brand: {
      name: 'سامسونگ',
    },
    category: {
      name: 'موبایل',
    },
    image: {
      name: 'samsung_s23_ultra',
      path: '/src/assets/product/g6.jpg',
    },
    festivals: [
      {
        festival_id: 1,
        product_id: 1,
        discount_percentage: 2,
      },
    ],
    products: [
      {
        id: 1,
        color_name: 'مشکی',
        color_hex: '#000000',
        size: 'XL',
        guarantee: 'گارانتی ۱۸ ماهه شرکتی',
        price: 49500000,
        discounted_price: 48500000,
        stock_count: 8,
        max_cart_count: 4,
        is_available: true,
        is_special: false,
        show_coming_soon: false,
        show_call_for_more: false,
      },
      {
        id: 2,
        color_name: 'مشکی',
        color_hex: '#000000',
        size: 'XLL',
        guarantee: 'گارانتی ۱۸ ماهه شرکتی',
        price: 49500000,
        discounted_price: 48500000,
        stock_count: 10,
        max_cart_count: 4,
        is_available: true,
        is_special: false,
        show_coming_soon: true,
        show_call_for_more: false,
      },
      {
        id: 3,
        color_name: 'کرم',
        color_hex: '#F3ECE2',
        size: 'L',
        guarantee: 'گارانتی ۱۸ ماهه شرکتی',
        price: 49700000,
        discounted_price: 48700000,
        stock_count: 5,
        max_cart_count: 3,
        is_available: true,
        is_special: true,
        show_coming_soon: false,
        show_call_for_more: false,
      },
      {
        id: 4,
        color_name: 'بنفش',
        color_hex: '#E7DBE5',
        size: 'L',
        guarantee: 'گارانتی ۲۴ ماهه شرکتی',
        price: 49950000,
        discounted_price: 48950000,
        stock_count: 5,
        max_cart_count: 2,
        is_available: true,
        is_special: false,
        show_coming_soon: false,
        show_call_for_more: false,
      },
    ],
  },
])
//----------------------------
</script>

<style scoped>

</style>
