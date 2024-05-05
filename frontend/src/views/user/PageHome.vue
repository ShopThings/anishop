<template>
  <div v-if="redirectInfo && Object.keys(redirectInfo).length">
    <redirection-gateway-form
      :action="redirectInfo.action"
      :inputs="redirectInfo.inputs"
      :method="redirectInfo.method"
    />
  </div>

  <template v-else>
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
          <span class="text-2xl">{{ countingStore.getOrderCount }}</span>
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
            درخواست مرجوع کالا
          </h1>
          <span class="text-2xl">{{ countingStore.getReturnOrderCount }}</span>
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
          <span class="text-2xl">{{ countingStore.getProductCommentCount + countingStore.getBlogCommentCount }}</span>
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
          <span class="text-2xl">{{ countingStore.getFavoriteProductCount }}</span>
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
          <span class="text-2xl">{{ countingStore.getAddressCount }}</span>
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
          <span class="text-2xl">{{ countingStore.getContactCount }}</span>
          <span class="mr-2 text-sm text-gray-400">مورد</span>
        </div>

        <div class="w-1/2 h-0.5 rounded-full bg-slate-200 absolute bottom-2 left-1/2 -translate-x-1/2"></div>
      </div>
    </div>

    <VTransitionFade>
      <div v-if="!waitingOrdersLoading && waitingOrders?.length">
        <div class="mt-3">
          <div class="flex flex-col divide-y divide-slate-200">
            <div
              v-for="(order, idx) in waitingOrders"
              :key="idx"
              class="py-5"
            >
              <div class="flex flex-wrap justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2">
                  <span class="font-iranyekan-light">سفارش با کد</span>
                  <span class="text-lg font-sans font-semibold" dir="ltr">{{ order.code }}</span>
                  <span class="font-iranyekan-light">در انتظار پرداخت</span>
                </div>

                <div
                  v-if="countdowns[order.code] && countdowns[order.code].isStarted"
                  class="rounded-lg flex items-center gap-3 py-2 px-4 bg-white border-2 border-emerald-500 mr-auto"
                >
                  <span class="text-sm">زمان باقیمانده رزرو:</span>
                  <div class="min-w-12 text-left text-emerald-700 flex flex-row-reverse items-center gap-1">
                    <span>{{ countdowns[order.code].minutes.value }}</span>
                    <span>:</span>
                    <span>{{ countdowns[order.code].seconds.value }}</span>
                  </div>
                </div>

                <div
                  v-else
                  class="rounded-lg flex items-center gap-3 py-2 px-4 !bg-rose-50 border-2 border-rose-500"
                >
                  <span class="text-sm">اتمام زمان رزور</span>
                </div>
              </div>

              <partial-card
                v-for="(item, idx2) in order.chunks"
                v-if="order?.chunks?.length"
                :key="item.id"
                class="border-0 p-3 mt-3"
              >
                <template #body>
                  <partial-pay-card
                    :card-number="idx2 + 1"
                    :loading="paymentLoadings[order.code] && paymentLoadings[order.code][item.id]"
                    :method-title="item.payment_method"
                    :price="item.price"
                    @click="payLinkHandler(item.id, order.code)"
                  />
                </template>
              </partial-card>
            </div>
          </div>
        </div>
      </div>
      <div v-else-if="waitingOrdersLoading" class="h-10 mt-4">
        <loader-circle container-bg-color=""/>
      </div>
    </VTransitionFade>

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
            :columns="topOrdersTableSetting.columns"
            :is-loading="topOrdersTableSetting.isLoading"
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
            :columns="topReturnOrdersTableSetting.columns"
            :is-loading="topReturnOrdersTableSetting.isLoading"
            :rows="topReturnOrdersTableSetting.rows"
            :total="topReturnOrdersTableSetting.total"
            @do-search="getLatestReturnOrders"
          >
            <template #code="{value}">
              <span class="tracking-widest text-lg">{{ value.code }}</span>
            </template>

            <template #order_code="{value}">
              <router-link
                :to="{name: 'user.order.detail', params: {code: value.order_code}}"
                class="text-blue-600 hover:text-opacity-80 text-sm"
                target="_blank"
              >
                <span class="tracking-widest text-lg">{{ value.order_code }}</span>
              </router-link>
            </template>

            <template #status="{value}">
              <partial-badge-status-return-order
                :color-hex="value.status.color_hex"
                :text="value.status.text"
              />
            </template>

            <template #requested_at="{value}">
              <span v-if="value.requested_at" class="text-xs">{{ value.requested_at }}</span>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
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
</template>

