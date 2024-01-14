<template>
  <base-loading-panel :loading="loading" type="table">
    <template #content>
      <base-semi-datatable
        pagination-theme="modern"
        :is-loading="table.isLoading"
        :columns="table.columns"
        :rows="table.rows"
        :total="table.total"
        @do-search="doSearch"
      >
        <template #emptyTableRows>
          <partial-empty-rows
            image="/empty-statuses/empty-contact.svg"
            image-class="w-60"
            message="شما هیچ تماسی ثبت نکرده‌اید"
          />
        </template>

        <template #id="{index}">
          <span class="text-sm">{{ index }}</span>
        </template>

        <template #title="{value}">
          <span class="text-sm">{{ value.title }}</span>
        </template>

        <template #answered_at="{value}">
          <span v-if="value.answered_at" class="text-emerald-500 text-sm border-b-2 border-emerald-500 pb-1">
              پاسخ داده شده
          </span>
          <span v-else class="text-rose-500 text-sm border-b-2 border-rose-500 pb-1">پاسخ داده نشده</span>
        </template>

        <template #op="{value}">
          <router-link
            :to="{name: 'user.contact.detail', params: {id: 12345}}"
            class="text-blue-600 hover:text-opacity-80 text-sm"
          >
            مشاهده جزئیات
          </router-link>
        </template>
      </base-semi-datatable>
    </template>
  </base-loading-panel>
</template>

<script setup>
import {reactive, ref} from "vue";
import BaseSemiDatatable from "@/components/base/BaseSemiDatatable.vue";
import PartialEmptyRows from "@/components/partials/PartialEmptyRows.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";

const loading = ref(true)
const table = reactive({
  isLoading: true,
  columns: [
    {
      field: 'id',
      label: '#',
      columnClasses: 'whitespace-nowrap',
      isKey: true,
    },
    {
      field: 'title',
      label: 'عنوان',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'answered_at',
      label: 'وضعیت پاسخ',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'op',
      label: 'عملیات',
      columnClasses: 'whitespace-nowrap',
    },
  ],
  rows: [],
  total: 0,
})

const doSearch = (offset, limit) => {
  table.isLoading = true

  // useRequest(apiRoutes., {
  //     params: {limit, offset, order, sort, text},
  // }, {
  //     success: (response) => {
  //         table.rows = response.data
  //         table.total = response.meta.total
  //
  //         return false
  //     },
  //     error: () => {
  //         table.rows = []
  //         table.total = 0
  //     },
  //     finally: () => {
  loading.value = false
  table.isLoading = false
  //     },
  // })
}

doSearch(0, 15)
</script>
