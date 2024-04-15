<template>
  <app-navigation-header title="جستجوی محصول"/>

  <div class="mb-8 p-3">
    <div class="flex flex-col lg:flex-row-reverse gap-6 sticky-container">
      <div class="grow">
        <base-paginator
          ref="productPaginatorRef"
          v-model:items="products"
          :extra-search-params="searchParams"
          :order="productOrder"
          :path="getSearchPath"
          :per-page="productPerPage"
          :scroll-margin-top="-160"
          :show-pagination-detail="true"
          :show-search="true"
          container-class="flex flex-wrap"
          item-container-class="w-full sm:w-1/2 md:w-1/3 lg:w-1/2 xl:w-1/3 ml-[-1px] mt-[-1px]"
          pagination-theme="modern"
          @order-changed="orderChangeHandler"
        >
          <template #BeforeItemsPanel="{offset, page, perPage, maxPage, total}">
            <div class="flex flex-wrap items-center justify-between gap-3 pb-3">
              <div class="lg:hidden">
                <base-popover-side panel-class="">
                  <template #button>
                    <base-button
                      class="!py-1 flex items-center gap-2 border-2 border-blue-600 !text-black hover:bg-white/40"
                      type="button"
                    >
                      <FunnelIcon class="w-6 h-6"/>
                      <span class="text-sm font-iranyekan-bold">نمایش فیلترها</span>
                    </base-button>
                  </template>

                  <template #panel="{close}">
                    <div class="mb-3 flex flex-row-reverse items-center p-3">
                      <button
                        class="w-[40px] h-[40px] border-0 py-2 px-2 bg-transparent text-black rounded-lg hover:bg-slate-200 active:bg-slate-300 focus:bg-sky-200 transition-all"
                        type="button"
                        @click="close"
                      >
                        <XMarkIcon class="h-6 w-6"/>
                      </button>
                    </div>

                    <div class="h-[calc(100vh-40px-2.25rem)] relative overflow-y-auto">
                      <div class="flex flex-col gap-3">
                        <div v-if="showFestivals">
                          <product-search-festivals
                            @loaded="(hasData) => {showFestivals = hasData}"
                            @select="festivalChangeHandler"
                          />
                        </div>

                        <div>
                          <product-search-filters-container
                            :search-params="searchParams"
                            filter-btn-class="sticky bottom-0 z-[1]"
                            @filter="filterHandler"
                          />
                        </div>
                      </div>
                    </div>
                  </template>
                </base-popover-side>
              </div>

              <partial-paginator-pagniation-info
                :current-page="page"
                :items-length="products?.length || 0"
                :max-page="maxPage"
                :offset="offset"
                :per-page="perPage"
                :total="total"
              />
            </div>
          </template>

          <template #empty>
            <partial-empty-rows
              image="/empty-statuses/empty-product.svg"
              image-class="!w-80"
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
              <loader-card class="!rounded-none"/>
            </div>
          </template>
        </base-paginator>
      </div>

      <Vue3StickySidebar
        :bottom-spacing="20"
        :min-width="1024"
        :top-spacing="114"
        class="shrink-0 hidden lg:block lg:w-80"
        containerSelector=".sticky-container"
        innerWrapperSelector='.sidebar__inner'
      >
        <div class="flex flex-col gap-6">
          <partial-card
            v-if="showFestivals"
            class="border-0 flex flex-col relative"
          >
            <template #body>
              <div
                class="absolute -top-[3px] -left-[3px] w-[calc(100%+6px)] h-[calc(100%+6px)] border-[3px] border-rose-600 rounded-lg animate-pulse -z-[1]"
              ></div>

              <product-search-festivals
                @loaded="(hasData) => {showFestivals = hasData}"
                @select="festivalChangeHandler"
              />
            </template>
          </partial-card>

          <partial-card class="border-0 flex flex-col pb-3">
            <template #body>
              <product-search-filters-container
                :search-params="searchParams"
                @filter="filterHandler"
              />
            </template>
          </partial-card>
        </div>
      </Vue3StickySidebar>
    </div>
  </div>

  <app-newsletter/>
</template>

