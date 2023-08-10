<template>
    <div class="vtl relative flex flex-col min-w-0">

        <slot name="title" v-if="title">
            <div class="mb-2">
                {{ title }}
            </div>
        </slot>

        <div v-if="setting.enableMultiOperation && hasCheckbox">
            <base-datatable-multi-operation
                @clear-selected-items="clearSelectedItems"
                :items="selectedItems"
                :operations="setting.selectionOperations"
            >
                <template #selectedItems="{items, close}">
                    <slot name="beforeSelectedFilesTable" :close="close"></slot>

                    <base-datatable
                        :is-slot-mode="setting.isSlotMode"
                        :is-static-mode="true"
                        :columns="setting.selectionColumns"
                        :rows="items"
                        :total="items.length"
                    >
                        <template v-for="(slot, index) of Object.keys(slots)" :key="index" v-slot:[slot]="data">
                            <slot :name="slot" :value="data.value"></slot>
                        </template>
                        <template v-slot:selection_remover="data">
                            <BackspaceIcon @click="removeSelectedItem(data.value)"
                                           v-tooltip.left="'حذف از انتخاب‌ها'"
                                           class="w-6 h-6 text-rose-500 cursor-pointer hover:scale-125 transition"/>
                        </template>
                    </base-datatable>

                    <div class="mt-3">
                        <slot name="afterSelectedFilesTable" :close="close">
                            <base-animated-button @click="close" class="bg-slate-600 px-5 mr-auto">
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
                @search="doSearchText"
                @clear-filter="clearSearchFilter"
                @refresh="refresh"
            />
        </div>

        <div
            class="flex flex-col relative overflow-hidden"
            :class="{
                    '__fixed-first-column': isFixedFirstColumn,
                    '__fixed-first-second-column': isFixedFirstColumn && hasCheckbox,
                  }"
        >
            <div v-show="isLoading"
                 class="absolute z-[3] top-0 left-0 w-full h-full bg-black/30 supports-[backdrop-filter]:bg-black/25 supports-[backdrop-filter]:backdrop-blur flex flex-col transition">
                <slot name="loadingBlock">
                    <div class="flex items-center justify-center flex-1">
                        <span style="color: white">
                            {{ messages.loading }}
                        </span>
                        <loader-circle container-bg-color=""
                                       main-container-klass="h-6 w-6 relative mr-2"></loader-circle>
                    </div>
                </slot>
            </div>

            <div class="overflow-x-auto">
                <div class="inline-block min-w-full">
                    <div class="overflow-hidden" ref="tableContainer">
                        <table ref="localTable"
                               class="text-sm text-left text-gray-500 rtl:text-right"
                               :style="[maxHeight !== 'auto' ? 'max-height: ' + maxHeight + 'px;' : '']"
                        >
                            <thead
                                class="text-xs text-gray-700 uppercase border-b-2 bg-cyan-400">
                            <tr>
                                <th v-if="hasCheckbox" scope="col"
                                    class="w-[1%] min-w-[38px] px-5 py-2.5">
                                    <div class="flex items-center">
                                        <input
                                            type="checkbox"
                                            class="w-4 h-4 text-blue-600 bg-white bg-opacity-40 border-white rounded focus:ring-blue-600 focus:ring-2"
                                            v-model="setting.isCheckAll"
                                        >
                                    </div>
                                </th>
                                <th v-for="(col, index) in columns" :key="index"
                                    scope="col" class="px-1"
                                    :class="[col.sortable ? 'cursor-pointer' : '', col.columnClasses]"
                                    :style="[col.width ? 'width: ' + col.width : 'width: auto', col.columnStyles]"
                                >
                                    <div
                                        class="py-2.5 px-2"
                                        :class="{
                                            'rounded-md bg-cyan-400 w-full hover:bg-cyan-300 hover:bg-opacity-50 transition text-white': (col.label && col.label.trim() !== '') || !!col.sortable
                                        }"
                                        @click.prevent="col.sortable ? doSort(col.field) : false"
                                    >
                                        <div
                                            class="flex rtl:flex-row-reverse items-center justify-start rtl:justify-end">
                                            <ArrowSmallUpIcon
                                                v-if="setting.order === col.field && setting.sort === 'desc'"
                                                class="ml-auto rtl:mr-auto rtl:ml-[unset] w-4 h-4 shrink-0"/>
                                            <ArrowSmallDownIcon
                                                v-else-if="setting.order === col.field && setting.sort === 'asc'"
                                                class="ml-auto rtl:mr-auto rtl:ml-[unset] w-4 h-4 shrink-0"/>
                                            <ArrowsUpDownIcon v-else-if="col.sortable"
                                                              class="ml-auto rtl:mr-auto rtl:ml-[unset] text-white text-opacity-40 w-4 h-4 shrink-0"/>
                                            <span class="ml-2 leading-relaxed">{{ col.label }}</span>
                                        </div>
                                    </div>
                                </th>
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
                                    <tr v-if="groupingKey !== ''"
                                        class="border-b transition">
                                        <td
                                            :colspan="hasCheckbox ? columns.length + 1 : columns.length"
                                            class="px-6 py-4"
                                        >
                                            <div class="flex">
                                                <div v-if="hasGroupToggle">
                                                    <a
                                                        :ref="(el) => (toggleButtonRefs[groupingIndex] = el)"
                                                        class="cursor-pointer"
                                                        @click.prevent="toggleGroup(groupingIndex)"
                                                    >
                                                        <ChevronDownIcon class="w-4 h-4"/>
                                                    </a>
                                                </div>
                                                <div
                                                    class="ml-2"
                                                    v-html="
                                                            groupingDisplay
                                                              ? groupingDisplay(groupingIndex)
                                                              : groupingIndex
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
                                        :name="'vtl-group-' + groupingIndex"
                                        class="border-b transition"
                                        :class="typeof rowClasses === 'function' ? rowClasses(row) : rowClasses"
                                        @click="emit('row-clicked', row)"
                                        @contextmenu="emit('row-context-menu', $event, row)"
                                    >
                                        <td v-if="hasCheckbox" class="w-[1%] min-w-[38px] px-5 py-2.5">
                                            <div class="flex items-center">
                                                <input
                                                    type="checkbox"
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-600 focus:ring-2"
                                                    :ref="
                                                        (el) => {
                                                          if(el && hasSelectedItemWithProperty(row)) {
                                                            el.checked = true;
                                                          }
                                                          rowCheckbox.push(el);
                                                        }
                                                      "
                                                    :value="row[setting.keyColumn]"
                                                    @click="checked(row, $event)"
                                                >
                                            </div>
                                        </td>
                                        <td
                                            v-for="(col, j) in columns"
                                            :key="j"
                                            class="px-5 py-2.5"
                                            :class="[col.cellClasses, col.columnClasses]"
                                            :style="[col.cellStyles, col.columnStyles]"
                                            @mouseenter="addHoverClassToTd"
                                            @mouseleave="removeHoverClassFromTd"
                                        >
                                            <div v-if="col.display" v-html="col.display(row)"></div>
                                            <div v-else>
                                                <div v-if="setting.isSlotMode && slots[col.field]">
                                                    <slot :name="col.field" :value="row"></slot>
                                                </div>
                                                <span v-else>{{ row[col.field] }}</span>
                                            </div>
                                        </td>
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
                                            :colspan="hasCheckbox ? columns.length + 1 : columns.length"
                                            class="px-6 py-4"
                                        >
                                            <div class="flex">
                                                <div v-if="hasGroupToggle">
                                                    <a
                                                        :ref="(el) => (toggleButtonRefs[groupingIndex] = el)"
                                                        class="cursor-pointer"
                                                        @click.prevent="toggleGroup(groupingIndex)"
                                                    >
                                                        <ChevronDownIcon class="w-4 h-4"/>
                                                    </a>
                                                </div>
                                                <div
                                                    class="ml-2"
                                                    v-html="
                                                            groupingDisplay
                                                              ? groupingDisplay(groupingIndex)
                                                              : groupingIndex
                                                          "
                                                ></div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr
                                        v-for="(row, i) in rows"
                                        :ref="
                                                  (el) => {
                                                    if (!groupingRowsRefs[groupingIndex]) {
                                                      groupingRowsRefs[groupingIndex] = [];
                                                    }
                                                    groupingRowsRefs[groupingIndex][i] = el;
                                                  }
                                                "
                                        :name="'vtl-group-' + groupingIndex"
                                        :key="row[setting.keyColumn] ? row[setting.keyColumn] : i"
                                        class="border-b transition"
                                        :class="typeof rowClasses === 'function' ? rowClasses(row) : rowClasses"
                                        @click="emit('row-clicked', row)"
                                        @contextmenu="emit('row-context-menu', $event, row)"
                                    >
                                        <td v-if="hasCheckbox" class="px-6 py-4">
                                            <div class="flex items-center">
                                                <input
                                                    type="checkbox"
                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-600 focus:ring-2"
                                                    :ref="
                                                            (el) => {
                                                              if(el && hasSelectedItemWithProperty(row)) {
                                                                el.checked = true;
                                                              }
                                                              rowCheckbox.push(el);
                                                            }
                                                          "
                                                    :value="row[setting.keyColumn]"
                                                    @click="checked(row, $event)"
                                                >
                                            </div>
                                        </td>
                                        <td
                                            v-for="(col, j) in columns"
                                            :key="j"
                                            class="px-6 py-4"
                                            :class="[col.cellClasses, col.columnClasses]"
                                            :style="[col.cellStyles, col.columnStyles]"
                                            @mouseenter="addHoverClassToTd"
                                            @mouseleave="removeHoverClassFromTd"
                                        >
                                            <div v-if="col.display" v-html="col.display(row)"></div>
                                            <div v-else>
                                                <div v-if="setting.isSlotMode && slots[col.field]">
                                                    <slot :name="col.field" :value="row"></slot>
                                                </div>
                                                <span v-else>{{ row[col.field] }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                </tbody>
                            </template>

                            <tfoot
                                class="text-xs text-gray-700 uppercase border-b-2 bg-cyan-400">
                            <tr>
                                <th v-if="hasCheckbox" scope="col"
                                    class="w-[1%] min-w-[38px] px-5 py-2.5">
                                    <div class="flex items-center">
                                        <input
                                            type="checkbox"
                                            class="w-4 h-4 text-blue-600 bg-white bg-opacity-40 border-white rounded focus:ring-blue-600 focus:ring-2"
                                            v-model="setting.isCheckAll"
                                        >
                                    </div>
                                </th>
                                <th v-for="(col, index) in columns" :key="index"
                                    scope="col" class="px-1"
                                    :class="[col.sortable ? 'cursor-pointer' : '', col.columnClasses]"
                                    :style="[col.width ? 'width: ' + col.width : 'width: auto', col.columnStyles]"
                                >
                                    <div
                                        class="py-2.5 px-2"
                                        :class="{
                                            'rounded-md bg-cyan-400 w-full hover:bg-cyan-300 hover:bg-opacity-50 transition text-white': (col.label && col.label.trim() !== '') || !!col.sortable
                                        }"
                                        @click.prevent="col.sortable ? doSort(col.field) : false"
                                    >
                                        <div
                                            class="flex rtl:flex-row-reverse items-center justify-start rtl:justify-end">
                                            <ArrowSmallUpIcon
                                                v-if="setting.order === col.field && setting.sort === 'desc'"
                                                class="ml-auto rtl:mr-auto rtl:ml-[unset] w-4 h-4 shrink-0"/>
                                            <ArrowSmallDownIcon
                                                v-else-if="setting.order === col.field && setting.sort === 'asc'"
                                                class="ml-auto rtl:mr-auto rtl:ml-[unset] w-4 h-4 shrink-0"/>
                                            <ArrowsUpDownIcon v-else-if="col.sortable"
                                                              class="ml-auto rtl:mr-auto rtl:ml-[unset] text-white text-opacity-40 w-4 h-4 shrink-0"/>
                                            <span class="ml-2 leading-relaxed">{{ col.label }}</span>
                                        </div>
                                    </div>
                                </th>
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
                    <div role="status"
                         aria-live="polite"
                         class="mb-3 text-sm text-gray-500 mb-3 sm:mb-0"
                         v-html="stringFormat(messages.pagingInfo, setting.offset, setting.limit, total)">
                    </div>

                    <div class="flex rtl:flex-row-reverse" v-if="setting.maxPage > 1">
                        <div class="mx-2">
                            <span class="text-sm text-gray-500">{{ messages.pageSizeChangeLabel }}</span>
                            <base-select :options="pageOptions"
                                         options-key="value"
                                         :text="setting.pageSize"
                                         :selected="setting.pageSize"
                                         options-class="bottom-full mb-1"
                                         @change="(item) => {setting.pageSize = item.value}"
                            >
                                <template #item="{item}">
                                    {{ item.text }}
                                </template>
                            </base-select>
                        </div>

                        <div class="mx-2" v-if="!setting.isHideSelectPaging">
                            <span class="text-sm text-gray-500">{{ messages.gotoPageLabel }}</span>
                            <base-select :options="setting.maxPage"
                                         :text="setting.page"
                                         :selected="setting.page"
                                         options-class="bottom-full mb-1"
                                         @change="(item) => {setting.page = item}"
                            >
                            </base-select>
                        </div>
                    </div>
                </div>

                <div class="mt-3" v-if="setting.maxPage > 1">
                    <ul class="flex justify-center text-center rtl:flex-row-reverse whitespace-nowrap no-underline">
                        <li>
                            <a v-tooltip.top="'صفحه اول'"
                               class="cursor-pointer relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-200 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                               :class="{'!cursor-not-allowed !bg-gray-100 !text-gray-400': setting.page <= 1}"
                               :disabled="setting.page <= 1"
                               aria-label="First"
                               @click.prevent="setting.page = 1"
                            >
                                    <span aria-hidden="true">
                                        <ChevronDoubleLeftIcon class="w-4 h-4"/>
                                    </span>
                                <span class="sr-only">صفحه اول</span>
                            </a>
                        </li>
                        <li>
                            <a v-tooltip.top="'صفحه قبل'"
                               class="cursor-pointer relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-800 ring-1 ring-inset ring-gray-200 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                               :class="{'!cursor-not-allowed !bg-gray-100 !text-gray-400': setting.page <= 1}"
                               :disabled="setting.page <= 1"
                               aria-label="Previous"
                               @click.prevent="prevPage"
                            >
                                    <span aria-hidden="true">
                                        <ChevronLeftIcon class="w-4 h-4"/>
                                    </span>
                                <span class="sr-only">صفحه قبل</span>
                            </a>
                        </li>
                        <li v-for="n in setting.paging"
                            :key="n"
                        >
                            <a v-tooltip.top="'صفحه ' + n"
                               class="cursor-pointer relative inline-flex items-center px-4 py-2 text-xs font-semibold text-gray-800 ring-1 ring-inset ring-gray-200 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                               :class="{'!cursor-not-allowed !bg-primary !text-white !ring-primary': setting.page === n}"
                               :disabled="setting.page === n"
                               @click.prevent="movePage(n)"
                            >
                                {{ n }}
                            </a>
                        </li>
                        <li>
                            <a v-tooltip.top="'صفحه بعد'"
                               class="cursor-pointer relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-800 ring-1 ring-inset ring-gray-200 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                               :class="{'!cursor-not-allowed !bg-gray-100 !text-gray-400': setting.page >= setting.maxPage}"
                               :disabled="setting.page >= setting.maxPage"
                               aria-label="Next"
                               @click.prevent="nextPage"
                            >
                                    <span aria-hidden="true">
                                        <ChevronRightIcon class="w-4 h-4"/>
                                    </span>
                                <span class="sr-only">صفحه بعد</span>
                            </a>
                        </li>
                        <li>
                            <a v-tooltip.top="'صفحه آخر'"
                               class="cursor-pointer relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-200 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                               :class="{'!cursor-not-allowed !bg-gray-100 !text-gray-400': setting.page >= setting.maxPage}"
                               :disabled="setting.page >= setting.maxPage"
                               aria-label="Last"
                               @click.prevent="setting.page = setting.maxPage"
                            >
                                    <span aria-hidden="true">
                                        <ChevronDoubleRightIcon class="w-4 h-4"/>
                                    </span>
                                <span class="sr-only">صفحه آخر</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </template>
        </div>

        <slot name="emptyTable" v-else-if="!isLoading">
            <div class="p-3">
                <div class="p-3 text-center text-rose-600 border rounded-md bg-gray-50 text-sm">
                    {{ messages.noDataAvailable }}
                </div>
            </div>
        </slot>
    </div>
