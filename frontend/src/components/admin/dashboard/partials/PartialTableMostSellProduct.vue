<template>
  <h2 class="text-slate-400 mb-3 mt-6">
    پرفروش‌ترین محصولات
  </h2>

  <div class="flex flex-wrap gap-1.5 items-center mb-3">
    <partial-input-label space="mb-1 sm:ml-1 sm:mb-0" title="نمایش در دوره"/>
    <base-select
      :options="periods"
      :selected="selectedPeriod"
      btn-space-class="py-1.5 px-3"
      options-key="value"
      options-text="text"
      optionsClass="mt-1 right-0"
      @change="periodChangeHandler"
    />
  </div>

  <base-semi-datatable
    :columns="table.columns"
    :is-loading="table.isLoading"
    :rows="table.rows"
    :sortable="table.sortable"
    :total="table.total"
    loading-message="در حال بارگذاری پرفرپش‌ترین محصولات"
    pagination-theme="modern"
  >
    <template #emptyTableRows>
      <partial-empty-rows
        image="/empty-statuses/empty-product.svg"
        image-class="w-60"
        message="محصول پرفروشی وجود ندارد!"
      />
    </template>

    <template #id="{index}">
      <span class="text-sm">{{ index }}</span>
    </template>

    <template v-slot:image="{value}">
      <base-lazy-image
        :alt="value.title"
        :lazy-src="value.image.path"
        :size="FileSizes.SMALL"
        class="h-auto !w-20 rounded"
      />
    </template>

    <template v-slot:title="{value}">
      <span class="text-sm">{{ value.title }}</span>
    </template>

    <template v-slot:brand="{value}">
      <span class="text-sm">{{ value.brand.name }}</span>
    </template>

    <template v-slot:category="{value}">
      <span class="text-sm">{{ value.category.name }}</span>
    </template>

    <template v-slot:count="{value}">
      <div class="inline-block items-center gap-2 py-1 px-2 rounded-md bg-violet-200">
        <span class="inline-block">{{ value.count }}</span>
        <span class="text-xs text-violet-500 mr-2">{{ value.unit_name }}</span>
      </div>
    </template>
  </base-semi-datatable>
</template>

<script setup>
import {onMounted, reactive, ref} from "vue";
import BaseSemiDatatable from "@/components/base/BaseSemiDatatable.vue";
import PartialEmptyRows from "@/components/partials/PartialEmptyRows.vue";
import {FileSizes} from "@/composables/file-list.js";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import BaseSelect from "@/components/base/BaseSelect.vue";
import {REPORT_PERIODS} from "@/composables/constants.js";

const table = reactive({
  isLoading: true,
  columns: [
    {
      field: 'id',
      label: '#',
      columnStyles: "width: 3%;",
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "تصویر",
      field: "image",
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "عنوان",
      field: "title",
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "برند",
      field: "brand",
    },
    {
      label: "دسته‌بندی",
      field: "category",
    },
    {
      label: "تعداد فروش",
      field: "count",
      columnClasses: 'whitespace-nowrap',
    },
  ],
  rows: [
    {
      id: 1,
      image: {
        name: 'no',
      },
      title: 'محصول پرفروش شماره ۱',
      brand: {
        name: 'سامسونگ',
      },
      category: {
        name: 'لوازم خانگی',
      },
      unit_name: 'عدد',
      count: 5,
    },
    {
      id: 2,
      image: {
        name: 'no',
      },
      title: 'محصول پرفروش شماره ۱',
      brand: {
        name: 'سامسونگ',
      },
      category: {
        name: 'لوازم خانگی',
      },
      unit_name: 'عدد',
      count: 2,
    },
    {
      id: 3,
      image: {
        name: 'no',
      },
      title: 'محصول پرفروش شماره ۱',
      brand: {
        name: 'سامسونگ',
      },
      category: {
        name: 'لوازم خانگی',
      },
      unit_name: 'عدد',
      count: 9,
    },
    {
      id: 4,
      image: {
        name: 'no',
      },
      title: 'محصول پرفروش شماره ۱',
      brand: {
        name: 'سامسونگ',
      },
      category: {
        name: 'لوازم خانگی',
      },
      unit_name: 'عدد',
      count: 3,
    },
    {
      id: 5,
      image: {
        name: 'no',
      },
      title: 'محصول پرفروش شماره ۱',
      brand: {
        name: 'سامسونگ',
      },
      category: {
        name: 'لوازم خانگی',
      },
      unit_name: 'عدد',
      count: 4,
    },
  ],
  total: 10,
  sortable: {
    order: "count",
    sort: "desc",
  },
})

const periods = [
  {
    value: REPORT_PERIODS.WEEKLY.value,
    text: REPORT_PERIODS.WEEKLY.text,
  },
  {
    value: REPORT_PERIODS.MONTHLY.value,
    text: REPORT_PERIODS.MONTHLY.text,
  },
  {
    value: REPORT_PERIODS.MONTHS_3.value,
    text: REPORT_PERIODS.MONTHS_3.text,
  },
  {
    value: REPORT_PERIODS.MONTHS_6.value,
    text: REPORT_PERIODS.MONTHS_6.text,
  },
  {
    value: REPORT_PERIODS.YEARLY.value,
    text: REPORT_PERIODS.YEARLY.text,
  },
]
const selectedPeriod = ref({
  value: REPORT_PERIODS.WEEKLY.value,
  text: REPORT_PERIODS.WEEKLY.text,
})

function periodChangeHandler(selected) {
  if (table.isLoading) return

  selectedPeriod.value = selected

  // TODO: make an API call to get what we needed...
}

onMounted(() => {
  // TODO: make request to get most sold products...

  setTimeout(() => {
    table.isLoading = false
  }, 3000)
})
</script>
