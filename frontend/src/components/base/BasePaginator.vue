<template>
  <div
      v-if="showSearch || (order && order.length)"
      class="pb-3 flex flex-col"
  >
    <div v-if="showSearch" class="grow">
      <base-datatable-search
          :show-refresh-button="false"
          :show-remove-filter-button-on-input="true"
          class="!p-0"
          @refresh="refreshSearchHandler"
          @search="searchHandler"
          @clear-filter="clearSearchHandler"
      />
    </div>
    <div
        v-if="order && order.length"
        class="mt-3 w-full sm:mt-0"
    >
      <div class="hidden md:flex md:items-center md:gap-2">
        <div class="font-iranyekan-light text-sm">
          مرتب سازی:
        </div>

        <ul class="flex items-center gap-2.5 grow">
          <li
              v-for="o in order"
              :key="o.id"
          >
            <button
                :class="[
                    o.id === selectedOrder.id ? 'border-b-rose-500 font-iranyekan-bold cursor-default' : 'hover:text-opacity-80',
                ]"
                class="border-b-2 border-transparent py-2 text-sm text-black"
                type="button"
                @click="changeOrderHandler(o)"
            >
              {{ o.text }}
            </button>
          </li>
        </ul>
      </div>

      <div class="w-full sm:w-48 md:hidden mr-auto">
        <base-select
            :options="order"
            :selected="selectedOrder"
            class="bg-white"
            options-key="id"
            options-text="text"
            @change="changeOrderHandler"
        />
      </div>
    </div>
  </div>

  <slot
      :maxPage="maxPage"
      :offset="offset"
      :page="currentPage"
      :perPage="+props.perPage"
      :total="total"
      name="BeforeItemsPanel"
  >
    <partial-paginator-pagniation-info
        v-if="showPaginationDetail"
        :offset="offset"
        :per-page="props.perPage"
        :current-page="currentPage"
        :max-page="maxPage"
        :total="total"
        :items-length="items?.length || 0"
    />
  </slot>

  <div ref="itemsContainerRef">
    <VTransitionSlideFadeDownY mode="out-in">
      <div v-if="!actualItems.length && !isLoading && !loading">
        <slot name="empty"></slot>
      </div>
      <div
          v-else
          :class="containerClass"
      >
        <div
            v-for="(item, idx) of actualItems"
            v-if="!loading && !isLoading"
            :key="idx + '_1'"
            :class="itemContainerClass"
        >
          <slot :item="item" name="item"></slot>
        </div>
        <div
            v-for="idx in perPage"
            v-else
            :key="idx + '_2'"
            :class="itemContainerClass"
        >
          <slot :index="idx" name="loading"></slot>
        </div>
      </div>
    </VTransitionSlideFadeDownY>
  </div>

  <div v-if="showPagination && maxPage > 1" class="mt-3">
    <base-pagination
        v-model:current-page="currentPage"
        v-model:max-page="maxPage"
        v-model:paging="paging"
        :move-page="movePage"
        :next-page="nextPage"
        :prev-page="prevPage"
        :theme="paginationTheme"
    />
  </div>
</template>

<script setup>
import {computed, nextTick, onBeforeUnmount, ref, watch} from "vue";
import {useRequest} from "@/composables/api-request.js";
import BaseDatatableSearch from "./datatable/BaseDatatableSearch.vue";
import BaseSelect from "./BaseSelect.vue";
import isFunction from "lodash.isfunction";
import BasePagination from "./BasePagination.vue";
import {formatPriceLikeNumber} from "@/composables/helper.js";
import {apiReplaceParams} from "@/router/api-routes.js";
import isObject from "lodash.isobject";
import VTransitionSlideFadeDownY from "@/transitions/VTransitionSlideFadeDownY.vue";
import PartialPaginatorPagniationInfo from "@/components/partials/PartialPaginatorPagniationInfo.vue";

const props = defineProps({
  containerClass: String,
  itemContainerClass: String,
  items: Array,
  path: String,
  pathReplacementParams: Object,
  extraSearchParams: Object,
  currentPage: {
    type: Number,
    default: 1,
  },
  perPage: {
    type: Number,
    default: 15,
  },
  // to control show loader from outside of component(local loading purposes)
  isLoading: Boolean,
  order: {
    type: Array,
    default: () => [],
    validator: (value) => {
      for (let i of value) {
        if (!i.id || !i.key || !i.text) return false
      }

      return true
    },
  },
  searchText: String,
  localSearchFilterHandler: Function,
  showSearch: Boolean,
  showPagination: {
    type: Boolean,
    default: true,
  },
  showPaginationDetail: Boolean,
  paginationTheme: String,
  //
  scrollMarginTop: {
    type: Number,
    default: 0,
  },
})
const emit = defineEmits(['update:items', 'update:searchText', 'order-changed'])

const itemsContainerRef = ref(null)
const loading = ref(true)

const actualItems = ref([])
const total = ref(props.items?.length || 0)

