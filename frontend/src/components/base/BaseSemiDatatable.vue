<template>
  <div v-if="setting.enableSearchBox">
    <base-datatable-search
        @refresh="refresh"
        @search="doSearchText"
        @clear-filter="clearSearchFilter"
    />
  </div>

  <template v-if="rows.length || isLoading">
    <div class="relative overflow-x-auto my-custom-scrollbar min-h-[38px] h-full">
      <table class="w-full">
        <thead>
        <tr class="text-white text-right text-opacity-80">
          <th v-for="(col, index) in columns" :key="index"
              :class="[
                  'py-3 px-4 text-xs bg-primary',
                  index === 0 ? 'rounded-r-xl' : '',
                  index === columns.length - 1 ? 'rounded-l-xl' : '',
                  col.sortable ? 'cursor-pointer' : '',
                  col.columnClasses,
              ]"
              :style="[col.width ? 'width: ' + col.width : 'width: auto']"
              scope="col"
          >
            <div
                :class="{
                  'rounded-md w-full transition text-white': (col.label && col.label.trim() !== '') || !!col.sortable,
                  'hover:bg-white/20 hover:bg-opacity-50': col.sortable
                }"
                class="py-2.5 px-2"
                @click.prevent="col.sortable ? doSort(col.field) : false"
            >
              <div
                  class="flex items-center justify-between gap-2">
                <span class="leading-relaxed">{{ col.label }}</span>
                <ArrowUpIcon
                    v-if="setting.order === col.field && setting.sort === 'desc'"
                    class="w-4 h-4 shrink-0"
                />
                <ArrowDownIcon
                    v-else-if="setting.order === col.field && setting.sort === 'asc'"
                    class="w-4 h-4 shrink-0"
                />
                <ArrowsUpDownIcon
                    v-else-if="col.sortable"
                    class="text-white text-opacity-40 w-4 h-4 shrink-0"
                />
              </div>
            </div>
          </th>
        </tr>
        <tr>
          <th :colspan="columns.length" class="py-1"></th>
        </tr>
        </thead>
        <tbody>
        <template
            v-for="(row, i) in rows"
            v-if="!isLoading"
            :key="i"
        >
          <tr
              :class="typeof rowClasses === 'function' ? rowClasses(row) : rowClasses"
              class="text-black"
          >
            <td
                v-for="(col, j) in columns"
                :key="j"
                :class="[
                  col.cellClasses,
                  col.columnClasses,
                  j === 0 ? 'rounded-r-xl' : '',
                  j === columns.length - 1 ? 'rounded-l-xl' : '',
              ]"
                :style="[col?.cellStyles, col?.columnStyles]"
                class="py-3 px-4 bg-white"
            >
              <div v-if="slots[col.field]">
                <slot :index="i + setting.offset" :name="col.field" :value="row"></slot>
              </div>
              <span v-else>{{ row[col.field] }}</span>
            </td>
          </tr>
          <tr v-if="i !== rows.length - 1">
            <td :colspan="columns.length" class="py-1"></td>
          </tr>
        </template>

        <tr v-else>
          <td :colspan="columns.length">
            <div
                class="absolute z-[3] top-0 left-0 w-full h-full bg-black/30 supports-[backdrop-filter]:bg-black/25 supports-[backdrop-filter]:backdrop-blur flex flex-col transition"
            >
              <slot name="loadingBlock">
                <div class="flex items-center justify-center flex-1">
                  <span style="color: white">
                      {{ loadingMessage }}
                  </span>
                  <loader-circle
                      container-bg-color=""
                      main-container-klass="h-6 w-6 relative mr-2"
                      small-circle-color="border-t-rose-700"
                  ></loader-circle>
                </div>
              </slot>
            </div>
          </td>
        </tr>
        </tbody>
      </table>
    </div>

    <div v-if="setting.maxPage > 1" class="mt-3">
      <base-pagination
          v-model:current-page="setting.page"
          v-model:max-page="setting.maxPage"
          v-model:paging="setting.paging"
          :move-page="movePage"
          :next-page="nextPage"
          :prev-page="prevPage"
          :theme="paginationTheme"
      />
    </div>
  </template>

  <template v-else-if="!isLoading">
    <template v-if="slots['emptyTableRows']">
      <div class="min-h-[38px]">
        <slot :message="emptyMessage" name="emptyTableRows"></slot>
      </div>
    </template>
    <partial-empty-rows
        v-else
        :message="emptyMessage"
        image="/images/empty-statuses/empty-data.svg"
    />
  </template>
</template>

<script setup>
import {computed, reactive, useSlots, watch} from "vue";
import LoaderCircle from "./loader/LoaderCircle.vue";
import PartialEmptyRows from "@/components/partials/PartialEmptyRows.vue";
import BasePagination from "./BasePagination.vue";
import BaseDatatableSearch from "@/components/base/datatable/BaseDatatableSearch.vue";
import {ArrowDownIcon, ArrowsUpDownIcon, ArrowUpIcon} from "@heroicons/vue/24/outline/index.js";

