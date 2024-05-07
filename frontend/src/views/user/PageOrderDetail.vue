<template>
  <div v-if="!loadingInfo && redirectInfo && Object.keys(redirectInfo).length">
    <redirection-gateway-form
      :action="redirectInfo.action"
      :inputs="redirectInfo.inputs"
      :method="redirectInfo.method"
    />
  </div>

  <template v-else>
    <template v-if="!loadingInfo">
      <div
        v-if="info?.remained_pay_time"
        class="mt-3"
      >
        <div class="flex justify-end">
          <div
            v-if="countdown && countdown.isStarted"
            class="rounded-lg flex items-center gap-3 py-2 px-4 bg-white border-2 border-emerald-500"
          >
            <span class="text-sm">زمان باقیمانده رزرو:</span>
            <div
              ref="timerRef"
              class="min-w-12 text-left text-emerald-700"
            >00:00
            </div>
          </div>

          <div
            v-else
            class="rounded-lg flex items-center gap-3 py-2 px-4 !bg-rose-50 border-2 border-rose-500"
          >
            <span class="text-sm">اتمام زمان رزور</span>
          </div>
        </div>

        <template
          v-for="(item, idx) in info.value.orders"
          v-if="info?.orders?.length"
          :key="item.id"
        >
          <partial-card
            v-if="!item.has_paid && item.is_waited_for_pay"
            class="border-0 p-3 mt-3"
          >
            <template #body>
              <partial-pay-card
                :card-number="idx + 1"
                :loading="paymentLoadings[id]"
                :method-title="item.payment_method"
                :price="item.price"
                @click="payLinkHandler(item.id)"
              />
            </template>
          </partial-card>
        </template>
      </div>

      <div class="my-3">
        <partial-badge-status-send
          :color-hex="info?.send_status?.color_hex"
          :text="info?.send_status?.title"
          class="w-full py-2 !text-sm"
        />
      </div>

      <div
        v-if="info?.description"
        class="mt-3 border-2 border-cyan-400 p-3 rounded-lg bg-white"
      >
        {{ info?.description }}
      </div>
    </template>
    <div
      v-else
      class="flex gap-2 items-center justify-center bg-slate-300 animate-pulse w-full h-8 rounded my-3"
    >
      <div class="w-36 h-3 rounded bg-orange-300"></div>
      <div class="w-8 h-3 rounded bg-orange-300"></div>
    </div>

    <div>
      <h2 class="text-slate-400 mb-1">
        اطلاعات سفارش
      </h2>

      <base-loading-panel
        :loading="loadingInfo"
        type="list"
      >
        <template #content>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <partial-card class="border-0 p-3">
              <template #body>
                <div class="flex flex-col">
                  <span class="text-xs text-gray-400 mb-1">شماره سفارش:</span>
                  <div class="text-sm tracking-widest">
                    {{ info?.code }}
                  </div>
                </div>
              </template>
            </partial-card>
            <partial-card class="border-0 p-3">
              <template #body>
                <div class="flex flex-col">
                  <span class="text-xs text-gray-400 mb-1">در تاریخ:</span>
                  <div class="text-sm">
                    {{ info?.ordered_at }}
                  </div>
                </div>
              </template>
            </partial-card>
            <partial-card class="border-0 p-3">
              <template #body>
                <div class="flex flex-col">
                  <span class="text-xs text-gray-400 mb-1">روش ارسال:</span>
                  <div class="text-sm">
                    {{ info?.send_method_title }}
                  </div>
                </div>
              </template>
            </partial-card>
            <partial-card class="border-0 p-3">
              <template #body>
                <div class="flex flex-col">
                  <span class="text-xs text-gray-400 mb-1">درخواست فاکتور:</span>
                  <div class="text-sm">
                    <span v-if="info?.is_needed_factor">بله</span>
                    <span v-else>خیر</span>
                  </div>
                </div>
              </template>
            </partial-card>
            <partial-card class="border-0 p-3">
              <template #body>
                <div class="flex flex-col">
                  <span class="text-xs text-gray-400 mb-1">کد تخفیف:</span>
                  <div class="text-sm">
                    <span v-if="info?.coupon_code" class="tracking-widest">{{ info?.coupon_code }}</span>
                    <MinusIcon v-else class="w-6 h-6 text-rose-500"/>
                  </div>
                </div>
              </template>
            </partial-card>
            <partial-card class="border-0 p-3">
              <template #body>
                <div class="flex flex-col">
                  <span class="text-xs text-gray-400 mb-1">مبلغ کوپن تخفیف:</span>
                  <div class="text-sm">
                    <template v-if="info?.coupon_price">
                      <span class="tracking-widest">{{ numberFormat(info?.coupon_price) }}</span>
                      <span class="text-xs mr-1">تومان</span>
                    </template>
                    <MinusIcon v-else class="w-6 h-6 text-rose-500"/>
                  </div>
                </div>
              </template>
            </partial-card>
            <partial-card class="border-0 p-3">
              <template #body>
                <div class="flex flex-col">
                  <span class="text-xs text-gray-400 mb-1">مبلغ قابل پرداخت:</span>
                  <div class="text-sm">
                    <span class="tracking-widest">{{ numberFormat(info?.final_price) }}</span>
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
                    <template v-if="info?.shipping_price && info?.shipping_price > 0">
                      <span class="tracking-widest">{{ numberFormat(info?.shipping_price) }}</span>
                      <span class="text-xs mr-1">تومان</span>
                    </template>
                    <span v-else>رایگان</span>
                  </div>
                </div>
              </template>
            </partial-card>
            <partial-card class="border-0 p-3">
              <template #body>
                <div class="flex flex-col">
                  <span class="text-xs text-gray-400 mb-1">مبلغ کل:</span>
                  <div class="text-sm">
                    <span class="tracking-widest">{{ numberFormat(info?.total_price) }}</span>
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
                  <span class="tracking-widest">{{
                      numberFormat(info?.total_price - info?.final_price)
                    }}</span>
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
        :loading="loadingInfo"
        type="list"
      >
        <template #content>
          <form @submit.prevent="onSubmit">
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
              <partial-card class="border-0 p-3 sm:col-span-2">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">نام:</span>
                    <template v-if="info?.send_status?.is_starting_badge">
                      <base-input
                        :in-edit-mode="false"
                        :is-editable="true"
                        :value="info?.receiver_name"
                        name="receiver_name"
                      />
                      <partial-input-error-message :error-message="errors?.receiver_name"/>
                    </template>
                    <div
                      v-else
                      class="text-sm"
                    >
                      {{ info?.receiver_name }}
                    </div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="border-0 p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">شماره تماس:</span>
                    <template v-if="info?.send_status?.is_starting_badge">
                      <base-input
                        :in-edit-mode="false"
                        :is-editable="true"
                        :value="info?.receiver_mobile"
                        name="receiver_mobile"
                      />
                      <partial-input-error-message :error-message="errors?.receiver_mobile"/>
                    </template>
                    <div
                      v-else
                      class="text-sm tracking-widest"
                    >
                      {{ info?.receiver_mobile }}
                    </div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="border-0 p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">کد ملی:</span>
                    <div class="text-sm tracking-widest">
                      {{ info?.national_code }}
                    </div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="border-0 p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">استان:</span>
                    <div class="text-sm">
                      {{ info?.province }}
                    </div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="border-0 p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">شهر:</span>
                    <div class="text-sm">
                      {{ info?.city }}
                    </div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="border-0 p-3 sm:col-span-2">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">کد پستی:</span>
                    <template v-if="info?.send_status?.is_starting_badge">
                      <base-input
                        :in-edit-mode="false"
                        :is-editable="true"
                        :value="info?.postal_code"
                        name="postal_code"
                      />
                      <partial-input-error-message :error-message="errors?.postal_code"/>
                    </template>
                    <div
                      v-else
                      class="text-sm tracking-widest"
                    >
                      {{ info?.postal_code }}
                    </div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="border-0 p-3 sm:col-span-4">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">آدرس:</span>
                    <template v-if="info?.send_status?.is_starting_badge">
                      <base-textarea
                        :in-edit-mode="false"
                        :is-editable="true"
                        :value="info?.address"
                        name="address"
                      />
                      <partial-input-error-message :error-message="errors?.address"/>
                    </template>
                    <div
                      v-else
                      class="text-sm"
                    >
                      {{ info?.address }}
                    </div>
                  </div>
                </template>
              </partial-card>
            </div>

            <div
              v-if="!info?.send_status?.is_starting_badge"
              class="px-2 py-3"
            >
              <base-animated-button
                :disabled="!canSubmit"
                class="bg-emerald-500 text-white mr-auto px-6 w-full text-sm sm:w-auto"
                type="submit"
              >
                <VTransitionFade>
                  <loader-circle
                    v-if="!canSubmit"
                    big-circle-color="border-transparent"
                    main-container-klass="absolute w-full h-full top-0 left-0"
                  />
                </VTransitionFade>

                <template #icon="{klass}">
                  <CheckIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
                </template>

                <span class="ml-auto">تغییر مشخصات ارسال</span>
              </base-animated-button>

              <div
                v-if="Object.keys(errors)?.length"
                class="text-left"
              >
                <div
                  class="w-full sm:w-auto sm:inline-block text-center text-sm border-2 border-rose-500 bg-rose-50 rounded-full py-1 px-3 mt-2"
                >
                  (
                  <span>{{ Object.keys(errors)?.length }}</span>
                  )
                  خطا، لطفا بررسی کنید
                </div>
              </div>
            </div>
          </form>
        </template>
      </base-loading-panel>
    </div>

    <div class="mt-6">
      <h2 class="text-slate-400 mb-1">
        وضعیت پرداخت
      </h2>

      <base-loading-panel
        :loading="loadingInfo"
        type="list"
      >
        <template #content>
          <base-semi-datatable
            :columns="ordersTableSetting.columns"
            :is-loading="ordersTableSetting.isLoading"
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
              <div class="text-lg font-iranyekan-bold">
                {{ numberFormat(value.must_pay_price) }}
                <span class="text-xs text-gray-400">تومان</span>
              </div>
            </template>

            <template #payment_method_title="{value}">
              <div class="flex">
                <span class="text-sm">{{ value.payment_method_title }}</span>
                <span class="mr-2 rounded bg-amber-200 py-0.5 px-2">{{ value.payment_method_type.text }}</span>
              </div>
            </template>

            <template #payment_status_changed_at="{value}">
            <span v-if="value.payment_status_changed_at"
                  class="text-xs">{{ value.payment_status_changed_at }}</span>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>

            <template #op="{value}">
              <a
                class="border-0 text-blue-600 hover:text-opacity-80 text-sm p-2"
                href="javascript:void(0)"
                @click="() => {showOrderPaymentDetail(value)}"
              >
                مشاهده جزئیات پرداخت
              </a>
            </template>
          </base-semi-datatable>

          <partial-dialog v-model:open="orderPaymentDetailOpen">
            <template #title>
              جزئیات پرداخت
            </template>

            <template #body>
              <base-datatable
                :columns="orderPaymentsTableSetting.columns"
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
      </base-loading-panel>
    </div>

    <div class="mt-6">
      <h2 class="text-slate-400 mb-1">
        آیتم‌های سفارش
      </h2>

      <base-loading-panel
        :loading="loadingItems"
        type="table"
      >
        <template #content>
          <partial-card class="border-0 p-3 sm:col-span-4">
            <template #body>
              <ul
                v-if="items?.length"
                class="divide-y divide-gray-100"
              >
                <li
                  v-for="product in items"
                  :key="product.id"
                  class="relative flex flex-col md:flex-row mb-2 py-3 pr-3 pl-10"
                >
                  <div class="absolute left-0 top-1.5">
                    <base-floating-drop-down
                      :items="[
                        {
                            text: 'ثبت دیدگاه',
                            operation: 'comment',
                        },
                        {
                            text: 'خرید مجدد کالا',
                            operation: 'buy',
                        },
                    ]"
                      :shift="false"
                      placement="right-start"
                    >
                      <template #button>
                        <button
                          class="text-gray-500 p-1 transition hover:text-black"
                          type="button"
                        >
                          <EllipsisVerticalIcon class="h-6 w-6"/>
                        </button>
                      </template>

                      <template #item="{item, hide}">
                        <button
                          class="flex items-center w-full p-2 text-sm transition hover:bg-gray-100 rounded-md"
                          type="button"
                          @click="handleOrderItemOperation(product, item, hide)"
                        >
                          <span class="text-sm">{{ item.text }}</span>
                        </button>
                      </template>
                    </base-floating-drop-down>
                  </div>

                  <div class="shrink-0">
                    <router-link
                      v-if="product?.product"
                      :to="{name: 'product.detail', params: {slug: product.product.slug}}"
                      class="inline-block"
                    >
                      <base-lazy-image
                        :alt="product.product.title"
                        :lazy-src="product.image.path"
                        :size="FileSizes.SMALL"
                        class="!w-36 md:!w-24 h-auto hover:scale-95 transition"
                      />
                    </router-link>
                    <div
                      v-else
                      class="inline-block text-center size-36 border rounded md:size-24 hover:scale-95 transition"
                    >
                      <div class="flex flex-col justify-center gap-2 h-full">
                        <PhotoIcon class="size-8 text-rose-300 mx-auto"/>
                        <span class="text-xs text-slate-400">فاقد تصویر</span>
                      </div>
                    </div>
                  </div>

                  <div class="grow mr-3">
                    <router-link
                      v-if="product?.product"
                      :to="{name: 'product.detail', params: {slug: product.product.slug}}"
                      class="inline-block mb-2 text-blue-600 hover:text-opacity-90 leading-relaxed"
                    >
                      {{ product.product_title }}
                    </router-link>
                    <div
                      v-else
                      class="inline-block mb-2 text-blue-600 hover:text-opacity-90 leading-relaxed"
                    >
                      {{ product.product_title }}
                    </div>

                    <div class="flex flex-wrap mb-2 items-center">
                      <div
                        v-if="product.discounted_price && getPercentageOfPortion(product.discounted_price, product.price) > 0"
                        class="rounded-lg bg-rose-500 text-white py-1 px-2 my-1 ml-3 flex items-center justify-center"
                      >
                        <span class="text-xs">%</span>
                        <div class="mr-1 inline-block text-sm">
                          {{ getPercentageOfPortion(product.discounted_price, product.price) }}
                        </div>
                      </div>

                      <div class="flex flex-wrap items-center gap-3">
                        <div class="text-xl">
                          <template v-if="product.discounted_price">
                            {{ numberFormat(product.discounted_price) }}
                          </template>
                          <template v-else>
                            {{ numberFormat(product.price) }}
                          </template>
                          <span class="text-xs text-gray-400">تومان</span>
                        </div>

                        <div
                          v-if="product.discounted_price"
                          class="relative text-center"
                        >
                        <span
                          class="absolute top-1/2 -translate-y-1/2 left-0 h-[1px] w-full bg-slate-500 -rotate-3"></span>
                          <div class="text-slate-500 text-sm">
                            {{ numberFormat(product.price) }}
                            <span class="text-xs text-gray-400">تومان</span>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="flex flex-wrap gap-2 items-center mb-2">
                      <div class="flex gap-1 items-center">
                        <span class="text-xl">{{ product.quantity }}</span>
                        <span class="text-sm text-gray-400">{{ product.unit_name }}</span>
                      </div>

                      <span v-if="product.is_returned"
                            class="py-1 px-2 text-xs border-2 border-yellow-500 rounded-full">مرجوع شده</span>
                    </div>
                  </div>

                  <div class="shrink-0 mr-3 text-sm md:w-72">
                    <div
                      v-if="product.color_name"
                      class="mb-2 flex items-center"
                    >
                      <span class="text-gray-600 ml-2 text-xs">رنگ:</span>
                      {{ product.color_name }}
                      <span
                        v-tooltip.top="'' + product.color_name + ''"
                        :style="'background-color:' + product.color_hex"
                        class="inline-block w-5 h-5 rounded-full border mr-2"
                      ></span>
                    </div>
                    <div
                      v-if="product.size"
                      class="mb-2"
                    >
                      <span class="text-gray-600 ml-2 text-xs">سایز:</span>
                      {{ product.size }}
                    </div>
                    <div v-if="product.guarantee">
                      <span class="text-gray-600 ml-2 text-xs">گارانتی:</span>
                      {{ product.guarantee }}
                    </div>
                  </div>
                </li>
              </ul>

              <div
                v-else
                class="text-orange-300 text-center p-2"
              >
                هیچ محصولی خریداری نشده است!
              </div>
            </template>
          </partial-card>
        </template>
      </base-loading-panel>
    </div>
  </template>
