<template>
  <base-loading-panel
    :loading="loading"
    type="content"
  >
    <template #content>
      <div class="bg-white mb-3 rounded-lg border p-3">
        سفارش دهنده -
        <router-link
          v-if="order?.user.id"
          :to="{name: 'admin.user.profile', params: {id: order?.user.id}}"
          class="text-blue-600 hover:text-opacity-90"
        >{{
            (order?.user?.first_name || order?.user?.last_name) ? (order?.user?.first_name + ' ' + order?.user?.last_name).trim() : order?.user.username
          }}
        </router-link>
      </div>

      <div class="md:flex md:gap-3">
        <partial-card class="mb-3 md:max-w-xs">
          <template #body>
            <div class="p-3">
              <div class="text-gray-500">
                خلاصه سفارش
              </div>

              <div class="mt-2 text-sm divide-y w-full">
                <div class="flex gap-2 items-center py-1">
                  <partial-badge-status-send class="w-full"/>
                </div>
                <div class="flex gap-2 items-center py-1">
                  <span class="text-xs text-gray-400 shrink-0">کد سفارش</span>
                  <div class="tracking-widest grow">172584328151083</div>
                </div>
                <div class="flex gap-2 items-center py-1">
                  <span class="text-xs text-gray-400 shrink-0">نام گیرنده:</span>
                  <div class="grow truncate">مهران مرادی</div>
                </div>
                <div class="flex gap-2 items-center py-1">
                  <span class="text-xs text-gray-400 shrink-0">شماره تماس:</span>
                  <div class="tracking-widest grow">۰۹۹۳۸۳۰۶۱۹۸</div>
                </div>
                <div class="flex gap-2 items-center py-1">
                  <span class="text-xs text-gray-400 shrink-0">استان:</span>
                  <div class="grow">کرمانشاه</div>
                </div>
                <div class="flex gap-2 items-center py-1">
                  <span class="text-xs text-gray-400 shrink-0">شهر:</span>
                  <div class="grow">قصر شیرین</div>
                </div>
                <div class="flex gap-2 items-center py-1">
                  <span class="text-xs text-gray-400 shrink-0">درخواست فاکتور:</span>
                  <div class="grow">خیر</div>
                </div>
                <div class="flex gap-2 items-center py-1">
                  <span class="text-xs text-gray-400 shrink-0">تحویل حضوری:</span>
                  <div class="grow">خیر</div>
                </div>
                <div class="flex gap-2 items-center py-1">
                  <span class="text-xs text-emerald-400 shrink-0">مبلغ قابل پرداخت:</span>
                  <div class="grow text-emerald-600">
                    <span class="tracking-widest">۶۷۹,۰۰۰</span>
                    <span class="text-xs mr-1">تومان</span>
                  </div>
                </div>
                <div class="flex gap-2 items-center py-1">
                  <span class="text-xs text-emerald-400 shrink-0">پرداخت شده تاکنون:</span>
                  <div class="grow text-emerald-600">
                    <span class="tracking-widest">۶۷۹,۰۰۰</span>
                    <span class="text-xs mr-1">تومان</span>
                  </div>
                </div>
              </div>
            </div>
          </template>
        </partial-card>

        <partial-card class="mb-3 md:grow">
          <template #body>
            <div class="p-3">
              <div class="text-gray-500">
                وضعیت پرداخت
              </div>

              <div class="max-h-72 mt-2 my-custom-scrollbar">
                <base-loading-panel type="table" :loading="ordersTableSetting.isLoading">
                  <template #content>
                    <base-semi-datatable
                      :is-loading="ordersTableSetting.isLoading"
                      :columns="ordersTableSetting.columns"
                      :rows="ordersTableSetting.rows"
                      :total="ordersTableSetting.total"
                    >
                      <template #payment_status="{value}">
                        <partial-badge-status-payment/>
                      </template>

                      <template #payed_at="{value}">
                        <span class="text-sm">{{ value.payed_at }}</span>
                      </template>

                      <template #created_at="{value}">
                        <span class="text-sm">{{ value.created_at }}</span>
                      </template>

                      <template #must_pay_price="{value}">
                        <span class="text-sm">{{ value.must_pay_price }}</span>
                      </template>

                      <template #gateway_type="{value}">
                        <span class="text-sm">{{ value.gateway_type }}</span>
                      </template>

                      <template #payment_status_changed_at="{value}">
                        <span class="text-sm">{{ value.payment_status_changed_at }}</span>
                      </template>

                      <template #payment_status_changed_by="{value}">
                        <span class="text-sm">{{ value.payment_status_changed_by }}</span>
                      </template>

                      <template #op_1="{value}">
                        <base-select
                          options-text="text"
                          options-key="value"
                          options="paymentStatuses"
                          :selected="value.payment_status"
                          @change="(selected) => {changePaymentStatus(selected, value)}"
                        />
                      </template>

                      <template #op_2="{value}">
                        <a
                          href="javascript:void(0)"
                          class="border-0 text-blue-600 hover:text-opacity-80 text-sm p-2"
                          @click="() => {showOrderPaymentDetail(value)}"
                        >
                          مشاهده جزئیات پرداخت
                        </a>
                      </template>
                    </base-semi-datatable>
                  </template>
                </base-loading-panel>
              </div>
            </div>

            <partial-dialog
              v-model:open="orderPaymentDetailOpen"
              container-klass="overflow-auto"
            >
              <template #title>
                جزئیات پرداخت
              </template>

              <template #body>
                <base-datatable
                  :is-loading="false"
                  :is-static-mode="true"
                  :is-slot-mode="true"
                  :columns="orderPaymentsTableSetting.columns"
                  :rows="orderPaymentsTableSetting.rows"
                >
                  <template #id="{value, index}">
                    {{ index }}
                  </template>
                  <template #status="{value}">
                    {{ value.status }}
                  </template>
                  <template #receipt="{value}">
                    {{ value.receipt }}
                  </template>
                  <template #message="{value}">
                    {{ value.message }}
                  </template>
                  <template #gateway_type="{value}">
                    {{ value.gateway_type }}
                  </template>
                  <template #payed_at="{value}">
                    {{ value.payed_at }}
                  </template>
                  <template #created_at="{value}">
                    {{ value.created_at }}
                  </template>
                </base-datatable>
              </template>
            </partial-dialog>
          </template>
        </partial-card>
      </div>

      <partial-card class="mb-3 lg:grow">
        <template #header>
          تغییر وضعیت ارسال
        </template>
        <template #body>
          <div class="p-3">
            <form-order-change-send-status
              v-model:selected="sendStatus"
            />
          </div>
        </template>
      </partial-card>

      <div class="mb-3">
        <base-accordion :open="false">
          <template #button>
            اطلاعات گیرنده
          </template>
          <template #panel>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 text-sm">
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">نام:</span>
                    <div class="text-black">مهران مرادی</div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">کد ملی:</span>
                    <div class="text-black tracking-widest">۳۳۶۰۳۶۵۸۸۷</div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">شماره تماس:</span>
                    <span class="text-black tracking-widest">۰۹۹۳۸۳۰۶۱۹۸</span>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">استان:</span>
                    <div class="text-black">کرمانشاه</div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">شهر:</span>
                    <div class="text-black">قصر شیرین</div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">کد پستی:</span>
                    <div class="text-black tracking-widest">۶۷۷۴۱۱۶۱۹۵</div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3 sm:col-span-2 lg:col-span-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">آدرس:</span>
                    <div class="text-black">
                      کرمانشاه قصرشیرین قصرجدید فاز 1
                    </div>
                  </div>
                </template>
              </partial-card>
            </div>
          </template>
        </base-accordion>
      </div>

      <div class="mb-3">
        <base-accordion :open="false">
          <template #button>
            جزئیات سفارش
          </template>
          <template #panel>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 text-sm">
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">کد سفارش:</span>
                    <div class="text-black tracking-widest">172584328151083</div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">مبلغ قابل پرداخت:</span>
                    <div class="text-black">
                      <span class="tracking-widest">۶۷۹,۰۰۰</span>
                      <span class="text-xs mr-1">تومان</span>
                    </div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">تاریخ ثبت سفارش:</span>
                    <div class="text-black">
                      ۱۷ شهریور ۱۴۰۲ در ساعت ۱۹ و ۳۴ دقیقه
                    </div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">وضعیت سفارش:</span>
                    <div class="text-black">
                      <partial-badge-status-send/>
                    </div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">هزینه ارسال:</span>
                    <div class="text-black">
                      <span class="tracking-widest">۴۴,۰۰۰</span>
                      <span class="text-xs mr-1">تومان</span>
                    </div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">درخواست فاکتور:</span>
                    <div class="text-black">خیر</div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">تحویل حضوری:</span>
                    <div class="text-black">خیر</div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">کد کوپن:</span>
                    <div class="text-black">-</div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">مبلغ کوپن:</span>
                    <div class="text-black">-</div>
                  </div>
                </template>
              </partial-card>
            </div>
          </template>
        </base-accordion>
      </div>
    </template>
  </base-loading-panel>

  <partial-card>
    <template #header>
      محصولات خریداری شده
    </template>
    <template #body>
      <div
        v-if="!itemsLoading"
        class="p-3"
      >
        <base-button
          type="submit"
          class="bg-orange-500 text-white mr-auto px-6 w-full sm:w-auto flex items-center"
          :disabled="isDownloadFactor"
          @click="factorDownloadHandler"
        >
          <VTransitionFade>
            <loader-circle
              v-if="isDownloadFactor"
              main-container-klass="absolute w-full h-full top-0 left-0"
              big-circle-color="border-transparent"
            />
          </VTransitionFade>

          <ArrowDownTrayIcon class="h-6 w-6 ml-2"/>
          <span class="mx-auto">دانلود فاکتور (پی دی اف)</span>
        </base-button>
      </div>

      <base-loading-panel :loading="itemsLoading" type="table">
        <template #content>
          <base-datatable
            ref="datatable"
            :enable-search-box="false"
            :enable-multi-operation="false"
            :is-slot-mode="true"
            :is-loading="table.isLoading"
            :columns="table.columns"
            :rows="table.rows"
            :has-checkbox="false"
            :total="table.totalRecordCount"
            @do-search="doSearch"
          >
            <template v-slot:product="{value}">

            </template>
            <template v-slot:unit_price="{value}">

            </template>
            <template v-slot:product_count="{value}">

            </template>
            <template v-slot:total_price="{value}">

            </template>
            <template v-slot:discount="{value}">

            </template>
            <template v-slot:discounted_price="{value}">

            </template>
          </base-datatable>
        </template>
      </base-loading-panel>
    </template>
  </partial-card>
