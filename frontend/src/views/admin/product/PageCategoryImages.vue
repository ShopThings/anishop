<template>
  <new-creation-guide-top>
    <template #text>
      با کلیک بر روی تصویر موجود در جدول اقدام به افزودن/ویرایش تصویر دسته‌بندی نمایید
    </template>
  </new-creation-guide-top>

  <partial-card ref="tableContainer">
    <template #header>
      لیست تصاویر دسته‌بندی‌ها
    </template>

    <template #body>
      <base-loading-panel :loading="loading" type="table">
        <template #content>
          <base-datatable
            ref="datatable"
            :enable-search-box="true"
            :enable-multi-operation="false"
            :is-slot-mode="true"
            :is-loading="table.isLoading"
            :columns="table.columns"
            :rows="table.rows"
            :has-checkbox="false"
            :total="table.totalRecordCount"
            :sortable="table.sortable"
            @do-search="doSearch"
          >
            <template v-slot:image="{value}">
              <base-media-placeholder
                type="image"
                :has-clear-button="true"
              />
            </template>
          </base-datatable>
        </template>
      </base-loading-panel>
    </template>
  </partial-card>
</template>

<script setup>
import {reactive, ref} from "vue"
import BaseDatatable from "@/components/base/BaseDatatable.vue"
import NewCreationGuideTop from "@/components/admin/NewCreationGuideTop.vue"
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import {useRouter} from "vue-router";
import {useToast} from "vue-toastification";
import BaseMediaPlaceholder from "@/components/base/BaseMediaPlaceholder.vue";

const router = useRouter()
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
      label: "تصویر",
      field: "image",
    },
    {
      label: "نام",
      field: "name",
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

  // useRequest(apiRoutes.admin.categoryImages.index, {
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
  //         table.sortable.order = order
  //         table.sortable.sort = sort
  //
  //         if (tableContainer.value && tableContainer.value.card)
  //             tableContainer.value.card.scrollIntoView({behavior: "smooth"})
  //     },
  // })
}

doSearch(0, 15, 'id', 'desc')
</script>
