<template>
  <base-loading-panel
      :loading="loading"
      type="content"
  >
    <template #content>
      <div class="bg-white mb-3 rounded-lg border p-3">
        سفارش دهنده -
        <router-link
            v-if="order?.user?.id"
            :to="{name: 'admin.user.profile', params: {id: order?.user?.id}}"
            class="text-blue-600 hover:text-opacity-90"
        >
          <partial-username-label v-if="order?.user" :user="order?.user"/>
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
                  <div class="tracking-widest grow">{{ order?.code }}</div>
                </div>
                <div class="flex gap-2 items-center py-1">
                  <span class="text-xs text-gray-400 shrink-0">نام گیرنده:</span>
                  <div class="grow truncate">{{ order?.receiver_name }}</div>
                </div>
                <div class="flex gap-2 items-center py-1">
                  <span class="text-xs text-gray-400 shrink-0">شماره تماس:</span>
                  <div class="tracking-widest grow">{{ order?.receiver_mobile }}</div>
                </div>
                <div class="flex gap-2 items-center py-1">
                  <span class="text-xs text-gray-400 shrink-0">استان:</span>
                  <div class="grow">{{ order?.province }}</div>
                </div>
                <div class="flex gap-2 items-center py-1">
                  <span class="text-xs text-gray-400 shrink-0">شهر:</span>
                  <div class="grow">{{ order?.city }}</div>
                </div>
                <div class="flex gap-2 items-center py-1">
                  <span class="text-xs text-gray-400 shrink-0">روش ارسال:</span>
                  <div class="grow">{{ order?.send_method_title }}</div>
                </div>
                <div class="flex gap-2 items-center py-1">
                  <span class="text-xs text-gray-400 shrink-0">درخواست فاکتور:</span>
                  <div class="grow">
                    <span v-if="order?.is_needed_factor">بله</span>
                    <span v-else>خیر</span>
                  </div>
                </div>
                <div class="flex gap-2 items-center py-1">
                  <span class="text-xs text-emerald-400 shrink-0">مبلغ قابل پرداخت:</span>
                  <div class="grow text-emerald-600">
                    <span class="tracking-widest">{{ numberFormat(order?.final_price) }}</span>
                    <span class="text-xs mr-1">تومان</span>
                  </div>
                </div>
                <div class="flex gap-2 items-center py-1">
                  <span class="text-xs text-emerald-400 shrink-0">پرداخت شده تاکنون:</span>
                  <div class="grow text-emerald-600">
                    <span class="tracking-widest">{{ numberFormat(paymentUntilNow) }}</span>
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
                <base-loading-panel :loading="loading" type="table">
                  <template #content>
                    <base-semi-datatable
                        :columns="ordersTableSetting.columns"
                        :is-loading="false"
                        :rows="ordersTableSetting.rows"
                        :total="ordersTableSetting.total"
                        :sortable="ordersTableSetting.sortable"
                    >
                      <template #payment_status="{value}">
                        <partial-badge-status-payment
                            :color-hex="value.payment_status.color_hex"
                            :text="value.payment_status.text"
                        />
                      </template>

                      <template #paid_at="{value}">
                        <span v-if="value.paid_at" class="text-xs">{{ value.paid_at }}</span>
                        <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
                      </template>

                      <template #created_at="{value}">
                        <span v-if="value.created_at" class="text-xs">{{ value.created_at }}</span>
                        <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
                      </template>

                      <template #must_pay_price="{value}">
                        <span class="tracking-widest">{{ numberFormat(value.must_pay_price) }}</span>
                        <span class="text-xs mr-1">تومان</span>
                      </template>

                      <template #payment_method_type="{value}">
                        <span class="text-sm">{{ value.payment_method_type.text }}</span>
                      </template>

                      <template #payment_status_changed_at="{value}">
                        <span v-if="value.payment_status_changed_at"
                              class="text-xs">{{ value.payment_status_changed_at }}</span>
                        <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
                      </template>

                      <template #payment_status_changed_by="{value}">
                        <partial-username-label
                            v-if="value.payment_status_changed_by"
                            :user="value.payment_status_changed_by"
                        />
                        <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
                      </template>

                      <template #op_1="{value}">
                        <base-select
                            :options="paymentStatuses"
                            :selected="value.payment_status"
                            options-key="value"
                            options-text="text"
                            @change="(selected) => {changePaymentStatus(selected, value)}"
                        />
                      </template>

                      <template #op_2="{value}">
                        <a
                            class="border-0 text-blue-600 hover:text-opacity-80 text-sm p-2"
                            href="javascript:void(0)"
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

            <partial-dialog v-model:open="orderPaymentDetailOpen">
              <template #title>
                جزئیات پرداخت
              </template>

              <template #body>
                <base-datatable
                    :columns="orderPaymentsTableSetting.columns"
                    :enable-multi-operation="false"
                    :enable-search-box="false"
                    :has-checkbox="false"
                    :is-loading="false"
                    :is-slot-mode="true"
                    :is-static-mode="true"
                    :rows="orderPaymentsTableSetting.rows"
                >
                  <template #id="{value, index}">
                    {{ index }}
                  </template>

                  <template #status="{value}">
                    <partial-badge-publish
                        :publish="value.status"
                        publish-text="پرداخت شده"
                        unpublish-text="پرداخت نشده"
                    />
                  </template>

                  <template #receipt="{value}">
                    <span class="text-black tracking-widest">{{ value.receipt }}</span>
                  </template>

                  <template #gateway_type="{value}">
                    {{ value.gateway_type.text }}
                  </template>

                  <template #paid_at="{value}">
                    <span v-if="value.paid_at" class="text-xs">{{ value.paid_at }}</span>
                    <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
                  </template>

                  <template #created_at="{value}">
                    <span v-if="value.created_at" class="text-xs">{{ value.created_at }}</span>
                    <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
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
                v-if="sendStatus"
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
                    <div class="text-black">{{ order?.receiver_name }}</div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">کد ملی:</span>
                    <div class="text-black tracking-widest">{{ order?.national_code }}</div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">شماره تماس:</span>
                    <div class="text-black tracking-widest">{{ order?.receiver_mobile }}</div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">استان:</span>
                    <div class="text-black">{{ order?.province }}</div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">شهر:</span>
                    <div class="text-black">{{ order?.city }}</div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">کد پستی:</span>
                    <div class="text-black tracking-widest">{{ order?.postal_code }}</div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3 sm:col-span-2 lg:col-span-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">آدرس:</span>
                    <div class="text-black">
                      {{ order?.address }}
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
                    <div class="text-black tracking-widest">{{ order?.code }}</div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">مبلغ قابل پرداخت:</span>
                    <div class="text-black">
                      <span class="tracking-widest">{{ numberFormat(order?.final_price) }}</span>
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
                      {{ order?.ordered_at }}
                    </div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">وضعیت سفارش:</span>
                    <div class="text-black">
                      <partial-badge-status-send
                          :color-hex="order?.send_status.color_hex"
                          :text="order?.send_status.title"
                      />
                    </div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">روش ارسال:</span>
                    <div class="text-black">{{ order?.send_method_title }}</div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">هزینه ارسال:</span>
                    <div class="text-black">
                      <span class="tracking-widest">{{ numberFormat(order?.shipping_price) }}</span>
                      <span class="text-xs mr-1">تومان</span>
                    </div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">درخواست فاکتور:</span>
                    <div class="text-black">
                      <span v-if="order?.is_needed_factor">بله</span>
                      <span v-else>خیر</span>
                    </div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">کد تخفیف:</span>
                    <div class="text-black">
                      <span v-if="order?.coupon_code"
                            class="rounded border-2 py-1 px-2 bg-slate-50">{{ order?.coupon_code }}</span>
                      <span v-else>-</span>
                    </div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">مبلغ کوپن تخفیف:</span>
                    <div class="text-black">
                      <div v-if="order?.coupon_price">
                        <span class="tracking-widest">{{ numberFormat(order?.coupon_price) }}</span>
                        <span class="text-xs mr-1">تومان</span>
                      </div>
                      <span v-else>-</span>
                    </div>
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
          v-if="!loading"
          class="p-3"
      >
        <base-button
            :disabled="isDownloadFactor"
            class="bg-orange-500 text-white mr-auto px-6 w-full sm:w-auto flex items-center"
            type="submit"
            @click="factorDownloadHandler"
        >
          <VTransitionFade>
            <loader-circle
                v-if="isDownloadFactor"
                big-circle-color="border-transparent"
                main-container-klass="absolute w-full h-full top-0 left-0"
            />
          </VTransitionFade>

          <ArrowDownTrayIcon class="h-6 w-6 ml-2"/>
          <span class="mx-auto">دانلود فاکتور (پی دی اف)</span>
        </base-button>
      </div>

      <base-loading-panel :loading="loading" type="table">
        <template #content>
          <base-datatable
              :columns="table.columns"
              :enable-multi-operation="false"
              :enable-search-box="false"
              :has-checkbox="false"
              :is-loading="false"
              :is-slot-mode="true"
              :is-static-mode="true"
              :rows="table.rows"
          >
            <template v-slot:product="{value}">
              <div class="flex flex-col gap-3">
                <partial-show-image :item="value.image"/>
                <span>{{ value.product_title }}</span>

                <ul class="flex flex-col gap-2.5">
                  <li v-if="value.color_name">
                    <partial-badge-color :hex="value.color_hex" :title="value.color_name"/>
                  </li>
                  <li v-if="value.size">
                    <partial-badge-size :title="value.size"/>
                  </li>
                  <li v-if="value.guarantee">
                    {{ value.guarantee }}
                  </li>
                </ul>

                <span v-if="value.is_returned"
                      class="bg-orange-400 py-1 px-3 rounded">مرجوع شده</span>
              </div>
            </template>

            <template v-slot:unit_price="{value}">
              <div class="text-lg font-iranyekan-bold">
                {{ numberFormat(value.unit_price) }}
                <span class="text-xs text-gray-400">تومان</span>
              </div>
            </template>

            <template v-slot:product_count="{value}">
              <span class="py-1 px-1.5 rounded bg-violet-100">{{ numberFormat(value.quantity) }}</span>
              <span class="mr-2 text-sm">{{ value.unit_name }}</span>
            </template>

            <template v-slot:total_price="{value}">
              <div class="text-lg font-iranyekan-bold">
                {{ numberFormat(value.price) }}
                <span class="text-xs text-gray-400">تومان</span>
              </div>
            </template>

            <template v-slot:discount="{value}">
              <div v-if="(+value.price) - (+value.discounted_price) > 0">
                <div class="text-lg font-iranyekan-bold">
                  {{ numberFormat((+value.price) - (+value.discounted_price)) }}
                  <span class="text-xs text-gray-400">تومان</span>
                </div>
              </div>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>

            <template v-slot:discounted_price="{value}">
              <div class="text-lg font-iranyekan-bold">
                {{ numberFormat(value.discounted_price) }}
                <span class="text-xs text-gray-400">تومان</span>
              </div>
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
import {useToast} from "vue-toastification";
import BaseAccordion from "@/components/base/BaseAccordion.vue";
import FormOrderChangeSendStatus from "./forms/FormOrderChangeSendStatus.vue";
import BaseDatatable from "@/components/base/BaseDatatable.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import {ArrowDownTrayIcon, MinusIcon} from "@heroicons/vue/24/outline/index.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import PartialBadgeStatusSend from "@/components/partials/PartialBadgeStatusSend.vue";
import BaseSemiDatatable from "@/components/base/BaseSemiDatatable.vue";
import PartialBadgeStatusPayment from "@/components/partials/PartialBadgeStatusPayment.vue";
import BaseSelect from "@/components/base/BaseSelect.vue";
import PartialDialog from "@/components/partials/PartialDialog.vue";
import {getRouteParamByKey, numberFormat} from "@/composables/helper.js";
import {OrderAPI} from "@/service/APIOrder.js";
import PartialShowImage from "@/components/partials/filemanager/PartialShowImage.vue";
import PartialBadgeColor from "@/components/partials/PartialBadgeColor.vue";
import PartialBadgeSize from "@/components/partials/PartialBadgeSize.vue";
import PartialUsernameLabel from "@/components/partials/PartialUsernameLabel.vue";
import {useConfirmToast} from "@/composables/toast-helper.js";
import PartialBadgePublish from "@/components/partials/PartialBadgePublish.vue";