</template>

<script setup>
import {computed, onMounted, reactive, ref} from "vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import {useRoute} from "vue-router";
import {useToast} from "vue-toastification";
import BaseAccordion from "@/components/base/BaseAccordion.vue";
import FormOrderChangeSendStatus from "./forms/FormOrderChangeSendStatus.vue";
import BaseDatatable from "@/components/base/BaseDatatable.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import {ArrowDownTrayIcon} from "@heroicons/vue/24/outline/index.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import PartialBadgeStatusSend from "@/components/partials/PartialBadgeStatusSend.vue";
import BaseSemiDatatable from "@/components/base/BaseSemiDatatable.vue";
import PartialBadgeStatusPayment from "@/components/partials/PartialBadgeStatusPayment.vue";
import BaseSelect from "@/components/base/BaseSelect.vue";
import PartialDialog from "@/components/partials/PartialDialog.vue";

const route = useRoute()
const toast = useToast()
const idParam = computed(() => {
  const id = parseInt(route.params.id, 10)
  if (isNaN(id)) return route.params.id
  return id
})

const loading = ref(false)

const order = ref(null)
const paymentStatuses = ref(null)
const paymentStatus = ref(null)
const sendStatus = ref(null)

onMounted(() => {
  // useRequest(apiReplaceParams(apiRoutes.admin.orders.paymentStatuses), null, {
  //     success: (response) => {
  //         paymentStatuses.value = response.data
  //
  //         loading.value = false
  //     },
  // })
  //
  // useRequest(apiReplaceParams(apiRoutes.admin.orders.show, {order: idParam.value}), null, {
  //     success: (response) => {
  //         order.value = response.data
  //         paymentStatus.value = response.data.payment_status
  //         sendStatus.value = response.data.send_status
  //
  //         loading.value = false
  //     },
  // })
})

