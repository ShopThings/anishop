<template>
  <app-navigation-header title="جستجوی محصول"/>

  <div class="mb-8 p-3">
    <div class="flex flex-col lg:flex-row-reverse gap-6 sticky-container">
      <div class="grow">
        <base-paginator
          ref="productPaginatorRef"
          v-model:total="paginatorSettings.totalProducts"
          :extra-search-params="paginatorSettings.extraSearchParams"
          :just-notice-order-changed="paginatorSettings.justNoticeOrderChanged"
          :load-on-appearance="paginatorSettings.loadOnAppearance"
          :number-of-loaders="paginatorSettings.numberOfLoaders"
          :order="paginatorSettings.productOrder"
          :path="paginatorSettings.searchPath"
          :per-page="paginatorSettings.getProductPerPage()"
          :scroll-margin-top="paginatorSettings.scrollMarginTop"
          :scroll-to-element-on-appearance="paginatorSettings.scrollToElementOnAppearance"
          :show-pagination-detail="paginatorSettings.showPaginationDetail"
          :show-search="paginatorSettings.showSearch"
          :search-text="route.query?.q || ''"
          container-class="flex flex-wrap"
          item-container-class="w-full sm:w-1/2 md:w-1/3 lg:w-1/2 xl:w-1/3 2xl:w-1/4 ml-[-1px] mt-[-1px]"
          pagination-theme="modern"
          @order-changed="orderChangeHandler"
        >
          <template #beforeItemsPanel="{offset, page, perPage, maxPage, total}">
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
                            filter-btn-class="sticky bottom-0 z-[1]"
                            @filters-loaded="filtersLoadedHandler"
                            @filter="filterHandler"
                            @clear="clearHandler"
                          />
                        </div>
                      </div>
                    </div>
                  </template>
                </base-popover-side>
              </div>

              <partial-paginator-pagination-info
                :current-page="page"
                :max-page="maxPage"
                :offset="offset"
                :per-page="perPage"
                :total="total"
              />
            </div>
          </template>

          <template #empty>
            <partial-empty-rows
              image="/images/empty-statuses/empty-product.svg"
              image-class="!w-[22rem]"
              message="هیچ محصولی پیدا نشد!"
            />
          </template>

          <template #item="{item}">
            <product-card
              :product="item"
              container-class="hover:shadow-xl duration-500 w-full hover:z-[1] hover:relative transition"
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
                @filters-loaded="filtersLoadedHandler"
                @filter="filterHandler"
                @clear="clearHandler"
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
import {inject, nextTick, reactive, ref, shallowRef, watch} from "vue";
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
import BasePopoverSide from "@/components/base/BasePopoverSide.vue";
import {XMarkIcon} from "@heroicons/vue/24/solid/index.js";
import {PRODUCT_ORDER_TYPES} from "@/composables/constants.js";
import ProductSearchFiltersContainer from "@/components/product/ProductSearchFiltersContainer.vue";
import {apiRoutes} from "@/router/api-routes.js";
import {useRoute, useRouter} from "vue-router";
import ProductSearchFestivals from "@/components/product/ProductSearchFestivals.vue";
import PartialPaginatorPaginationInfo from "@/components/partials/PartialPaginatorPaginationInfo.vue";
import {useProductFilterParamStore} from "@/store/StoreProductFilter.js";

const homeSettingStore = inject('homeSettingStore')

const productPaginatorRef = ref(null)
const showFestivals = ref(true)

//----------------------------
// Search Products
//----------------------------
const router = useRouter()
const route = useRoute()

const filterParamStore = useProductFilterParamStore()

const paginatorSettings = reactive({
  searchPath: apiRoutes.products.index,
  totalProducts: 0,
  getProductPerPage: () => {
    let ppp = +homeSettingStore.getProductEachPage
    return !isNaN(ppp) ? ppp : 12
  },
  loadOnAppearance: false,
  extraSearchParams: filterParamStore.routeKeys,
  productOrder: [],
  justNoticeOrderChanged: true,
  scrollToElementOnAppearance: true,
  scrollMarginTop: -160,
  showPaginationDetail: true,
  showSearch: true,
  numberOfLoaders: 6,
})

// create orders
let counter = 1
for (const t in PRODUCT_ORDER_TYPES) {
  if (PRODUCT_ORDER_TYPES.hasOwnProperty(t)) {
    paginatorSettings.productOrder.push({
      id: counter++,
      key: PRODUCT_ORDER_TYPES[t].value,
      text: PRODUCT_ORDER_TYPES[t].text,
    })
    filterParamStore.setOrder(PRODUCT_ORDER_TYPES[t].value)
  }
}

function festivalChangeHandler(selected) {
  filterParamStore.setFestival(selected.id)
  filterHandler()
}

function orderChangeHandler(selected) {
  filterParamStore.setOrder(selected.key)
  router.push({query: Object.assign({}, route.query, filterParamStore.getRouteQueryObject())})
}

function goToFirstPage() {
  nextTick(() => {
    if (productPaginatorRef.value) {
      productPaginatorRef.value.goToPage(1)
    }
  })
}

const isLocallyQueryChange = shallowRef(false)

function triggerRouteOnSearchParams() {
  isLocallyQueryChange.value = true
  router.push({query: filterParamStore.getRouteQueryObject()})
  goToFirstPage()
}

function filtersLoadedHandler() {
  goToFirstPage()
}

function filterHandler() {
  triggerRouteOnSearchParams()
}

function clearHandler() {
  filterParamStore.resetSearchParams(['category', 'festival', 'order'])
  triggerRouteOnSearchParams()
}

watch(() => route.query, () => {
  filterParamStore.readFiltersFromRoute()

  if (!isLocallyQueryChange.value) {
    goToFirstPage()
  } else {
    isLocallyQueryChange.value = false
  }
})
</script>
