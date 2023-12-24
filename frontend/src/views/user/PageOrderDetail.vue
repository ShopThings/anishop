<template>
  <div
      v-if="1"
      class="my-3"
  >
    <partial-badge-status-send class="w-full py-2 !text-sm"/>
  </div>

  <div>
    <h2 class="text-slate-400 mb-1">
      اطلاعات سفارش
    </h2>

    <base-loading-panel
        type="list"
        :loading="loadingInfo"
    >
      <template #content>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <partial-card class="border-0 p-3">
            <template #body>
              <div class="flex flex-col">
                <span class="text-xs text-gray-400 mb-1">شماره سفارش:</span>
                <div class="text-sm tracking-widest">
                  861247269716759
                </div>
              </div>
            </template>
          </partial-card>
          <partial-card class="border-0 p-3">
            <template #body>
              <div class="flex flex-col">
                <span class="text-xs text-gray-400 mb-1">در تاریخ:</span>
                <div class="text-sm">
                  ۱۷ شهریور ۱۴۰۲ در ساعت ۱۹ و ۳۴ دقیقه
                </div>
              </div>
            </template>
          </partial-card>
          <partial-card class="border-0 p-3">
            <template #body>
              <div class="flex flex-col">
                <span class="text-xs text-gray-400 mb-1">کد کوپن:</span>
                <div class="text-sm">
                  <MinusIcon class="w-6 h-6 text-rose-500"/>
                </div>
              </div>
            </template>
          </partial-card>
          <partial-card class="border-0 p-3">
            <template #body>
              <div class="flex flex-col">
                <span class="text-xs text-gray-400 mb-1">مبلغ کوپن:</span>
                <div class="text-sm">
                  <MinusIcon class="w-6 h-6 text-rose-500"/>
                </div>
              </div>
            </template>
          </partial-card>
          <partial-card class="border-0 p-3">
            <template #body>
              <div class="flex flex-col">
                <span class="text-xs text-gray-400 mb-1">مبلغ قابل پرداخت:</span>
                <div class="text-sm">
                  <span class="tracking-widest">۶۷۹,۰۰۰</span>
                  <span class="text-xs mr-1">تومان</span>
                </div>
              </div>
            </template>
          </partial-card>
          <partial-card class="border-0 p-3">
            <template #body>
              <div class="flex flex-col">
                <span class="text-xs text-gray-400 mb-1">هزینه ارسال:</span>
                <div class="text-sm">
                  رایگان
                </div>
              </div>
            </template>
          </partial-card>
          <partial-card class="border-0 p-3">
            <template #body>
              <div class="flex flex-col">
                <span class="text-xs text-gray-400 mb-1">مبلغ کل:</span>
                <div class="text-sm">
                  <span class="tracking-widest">۷۰۰,۰۰۰</span>
                  <span class="text-xs mr-1">تومان</span>
                </div>
              </div>
            </template>
          </partial-card>
          <partial-card class="border-0 p-3">
            <template #body>
              <div class="flex flex-col">
                <span class="text-xs text-gray-400 mb-1">مبلغ تخفیف:</span>
                <div class="text-sm">
                  <span class="tracking-widest">۲۱,۰۰۰</span>
                  <span class="text-xs mr-1">تومان</span>
                </div>
              </div>
            </template>
          </partial-card>
        </div>
      </template>
    </base-loading-panel>
  </div>

  <div class="mt-6">
    <h2 class="text-slate-400 mb-1">
      اطلاعات گیرنده
    </h2>

    <base-loading-panel
        type="list"
        :loading="loadingInfo"
    >
      <template #content>
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
          <partial-card class="border-0 p-3 sm:col-span-2">
            <template #body>
              <div class="flex flex-col">
                <span class="text-xs text-gray-400 mb-1">نام:</span>
                <div class="text-sm">
                  محمد مهدی دهقان منشادی
                </div>
              </div>
            </template>
          </partial-card>
          <partial-card class="border-0 p-3">
            <template #body>
              <div class="flex flex-col">
                <span class="text-xs text-gray-400 mb-1">شماره تماس:</span>
                <div class="text-sm tracking-widest">
                  09179516271
                </div>
              </div>
            </template>
          </partial-card>
          <partial-card class="border-0 p-3">
            <template #body>
              <div class="flex flex-col">
                <span class="text-xs text-gray-400 mb-1">کد ملی:</span>
                <div class="text-sm tracking-widest">
                  4420549033
                </div>
              </div>
            </template>
          </partial-card>
          <partial-card class="border-0 p-3">
            <template #body>
              <div class="flex flex-col">
                <span class="text-xs text-gray-400 mb-1">استان:</span>
                <div class="text-sm">
                  فارس
                </div>
              </div>
            </template>
          </partial-card>
          <partial-card class="border-0 p-3">
            <template #body>
              <div class="flex flex-col">
                <span class="text-xs text-gray-400 mb-1">شهر:</span>
                <div class="text-sm">
                  آباده
                </div>
              </div>
            </template>
          </partial-card>
          <partial-card class="border-0 p-3 sm:col-span-2">
            <template #body>
              <div class="flex flex-col">
                <span class="text-xs text-gray-400 mb-1">کد پستی:</span>
                <div class="text-sm tracking-widest">
                  1234567891
                </div>
              </div>
            </template>
          </partial-card>
          <partial-card class="border-0 p-3 sm:col-span-4">
            <template #body>
              <div class="flex flex-col">
                <span class="text-xs text-gray-400 mb-1">آدرس:</span>
                <div class="text-sm">
                  خونمونم فعلا
                </div>
              </div>
            </template>
          </partial-card>
        </div>
      </template>
    </base-loading-panel>
  </div>

  <div class="mt-6">
    <h2 class="text-slate-400 mb-1">
      وضعیت پرداخت
    </h2>

    <base-loading-panel
        type="list"
        :loading="loadingInfo"
    >
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

          <template #op="{value}">
            <a
                href="javascript:void(0)"
                class="border-0 text-blue-600 hover:text-opacity-80 text-sm p-2"
                @click="() => {showOrderPaymentDetail(value)}"
            >
              مشاهده جزئیات پرداخت
            </a>
          </template>
        </base-semi-datatable>

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
    </base-loading-panel>
  </div>

  <div class="mt-6">
    <h2 class="text-slate-400 mb-1">
      آیتم‌های سفارش
    </h2>

    <base-loading-panel
        type="table"
        :loading="loadingItems"
    >
      <template #content>
        <partial-card class="border-0 p-3 sm:col-span-4">
          <template #body>
            <ul class="divide-y divide-gray-100">
              <li class="relative flex flex-col md:flex-row mb-2 py-3 pr-3 pl-10">
                <div class="absolute left-0 top-1.5">
                  <base-floating-drop-down
                      placement="right-start"
                      :shift="false"
                      :items="[
                                            {
                                                text: 'ثبت دیدگاه',
                                            },
                                            {
                                                text: 'خرید مجدد کالا',
                                            },
                                        ]"
                  >
                    <template #button>
                      <button type="button"
                              class="text-gray-500 p-1 transition hover:text-black">
                        <EllipsisVerticalIcon class="h-6 w-6"/>
                      </button>
                    </template>

                    <template #item="{item, hide}">
                      <a
                          href="javascript:void(0)"
                          class="flex items-center w-full p-2 text-sm transition hover:bg-gray-100 rounded-md"
                          @click="hide()"
                      >
                        <span class="text-sm">{{ item.text }}</span>
                      </a>
                    </template>
                  </base-floating-drop-down>
                </div>

                <div class="shrink-0">
                  <router-link
                      :to="{name: 'product.detail', params: {id: 1}}"
                      class="inline-block"
                  >
                    <base-lazy-image
                        alt="تصویر محصول"
                        lazy-src="/src/assets/products/p1.jpg"
                        class="!w-24 h-auto hover:scale-95 transition"
                    />
                  </router-link>
                </div>
                <div class="grow mr-3">
                  <router-link
                      :to="{name: 'product.detail', params: {id: 1}}"
                      class="inline-block mb-2 text-blue-600 hover:text-opacity-90 leading-relaxed"
                  >
                    لپتاپ خیلی باحال و کاربردی عمو فردوس
                  </router-link>
                  <div class="flex flex-wrap mb-2 items-center">
                    <div
                        class="rounded-lg bg-rose-500 text-white py-1 px-2 my-1 ml-3 flex items-center justify-center">
                      <span class="text-xs">%</span>
                      <div class="mr-1 inline-block text-sm">10</div>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                      <div class="text-xl">
                        450,000
                        <span class="text-xs text-gray-400">تومان</span>
                      </div>

                      <div class="relative text-center">
                                                <span
                                                    class="absolute top-1/2 -translate-y-1/2 left-0 h-[1px] w-full bg-slate-500 -rotate-3"></span>
                        <div class="text-slate-500 text-sm">
                          500,000
                          <span class="text-xs text-gray-400">تومان</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="flex flex-wrap gap-2 items-center mb-2">
                    <div class="flex gap-1 items-center">
                      <span class="text-xl">2</span>
                      <span class="text-sm text-gray-400">عدد</span>
                    </div>
                  </div>
                </div>
                <div class="shrink-0 mr-3 text-sm md:w-72">
                  <div>
                    <span class="text-gray-600 ml-2 text-xs">گارانتی:</span>
                    با ضمانت سام سرویس
                  </div>
                </div>
              </li>
              <li class="relative flex flex-col md:flex-row mb-2 py-3 pr-3 pl-10">
                <div class="absolute left-0 top-1.5">
                  <base-floating-drop-down
                      placement="right-start"
                      :shift="false"
                      :items="[
                                            {
                                                text: 'ثبت دیدگاه',
                                            },
                                            {
                                                text: 'خرید مجدد کالا',
                                            },
                                        ]"
                  >
                    <template #button>
                      <button type="button"
                              class="text-gray-500 p-1 transition hover:text-black">
                        <EllipsisVerticalIcon class="h-6 w-6"/>
                      </button>
                    </template>

                    <template #item="{item, hide}">
                      <a
                          href="javascript:void(0)"
                          class="flex items-center w-full p-2 text-sm transition hover:bg-gray-100 rounded-md"
                          @click="hide()"
                      >
                        <span class="text-sm">{{ item.text }}</span>
                      </a>
                    </template>
                  </base-floating-drop-down>
                </div>

                <div class="shrink-0">
                  <router-link
                      :to="{name: 'product.detail', params: {id: 1}}"
                      class="inline-block"
                  >
                    <base-lazy-image
                        alt="تصویر محصول"
                        lazy-src="/src/assets/products/p2.jpg"
                        class="!w-24 h-auto hover:scale-95 transition"
                    />
                  </router-link>
                </div>
                <div class="grow mr-3">
                  <router-link
                      :to="{name: 'product.detail', params: {id: 1}}"
                      class="inline-block mb-2 text-blue-600 hover:text-opacity-90 leading-relaxed"
                  >
                    ماوس آبکی و مسخره‌ای که خیلی قیمت نداره ولی میتونی هر وقت خراب شد یکیشو بگیری
                    چون ارزونه مثلا
                  </router-link>
                  <div class="flex flex-wrap mb-2 items-center">
                    <div class="flex flex-wrap items-center gap-3">
                      <div class="text-xl">
                        270,000
                        <span class="text-xs text-gray-400">تومان</span>
                      </div>
                    </div>
                  </div>
                  <div class="flex flex-wrap gap-2 items-center mb-2">
                    <div class="flex gap-1 items-center">
                      <span class="text-xl">5</span>
                      <span class="text-sm text-gray-400">عدد</span>
                    </div>
                    <span class="py-1 px-2 text-xs border-2 border-yellow-500 rounded-full">مرجوع شده</span>
                  </div>
                </div>
                <div class="shrink-0 mr-3 text-sm md:w-72">
                  <div class="mb-2 flex items-center">
                    <span class="text-gray-600 ml-2 text-xs">رنگ:</span>
                    قهوه‌ای تیره
                    <span
                        v-tooltip.top="'قهوه‌ای تیره'"
                        class="inline-block w-5 h-5 rounded-full border mr-2"
                        style="background-color: #833406;"
                    ></span>
                  </div>
                  <div class="mb-2">
                    <span class="text-gray-600 ml-2 text-xs">سایز:</span>
                    2XL
                  </div>
                  <div>
                    <span class="text-gray-600 ml-2 text-xs">گارانتی:</span>
                    با ضمانت سام سرویس
                  </div>
                </div>
              </li>
              <li class="relative flex flex-col md:flex-row mb-2 py-3 pr-3 pl-10">
                <div class="absolute left-0 top-1.5">
                  <base-floating-drop-down
                      placement="right-start"
                      :shift="false"
                      :items="[
                                            {
                                                text: 'ثبت دیدگاه',
                                            },
                                            {
                                                text: 'خرید مجدد کالا',
                                            },
                                        ]"
                  >
                    <template #button>
                      <button type="button"
                              class="text-gray-500 p-1 transition hover:text-black">
                        <EllipsisVerticalIcon class="h-6 w-6"/>
                      </button>
                    </template>

                    <template #item="{item, hide}">
                      <a
                          href="javascript:void(0)"
                          class="flex items-center w-full p-2 text-sm transition hover:bg-gray-100 rounded-md"
                          @click="hide()"
                      >
                        <span class="text-sm">{{ item.text }}</span>
                      </a>
                    </template>
                  </base-floating-drop-down>
                </div>

                <div class="shrink-0">
                  <router-link
                      :to="{name: 'product.detail', params: {id: 1}}"
                      class="inline-block"
                  >
                    <base-lazy-image
                        alt="تصویر محصول"
                        lazy-src="/src/assets/products/p3.jpg"
                        class="!w-24 h-auto hover:scale-95 transition"
                    />
                  </router-link>
                </div>
                <div class="grow mr-3">
                  <router-link
                      :to="{name: 'product.detail', params: {id: 1}}"
                      class="inline-block mb-2 text-blue-600 hover:text-opacity-90 leading-relaxed"
                  >
                    لپتاپ خیلی باحال و کاربردی عمو فردوس
                  </router-link>
                  <div class="flex flex-wrap mb-2 items-center">
                    <div class="flex flex-wrap items-center gap-3">
                      <div class="text-xl">
                        50,000
                        <span class="text-xs text-gray-400">تومان</span>
                      </div>
                    </div>
                  </div>
                  <div class="flex flex-wrap gap-2 items-center mb-2">
                    <div class="flex gap-1 items-center">
                      <span class="text-xl">1</span>
                      <span class="text-sm text-gray-400">عدد</span>
                    </div>
                  </div>
                </div>
                <div class="shrink-0 mr-3 text-sm  md:w-72">
                  <div class="mb-2 flex items-center">
                    <span class="text-gray-600 ml-2 text-xs">رنگ:</span>
                    قرمز
                    <span
                        v-tooltip.top="'قرمز'"
                        class="inline-block w-5 h-5 rounded-full border mr-2"
                        style="background-color: #e44444;"
                    ></span>
                  </div>
                  <div>
                    <span class="text-gray-600 ml-2 text-xs">گارانتی:</span>
                    با ضمانت عمو سرویس
                  </div>
                </div>
              </li>
            </ul>
          </template>
        </partial-card>
      </template>
    </base-loading-panel>
  </div>