</template>

<script setup>
import {computed, nextTick, onBeforeUpdate, onMounted, reactive, ref, useSlots, watch} from "vue"
import {
    ChevronDownIcon, ArrowSmallUpIcon, ArrowSmallDownIcon, ArrowsUpDownIcon,
    ChevronLeftIcon, ChevronRightIcon, ChevronDoubleLeftIcon, ChevronDoubleRightIcon,
    XCircleIcon, BackspaceIcon,
} from '@heroicons/vue/24/outline'
import BaseSelect from "./BaseSelect.vue";
import LoaderCircle from "./loader/LoaderCircle.vue";
import BaseDatatableSearch from "./datatable/BaseDatatableSearch.vue";
import BaseDatatableMultiOperation from "./datatable/BaseDatatableMultiOperation.vue";
import BaseAnimatedButton from "./BaseAnimatedButton.vue";
import BaseButton from "./BaseButton.vue";

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
            cellClasses: '!bg-rose-50',
        });
        return cols;
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
            //
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

    if (searchText.value.length)
        emit("do-search", offset, limit, order, sort, searchText.value);

    if (setting.isCheckAll) {
        setting.isCheckAll = false;
    } else {
        if (props.hasCheckbox) {
            clearChecked();
        }
    }
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

        if (setting.isCheckAll) {
            setting.isCheckAll = false;
        } else {
            if (props.hasCheckbox) {
                clearChecked();
            }
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
        if (!result[v[props.groupingKey]]) {
            result[v[props.groupingKey]] = [];
        }
        result[v[props.groupingKey]].push(v);
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
                        (isOpen && el.parentElement.classList.contains("__group-open")) ||
                        (!isOpen && !el.parentElement.classList.contains("__group-open"))
                    ) {
                        el.parentElement.classList.toggle("__group-open");
                        el.parentElement.classList.toggle("rotate-90 transition");
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
        targetEl.parentElement.classList.toggle("__group-open");
        targetEl.parentElement.classList.toggle("rotate-90 transition");
        const isClose = targetEl.parentElement.classList.contains("__group-open");
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

function removeSelectedItem(value) {
    removeFromSelectedItem(value)
}

function getParent(el, tagName) {
    tagName = tagName.toLowerCase();
    while (el && el.parentNode) {
        el = el.parentNode;
        if (el.tagName && el.tagName.toLowerCase() == tagName) {
            return el;
        }
    }
    return null;
}

function getColumn(tab, col) {
    if (!tab) return [];

    var n = tab.rows.length;
    var i, cols = [], tr, td;

    if (col < 0) {
        return [];
    }
    for (i = 0; i < n; i++) {
        if (tab.rows[i].cells.length > col) {
            cols.push(tab.rows[i].cells[col]);
        }
    }

    return cols;
}

function getChild(element, tagName) {
    tagName = tagName.toLowerCase();
    for (const child of element.children) {
        if (child.tagName.toLowerCase() === tagName) return child;
    }
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

defineExpose({
    table: localTable,
    tableContainer,
    refresh,
    resetSelection: () => {
        clearSelectedItems()
    },
    resetSelectionItem: (row) => {
        removeSelectedItem(row)
    },
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
