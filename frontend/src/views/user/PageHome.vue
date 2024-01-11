<template>
  <div class="flex flex-wrap gap-3">
    <div class="rounded-2xl bg-white px-3 pt-4 pb-6 flex flex-col gap-3 relative grow sm:grow-0 min-w-[12rem]">
      <div class="flex gap-3 items-center justify-between">
        <ShoppingBagIcon class="h-8 w-8 text-indigo-500"/>
        <router-link
          :to="{name: 'user.orders'}"
          class="text-blue-600 hover:text-opacity-80 text-sm"
        >
          <div class="flex items-center">
            <span class="ml-2">مشاهده</span>
            <div class="p-1 bg-slate-100 rounded-full">
              <ChevronLeftIcon class="w-4 h-4 text-black"/>
            </div>
          </div>
        </router-link>
      </div>
      <div>
        <h1 class="text-gray-400 mb-1">
          سفارشات
        </h1>
        <span class="text-2xl">12</span>
        <span class="mr-2 text-sm text-gray-400">مورد</span>
      </div>

      <div class="w-1/2 h-0.5 rounded-full bg-slate-200 absolute bottom-2 left-1/2 -translate-x-1/2"></div>
    </div>
    <div class="rounded-2xl bg-white px-3 pt-4 pb-6 flex flex-col gap-3 relative grow sm:grow-0 min-w-[12rem]">
      <div class="flex gap-3 items-center justify-between">
        <ArchiveBoxXMarkIcon class="h-8 w-8 text-teal-500"/>
        <router-link
          :to="{name: 'user.return_orders'}"
          class="text-blue-600 hover:text-opacity-80 text-sm"
        >
          <div class="flex items-center">
            <span class="ml-2">مشاهده</span>
            <div class="p-1 bg-slate-100 rounded-full">
              <ChevronLeftIcon class="w-4 h-4 text-black"/>
            </div>
          </div>
        </router-link>
      </div>
      <div>
        <h1 class="text-gray-400 mb-1">
          سفارشات مرجوع شده
        </h1>
        <span class="text-2xl">0</span>
        <span class="mr-2 text-sm text-gray-400">مورد</span>
      </div>

      <div class="w-1/2 h-0.5 rounded-full bg-slate-200 absolute bottom-2 left-1/2 -translate-x-1/2"></div>
    </div>
    <div class="rounded-2xl bg-white px-3 pt-4 pb-6 flex flex-col gap-3 relative grow sm:grow-0 min-w-[12rem]">
      <div class="flex gap-3 items-center justify-between">
        <ChatBubbleLeftRightIcon class="h-8 w-8 text-yellow-400"/>
        <router-link
          :to="{name: 'user.comments'}"
          class="text-blue-600 hover:text-opacity-80 text-sm"
        >
          <div class="flex items-center">
            <span class="ml-2">مشاهده</span>
            <div class="p-1 bg-slate-100 rounded-full">
              <ChevronLeftIcon class="w-4 h-4 text-black"/>
            </div>
          </div>
        </router-link>
      </div>
      <div>
        <h1 class="text-gray-400 mb-1">
          دیدگاه‌ها
        </h1>
        <span class="text-2xl">0</span>
        <span class="mr-2 text-sm text-gray-400">مورد</span>
      </div>

      <div class="w-1/2 h-0.5 rounded-full bg-slate-200 absolute bottom-2 left-1/2 -translate-x-1/2"></div>
    </div>
    <div class="rounded-2xl bg-white px-3 pt-4 pb-6 flex flex-col gap-3 relative grow sm:grow-0 min-w-[12rem]">
      <div class="flex gap-3 items-center justify-between">
        <HeartIcon class="h-8 w-8 text-rose-400"/>
        <router-link
          :to="{name: 'user.favorite_products'}"
          class="text-blue-600 hover:text-opacity-80 text-sm"
        >
          <div class="flex items-center">
            <span class="ml-2">مشاهده</span>
            <div class="p-1 bg-slate-100 rounded-full">
              <ChevronLeftIcon class="w-4 h-4 text-black"/>
            </div>
          </div>
        </router-link>
      </div>
      <div>
        <h1 class="text-gray-400 mb-1">
          محصولات مورد علاقه
        </h1>
        <span class="text-2xl">0</span>
        <span class="mr-2 text-sm text-gray-400">مورد</span>
      </div>

      <div class="w-1/2 h-0.5 rounded-full bg-slate-200 absolute bottom-2 left-1/2 -translate-x-1/2"></div>
    </div>
    <div class="rounded-2xl bg-white px-3 pt-4 pb-6 flex flex-col gap-3 relative grow sm:grow-0 min-w-[12rem]">
      <div class="flex gap-3 items-center justify-between">
        <BookOpenIcon class="h-8 w-8 text-blue-500"/>
        <router-link
          :to="{name: 'user.addresses'}"
          class="text-blue-600 hover:text-opacity-80 text-sm"
        >
          <div class="flex items-center">
            <span class="ml-2">مشاهده</span>
            <div class="p-1 bg-slate-100 rounded-full">
              <ChevronLeftIcon class="w-4 h-4 text-black"/>
            </div>
          </div>
        </router-link>
      </div>
      <div>
        <h1 class="text-gray-400 mb-1">
          آدرس‌های من
        </h1>
        <span class="text-2xl">1</span>
        <span class="mr-2 text-sm text-gray-400">مورد</span>
      </div>

      <div class="w-1/2 h-0.5 rounded-full bg-slate-200 absolute bottom-2 left-1/2 -translate-x-1/2"></div>
    </div>
    <div class="rounded-2xl bg-white px-3 pt-4 pb-6 flex flex-col gap-3 relative grow sm:grow-0 min-w-[12rem]">
      <div class="flex gap-3 items-center justify-between">
        <ChatBubbleLeftEllipsisIcon class="h-8 w-8 text-fuchsia-500"/>
        <router-link
          :to="{name: 'user.contacts'}"
          class="text-blue-600 hover:text-opacity-80 text-sm"
        >
          <div class="flex items-center">
            <span class="ml-2">مشاهده</span>
            <div class="p-1 bg-slate-100 rounded-full">
              <ChevronLeftIcon class="w-4 h-4 text-black"/>
            </div>
          </div>
        </router-link>
      </div>
      <div>
        <h1 class="text-gray-400 mb-1">
          تماس‌های من
        </h1>
        <span class="text-2xl">0</span>
        <span class="mr-2 text-sm text-gray-400">مورد</span>
      </div>

      <div class="w-1/2 h-0.5 rounded-full bg-slate-200 absolute bottom-2 left-1/2 -translate-x-1/2"></div>
    </div>
  </div>

  <div>
    <base-loading-panel :loading="ordersTableLoading" type="table">
      <template #content>
        <partial-general-title title="آخرین سفارشات">
          <template #extra>
            <router-link
              :to="{name: 'user.orders'}"
              class="text-blue-600 hover:text-opacity-80 text-sm"
            >
              <div class="flex items-center">
                <span class="ml-2">مشاهده همه</span>
                <ChevronLeftIcon class="w-4 h-4"/>
              </div>
            </router-link>
          </template>
        </partial-general-title>

        <base-semi-datatable
          :is-loading="topOrdersTableSetting.isLoading"
          :columns="topOrdersTableSetting.columns"
          :rows="topOrdersTableSetting.rows"
          :total="topOrdersTableSetting.total"
          @do-search="getLatestOrders"
        >
          <template #emptyTableRows>
            <partial-empty-rows
              image="/empty-statuses/empty-order.svg"
              image-class="w-60"
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
  </div>

  <div>
    <base-loading-panel :loading="returnOrdersTableLoading" type="table">
      <template #content>
        <partial-general-title title="آخرین سفارشات مرجوع شده">
          <template #extra>
            <router-link
              :to="{name: 'user.return_orders'}"
              class="text-blue-600 hover:text-opacity-80 text-sm"
            >
              <div class="flex items-center">
                <span class="ml-2">مشاهده همه</span>
                <ChevronLeftIcon class="w-4 h-4"/>
              </div>
            </router-link>
          </template>
        </partial-general-title>

        <base-semi-datatable
          :is-loading="topReturnOrdersTableSetting.isLoading"
          :columns="topReturnOrdersTableSetting.columns"
          :rows="topReturnOrdersTableSetting.rows"
          :total="topReturnOrdersTableSetting.total"
          @do-search="getLatestReturnOrders"
        >
          <template #status="{value}">
            <partial-badge-status-return-order/>
          </template>

          <template #requested_at="{value}">
            <span class="text-sm">{{ value.requested_at }}</span>
          </template>

          <template #op="{value}">
            <router-link
              :to="{name: 'user.return_order.detail', params: {code: 12345}}"
              class="text-blue-600 hover:text-opacity-80 text-sm"
            >
              مشاهده جزئیات
            </router-link>
          </template>
        </base-semi-datatable>
      </template>
    </base-loading-panel>
  </div>