</template>

<script setup>
import {reactive, ref} from "vue";
import {EllipsisVerticalIcon, MinusIcon} from "@heroicons/vue/24/outline/index.js";
import PartialCard from "../../components/partials/PartialCard.vue";
import BaseLoadingPanel from "../../components/base/BaseLoadingPanel.vue";
import PartialBadgeStatusSend from "../../components/partials/PartialBadgeStatusSend.vue";
import BaseSemiDatatable from "../../components/base/BaseSemiDatatable.vue";
import PartialBadgeStatusPayment from "../../components/partials/PartialBadgeStatusPayment.vue";
import BaseDatatable from "../../components/base/BaseDatatable.vue";
import PartialDialog from "../../components/partials/PartialDialog.vue";
import BaseLazyImage from "../../components/base/BaseLazyImage.vue";
import BaseFloatingDropDown from "../../components/base/BaseFloatingDropDown.vue";

const loadingInfo = ref(false)
const loadingItems = ref(false)

const info = ref(null)
const items = ref(null)

//--------------------------------
// Order Payment stuffs
//--------------------------------
const orderPaymentDetailOpen = ref(false)
const ordersTableSetting = reactive({
  isLoading: false,
  columns: [
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
      field: 'op',
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

function showOrderPaymentDetail(item) {
  orderPaymentsTableSetting.rows = item.payments
  orderPaymentDetailOpen.value = true
}

//--------------------------------
</script>

<style scoped>

</style>
