<template>
  <base-loading-panel
      :loading="builderLoading"
      loading-text="در حال بارگذاری جستجوی پیشرفته"
      type="dot-orbit"
  >
    <template #content>
      <base-query-builder :columns="columns" :query="query"/>

      <partial-card class="mt-3">
        <template #body>
          <div class="flex flex-col sm:flex-row justify-end p-3">
            <base-button
                class="bg-primary rounded-r-lg rounded-l-lg sm:rounded-l-none border-primary text-sm my-1.5 sm:px-6"
                @click="filterQB">
              فیلتر اطلاعات
            </base-button>
            <base-button
                class="bg-gray-200 !text-black border border-gray-300 rounded-l-lg rounded-r-lg sm:rounded-r-none text-sm my-1.5 sm:px-6"
                @click="clearQBFilter">
              حذف فیلتر
            </base-button>
          </div>
        </template>
      </partial-card>
    </template>
  </base-loading-panel>

  <partial-card
      ref="tableContainer"
      class="mt-3"
  >
    <template #header>
      لیست محصولات
    </template>

    <template #body>
      <div
          v-if="!loading"
          class="p-3"
      >
        <base-button
            :disabled="isDownloadExcel"
            class="bg-green-600 text-white mr-auto px-6 w-full sm:w-auto flex items-center"
            type="submit"
            @click="excelDownloadHandler"
        >
          <VTransitionFade>
            <loader-circle
                v-if="isDownloadExcel"
                big-circle-color="border-transparent"
                main-container-klass="absolute w-full h-full top-0 left-0"
            />
          </VTransitionFade>

          <TableCellsIcon class="h-6 w-6 ml-2"/>
          <span class="mx-auto">دانلود گزارش (فایل اکسل)</span>
        </base-button>
      </div>

      <base-loading-panel :loading="loading" type="table">
        <template #content>
          <base-datatable
              ref="datatable"
              :columns="table.columns"
              :enable-multi-operation="false"
              :enable-search-box="false"
              :has-checkbox="false"
              :is-loading="table.isLoading"
              :is-slot-mode="true"
              :rows="table.rows"
              :sortable="table.sortable"
              :total="table.totalRecordCount"
              @do-search="doSearch"
          >
            <template v-slot:title="{value}">

            </template>
            <template v-slot:brand="{value}">

            </template>
            <template v-slot:category="{value}">

            </template>
            <template v-slot:stock_count="{value}">

            </template>
            <template v-slot:is_published="{value}">

            </template>
            <template v-slot:is_available="{value}">

            </template>
            <template v-slot:updated_at="{value}">
              <span v-if="value.updated_at" class="text-xs">{{ value.updated_at }}</span>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>
            <template v-slot:created_at="{value}">
              <span v-if="value.created_at" class="text-xs">{{ value.created_at }}</span>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>
          </base-datatable>
        </template>
      </base-loading-panel>
    </template>
  </partial-card>
</template>

<script setup>
import {onMounted, reactive, ref} from "vue";
import BaseQueryBuilder from "@/components/base/BaseQueryBuilder.vue";
import {MinusIcon, TableCellsIcon} from "@heroicons/vue/24/outline/index.js"
import BaseDatatable from "@/components/base/BaseDatatable.vue"
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import {useToast} from "vue-toastification";
import BaseButton from "@/components/base/BaseButton.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";

//-----------------------------------
// Query-Builder stuffs
//-----------------------------------
const builderLoading = ref(false)

const query = reactive([])
const columns = ref([])

function filterQB() {
  // make a request to get filtered items
}

function clearQBFilter() {
  query.length = 0
}

onMounted(() => {
  // useRequest(apiRoutes.admin.reports.usersQueryBuilder, null, {
  //     success: (response) => {
  //         columns.value = response.data
  //
  //         builderLoading.value = false
  //     },
  // })
})
//-----------------------------------

//-----------------------------------
// Export excel from query
//-----------------------------------
const isDownloadExcel = ref(false)

function excelDownloadHandler() {
  if (!isDownloadExcel.value) return
}

//-----------------------------------

//-----------------------------------
// Table stuffs
//-----------------------------------
const toast = useToast()

const datatable = ref(null)
const tableContainer = ref(null)
const loading = ref(true)
const table = reactive({
  isLoading: false,
  columns: [
    {
      label: "#",
      field: "id",
      columnStyles: "width: 3%;",
      sortable: true,
      isKey: true,
    },
    {
      label: "عنوان",
      field: "title",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "برند",
      field: "brand",
      sortable: true,
    },
    {
      label: "دسته‌بندی",
      field: "category",
      sortable: true,
    },
    {
      label: "تعداد موجود",
      field: "stock_count",
      sortable: true,
    },
    {
      label: "وضعیت نمایش",
      field: "is_published",
      sortable: true,
    },
    {
      label: "وضعیت موجودی",
      field: "is_available",
      sortable: true,
    },
    {
      label: "تاریخ ویرایش",
      field: "updated_at",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "تاریخ ایجاد",
      field: "created_at",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
  ],
  rows: [],
  totalRecordCount: 0,
  sortable: {
    order: "id",
    sort: "desc",
  },
})

const doSearch = (offset, limit, order, sort, text) => {
  table.isLoading = true

  // useRequest(apiRoutes.admin.products.index, {
  //     params: {limit, offset, order, sort, text},
  // }, {
  //     success: (response) => {
  //         table.rows = response.data
  //         table.totalRecordCount = response.meta.total
  //
  //         return false
  //     },
  //     error: () => {
  //         table.rows = []
  //         table.totalRecordCount = 0
  //     },
  //     finally: () => {
  loading.value = false
  table.isLoading = false
  //     table.sortable.order = order
  //     table.sortable.sort = sort
  //
  //     if (tableContainer.value && tableContainer.value.card)
  //         tableContainer.value.card.scrollIntoView({behavior: "smooth"})
  // },
  // })
}

doSearch(0, 15, 'id', 'desc')
//-----------------------------------
</script>
