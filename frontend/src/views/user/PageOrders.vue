<template>
  <div class="px-3 py-5 flex gap-3 mb-6 border bg-white">
    <StarIcon class="w-6 h-6 text-orange-600 shrink-0"/>
    <div class="text-orange-600">
      در صورت نیاز به لغو سفارش، صفحه
      <router-link
          :to="{name: 'pages', params: {url: 'how-to-cancel-order'}}"
          target="_blank"
          class="mx-1.5 underline underline-offset-8 text-black hover:text-opacity-90 transition"
      >
        نحوه لغو سفارش
      </router-link>
      را مطالعه نمایید.
    </div>
  </div>

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
              image="/empty-statuses/empty-order.svg"
              image-class="w-64"
              message="هیچ سفارشی ثبت نشده است"
          />
        </template>

        <template #send_status="{value}">
          <partial-badge-status-send/>
        </template>

        <template #payment_status="{value}">
          <partial-badge-status-payment/>
        </template>

        <template #ordered_at="{value}">
          <span class="text-sm">{{ value.ordered_at }}</span>
        </template>

        <template #total_price="{value}">
          <span class="text-sm">{{ value.total_price }}</span>
        </template>

        <template #payment_method="{value}">
          <span class="text-sm">{{ value.payment_method }}</span>
        </template>

        <template #op="{value}">
          <router-link
              :to="{name: 'user.order.detail', params: {code: 12345}}"
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
import BaseLoadingPanel from "../../components/base/BaseLoadingPanel.vue";
import PartialBadgeStatusPayment from "../../components/partials/PartialBadgeStatusPayment.vue";
import BaseSemiDatatable from "../../components/base/BaseSemiDatatable.vue";
import PartialBadgeStatusSend from "../../components/partials/PartialBadgeStatusSend.vue";
import PartialEmptyRows from "../../components/partials/PartialEmptyRows.vue";
import {StarIcon} from "@heroicons/vue/24/outline/index.js";

const loading = ref(true)
const table = reactive({
  isLoading: true,
  columns: [
    {
      field: 'code',
      label: 'کد سفارش',
      columnClasses: 'whitespace-nowrap',
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
    },
    {
      field: 'total_price',
      label: 'جمع مبلغ',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'op',
      label: 'عملیات',
      columnClasses: 'whitespace-nowrap',
    },
  ],
  rows: [
    {
      code: '355902668083326',
      send_status: '',
      payment_status: '',
      ordered_at: '۳۰ مهر ۱۴۰۲',
      total_price: '۵۰۰,۰۰۰ تومان',
    },
    {
      code: '978418891011307',
      send_status: '',
      payment_status: '',
      ordered_at: '۲۴ شهریور ۱۴۰۲',
      total_price: '۵۰۰,۰۰۰ تومان',
    },
    {
      code: '652605648895526',
      send_status: '',
      payment_status: '',
      ordered_at: '۱۰ خرداد ۱۴۰۲',
      total_price: '۵۰۰,۰۰۰ تومان',
    },
    {
      code: '355902668083326',
      send_status: '',
      payment_status: '',
      ordered_at: '۳۰ مهر ۱۴۰۲',
      total_price: '۵۰۰,۰۰۰ تومان',
    },
    {
      code: '978418891011307',
      send_status: '',
      payment_status: '',
      ordered_at: '۲۴ شهریور ۱۴۰۲',
      total_price: '۵۰۰,۰۰۰ تومان',
    },
    {
      code: '652605648895526',
      send_status: '',
      payment_status: '',
      ordered_at: '۱۰ خرداد ۱۴۰۲',
      total_price: '۵۰۰,۰۰۰ تومان',
    },
    {
      code: '355902668083326',
      send_status: '',
      payment_status: '',
      ordered_at: '۳۰ مهر ۱۴۰۲',
      total_price: '۵۰۰,۰۰۰ تومان',
    },
    {
      code: '978418891011307',
      send_status: '',
      payment_status: '',
      ordered_at: '۲۴ شهریور ۱۴۰۲',
      total_price: '۵۰۰,۰۰۰ تومان',
    },
    {
      code: '652605648895526',
      send_status: '',
      payment_status: '',
      ordered_at: '۱۰ خرداد ۱۴۰۲',
      total_price: '۵۰۰,۰۰۰ تومان',
    },
    {
      code: '355902668083326',
      send_status: '',
      payment_status: '',
      ordered_at: '۳۰ مهر ۱۴۰۲',
      total_price: '۵۰۰,۰۰۰ تومان',
    },
    {
      code: '978418891011307',
      send_status: '',
      payment_status: '',
      ordered_at: '۲۴ شهریور ۱۴۰۲',
      total_price: '۵۰۰,۰۰۰ تومان',
    },
    {
      code: '652605648895526',
      send_status: '',
      payment_status: '',
      ordered_at: '۱۰ خرداد ۱۴۰۲',
      total_price: '۵۰۰,۰۰۰ تومان',
    },
    {
      code: '355902668083326',
      send_status: '',
      payment_status: '',
      ordered_at: '۳۰ مهر ۱۴۰۲',
      total_price: '۵۰۰,۰۰۰ تومان',
    },
    {
      code: '978418891011307',
      send_status: '',
      payment_status: '',
      ordered_at: '۲۴ شهریور ۱۴۰۲',
      total_price: '۵۰۰,۰۰۰ تومان',
    },
    {
      code: '652605648895526',
      send_status: '',
      payment_status: '',
      ordered_at: '۱۰ خرداد ۱۴۰۲',
      total_price: '۵۰۰,۰۰۰ تومان',
    },
    {
      code: '355902668083326',
      send_status: '',
      payment_status: '',
      ordered_at: '۳۰ مهر ۱۴۰۲',
      total_price: '۵۰۰,۰۰۰ تومان',
    },
    {
      code: '978418891011307',
      send_status: '',
      payment_status: '',
      ordered_at: '۲۴ شهریور ۱۴۰۲',
      total_price: '۵۰۰,۰۰۰ تومان',
    },
    {
      code: '652605648895526',
      send_status: '',
      payment_status: '',
      ordered_at: '۱۰ خرداد ۱۴۰۲',
      total_price: '۵۰۰,۰۰۰ تومان',
    },
  ],
  total: 18,
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

<style scoped>

</style>