<script setup>
import {computed, inject, nextTick, onMounted, ref, shallowRef} from "vue";
import {FunnelIcon} from "@heroicons/vue/24/outline/index.js";
import Vue3StickySidebar from "vue3-sticky-sidebar";
import BasePaginator from "@/components/base/BasePaginator.vue";
import PartialEmptyRows from "@/components/partials/PartialEmptyRows.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import LoaderCard from "@/components/base/loader/LoaderCard.vue";
import ProductCard from "@/components/product/ProductCard.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import AppNavigationHeader from "@/components/AppNavigationHeader.vue";
import AppNewsletter from "@/components/AppNewsletter.vue";
import {numberFormat} from "@/composables/helper.js";
import BasePopoverSide from "@/components/base/BasePopoverSide.vue";
import {XMarkIcon} from "@heroicons/vue/24/solid/index.js";
import {PRODUCT_ORDER_TYPES} from "@/composables/constants.js";
import ProductSearchFiltersContainer from "@/components/product/ProductSearchFiltersContainer.vue";
import {apiRoutes} from "@/router/api-routes.js";
import {useRoute, useRouter} from "vue-router";
import ProductSearchFestivals from "@/components/product/ProductSearchFestivals.vue";
import PartialPaginatorPagniationInfo from "@/components/partials/PartialPaginatorPagniationInfo.vue";
import {watchImmediate} from "@vueuse/core";

const homeSettingStore = inject('homeSettingStore')

const productPerPage = computed(() => {
  let ppp = +homeSettingStore.getProductEachPage
  return !isNaN(ppp) ? ppp : 12
})
const getSearchPath = computed(() => {
  return apiRoutes.products.index
})

const productPaginatorRef = ref(null)
const showFestivals = ref(true)

//----------------------------
// Search Products
//----------------------------
const router = useRouter()
const route = useRoute()

const products = ref([])
const searchParams = ref({})
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
    searchParams.value.order = PRODUCT_ORDER_TYPES[t].value
  }
}

function festivalChangeHandler(selected) {
  searchParams.value.festival = selected.id
}

function orderChangeHandler(selected) {
  searchParams.value.order = selected.key
  router.push({query: Object.assign({}, route.query, searchParams.value)})
}

const isLocallyQueryChange = shallowRef(false)

function assignQueryParamsToSearchParams() {
  if (route.query?.min_price && route.query?.max_price) {
    searchParams.value.price_range = [route.query.min_price, route.query.max_price]
  }
  searchParams.value.only_available = route.query?.only_available
  searchParams.value.is_special = route.query?.is_special
  searchParams.value.dynamic_filters = route.query?.dynamic_filters
  searchParams.value.category = route.query?.category
  searchParams.value.festival = route.query?.festival
  searchParams.value.order = route.query?.order

  // handle brands array or a single brand
  if (route.query?.brands && Array.isArray(route.query.brands) && route.query.brands.length) {
    searchParams.value.brands = route.query.brands
  } else if (route.query?.brand) {
    searchParams.value.brands = Array.isArray(route.query.brand) ? route.query.brand : [route.query.brand]
  }
}

function filterHandler(params) {
  Object.assign(searchParams.value, params)

  isLocallyQueryChange.value = true

  let queryObj = {
    query: {
      brands: searchParams.value?.brands,
      only_available: searchParams.value?.only_available,
      is_special: searchParams.value?.is_special,
      dynamic_filters: searchParams.value?.dynamic_filters,
      order: searchParams.value?.order
    }
  }

  if (
    searchParams.value?.price_range &&
    searchParams.value.price_range[0] &&
    searchParams.value.price_range[1]
  ) {
    queryObj.min_price = searchParams.value.price_range[0]
    queryObj.max_price = searchParams.value.price_range[1]
  }

  router.push(queryObj)

  nextTick(() => {
    if (productPaginatorRef.value) {
      productPaginatorRef.value.goToPage(1)
    }
  })
}

watchImmediate(() => route.query, () => {
  assignQueryParamsToSearchParams()

  if (!isLocallyQueryChange.value) {
    nextTick(() => {
      if (productPaginatorRef.value) {
        productPaginatorRef.value.goToPage(1)
      }
    })
  } else {
    isLocallyQueryChange.value = false
  }
})

onMounted(() => {
  assignQueryParamsToSearchParams()
})
</script>
