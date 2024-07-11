<template>
  <div class="vtl relative min-w-0">
    <slot v-if="title" name="title">
      <div class="mb-2">
        {{ title }}
      </div>
    </slot>

    <div v-if="setting.enableMultiOperation && hasCheckbox">
      <base-datatable-multi-operation
        :items="selectedItems"
        :operations="setting.selectionOperations"
        @clear-selected-items="clearSelectedItems"
      >
        <template #selectedItems="{items, close}">
          <slot :close="close" name="beforeSelectedItemsTable"></slot>

          <base-datatable
            :columns="setting.selectionColumns"
            :is-slot-mode="setting.isSlotMode"
            :is-static-mode="true"
            :rows="items"
            :total="items.length"
          >
            <template v-for="(slot, index) of Object.keys(slots)" :key="index" v-slot:[slot]="data">
              <slot :index="index + setting.offset" :name="slot" :value="data.value"></slot>
            </template>
            <template v-slot:selection_remover="data">
              <BackspaceIcon
                v-tooltip.left="'حذف از انتخاب‌ها'"
                class="w-6 h-6 text-rose-500 cursor-pointer hover:scale-125 transition"
                @click="removeFromSelectedItem(data.value)"
              />
            </template>
          </base-datatable>

          <div class="mt-3">
            <slot :close="close" name="afterSelectedItemsTable">
              <base-animated-button class="bg-slate-600 !py-1 px-5 mr-auto" @click="close">
                <template #icon="{klass}">
                  <XCircleIcon :class="klass" class="w-6 h-6 ml-2"/>
                </template>
                بستن
              </base-animated-button>
            </slot>
          </div>
        </template>
      </base-datatable-multi-operation>
    </div>

    <div v-if="setting.enableSearchBox">
      <base-datatable-search
        @refresh="refresh"
        @search="doSearchText"
        @clear-filter="clearSearchFilter"
      />
    </div>

    <slot name="beforeItemsTable"></slot>

    <div
      :class="{
        '__fixed-first-column': isFixedFirstColumn,
        '__fixed-first-second-column': isFixedFirstColumn && hasCheckbox,
      }"
      class="flex flex-col relative"
    >
      <div v-show="isLoading"
           class="absolute z-[4] top-0 left-0 w-full h-full bg-black/30 supports-[backdrop-filter]:bg-black/20 supports-[backdrop-filter]:backdrop-blur-sm flex flex-col transition">
        <slot name="loadingBlock">
          <div class="flex items-center justify-center flex-1">
            <span class="text-white text-shadow">{{ messages.loading }}</span>
            <loader-circle
              container-bg-color=""
              main-container-klass="h-6 w-6 relative mr-2.5"
            ></loader-circle>
          </div>
        </slot>
      </div>

      <div class="my-custom-scrollbar overflow-x-auto">
        <div class="inline-block min-w-full">
          <div ref="tableContainer" class="overflow-hidden">
            <table
              ref="localTable"
              :style="[maxHeight !== 'auto' ? 'max-height: ' + maxHeight + 'px;' : '']"
              class="text-sm text-left text-gray-500 rtl:text-right w-full"
            >
              <thead class="text-xs text-gray-800 uppercase border-b-2 bg-cyan-400">
              <tr>
                <th
                  v-if="hasCheckbox"
                  class="w-[1%] min-w-[38px] px-5 py-2.5"
                  scope="col"
                >
                  <div class="flex items-center">
                    <input
                      v-model="setting.isCheckAll"
                      class="w-4 h-4 text-blue-600 bg-white bg-opacity-40 border-white rounded focus:ring-blue-600 focus:ring-2"
                      type="checkbox"
                      @change="changeCheckAll"
                    >
                  </div>
                </th>
                <template
                  v-for="(col, index) in columns"
                  :key="index"
                >
                  <th
                    v-if="col?.show !== false"
                    :class="[col.sortable ? 'cursor-pointer' : '', col.columnClasses]"
                    :style="[col.width ? 'width: ' + col.width : 'width: auto', col.columnStyles]"
                    class="px-1"
                    scope="col"
                  >
                    <div
                      :class="{
                        'rounded-md bg-cyan-400 w-full hover:bg-cyan-300 hover:bg-opacity-50 transition text-white': (col.label && col.label.trim() !== '') || !!col.sortable
                      }"
                      class="py-2.5 px-2"
                      @click.prevent="col.sortable ? doSort(col.field) : false"
                    >
                      <div class="flex rtl:flex-row-reverse items-center justify-start rtl:justify-end">
                        <ArrowUpIcon
                          v-if="setting.order === col.field && setting.sort === 'desc'"
                          class="ml-auto rtl:mr-auto rtl:ml-[unset] w-4 h-4 shrink-0"
                        />
                        <ArrowDownIcon
                          v-else-if="setting.order === col.field && setting.sort === 'asc'"
                          class="ml-auto rtl:mr-auto rtl:ml-[unset] w-4 h-4 shrink-0"
                        />
                        <ArrowsUpDownIcon
                          v-else-if="col.sortable"
                          class="ml-auto rtl:mr-auto rtl:ml-[unset] text-white text-opacity-40 w-4 h-4 shrink-0"
                        />
                        <span class="ml-2 leading-relaxed">{{ col.label }}</span>
                      </div>
                    </div>
                  </th>
                </template>
              </tr>
              </thead>

              <template v-if="rows.length > 0">
                <tbody
                  v-if="isStaticMode"
                  :set="(templateRows = groupingKey === '' ? [localRows] : localRows)"
                >
                <template
                  v-for="(rows, groupingIndex) in templateRows"
                  :key="groupingIndex"
                >
                  <tr
                    v-if="groupingKey !== ''"
                    class="border-b transition"
                  >
                    <td
                      :colspan="setting.columnsLength"
                      class="px-6 py-4"
                    >
                      <div class="flex gap-2.5 items-center">
                        <a
                          v-if="hasGroupToggle"
                          :ref="(el) => (toggleButtonRefs[groupingIndex] = el)"
                          class="flex items-center justify-center cursor-pointer rounded-md size-8 border border-blue-200 hover:bg-blue-100 transition"
                          @click.prevent="toggleGroup(groupingIndex)"
                        >
                          <ChevronDownIcon class="w-4 h-4 transition"/>
                        </a>
                        <div
                          v-html="
                              groupingDisplay
                                ? groupingDisplay(groupingIndex)
                                : groupingIndex + '<span class=\'mr-1.5 rounded-full py-0.5 px-0.5 min-w-6 h-6 text-center bg-green-100 border border-green-300 inline-block\'>' + (rows?.length || 0) + '</span>'
                            "
                        ></div>
                      </div>
                    </td>
                  </tr>

                  <tr
                    v-for="(row, i) in rows"
                    :key="row[setting.keyColumn] ? row[setting.keyColumn] : i"
                    :ref="
                        (el) => {
                          if (!groupingRowsRefs[groupingIndex]) {
                            groupingRowsRefs[groupingIndex] = [];
                          }
                          groupingRowsRefs[groupingIndex][i] = el;
                        }
                      "
                    :class="typeof rowClasses === 'function' ? rowClasses(row) : rowClasses"
                    :name="'vtl-group-' + groupingIndex"
                    class="border-b transition"
                    @click="(e) => {emit('row-clicked', row, e.target.closest('tr'))}"
                    @contextmenu="emit('row-context-menu', $event, row)"
                  >
                    <td v-if="hasCheckbox" class="w-[1%] min-w-[38px] px-5 py-2.5">
                      <div class="flex items-center">
                        <input
                          :ref="
                            (el) => {
                              if(el && hasSelectedItemWithProperty(row)) {
                                el.checked = true;
                              }
                              rowCheckbox.push(el);
                            }
                            "
                          :value="row[setting.keyColumn]"
                          class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-600 focus:ring-2"
                          type="checkbox"
                          @click="checked(row, $event)"
                        >
                      </div>
                    </td>

                    <template
                      v-for="(col, j) in columns"
                      :key="j"
                    >
                      <td
                        v-if="col?.show !== false"
                        :class="[
                          typeof col.cellClasses === 'function' ? col.cellClasses(row) : col.cellClasses,
                           col.columnClasses
                        ]"
                        :dir="col.columnDir ?? 'rtl'"
                        :style="[col.cellStyles, col.columnStyles]"
                        class="px-5 py-2.5"
                        @mouseenter="addHoverClassToTd"
                        @mouseleave="removeHoverClassFromTd"
                      >
                        <div v-if="col.display" v-html="col.display(row)"></div>
                        <div v-else>
                          <div v-if="setting.isSlotMode && slots[col.field]">
                            <slot
                              :index="i + setting.offset"
                              :name="col.field"
                              :value="row"
                            ></slot>
                          </div>
                          <span v-else>{{ row[col.field] }}</span>
                        </div>
                      </td>
                    </template>
                  </tr>
                </template>
                </tbody>
                <tbody
                  v-else
                  :set="(templateRows = groupingKey === '' ? [rows] : groupingRows)"
                >
                <template
                  v-for="(rows, groupingIndex) in templateRows"
                  :key="groupingIndex"
                >
                  <tr v-if="groupingKey !== ''"
                      class="border-b transition">
                    <td
                      :colspan="setting.columnsLength"
                      class="px-6 py-4"
                    >
                      <div class="flex gap-2.5 items-center">
                        <a
                          v-if="hasGroupToggle"
                          :ref="(el) => (toggleButtonRefs[groupingIndex] = el)"
                          class="flex items-center justify-center cursor-pointer rounded-md size-8 border border-blue-200 hover:bg-blue-100 transition"
                          @click.prevent="toggleGroup(groupingIndex)"
                        >
                          <ChevronDownIcon class="w-4 h-4 transition"/>
                        </a>
                        <div
                          v-html="
                              groupingDisplay
                                ? groupingDisplay(groupingIndex)
                                : groupingIndex + '<span class=\'mr-1.5 rounded-full py-0.5 px-0.5 min-w-6 h-6 text-center bg-green-100 border border-green-300 inline-block\'>' + (rows?.length || 0) + '</span>'
                            "
                        ></div>
                      </div>
                    </td>
                  </tr>

                  <tr
                    v-for="(row, i) in rows"
                    :key="row[setting.keyColumn] ? row[setting.keyColumn] : i"
                    :ref="
                        (el) => {
                          if (!groupingRowsRefs[groupingIndex]) {
                            groupingRowsRefs[groupingIndex] = [];
                          }
                          groupingRowsRefs[groupingIndex][i] = el;
                        }
                      "
                    :class="typeof rowClasses === 'function' ? rowClasses(row) : rowClasses"
                    :name="'vtl-group-' + groupingIndex"
                    class="border-b transition"
                    @click="(e) => {emit('row-clicked', row, e.target.closest('tr'))}"
                    @contextmenu="emit('row-context-menu', $event, row)"
                  >
                    <td v-if="hasCheckbox" class="px-6 py-4">
                      <div class="flex items-center">
                        <input
                          :ref="
                              (el) => {
                                if(el && hasSelectedItemWithProperty(row)) {
                                  el.checked = true;
                                  const tr = getParent(el, 'tr');
                                  if(tr) {
                                      tr.classList.add('row-is-checked');
                                  }
                                }
                                rowCheckbox.push(el);
                              }
                            "
                          :value="row[setting.keyColumn]"
                          class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-600 focus:ring-2"
                          type="checkbox"
                          @click="checked(row, $event)"
                        >
                      </div>
                    </td>

                    <template
                      v-for="(col, j) in columns"
                      :key="j"
                    >
                      <td
                        v-if="col?.show !== false"
                        :class="[
                          typeof col.cellClasses === 'function' ? col.cellClasses(row) : col.cellClasses,
                           col.columnClasses
                        ]"
                        :dir="col.columnDir ?? 'rtl'"
                        :style="[col.cellStyles, col.columnStyles]"
                        class="px-6 py-4"
                        @mouseenter="addHoverClassToTd"
                        @mouseleave="removeHoverClassFromTd"
                      >
                        <div v-if="col.display" v-html="col.display(row)"></div>
                        <div v-else>
                          <div v-if="setting.isSlotMode && slots[col.field]">
                            <slot
                              :index="i + setting.offset"
                              :name="col.field"
                              :value="row"
                            ></slot>
                          </div>
                          <span v-else>{{ row[col.field] }}</span>
                        </div>
                      </td>
                    </template>
                  </tr>
                </template>
                </tbody>
              </template>

              <tbody v-else-if="!isLoading">
              <tr>
                <td :colspan="setting.columnsLength">
                  <slot name="emptyTable">
                    <div class="p-3">
                      <div class="p-3 text-center text-rose-600 border rounded-md bg-gray-50 text-sm">
                        {{ messages.noDataAvailable }}
                      </div>
                    </div>
                  </slot>
                </td>
              </tr>
              </tbody>

              <tfoot class="text-xs text-gray-800 uppercase border-b-2 bg-cyan-400">
              <tr>
                <th
                  v-if="hasCheckbox"
                  class="w-[1%] min-w-[38px] px-5 py-2.5"
                  scope="col"
                >
                  <div class="flex items-center">
                    <input
                      v-model="setting.isCheckAll"
                      class="w-4 h-4 text-blue-600 bg-white bg-opacity-40 border-white rounded focus:ring-blue-600 focus:ring-2"
                      type="checkbox"
                      @change="changeCheckAll"
                    >
                  </div>
                </th>
                <template
                  v-for="(col, index) in columns"
                  :key="index"
                >
                  <th
                    v-if="col?.show !== false"
                    :class="[col.sortable ? 'cursor-pointer' : '', col.columnClasses]"
                    :style="[col.width ? 'width: ' + col.width : 'width: auto', col.columnStyles]"
                    class="px-1"
                    scope="col"
                  >
                    <div
                      :class="{
                        'rounded-md bg-cyan-400 w-full hover:bg-cyan-300 hover:bg-opacity-50 transition text-white': (col.label && col.label.trim() !== '') || !!col.sortable
                    }"
                      class="py-2.5 px-2"
                      @click.prevent="col.sortable ? doSort(col.field) : false"
                    >
                      <div class="flex rtl:flex-row-reverse items-center justify-start rtl:justify-end">
                        <ArrowUpIcon
                          v-if="setting.order === col.field && setting.sort === 'desc'"
                          class="ml-auto rtl:mr-auto rtl:ml-[unset] w-4 h-4 shrink-0"
                        />
                        <ArrowDownIcon
                          v-else-if="setting.order === col.field && setting.sort === 'asc'"
                          class="ml-auto rtl:mr-auto rtl:ml-[unset] w-4 h-4 shrink-0"
                        />
                        <ArrowsUpDownIcon
                          v-else-if="col.sortable"
                          class="ml-auto rtl:mr-auto rtl:ml-[unset] text-white text-opacity-40 w-4 h-4 shrink-0"
                        />
                        <span class="leading-relaxed">{{ col.label }}</span>
                      </div>
                    </div>
                  </th>
                </template>
              </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div v-if="rows.length > 0" :class="{'px-3 py-4': !setting.isHidePaging}">
      <template v-if="!setting.isHidePaging">
        <div class="sm:flex sm:justify-between sm:items-start sm:rtl:flex-row-reverse">
          <div aria-live="polite"
               class="text-sm text-gray-500 mb-3 sm:mb-0"
               role="status"
               v-html="stringFormat(messages.pagingInfo, setting.offset, setting.limit, total)">
          </div>

          <div v-if="setting.maxPage > 1" class="flex rtl:flex-row-reverse">
            <div class="mx-2">
              <span class="text-sm text-gray-500">{{ messages.pageSizeChangeLabel }}</span>
              <base-select
                :options="pageOptions"
                :selected="{value: setting.pageSize, text: setting.pageSize}"
                options-class="bottom-full mb-1"
                options-key="value"
                options-text="text"
                @change="(item) => {setting.pageSize = item.value}"
              >
                <template #item="{item}">
                  {{ item.text }}
                </template>
              </base-select>
            </div>

            <div v-if="!setting.isHideSelectPaging" class="mx-2">
              <span class="text-sm text-gray-500">{{ messages.gotoPageLabel }}</span>
              <base-select
                :options="pageNumberOptions"
                :selected="{value: setting.page, text: setting.page}"
                options-class="bottom-full mb-1"
                options-key="value"
                options-text="text"
                @change="(item) => {setting.page = item.value}"
              >
              </base-select>
            </div>
          </div>
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
    </div>
  </div>