<script setup>
import {inject, onMounted, reactive, ref} from "vue";
import {
  ArchiveBoxXMarkIcon,
  BookOpenIcon,
  ChatBubbleLeftEllipsisIcon,
  ChatBubbleLeftRightIcon,
  HeartIcon,
  ShoppingBagIcon,
} from "@heroicons/vue/24/solid/index.js";
import {ChevronLeftIcon, MinusIcon} from "@heroicons/vue/24/outline/index.js";
import PartialGeneralTitle from "@/components/partials/PartialGeneralTitle.vue";
import BaseSemiDatatable from "@/components/base/BaseSemiDatatable.vue";
import PartialBadgeStatusPayment from "@/components/partials/PartialBadgeStatusPayment.vue";
import PartialBadgeStatusSend from "@/components/partials/PartialBadgeStatusSend.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialBadgeStatusReturnOrder from "@/components/partials/PartialBadgeStatusReturnOrder.vue";
import PartialEmptyRows from "@/components/partials/PartialEmptyRows.vue";
import {UserPanelDashboardAPI, UserPanelOrderAPI} from "@/service/APIUserPanel.js";
import {numberFormat} from "@/composables/helper.js";
import PartialCard from "@/components/partials/PartialCard.vue";
import PartialPayCard from "@/components/partials/pages/PartialPayCard.vue";
import {HomeCheckoutAPI} from "@/service/APIHomePages.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {useCountdown} from "@/composables/countdown-timer.js";
import RedirectionGatewayForm from "@/components/RedirectionGatewayForm.vue";

const countingStore = inject('countingStore')

//-----------------------------------------------
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
  rows: [],
  total: 0,
  sortable: {
    order: "ordered_at",
    sort: "desc",
  },
})

const getLatestOrders = () => {
  topOrdersTableSetting.isLoading = true

  UserPanelDashboardAPI.fetchLatestOrders({
    success: (response) => {
      topOrdersTableSetting.rows = response.data
      topOrdersTableSetting.total = response.data.length

      return false
    },
    error: () => {
      topOrdersTableSetting.rows = []
      topOrdersTableSetting.total = 0
    },
    finally: () => {
      ordersTableLoading.value = false
      topOrdersTableSetting.isLoading = false
    },
  })
}

//-----------------------------------------------
const topReturnOrdersTableSetting = reactive({
  isLoading: true,
  columns: [
    {
      field: 'code',
      label: 'کد درخواست',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'order_code',
      label: 'کد سفارش',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'status',
      label: 'وضعیت',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'requested_at',
      label: 'تاریخ درخواست',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'op',
      label: 'عملیات',
    },
  ],
  rows: [],
  total: 0,
  sortable: {
    order: "requested_at",
    sort: "desc",
  },
})

const getLatestReturnOrders = () => {
  topReturnOrdersTableSetting.isLoading = true

  UserPanelDashboardAPI.fetchLatestReturnOrders({
    success: (response) => {
      topReturnOrdersTableSetting.rows = response.data
      topReturnOrdersTableSetting.total = response.data.length

      return false
    },
    error: () => {
      topReturnOrdersTableSetting.rows = []
      topReturnOrdersTableSetting.total = 0
    },
    finally: () => {
      returnOrdersTableLoading.value = false
      topReturnOrdersTableSetting.isLoading = false
    },
  })
}

//-----------------------------------------------
const waitingOrders = ref(null)
const waitingOrdersLoading = ref(true)
const countdowns = reactive({})
const redirectInfo = ref(null)

const paymentLoadings = reactive({})

function payLinkHandler(id, code) {
  if (
    !code ||
    paymentLoadings[code][id] === true
  ) return

  if (!paymentLoadings[code]) {
    paymentLoadings[code] = {}
  }

  paymentLoadings[code][id] = true

  HomeCheckoutAPI.payOrder(id, code, {
    success(response) {
      redirectInfo.value = response.data
    },
    finally() {
      paymentLoadings[code][id] = false
    },
  })
}

onMounted(() => {
  getLatestOrders()
  getLatestReturnOrders()

  UserPanelOrderAPI.fetchUnpaidOrderPayments({
    success(response) {
      waitingOrders.value = response.data

      if (response.data) {
        for (let o of response.data) {
          countdowns[o.code] = useCountdown(response.data.remained_pay_time || 0)

          countdowns[o.code].start(() => {
            countdowns[o.code].stop()
          })
        }
      }
    },
    finally() {
      waitingOrdersLoading.value = false
    },
  })
})
</script>
