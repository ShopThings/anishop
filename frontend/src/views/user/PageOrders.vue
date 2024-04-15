<template>
  <div class="px-3 py-5 flex gap-3 mb-6 bg-white rounded-lg shadow-sm text-sm">
    <StarIcon class="w-5 h-5 text-orange-600 shrink-0"/>
    <div class="text-orange-600">
      در صورت نیاز به لغو سفارش، صفحه
      <router-link
          :to="{name: 'pages', params: {url: 'how-to-cancel-order'}}"
          class="mx-1.5 underline underline-offset-8 text-black hover:text-opacity-90 transition"
          target="_blank"
      >
        نحوه لغو سفارش
      </router-link>
      را مطالعه نمایید.
    </div>
  </div>

  <base-loading-panel :loading="loading" type="table">
    <template #content>
      <base-semi-datatable
          :columns="table.columns"
          :enable-search-box="true"
          :is-loading="table.isLoading"
          :rows="table.rows"
          :total="table.total"
          pagination-theme="modern"
          @do-search="doSearch"
      >
        <template #emptyTableRows>
          <partial-empty-rows
              image="/empty-statuses/empty-order.svg"
              image-class="w-64"
              message="هیچ سفارشی ثبت نشده است"
          />
        </template>

        <template #code="{value}">
          <span class="tracking-widest text-lg">{{ value.code }}</span>
        </template>

        <template #send_status="{value}">
          <partial-badge-status-send
              :color-hex="value.send_status_color_hex"
              :text="value.send_status_title"
          />
        </template>

        <template #payment_status="{value}">
          <partial-badge-status-payment
              :color-hex="value.payment_status.color_hex"
              :text="value.payment_status.text"
          />
        </template>

        <template #ordered_at="{value}">
          <span v-if="value.ordered_at" class="text-xs">{{ value.ordered_at }}</span>
          <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
        </template>

        <template #total_price="{value}">
          <div class="text-lg font-iranyekan-bold">
            {{ numberFormat(value.total_price) }}
            <span class="text-xs text-gray-400">تومان</span>
          </div>
        </template>

        <template #op="{value}">
          <router-link
              :to="{name: 'user.order.detail', params: {code: value.code}}"
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
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialBadgeStatusPayment from "@/components/partials/PartialBadgeStatusPayment.vue";
import BaseSemiDatatable from "@/components/base/BaseSemiDatatable.vue";
import PartialBadgeStatusSend from "@/components/partials/PartialBadgeStatusSend.vue";
import PartialEmptyRows from "@/components/partials/PartialEmptyRows.vue";
import {MinusIcon, StarIcon} from "@heroicons/vue/24/outline/index.js";
import {numberFormat} from "@/composables/helper.js";
import {UserPanelOrderAPI} from "@/service/APIUserPanel.js";

const loading = ref(true)
const table = reactive({
  isLoading: true,
  columns: [
    {
      field: 'code',
      label: 'کد سفارش',
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      field: 'send_status',
      label: 'وضعیت سفارش',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'payment_status',
      label: 'وضعیت پرداخت',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'ordered_at',
      label: 'تاریخ سفارش',
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      field: 'total_price',
      label: 'جمع مبلغ',
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      field: 'op',
      label: 'عملیات',
      columnClasses: 'whitespace-nowrap',
    },
  ],
  rows: [],
  total: 0,
  sortable: {
    order: "ordered_at",
    sort: "desc",
  },
})

const doSearch = (offset, limit, order, sort, text) => {
  table.isLoading = true

  UserPanelOrderAPI.fetchAll({limit, offset, order, sort, text}, {
    success: (response) => {
      table.rows = response.data
      table.total = response.meta.total

      return false
    },
    error: () => {
      table.rows = []
      table.total = 0
    },
    finally: () => {
      loading.value = false
      table.isLoading = false
    },
  })
}

doSearch(0, 15)
</script>