//-----------------------------------
// Payment section
//-----------------------------------
const orderPaymentDetailOpen = ref(false)

const ordersTableSetting = reactive({
  isLoading: false,
  columns: [
    {
      field: 'op_1',
      label: '',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'payment_status',
      label: 'وضعیت پرداخت',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'payed_at',
      label: 'تاریخ پرداخت',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'created_at',
      label: 'تاریخ ایجاد',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'must_pay_price',
      label: 'مبلغ',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'gateway_type',
      label: 'نوع درگاه',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'payment_status_changed_at',
      label: 'تاریخ تغییر وضعیت',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'payment_status_changed_by',
      label: 'تغییر وضعیت توسط',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'op_2',
      label: '',
      columnClasses: 'whitespace-nowrap',
    },
  ],
  rows: [],
  total: 0,
})

const orderPaymentsTableSetting = reactive({
  columns: [
    {
      label: "#",
      field: "id",
      columnStyles: "width: 3%;",
      sortable: true,
      isKey: true,
    },
    {
      field: 'status',
      label: 'وضعیت پرداخت',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'receipt',
      label: 'کد رهگیری',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'message',
      label: 'پیام',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'gateway_type',
      label: 'از طریق درگاه',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'payed_at',
      label: 'تاریخ پرداخت',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'created_at',
      label: 'تاریخ اتصال',
      columnClasses: 'whitespace-nowrap',
    },
  ],
  rows: [],
})

function changePaymentStatus(selected, item) {
  // change payment status
  // ...
}

function showOrderPaymentDetail(item) {
  orderPaymentsTableSetting.rows = item.payments
  orderPaymentDetailOpen.value = true
}

//-----------------------------------

//-----------------------------------
// Factor downloading
//-----------------------------------
const isDownloadFactor = ref(false)

function factorDownloadHandler() {
  if (!isDownloadFactor.value) return
}

//-----------------------------------

//-----------------------------------
// Items stuffs
//-----------------------------------
const datatable = ref(null)
const tableContainer = ref(null)
const itemsLoading = ref(false)
const table = reactive({
  isLoading: false,
  columns: [
    {
      label: "#",
      field: "id",
      columnStyles: "width: 3%;",
      isKey: true,
    },
    {
      label: "مشخصات محصول",
      field: "product",
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "فی (به تومان)",
      field: "unit_price",
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "تعداد",
      field: "product_count",
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "مبلغ بدون تخفیف(به تومان)",
      field: "total_price",
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "مبلغ تخفیف(به تومان)",
      field: "discount",
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "مبلغ نهایی(به تومان)",
      field: "discounted_price",
      columnClasses: 'whitespace-nowrap',
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

  // useRequest(apiRoutes.admin.orders.index, {
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
  itemsLoading.value = false
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
//-----------------------------------
</script>
