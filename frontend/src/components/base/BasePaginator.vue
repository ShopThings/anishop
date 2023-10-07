<template>
    <div class="py-3 flex flex-col">
        <div v-if="showSearch">
            <base-datatable-search
                class="!p-0"
                @search="searchHandler"
                @clear-filter="clearSearchHandler"
                @refresh="refreshSearchHandler"
            />
        </div>
        <div
            v-if="order && order.length"
            class="w-56"
        >
            <base-select
                class="bg-white"
                options-text="text"
                options-key="id"
                :options="order"
                :selected="selectedOrder"
                @change="changeOrderHandler"
            />
        </div>
    </div>

    <div :class="containerClass">
        <slot v-if="!actualItems.length" name="empty"></slot>
        <template v-else>
            <div
                v-if="!loading"
                v-for="(item, idx) of actualItems"
                :key="idx + '_1'"
                :class="itemContainerClass"
            >
                <slot name="item" :item="item"></slot>
            </div>
            <div
                v-else
                v-for="idx in perPage"
                :key="idx + '_2'"
                :class="itemContainerClass"
            >
                <slot name="loading" :index="idx"></slot>
            </div>
        </template>
    </div>

    <div v-if="showPagination && maxPage > 1" class="mt-3">
        <ul class="flex justify-center text-center rtl:flex-row-reverse whitespace-nowrap no-underline flex-wrap">
            <li>
                <a v-tooltip.top="'صفحه اول'"
                   class="cursor-pointer bg-white relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-200 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                   :class="{'!cursor-not-allowed !bg-gray-100 !text-gray-400': currentPage <= 1}"
                   :disabled="currentPage <= 1"
                   aria-label="First"
                   @click.prevent="currentPage = 1"
                >
                    <span aria-hidden="true"><ChevronDoubleLeftIcon class="w-4 h-4"/></span>
                    <span class="sr-only">صفحه اول</span>
                </a>
            </li>
            <li>
                <a v-tooltip.top="'صفحه قبل'"
                   class="cursor-pointer bg-white relative inline-flex items-center px-4 py-2 text-sm text-gray-800 ring-1 ring-inset ring-gray-200 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                   :class="{'!cursor-not-allowed !bg-gray-100 !text-gray-400': currentPage <= 1}"
                   :disabled="currentPage <= 1"
                   aria-label="Previous"
                   @click.prevent="prevPage"
                >
                    <span aria-hidden="true"><ChevronLeftIcon class="w-4 h-4"/></span>
                    <span class="sr-only">صفحه قبل</span>
                </a>
            </li>
            <li v-for="n in paging"
                :key="n"
            >
                <a v-tooltip.top="'صفحه ' + n"
                   class="cursor-pointer bg-white relative inline-flex items-center px-4 py-2 text-xs text-gray-800 ring-1 ring-inset ring-gray-200 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                   :class="{'!cursor-not-allowed !bg-primary !text-white !ring-primary': currentPage === n}"
                   :disabled="currentPage === n"
                   @click.prevent="movePage(n)"
                >
                    {{ n }}
                </a>
            </li>
            <li>
                <a v-tooltip.top="'صفحه بعد'"
                   class="cursor-pointer bg-white relative inline-flex items-center px-4 py-2 text-sm text-gray-800 ring-1 ring-inset ring-gray-200 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                   :class="{'!cursor-not-allowed !bg-gray-100 !text-gray-400': currentPage >= maxPage}"
                   :disabled="currentPage >= maxPage"
                   aria-label="Next"
                   @click.prevent="nextPage"
                >
                    <span aria-hidden="true"><ChevronRightIcon class="w-4 h-4"/></span>
                    <span class="sr-only">صفحه بعد</span>
                </a>
            </li>
            <li>
                <a v-tooltip.top="'صفحه آخر'"
                   class="cursor-pointer bg-white relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-200 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                   :class="{'!cursor-not-allowed !bg-gray-100 !text-gray-400': currentPage >= maxPage}"
                   :disabled="currentPage >= maxPage"
                   aria-label="Last"
                   @click.prevent="currentPage = maxPage"
                >
                    <span aria-hidden="true"><ChevronDoubleRightIcon class="w-4 h-4"/></span>
                    <span class="sr-only">صفحه آخر</span>
                </a>
            </li>
        </ul>
    </div>
</template>

<script setup>
import {computed, nextTick, onUnmounted, ref, watch} from "vue";
import {
    ChevronDoubleLeftIcon,
    ChevronDoubleRightIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
} from "@heroicons/vue/24/outline/index.js";
import {useRequest} from "../../composables/api-request.js";
import BaseDatatableSearch from "./datatable/BaseDatatableSearch.vue";
import BaseSelect from "./BaseSelect.vue";
import isFunction from "lodash.isfunction";

const props = defineProps({
    containerClass: String,
    itemContainerClass: String,
    items: Array,
    path: String,
    currentPage: {
        type: Number,
        default: 1,
    },
    perPage: {
        type: Number,
        default: 15,
    },
    isLocal: {
        type: Boolean,
        default: false,
    },
    order: {
        type: Array,
        default: () => [],
        validator: (value) => {
            for (let i of value) {
                if (!i.id || !i.key || !i.text || !i.sort) return false
            }

            return true
        },
    },
    searchText: String,
    searchFilterHandler: Function,
    showSearch: Boolean,
    showPagination: {
        type: Boolean,
        default: true,
    },
})
const emit = defineEmits(['update:items', 'update:searchText', 'search', 'page-changed'])

const loading = ref(true)

const actualItems = ref([])
const total = ref(props.items?.length || 0)

const currentPage = ref(1)
const maxPage = computed(() => {
    if (total.value <= 0) return 0

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

//-------------------------------
// Order operations
//-------------------------------
function changeOrderHandler(selected) {
    selectedOrder.value = selected
    goToPage(currentPage.value)
}

//-------------------------------

watch([currentPage, () => props.path, () => props.items], () => {
    goToPage(currentPage.value)
})

let localLoadingTimeout

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

    loading.value = true

    if (props.items?.length) {
        clearTimeout(localLoadingTimeout)

        new Promise((resolve) => {
            let rows = props.items

            if (isFunction(props.searchFilterHandler)) {
                rows = rows.filter((item) => {
                    return props.searchFilterHandler.call(null, item, params.text)
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
                actualItems.value.push(rows[index]);
            }

            localLoadingTimeout = setTimeout(() => {
                loading.value = false
                resolve()
            }, 1000)
        })
    } else if (props.path && props.path.trim() !== '') {
        useRequest(props.path, {params}, {
            success: (response) => {
                actualItems.value = response.data || []
                total.value = response.meta.total || 0

                loading.value = false
            },
        })
    } else {
        actualItems.value = []
        loading.value = false
    }
}

goToPage(currentPage.value)

function prevPage() {
    if (currentPage.value - 1 > 0)
        currentPage.value--
}

function movePage(page) {
    if (page > 0 && page <= maxPage.value)
        currentPage.value = page
}

function nextPage() {
    if (currentPage.value + 1 <= maxPage.value)
        currentPage.value++
}

onUnmounted(() => {
    clearTimeout(localLoadingTimeout)
})
</script>

<style scoped>

</style>
