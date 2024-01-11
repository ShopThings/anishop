<template>
  <template v-if="rows.length || isLoading">
    <div class="relative overflow-x-auto my-custom-scrollbar min-h-[38px]">
      <table class="w-full">
        <thead>
        <tr class="text-white text-right text-opacity-80">
          <th v-for="(col, index) in columns" :key="index"
              scope="col"
              :class="[
                  'py-3 px-4 text-xs bg-primary',
                  index === 0 ? 'rounded-r-xl' : '',
                  index === columns.length - 1 ? 'rounded-l-xl' : '',
                  col.columnClasses,
              ]"
              :style="[col.width ? 'width: ' + col.width : 'width: auto']"
          >
            <span class="leading-relaxed">{{ col.label }}</span>
          </th>
        </tr>
        <tr>
          <th :colspan="columns.length" class="py-1"></th>
        </tr>
        </thead>
        <tbody>
        <template
          v-if="!isLoading"
          v-for="(row, i) in rows"
          :key="i"
        >
          <tr
            class="text-black"
            :class="typeof rowClasses === 'function' ? rowClasses(row) : rowClasses"
          >
            <td
              v-for="(col, j) in columns"
              :key="j"
              class="py-3 px-4 bg-white"
              :class="[
                        col.cellClasses,
                        col.columnClasses,
                        j === 0 ? 'rounded-r-xl' : '',
                        j === columns.length - 1 ? 'rounded-l-xl' : '',
                    ]"
              :style="[col?.cellStyles, col?.columnStyles]"
            >
              <div v-if="slots[col.field]">
                <slot :name="col.field" :value="row" :index="i + setting.offset"></slot>
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

    <div class="mt-3" v-if="setting.maxPage > 1">
      <base-pagination
        :theme="paginationTheme"
        :next-page="nextPage"
        :move-page="movePage"
        :prev-page="prevPage"
        v-model:max-page="setting.maxPage"
        v-model:paging="setting.paging"
        v-model:current-page="setting.page"
      />
    </div>
  </template>

  <template v-else-if="!isLoading">
    <template v-if="slots['emptyTableRows']">
      <div class="min-h-[38px]">
        <slot name="emptyTableRows" :message="emptyMessage"></slot>
      </div>
    </template>
    <partial-empty-rows
      v-else
      image="/empty-statuses/empty-data.svg"
      :message="emptyMessage"
    />
  </template>
</template>

<script setup>
import {computed, reactive, useSlots, watch} from "vue";
import LoaderCircle from "./loader/LoaderCircle.vue";
import PartialEmptyRows from "../partials/PartialEmptyRows.vue";
import BasePagination from "./BasePagination.vue";

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
  loadingMessage: {
    type: String,
    default: 'در حال بارگذاری...',
  },
  emptyMessage: {
    type: String,
    default: 'هیچ موردی وجود ندارد',
  },
  paginationTheme: String,
})
const emit = defineEmits(['do-search'])
const slots = useSlots()

const setting = reactive({
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
})

const changePage = (page, prevPage) => {
  let offset = (page - 1) * setting.pageSize;
  let limit = setting.pageSize;
  if (page > 1 || page === prevPage) {
    // Call query will only be executed if the page number is changed without re-query
    emit("do-search", offset, limit);
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
</script>