</template>

<script setup>
import {inject, onMounted, reactive, ref} from "vue";
import {CheckIcon, EllipsisVerticalIcon, MinusIcon, PhotoIcon} from "@heroicons/vue/24/outline/index.js";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialBadgeStatusSend from "@/components/partials/PartialBadgeStatusSend.vue";
import BaseSemiDatatable from "@/components/base/BaseSemiDatatable.vue";
import PartialBadgeStatusPayment from "@/components/partials/PartialBadgeStatusPayment.vue";
import BaseDatatable from "@/components/base/BaseDatatable.vue";
import PartialDialog from "@/components/partials/PartialDialog.vue";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import BaseFloatingDropDown from "@/components/base/BaseFloatingDropDown.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseTextarea from "@/components/base/BaseTextarea.vue";
import {getPercentageOfPortion, getRouteParamByKey, numberFormat} from "@/composables/helper.js";
import {UserPanelOrderAPI} from "@/service/APIUserPanel.js";
import PartialBadgePublish from "@/components/partials/PartialBadgePublish.vue";
import {FileSizes} from "@/composables/file-list.js";
import {useRouter} from "vue-router";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import yup, {transformNumbersToEnglish} from "@/validation/index.js";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import {useToast} from "vue-toastification";
import {useCountdown} from "@/composables/countdown-timer.js";
import PartialPayCard from "@/components/partials/pages/PartialPayCard.vue";
import {HomeCheckoutAPI} from "@/service/APIHomePages.js";
import RedirectionGatewayForm from "@/components/RedirectionGatewayForm.vue";