</template>

<script setup>
import {computed, nextTick, onBeforeUpdate, onMounted, reactive, ref, useSlots, watch} from "vue"
import {
  ArrowDownIcon,
  ArrowsUpDownIcon,
  ArrowUpIcon,
  BackspaceIcon,
  ChevronDownIcon,
  XCircleIcon,
} from '@heroicons/vue/24/outline'
import BaseSelect from "./BaseSelect.vue";
import LoaderCircle from "./loader/LoaderCircle.vue";
import BaseDatatableSearch from "./datatable/BaseDatatableSearch.vue";
import BaseDatatableMultiOperation from "./datatable/BaseDatatableMultiOperation.vue";
import BaseAnimatedButton from "./BaseAnimatedButton.vue";
import BasePagination from "./BasePagination.vue";
import {nestedArray} from "@/composables/helper.js";

const props = defineProps({
  isLoading: {
    type: Boolean,
    require: true,
  },
  isReSearch: {
    type: Boolean,
    require: true,
  },
  hasCheckbox: {
    type: Boolean,
    default: false,
  },
  checkedReturnType: {
    type: String,
    default: "key",
  },
  title: {
    type: String,
    default: "",
  },
  isFixedFirstColumn: {
    type: Boolean,
    default: false,
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
    default: 10,
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
  messages: {
    type: Object,
    default: () => {
      return {
        pagingInfo: 'نمایش' + " <span class=\"text-blue-500\">" + "{0}" + "</span>"
          + "-<span class=\"text-blue-500\">" + "{1}" + "</span> "
          + 'از مجموع' + " <span class=\"text-blue-500\">" + "{2}" + "</span> " + 'رکورد',
        pageSizeChangeLabel: "تعداد نمایش در هر صفحه:",
        gotoPageLabel: "رفتن به صفحه:",
        noDataAvailable: "هیچ داده‌ای وجود ندارد.",
        loading: "در حال بارگذاری",
      };
    },
  },
  isStaticMode: {
    type: Boolean,
    default: false,
  },
  isSlotMode: {
    type: Boolean,
    default: false,
  },
  isHidePaging: {
    type: Boolean,
    default: false,
  },
  isHideSelectPaging: {
    type: Boolean,
    default: true,
  },
  pageOptions: {
    type: Array,
    default: () => [
      {
        value: 15,
        text: 15,
      },
      {
        value: 25,
        text: 25,
      },
      {
        value: 50,
        text: 50,
      },
    ],
  },
  groupingKey: {
    type: String,
    default: "",
  },
  hasGroupToggle: {
    type: Boolean,
    default: false,
  },
  groupingDisplay: {
    type: Function,
    default: null,
  },
  maxHeight: {
    default: "auto",
  },
  startCollapsed: {
    type: Boolean,
    default: false,
  },
  isKeepCollapsed: {
    type: Boolean,
    default: false,
  },
  enableSearchBox: {
    type: Boolean,
    default: false,
  },
  enableMultiOperation: {
    type: Boolean,
    default: false,
  },
  selectionOperations: {
    type: Object,
    default: () => {
      return {};
    },
  },
  selectionColumns: {
    type: Array,
    default: () => {
      return [];
    },
  },
  paginationTheme: String,
})
const emit = defineEmits([
  "return-checked-rows",
  'search-text',
  'clear-search-filter',
  "do-search",
  "is-finished",
  "get-now-page",
  "row-clicked",
  "row-context-menu",
  "row-toggled",
])
const slots = useSlots()

const tableContainer = ref(null);
let localTable = ref(null);

let defaultPageSize =
  props.pageOptions.length > 0
    ? ref(props.pageOptions[0].value)
    : ref(props.pageSize);
if (props.pageOptions.length > 0) {
  props.pageOptions.forEach((v) => {
    if (
      Object.prototype.hasOwnProperty.call(v, "value") &&
      Object.prototype.hasOwnProperty.call(v, "text") &&
      props.pageSize === v.value
    ) {
      defaultPageSize.value = v.value;
    }
  });
}

const searchText = ref('');
const selectedItems = ref([]);

const setting = reactive({
  enableSearchBox: props.enableSearchBox,
  enableMultiOperation: props.enableMultiOperation,
  selectionOperations: props.selectionOperations,
  isSlotMode: props.isSlotMode,
  isCheckAll: false,
  isHidePaging: props.isHidePaging,
  isHideSelectPaging: props.isHideSelectPaging,
  selectionColumns: computed(() => {
    const cols = props.selectionColumns;
    cols.unshift({
      label: "",
      field: "selection_remover",
      columnStyles: "width: 3%;",
      cellClasses: '!bg-rose-50',
    });
    return cols;
  }),
  columnsLength: computed(() => {
    return props.columns.length + (props.hasCheckbox ? 1 : 0)
  }),
  keyColumn: computed(() => {
    let key = "";
    Object.assign(props.columns).forEach((col) => {
      if (col.isKey) {
        key = col.field;
      }
    });
    return key;
  }),
  page: props.page,
  pageSize: defaultPageSize.value,
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
  pageNumberOptions: computed(() => {
    const mp = setting.maxPage
    const pnObj = []
    for (let i = 0; i < mp; i++) {
      pnObj.push({
        value: i,
        text: i,
      })
    }
    return pnObj
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
  pageOptions: props.pageOptions,
});

const isChecked = ref([]);

const localRows = computed(() => {
  let rows = props.rows;
  var collator = new Intl.Collator(undefined, {
    numeric: true,
    sensitivity: "base",
  });
  let sortOrder = setting.sort === "desc" ? -1 : 1;
  rows.sort(function (a, b) {
    return collator.compare(a[setting.order], b[setting.order]) * sortOrder;
  });

  let result;
  if (props.groupingKey) {
    let tmp = {};
    rows.forEach((v) => {
      if (!tmp[v[props.groupingKey]]) {
        tmp[v[props.groupingKey]] = [];
      }
      tmp[v[props.groupingKey]].push(v);
    });

    result = {};
    for (let index = setting.offset - 1; index < setting.limit; index++) {
      result[rows[index][props.groupingKey]] = tmp[rows[index][props.groupingKey]];
    }
  } else {
    result = [];
    for (let index = setting.offset - 1; index < setting.limit; index++) {
      result.push(rows[index]);
    }
  }

  nextTick(function () {
    callIsFinished();
  });

  return result;
});

////////////////////////////
//
// Checkbox
// (Checkbox related operations)
//

const rowCheckbox = ref([]);

onBeforeUpdate(() => {
  rowCheckbox.value = [];
});

/**
 * Check all checkboxes for monitoring
 */
watch(
  () => setting.isCheckAll,
  (state) => {
    if (props.hasCheckbox) {
      isChecked.value = [];
      let tmpRows = (props.isStaticMode) ? props.rows.slice((setting.offset - 1), setting.limit) : props.rows;
      if (state) {
        if (props.checkedReturnType === "row") {
          isChecked.value = tmpRows;
        } else {
          tmpRows.forEach((val) => {
            isChecked.value.push(val[setting.keyColumn]);
          });
        }
      }

      // Return the selected data on the screen
      emit("return-checked-rows", isChecked.value);
    }
  }
);

/**
 * hasCheckbox props for monitoring
 */
watch(
  () => props.hasCheckbox,
  (v) => {
    if (!v) {
      setting.isCheckAll = false;
    }
  }
);

function changeCheckAll(e) {
  if (props.hasCheckbox) {
    const state = e.target.checked

    let tmpRows = (props.isStaticMode) ? props.rows.slice((setting.offset - 1), setting.limit) : props.rows;
    tmpRows.forEach((val) => {
      if (state) {
        if (!hasSelectedItemWithProperty(val))
          selectedItems.value.push(val)
      } else {
        removeFromSelectedItem(val)
      }
    });

    rowCheckbox.value.forEach((val) => {
      if (val) {
        val.checked = state;

        const tr = getParent(val, 'tr');
        if (state) {
          tr.classList.add('row-is-checked');
        } else {
          tr.classList.remove('row-is-checked');
        }
      }
    });
  }
}

/**
 * Checkbox click event
 */
const checked = (row, event) => {
  event.stopPropagation();
  let checkboxValue = row[setting.keyColumn];
  if (props.checkedReturnType === "row") {
    checkboxValue = row;
  }

  const tr = getParent(event.target, 'tr');
  if (event.target.checked) {
    isChecked.value.push(checkboxValue);
    //
    if (!hasSelectedItemWithProperty(row))
      selectedItems.value.push(row);

    tr.classList.add('row-is-checked');
  } else {
    const index = isChecked.value.indexOf(checkboxValue);
    if (index >= 0) {
      isChecked.value.splice(index, 1);
    }
    if (hasSelectedItemWithProperty(row))
      removeFromSelectedItem(row)

    tr.classList.remove('row-is-checked');
  }
  if (isChecked.value.length === props.rows.length) {
    setting.isCheckAll = true;
  } else {
    // Return the selected data on the screen
    emit("return-checked-rows", isChecked.value);
  }
};

/**
 * Clear all selected data on the screen
 */
const clearChecked = () => {
  isChecked.value = [];
  rowCheckbox.value.forEach((val) => {
    if (val && val.checked) {
      val.checked = false;
      const tr = getParent(val, 'tr');
      tr.classList.remove('row-is-checked');
    }
  });
  // Return the selected data on the screen
  emit("return-checked-rows", isChecked.value);
};

////////////////////////////
//
// Sorting, page change, etc. related operations
//

const clearSelectedItems = () => {
  selectedItems.value = [];
  clearChecked();
}

const doSearchText = (text) => {
  searchText.value = text.trim();
  let offset = 0;
  let limit = setting.pageSize;
  let order = setting.order;
  let sort = setting.sort;

  if (searchText.value.length) {
    emit("do-search", offset, limit, order, sort, searchText.value);
  }

  if (setting.isCheckAll) {
    setting.isCheckAll = false;
  } else {
    if (props.hasCheckbox) {
      clearChecked();
    }
  }
}

const clearSearchFilter = (payload) => {
  searchText.value = '';

  if (payload?.name) {
    payload.resetField(payload.name)
  }

  let offset = 0;
  let limit = setting.pageSize;
  let order = setting.order;
  let sort = setting.sort;

  emit('clear-search-filter')
  emit("do-search", offset, limit, order, sort, searchText.value);

  if (setting.isCheckAll) {
    setting.isCheckAll = false;
  } else {
    if (props.hasCheckbox) {
      clearChecked();
    }
  }
}

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

  if (setting.isCheckAll) {
    setting.isCheckAll = false;
  } else {
    if (props.hasCheckbox) {
      clearChecked();
    }
  }
};

const changePage = (page, prevPage) => {
  setting.isCheckAll = false;
  if (props.hasCheckbox) {
    isChecked.value = [];
  }
  let order = setting.order;
  let sort = setting.sort;
  let offset = (page - 1) * setting.pageSize;
  let limit = setting.pageSize;
  let text = searchText.value;
  if (!props.isReSearch || page > 1 || page === prevPage) {
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
      emit("get-now-page", setting.page);
    } else if (val >= setting.maxPage) {
      setting.page = setting.maxPage;
      emit("get-now-page", setting.page);
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

// Monitoring data changes
watch(
  () => props.rows,
  () => {
    if (props.isReSearch || props.isStaticMode) {
      setting.page = 1;
    }
    nextTick(function () {
      // 資料完成渲染後回傳私有元件 (Return the private components after the data is rendered)
      if (!props.isStaticMode) {
        callIsFinished();
      }
    });
  }
);

const stringFormat = (template, ...args) => {
  return template.replace(/{(\d+)}/g, function (match, number) {
    return typeof args[number] != "undefined" ? args[number] : match;
  });
};

// Call 「is-finished」 Method
const callIsFinished = () => {
  if (localTable.value) {
    let localElement = localTable.value.getElementsByClassName("is-rows-el");
    emit("is-finished", localElement);
  }
  emit("get-now-page", setting.page);
};

// Toggle button elements
const toggleButtonRefs = ref({});
// Grouping rows
const groupingRowsRefs = ref({});
// Saved toggle status
const groupingToggleStatus = ref({});

// Data rows for grouping (Default-mode only)
const groupingRows = computed(() => {
  let result = {};
  props.rows.forEach((v) => {
    if (!result[nestedArray.get(v, props.groupingKey)]) {
      result[nestedArray.get(v, props.groupingKey)] = [];
    }
    result[nestedArray.get(v, props.groupingKey)].push(v);
  });

  nextTick(function () {
    if (props.startCollapsed || props.isKeepCollapsed) {
      for (const [groupIndex, el] of Object.entries(toggleButtonRefs.value)) {
        if (el) {
          let isOpen = !props.startCollapsed;
          if (
            props.isKeepCollapsed &&
            groupingToggleStatus.value[groupIndex] !== undefined
          ) {
            isOpen = !groupingToggleStatus.value[groupIndex];
          }
          if (
            (isOpen && el.classList.contains("__group-open")) ||
            (!isOpen && !el.classList.contains("__group-open"))
          ) {
            el.classList.toggle("__group-open");
            el.children[0].classList.toggle("rotate-90");
          }
          if (!isOpen) {
            groupingRowsRefs.value[groupIndex].forEach((element) => {
              if (element) {
                element.classList.add("hidden");
              }
            });
          }
        }
      }
    }
    callIsFinished();
  });

  return result;
});

const toggleGroup = (groupIndex) => {
  let targetEl = toggleButtonRefs.value[groupIndex];
  if (targetEl) {
    targetEl.classList.toggle("__group-open");
    targetEl.children[0].classList.toggle("rotate-90");
    const isClose = targetEl.classList.contains("__group-open");
    groupingRowsRefs.value[groupIndex].forEach((element) => {
      if (element) {
        if (isClose) {
          element.classList.add("hidden");
        } else {
          element.classList.remove("hidden");
        }
      }
    });
    groupingToggleStatus.value[groupIndex] = isClose;
    emit("row-toggled", groupingRows.value[groupIndex], isClose);
  }
};

function hasSelectedItemWithProperty(row) {
  if (!setting.keyColumn.length) return false;

  const array = selectedItems.value;
  const property = setting.keyColumn
  for (let i = 0; i < array.length; i++) {
    if (array[i][property] && array[i][property] === row[property]) {
      return true;
    }
  }
  return false;
}

function removeFromSelectedItem(row) {
  if (!setting.keyColumn.length) return;
  const property = setting.keyColumn;

  for (let i = 0; i < selectedItems.value.length; i++) {
    if (selectedItems.value[i][property] && selectedItems.value[i][property] === row[property]) {
      selectedItems.value.splice(i, 1);
      i = Math.max(i - 2, 0);
    }
  }
}

function getParent(el, tagName) {
  return el.closest(tagName);
}

function getColumn(tab, col) {
  if (!tab || col < 0) return [];

  return Array.from(tab.rows)
    .filter(row => row.cells.length > col)
    .map(row => row.cells[col]);
}

function getChild(element, tagName) {
  tagName = tagName.toLowerCase();
  return Array.from(element.children)
    .find(child => child.tagName.toLowerCase() === tagName) || null;
}

/**
 * Add hover class to td
 *
 * @param {MouseEvent} mouseEvent
 */
const addHoverClassToTd = (mouseEvent) => {
  const tr = mouseEvent.target.parentNode;
  const table = getParent(mouseEvent.target, 'table');
  const cols = getColumn(getChild(table, 'tbody'), mouseEvent.target.cellIndex);

  tr.classList.add('hover');
  for (const col of cols) {
    col.classList.add('hover');
  }
};

/**
 * Remove hover class from td
 *
 * @param {MouseEvent} mouseEvent
 */
const removeHoverClassFromTd = (mouseEvent) => {
  const tr = mouseEvent.target.parentNode;
  const table = getParent(mouseEvent.target, 'table');
  const cols = getColumn(getChild(table, 'tbody'), mouseEvent.target.cellIndex);

  tr.classList.remove('hover');
  for (const col of cols) {
    col.classList.remove('hover');
  }
};

const refresh = () => {
  let order = setting.order;
  let sort = setting.sort;
  let offset = (setting.page - 1) * setting.pageSize;
  let limit = setting.pageSize;
  let text = searchText.value;
  emit("do-search", offset, limit, order, sort, text);
}

/**
 * Different with "refresh" is reset offset and text of contents
 */
const reload = () => {
  clearSearchFilter()
}

defineExpose({
  table: localTable,
  tableContainer,
  refresh,
  reload,
  resetSelection: () => {
    clearSelectedItems()
  },
  resetSelectionItem: removeFromSelectedItem,
})

onMounted(() => {
  nextTick(() => {
    if (props.rows.length > 0) {
      callIsFinished();
    }
  });
});
</script>

<style scoped>
.vtl {
  word-wrap: break-word;
}

.vtl .hover {
  background-color: rgba(0, 0, 0, .02);
}

.vtl .row-is-checked {
  background-color: rgba(160, 42, 229, 0.07);
}

.vtl .__fixed-first-column {
  overflow-x: auto;
}

.vtl .__fixed-first-column tr th:first-child {
  position: sticky;
  left: 0;
  z-index: 2;
}

.vtl .__fixed-first-column tr td:first-child {
  position: sticky;
  left: 0;
}

.vtl .__fixed-first-column tr th:first-child::before,
.vtl .__fixed-first-second-column tr th:nth-child(2)::before {
  content: "";
  position: absolute;
  border-right: 1px solid #454d55;
  left: 0;
  top: 0;
  width: 102%;
  height: 102%;
}

.vtl .__fixed-first-column tr td:first-child::before,
.vtl .__fixed-first-column tr td:nth-child(2)::before {
  content: "";
  position: absolute;
  border-right: 1px solid #dee2e6;
  left: 0;
  top: 0;
  width: 102%;
  height: 102%;
}

.vtl .__fixed-first-column tr td:first-child,
.vtl .__fixed-first-second-column tr td:nth-child(2) {
  background-color: white;
}

.vtl .__fixed-first-column tr.hover td:first-child,
.vtl .__fixed-first-second-column tr.hover td:nth-child(2) {
  background-color: #ececec;
}

.vtl .__fixed-first-second-column tr th:nth-child(2),
.vtl .__fixed-first-second-column tr td:nth-child(2) {
  position: sticky;
  left: 38px;
}

.vtl .__fixed-first-second-column tr th:nth-child(2) {
  z-index: 2;
}
</style>