const props = defineProps({
  isLoading: {
    type: Boolean,
    require: true,
  },
  columns: {
    type: Array,
    default: () => {
      return [];
    },
  },
  rows: {
    type: Array,
    default: () => {
      return [];
    },
  },
  rowClasses: {
    type: [Array, Function],
    default: () => {
      return [];
    },
  },
  pageSize: {
    type: Number,
    default: 15,
  },
  total: {
    type: Number,
    default: 100,
  },
  page: {
    type: Number,
    default: 1,
  },
  sortable: {
    type: Object,
    default: () => {
      return {
        order: "id",
        sort: "asc",
      };
    },
  },
  loadingMessage: {
    type: String,
    default: 'در حال بارگذاری...',
  },
  emptyMessage: {
    type: String,
    default: 'هیچ موردی وجود ندارد',
  },
  paginationTheme: String,
  //
  enableSearchBox: {
    type: Boolean,
    default: false,
  },
})
const emit = defineEmits(['do-search'])
const slots = useSlots()

const setting = reactive({
  enableSearchBox: props.enableSearchBox,
  pageSize: props.pageSize,
  page: props.page,
  maxPage: computed(() => {
    if (props.total <= 0) {
      return 0;
    }
    let maxPage = Math.floor(props.total / setting.pageSize);
    let mod = props.total % setting.pageSize;
    if (mod > 0) {
      maxPage++;
    }
    return maxPage;
  }),
  offset: computed(() => {
    return (setting.page - 1) * setting.pageSize + 1;
  }),
  limit: computed(() => {
    let limit = setting.page * setting.pageSize;
    return props.total >= limit ? limit : props.total;
  }),
  paging: computed(() => {
    let startPage = setting.page - 2 <= 0 ? 1 : setting.page - 2;
    if (setting.maxPage - setting.page <= 2) {
      startPage = setting.maxPage - 4;
    }
    startPage = startPage <= 0 ? 1 : startPage;
    let pages = [];
    for (let i = startPage; i <= setting.maxPage; i++) {
      if (pages.length < 5) {
        pages.push(i);
      }
    }
    return pages;
  }),
  // Sortable for local
  order: props.sortable.order,
  sort: props.sortable.sort,
})

const changePage = (page, prevPage) => {
  let order = setting.order;
  let sort = setting.sort;
  let offset = (page - 1) * setting.pageSize;
  let limit = setting.pageSize;
  let text = searchText.value;
  if (page > 1 || page === prevPage) {
    // Call query will only be executed if the page number is changed without re-query
    emit("do-search", offset, limit, order, sort, text);
  }
};
// Monitor page switching
watch(() => setting.page, changePage);
// Monitor manual page switching
watch(
    () => props.page,
    (val) => {
      if (val <= 1) {
        setting.page = 1;
      } else if (val >= setting.maxPage) {
        setting.page = setting.maxPage;
      } else {
        setting.page = val;
      }
    }
);

const changePageSize = () => {
  if (setting.page === 1) {
    // changePage()
    changePage(setting.page, setting.page);
  } else {
    // changePage()
    setting.page = 1;
    setting.isCheckAll = false;
  }
};
// Monitor display number switch from component
watch(() => setting.pageSize, changePageSize);
// Monitor display number switch from prop
watch(
    () => props.pageSize,
    (newPageSize) => {
      setting.pageSize = newPageSize;
    }
);

const prevPage = () => {
  if (setting.page === 1) {
    // If it is the first page, it will not be executed
    return false;
  }
  setting.page--;
};

/**
 * Move to the specified number of pages
 */
const movePage = (page) => {
  setting.page = page;
};

const nextPage = () => {
  if (setting.page >= setting.maxPage) {
    // If it is equal to or greater than the maximum number of pages, no execution
    return false;
  }
  setting.page++;
};

/**
 * Call execution sequencing
 */
const doSort = (order) => {
  let sort = "asc";
  if (order === setting.order) {
    if (setting.sort === "asc") {
      sort = "desc";
    }
  }
  let offset = (setting.page - 1) * setting.pageSize;
  let limit = setting.pageSize;
  setting.order = order;
  setting.sort = sort;
  let text = searchText.value;
  emit("do-search", offset, limit, order, sort, text);
};

const doSearchText = (text) => {
  searchText.value = text.trim();
  let offset = 0;
  let limit = setting.pageSize;
  let order = setting.order;
  let sort = setting.sort;

  if (searchText.value.length)
    emit("do-search", offset, limit, order, sort, searchText.value);
}

const clearSearchFilter = (payload) => {
  if (payload.value?.length) {
    searchText.value = '';
    payload.resetField(payload.name)

    let offset = 0;
    let limit = setting.pageSize;
    let order = setting.order;
    let sort = setting.sort;

    emit("do-search", offset, limit, order, sort, searchText.value);
  }
}

const refresh = () => {
  let order = setting.order;
  let sort = setting.sort;
  let offset = (setting.page - 1) * setting.pageSize;
  let limit = setting.pageSize;
  let text = searchText.value;
  emit("do-search", offset, limit, order, sort, text);
}

defineExpose({
  refresh,
})
</script>