const toast = useToast()
const idParam = getRouteParamByKey('id', null, false)

const loading = ref(true)

const order = ref(null)
const paymentStatuses = ref(null)
const paymentStatus = ref(null)
const sendStatus = ref(null)

const paymentUntilNow = computed(() => {
  if (!order.orders) return 0;

  return order.orders.reduce((total, item) => {
    total += item.must_pay_price || 0
    return total
  })
})

//-----------------------------------
// Payment section
//-----------------------------------
const orderPaymentDetailOpen = ref(false)

const ordersTableSetting = reactive({
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
      field: 'paid_at',
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
      field: 'payment_method_type',
      label: 'شیوه پرداخت',
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
  sortable: {
    order: "created_at",
    sort: "desc",
  },
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
      field: 'paid_at',
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
  useConfirmToast(() => {
    OrderAPI.updateOrderPayment(item.id, {
      payment_status: selected.value.value
    }, {
      success() {
        toast.success('تغییر وضعیت پرداخت انجام شد.')
      },
    })
  }, 'تغییر وضعیت پرداخت')
}

function showOrderPaymentDetail(item) {
  orderPaymentsTableSetting.rows = item.payments || []
  orderPaymentDetailOpen.value = true
}

//-----------------------------------
// Factor downloading
//-----------------------------------
const isDownloadFactor = ref(false)

function factorDownloadHandler() {
  if (!isDownloadFactor.value) return

  isDownloadFactor.value = true

  OrderAPI.exportPdf(idParam.value, {
    finally() {
      isDownloadFactor.value = false
    },
  })
}

//-----------------------------------
// Items stuffs
//-----------------------------------
const table = reactive({
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
  sortable: {
    order: "id",
    sort: "desc",
  },
})

//-----------------------------------
onMounted(() => {
  OrderAPI.fetchById(idParam.value, {
    success: (response) => {
      order.value = response.data
      paymentStatus.value = response.data.payment_status
      sendStatus.value = response.data.send_status

      // orders of order details (pay connections)
      ordersTableSetting.rows = response.data?.orders || []
      ordersTableSetting.total = response.data?.orders?.length || 0

      // order items
      table.rows = response.data?.items || []

      loading.value = false
    },
  })

  OrderAPI.fetchPaymentStatuses({
    success: (response) => {
      paymentStatuses.value = []
      for (let i = 0; i < response.data.length; i++) {
        paymentStatuses.push({
          text: response.data[i],
          value: i,
        })
      }
    },
  })
})
</script>