</template>

<script setup>
import {reactive, ref} from "vue";
import {
  ShoppingBagIcon,
  ArchiveBoxXMarkIcon,
  ChatBubbleLeftRightIcon,
  HeartIcon,
  BookOpenIcon,
  ChatBubbleLeftEllipsisIcon,
} from "@heroicons/vue/24/solid/index.js";
import {ChevronLeftIcon} from "@heroicons/vue/24/outline/index.js";
import PartialGeneralTitle from "../../components/partials/PartialGeneralTitle.vue";
import BaseSemiDatatable from "../../components/base/BaseSemiDatatable.vue";
import PartialBadgeStatusPayment from "../../components/partials/PartialBadgeStatusPayment.vue";
import PartialBadgeStatusSend from "../../components/partials/PartialBadgeStatusSend.vue";
import BaseLoadingPanel from "../../components/base/BaseLoadingPanel.vue";
import PartialBadgeStatusReturnOrder from "../../components/partials/PartialBadgeStatusReturnOrder.vue";
import PartialEmptyRows from "../../components/partials/PartialEmptyRows.vue";

const ordersTableLoading = ref(true)
const returnOrdersTableLoading = ref(true)

const topOrdersTableSetting = reactive({
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
  ],
  total: 3,
})

const getLatestOrders = (offset, limit) => {
  topOrdersTableSetting.isLoading = true

  // useRequest(apiRoutes., {
  //     params: {limit, offset, order, sort, text},
  // }, {
  //     success: (response) => {
  //         topOrdersTableSetting.rows = response.data
  //         topOrdersTableSetting.total = response.meta.total
  //
  //         return false
  //     },
  //     error: () => {
  //         topOrdersTableSetting.rows = []
  //         topOrdersTableSetting.total = 0
  //     },
  //     finally: () => {
  ordersTableLoading.value = false
  topOrdersTableSetting.isLoading = false
  //     },
  // })
}

getLatestOrders(0, 15)

const topReturnOrdersTableSetting = reactive({
  isLoading: true,
  columns: [
    {
      field: 'code',
      label: 'کد درخواست',
    },
    {
      field: 'order_code',
      label: 'کد سفارش',
    },
    {
      field: 'status',
      label: 'وضعیت',
    },
    {
      field: 'requested_at',
      label: 'تاریخ درخواست',
    },
    {
      field: 'op',
      label: 'عملیات',
    },
  ],
  rows: [],
  total: 0,
})

const getLatestReturnOrders = (offset, limit) => {
  topReturnOrdersTableSetting.isLoading = true

  // useRequest(apiRoutes., {
  //     params: {limit, offset, order, sort, text},
  // }, {
  //     success: (response) => {
  //         topReturnOrdersTableSetting.rows = response.data
  //         topReturnOrdersTableSetting.total = response.meta.total
  //
  //         return false
  //     },
  //     error: () => {
  //         topReturnOrdersTableSetting.rows = []
  //         topReturnOrdersTableSetting.total = 0
  //     },
  //     finally: () => {
  returnOrdersTableLoading.value = false
  topReturnOrdersTableSetting.isLoading = false
  //     },
  // })
}

getLatestReturnOrders(0, 15)
</script>