const router = useRouter()
const toast = useToast()
const codeParam = getRouteParamByKey('code')

const cartStore = inject('cartStore')

const info = ref(null)
const loadingInfo = ref(true)

const items = ref(null)
const loadingItems = ref(true)

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
      field: 'payment_method_title',
      label: 'شیوه پرداخت',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'payment_status_changed_at',
      label: 'تاریخ تغییر وضعیت',
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
  total: 0,
  sortable: {
    order: "created_at",
    sort: "desc",
  },
})

function showOrderPaymentDetail(item) {
  orderPaymentsTableSetting.rows = item.payments
  orderPaymentsTableSetting.total = item.payments?.length || 0
  orderPaymentDetailOpen.value = true
}

//--------------------------------
// Order items operations
//--------------------------------
function handleOrderItemOperation(product, item, hide) {
  hide()

  if (!item?.operation || !product?.product_code) return

  // [comment, buy]
  if (item.operation === 'comment') {
    if (product?.product?.slug) {
      router.push({name: 'user.comment.add', params: {slug: product.product.slug}})
    }
  } else if (item.operation === 'buy') {
    cartStore.addItem(product.product_code)
  }
}

//--------------------------------
const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    address: yup.string().required('آدرس خود را وارد نمایید.'),
    postal_code: yup.number().required('کدپستی را وارد نمایید.'),
    receiver_name: yup.string()
      .persian('نام گیرنده باید از حروف فارسی باشد.')
      .required('نام گیرنده را وارد نمایید.'),
    receiver_mobile: yup.string()
      .transform(transformNumbersToEnglish)
      .persianMobile('شماره موبایل گیرنده نامعتبر است.')
      .required('موبایل گیرنده را وارد نمایید.'),
  }),
}, (values, actions) => {
  if (!info.value?.send_status?.is_starting_badge) {
    toast.info('امکان تغییر وجود ندارد.')
  }

  if (!info.value?.send_status?.is_starting_badge) return

  canSubmit.value = false

  UserPanelOrderAPI.updateById(codeParam.value, {
    address: values.address,
    postal_code: values.postal_code,
    receiver_name: values.receiver_name,
    receiver_mobile: values.receiver_mobile,
  }, {
    success(response) {
      toast.success('ویرایش اطلاعات ارسال با موفقیت انجام شد.')

      info.value.address = response.data.address
      info.value.postal_code = response.data.postal_code
      info.value.receiver_name = response.data.receiver_name
      info.value.receiver_mobile = response.data.receiver_mobile
    },
    error(error) {
      if (error?.errors && Object.keys(error.errors).length >= 1) {
        actions.setErrors(error.errors)
      }
    },
    finally() {
      canSubmit.value = true
    },
  })
})

//--------------------------------
const timerRef = ref(null)
const countdown = ref(null)
const redirectInfo = ref(null)

const paymentLoadings = reactive({})

function payLinkHandler(id) {
  if (
    !info.value?.code ||
    paymentLoadings[id] === true
  ) return

  paymentLoadings[id] = true

  HomeCheckoutAPI.payOrder(id, info.value.code, {
    success(response) {
      redirectInfo.value = response.data
    },
    finally() {
      paymentLoadings[id] = false
    },
  })
}

onMounted(() => {
  UserPanelOrderAPI.fetchById(codeParam.value, {
    success(response) {
      info.value = response.data
      items.value = response.data.items

      ordersTableSetting.rows = response.data.orders
      ordersTableSetting.total = response.data.orders?.length || 0

      loadingInfo.value = false
      loadingItems.value = false

      countdown.value = useCountdown(response.data.remained_pay_time || 0, timerRef)

      countdown.value.start(() => {
        countdown.value.stop()
      })
    },
  })
})
</script>
