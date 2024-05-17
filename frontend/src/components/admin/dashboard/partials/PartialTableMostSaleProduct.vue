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
    empty-message="محصول پرفروشی وجود ندارد!"
    loading-message="در حال بارگذاری پرفروش‌ترین محصولات"
    pagination-theme="modern"
  >
    <template #id="{index}">
      <span class="text-sm">{{ index }}</span>
    </template>

    <template v-slot:image="{value}">
      <base-lazy-image
        :alt="value.title"
        :lazy-src="value.image.path"
        :size="FileSizes.SMALL"
        :is-local="false"
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
import {FileSizes} from "@/composables/file-list.js";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import BaseSelect from "@/components/base/BaseSelect.vue";
import {REPORT_PERIODS} from "@/composables/constants.js";
import {AdminPanelDashboardAPI} from "@/service/APIAdminPanel.js";

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
  rows: [],
  total: 0,
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

function getPeriodData(selected = null) {
  table.isLoading = true

  AdminPanelDashboardAPI.getMostSaleProducts(selectedPeriod.value.value, {
    success(response) {
      table.rows = response.rows || []
      table.total = response.rows?.length || 0

      if (selected) {
        // Assign selected period again to prevent mistaken selecting
        selectedPeriod.value = selected
      }
    },
    finally() {
      table.isLoading = false
    },
  })
}

function periodChangeHandler(selected) {
  if (table.isLoading) return

  selectedPeriod.value = selected

  getPeriodData(selected)
}

onMounted(() => {
  getPeriodData()
})
</script>