const currentPage = ref(1)
const maxPage = computed(() => {
  if (total.value <= 0) return 1

  let lastPage = Math.floor(total.value / props.perPage)
  let mod = total.value % props.perPage

  if (mod > 0) lastPage++
  return lastPage
})
const query = computed({
  get() {
    return props.searchText
  },
  set(value) {
    emit('update:searchText', value)
  },
})
const paging = computed(() => {
  let startPage = currentPage.value - 2 <= 0 ? 1 : currentPage.value - 2;
  if (maxPage.value - currentPage.value <= 2) {
    startPage = maxPage.value - 4;
  }
  startPage = startPage <= 0 ? 1 : startPage;
  let pages = [];
  for (let i = startPage; i <= maxPage.value; i++) {
    if (pages.length < 5) {
      pages.push(i);
    }
  }
  return pages;
})
const offset = computed(() => {
  return (currentPage.value - 1) * props.perPage
})

//-------------------------------
// First selected order process
//-------------------------------
const selectedOrder = ref(null)

function prepareSelectedOrder() {
  if (!props.order.length) return

  for (let i of props.order) {
    if (i.selected) {
      selectedOrder.value = i
      break
    }
  }

  if (!selectedOrder.value)
    selectedOrder.value = props.order[0]
}

prepareSelectedOrder()

watch(() => props.order, () => {
  prepareSelectedOrder()
})

//-------------------------------
watch([() => props.extraSearchParams, () => props.path, () => props.items], () => {
  goToPage(1)
})

//-------------------------------
// Search operations
//-------------------------------
function searchHandler(searchText) {
  query.value = searchText
  nextTick(() => {
    goToPage(currentPage.value)
  })
}

function clearSearchHandler(payload) {
  query.value = '';
  nextTick(() => {
    payload.resetField(payload.name)
    if (currentPage.value === 1)
      goToPage(currentPage.value)
    else
      currentPage.value = 1
  })
}

function refreshSearchHandler() {
  goToPage(currentPage.value)
}

//-------------------------------
// Order operations
//-------------------------------
function changeOrderHandler(selected) {
  if (selected.id === selectedOrder.value.id) return

  emit('order-changed', selected)
  selectedOrder.value = selected
  goToPage(currentPage.value)
}

//-------------------------------
watch(currentPage, () => {
  goToPage(currentPage.value)
})

let localLoadingTimeout = null

function goToPage(page) {
  let params = {
    limit: props.perPage,
    offset: (page - 1) * props.perPage,
    text: query.value || ''
  }

  if (selectedOrder.value) {
    params.order = selectedOrder.value.key
    params.sort = selectedOrder.value.sort
  }

  if (isObject(props.extraSearchParams) && Object.keys(props.extraSearchParams).length) {
    params = Object.assign({}, props.extraSearchParams, params)
    props.extraSearchParams.order = params.order
  }

  loading.value = true

  if (props.items?.length) {
    clearTimeout(localLoadingTimeout)

    new Promise((resolve) => {
      let rows = props.items

      if (isFunction(props.localSearchFilterHandler)) {
        rows = rows.filter((item) => {
          return props.localSearchFilterHandler.call(null, item, params.text)
        });
      }

      let collator = new Intl.Collator(undefined, {
        numeric: true,
        sensitivity: "base",
      });

      if (selectedOrder.value) {
        let sortOrder = params.sort === "desc" ? -1 : 1;
        rows.sort(function (a, b) {
          return collator.compare(a[params.order], b[params.order]) * sortOrder;
        });
      }

      actualItems.value = [];
      for (let index = params.offset; index < (params.limit * page) && index < props.items.length; index++) {
        if (rows[index])
          actualItems.value.push(rows[index]);
      }

      localLoadingTimeout = setTimeout(() => {
        loading.value = false
        goToTop()

        resolve()
      }, 1000)
    })
  } else if (props.path && props.path.trim() !== '') {
    if (isObject(props.pathReplacementParams)) {
      useRequest(apiReplaceParams(props.path, props.pathReplacementParams), {params}, {
        success: (response) => {
          actualItems.value = response.data || []
          total.value = response.meta.total || 0

          loading.value = false
          goToTop()
        },
      })
    } else {
      useRequest(props.path, {params}, {
        success: (response) => {
          actualItems.value = response.data || []
          total.value = response.meta.total || 0

          loading.value = false
          goToTop()
        },
      })
    }
  } else {
    actualItems.value = []
    loading.value = false
  }
}

goToPage(currentPage.value)

function prevPage() {
  if (currentPage.value - 1 > 0) {
    currentPage.value--
  }
}

function movePage(page) {
  if (page > 0 && page <= maxPage.value) {
    currentPage.value = page
  }
}

function nextPage() {
  if (currentPage.value + 1 <= maxPage.value) {
    currentPage.value++
  }
}

function goToTop() {
  if (!itemsContainerRef.value) return

  // Calculate the position to scroll to (slightly above the target element)
  const scrollToY = itemsContainerRef.value.getBoundingClientRect().top + window.scrollY + props.scrollMarginTop;

  // Scroll to the calculated position
  window.scrollTo({
    top: scrollToY,
    behavior: 'smooth'
  });
}

onBeforeUnmount(() => {
  clearTimeout(localLoadingTimeout)
  localLoadingTimeout = null
})

defineExpose({
  goToPage,
  goToTop,
})
</script>
