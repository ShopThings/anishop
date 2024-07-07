<template>
  <base-loading-panel
    :loading="loading"
    type="content"
  >
    <template #content>
      <div class="mb-3">
        <partial-card-navigation
          :to="{name: 'admin.order.detail', params: {id: order?.code}}"
          bg-color="bg-gradient-to-r from-cyan-500 to-indigo-500"
        >
          <span class="text-white text-lg grow">جزئیات سفارش</span>
          <ShoppingBagIcon class="h-12 w-12 text-white text-opacity-50 mr-3"/>
        </partial-card-navigation>
      </div>

      <div class="bg-white mb-3 rounded-lg border p-3">
        جزئیات سفارش مرجوعی به کد
        <span
          v-if="returnOrder?.id"
          class="text-slate-400 text-base"
        >{{ returnOrder?.code }}</span>
      </div>

      <div class="bg-white mb-3 rounded-lg border p-3">
        مرجوع کننده -
        <router-link
          v-if="returnOrder?.user.id"
          :to="{name: 'admin.user.profile', params: {id: returnOrder?.user.id}}"
          class="text-blue-600 hover:text-opacity-90"
        >
          <partial-username-label :user="returnOrder?.user"/>
        </router-link>
        <div v-else class="text-slate-600">
          <partial-username-label
            :user="{first_name: order?.first_name, last_name: order?.last_name, username: order?.mobile}"
          />
        </div>
      </div>

      <partial-card
        v-if="returnOrder?.is_in_end_status"
        class="mb-3 p-3"
      >
        <template #body>
          <div class="flex flex-wrap items-center gap-3">
            <div class="flex flex-col gap-2 text-sm">
              <div>
                لطفا وضعیت سفارش را در جزئیات سفارش در بالا، تغییر دهید.
              </div>
              <div class="text-amber-500">
                در صورت تغییر وضعیت سفارش، نیازی به بازگردانی محصولات به انبار از طریق دکمه روبرو نمی‌باشد!
              </div>
            </div>
            <base-button
              :disabled="returnOrdersItemsLoading"
              class="bg-primary text-white mr-auto px-4 !py-2 !text-sm w-full sm:w-auto"
              type="button"
              @click="returnOrderItemsHandler"
            >
              <VTransitionFade>
                <loader-circle
                  v-if="returnOrdersItemsLoading"
                  big-circle-color="border-transparent"
                  main-container-klass="absolute w-full h-full top-0 left-0"
                />
              </VTransitionFade>

              <span>بازگردانی محصولات مرجوعی به انبار</span>
            </base-button>
          </div>
        </template>
      </partial-card>

      <partial-card
        v-if="returnOrder?.wait_for_user || returnOrder?.is_in_end_status"
        class="mb-3"
      >
        <template #body>
          <div class="p-3">
            <partial-input-label title="توضیحات تکمیلی برای کاربر"/>
            <p class="text-slate-500 text-sm mt-2">
              {{ returnOrder?.admin_description }}
            </p>
          </div>
        </template>
      </partial-card>

      <partial-card
        v-if="!returnOrder?.wait_for_user"
        class="mb-3"
      >
        <template #header>
          تغییر وضعیت مرجوعی
        </template>
        <template #body>
          <div class="p-3">
            <form-return-order-change-status
              :description="returnOrder?.admin_description"
              @updated="statusChangeHandler"
            />
          </div>
        </template>
      </partial-card>

      <div class="mb-3">
        <base-accordion
          :open="true"
          open-btn-class="!bg-violet-50 !border-violet-500"
        >
          <template #button>
            جزئیات درخواست
          </template>
          <template #panel>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">کد مرجوعی:</span>
                    <div class="text-black tracking-widest">{{ returnOrder?.code }}</div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">وضعیت ارجاع:</span>
                    <div class="text-black">
                      <partial-badge-status-return-order
                        :color-hex="returnOrder?.status.color_hex"
                        :text="returnOrder?.status.text"
                      />
                    </div>
                    <div
                      v-if="returnOrder?.wait_for_user && !returnOrder?.is_in_end_status"
                      class="mt-2 text-orange-500 text-sm"
                    >
                      در انتظار اقدام کاربر
                    </div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">تاریخ درخواست:</span>
                    <div class="text-black">
                      {{ returnOrder?.requested_at }}
                    </div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3 sm:col-span-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">توضیحات کاربر:</span>
                    <div class="text-black">
                      {{ returnOrder?.description || '-' }}
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
      محصولات مرجوع شده
    </template>
    <template #body>
      <base-loading-panel :loading="loading" type="table">
        <template #content>
          <base-datatable
            :columns="table.columns"
            :enable-multi-operation="false"
            :enable-search-box="false"
            :has-checkbox="false"
            :is-slot-mode="true"
            :is-static-mode="true"
            :rows="table.rows"
            :total="table.total"
          >
            <template v-slot:id="{index}">
              {{ index }}
            </template>

            <template v-slot:product="{value}">
              <div class="flex flex-col gap-3">
                <router-link
                  v-if="value.order_item?.product"
                  :to="{name: 'product.detail', params: {slug: value.order_item.product.slug}}"
                  class="inline-block"
                  target="_blank"
                >
                  <base-lazy-image
                    :alt="value.order_item.product.title"
                    :is-local="false"
                    :lazy-src="value.order_item.product.image.path"
                    :size="FileSizes.SMALL"
                    class="!w-20 !h-20"
                  />
                </router-link>
                <base-lazy-image
                  v-else
                  :alt="value.order_item.product_title"
                  :is-local="false"
                  :lazy-src="value.order_item.image.path"
                  :size="FileSizes.SMALL"
                  class="!w-20 !h-20"
                />

                <router-link
                  v-if="value.order_item?.product"
                  :to="{name: 'product.detail', params: {slug: value.order_item.product.slug}}"
                  class="inline-block text-blue-600 hover:text-opacity-90 leading-relaxed"
                  target="_blank"
                >
                  {{ value.order_item.product.title }}
                </router-link>
                <div
                  v-else
                  class="inline-block mb-2 text-blue-600 hover:text-opacity-90 leading-relaxed"
                >
                  {{ value.order_item.product_title }}
                </div>

                <ul class="flex flex-col gap-2.5 text-xs rounded-lg p-2 border border-slate-200 text-black bg-white">
                  <li v-if="value.order_item.color_name" class="flex items-center gap-1.5">
                    <span class="text-gray-400">رنگ:</span>
                    <partial-badge-color :hex="value.order_item.color_hex" :title="value.order_item.color_name"/>
                  </li>
                  <li v-if="value.order_item.size" class="flex items-center gap-1.5">
                    <span class="text-gray-400">سایز:</span>
                    <partial-badge-size :title="value.order_item.size"/>
                  </li>
                  <li v-if="value.order_item.guarantee" class="flex items-center gap-1.5">
                    <span class="text-gray-400 text-xs">گارانتی:</span>
                    {{ value.order_item.guarantee }}
                  </li>
                </ul>
              </div>
            </template>

            <template v-slot:is_accepted="{value}">
              <base-switch-confirmation
                v-if="!returnOrder?.is_in_end_status"
                v-model="value.is_accepted"
                :api="ReturnOrderAPI"
                :parameters="[idParam, value.id]"
                api-method="modifyOrderItem"
                off-label="عدم تایید درخواست"
                on-label="تایید درخواست"
                success-message="وضعیت تایید مرجوع کالا تغییر یافت."
                update-key="is_accepted"
              />

              <partial-badge-publish
                v-else
                :publish="!!value.is_accepted"
                publish-text="تایید درخواست"
                unpublish-text="عدم تایید درخواست"
              />
            </template>

            <template v-slot:unit_price="{value}">
              <div class="text-lg font-iranyekan-bold">
                {{ numberFormat(value.order_item.unit_price) }}
                <span class="text-xs text-gray-400">تومان</span>
              </div>
            </template>

            <template v-slot:product_count="{value}">
                  <span class="text-black text-base px-2.5 rounded-lg border-2 border-teal-300 bg-teal-50">{{
                      value.return_quantity
                    }}</span>
              از
              <span class="text-black text-base px-2.5 rounded-lg border-2 border-teal-300 bg-teal-50">{{
                  value.order_item.quantity
                }}</span>
              {{ value.order_item.unit_name }}
            </template>

            <template v-slot:discounted_price="{value}">
              <div class="text-lg font-iranyekan-bold">
                {{ numberFormat(value.order_item.discounted_price) }}
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
import {onMounted, reactive, ref} from "vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import FormReturnOrderChangeStatus from "./forms/FormReturnOrderChangeStatus.vue";
import {ShoppingBagIcon} from "@heroicons/vue/24/outline/index.js";
import PartialCardNavigation from "@/components/partials/PartialCardNavigation.vue";
import {useToast} from "vue-toastification";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import BaseAccordion from "@/components/base/BaseAccordion.vue";
import BaseDatatable from "@/components/base/BaseDatatable.vue";
import PartialBadgeStatusReturnOrder from "@/components/partials/PartialBadgeStatusReturnOrder.vue";
import PartialUsernameLabel from "@/components/partials/PartialUsernameLabel.vue";
import {getRouteParamByKey, numberFormat} from "@/composables/helper.js";
import {ReturnOrderAPI} from "@/service/APIOrder.js";
import PartialBadgeColor from "@/components/partials/PartialBadgeColor.vue";
import PartialBadgeSize from "@/components/partials/PartialBadgeSize.vue";
import PartialBadgePublish from "@/components/partials/PartialBadgePublish.vue";
import BaseSwitchConfirmation from "@/components/base/BaseSwitchConfirmation.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {useConfirmToast} from "@/composables/toast-helper.js";
import {FileSizes} from "@/composables/file-list.js";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";

const toast = useToast()
const idParam = getRouteParamByKey('id', null, false)

const loading = ref(true)

const returnOrder = ref(null)
const order = ref(null)
const returnStatus = ref(null)

//-----------------------------------
// Table stuffs
//-----------------------------------
const datatable = ref(null)
const tableContainer = ref(null)
const table = reactive({
  columns: [
    {
      label: "#",
      field: "id",
      columnStyles: "width: 3%;",
      isKey: true,
    },
    {
      label: "مشخصات محصول مرجوع شده",
      field: "product",
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "وضعیت مرجوعی",
      field: "is_accepted",
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "تعداد مرجوع شده/تعداد خریداری شده",
      field: "product_count",
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "فی (به تومان)",
      field: "unit_price",
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "مبلغ نهایی (به تومان)",
      field: "discounted_price",
      columnClasses: 'whitespace-nowrap',
    },
  ],
  rows: [],
  total: 0,
  sortable: {
    order: "id",
    sort: "desc",
  },
})

//-----------------------------------
// Return order items operation
//-----------------------------------
const returnOrdersItemsLoading = ref(false)

function returnOrderItemsHandler() {
  useConfirmToast(() => {
      returnOrdersItemsLoading.value = true

      ReturnOrderAPI.returnOrderItemsToStock(idParam.value, {
        success() {
          toast.success('محصولات بازگشتی به انبار بازگردانده شد.')
        },
        finally() {
          returnOrdersItemsLoading.value = false
        },
      })
    },
    'بازگرداندن کالاهای مرجوع شده به انبار',
    'توجه داشته باشید، پس از انجام این عمل، وضعیت مرجوع را تغییر ندهید. در صورت تغییر وضعیت تمام مشکلات احتمالی به عهده خود شماست.'
  )
}

//-----------------------------------
function statusChangeHandler(status, description) {
  returnStatus.value.text = status.title
  returnStatus.value.value = status.code
  returnStatus.value.color_hex = status.color_hex

  returnOrder.value.admin_description = description
}

function fetchReturnOrder() {
  ReturnOrderAPI.fetchById(idParam.value, {
    success: (response) => {
      returnOrder.value = response.data
      order.value = response.data.order_detail
      returnStatus.value = response.data.status

      table.rows = response.data.items
      table.total = response.data.items?.length || 0

      loading.value = false
    },
  })
}

onMounted(() => {
  fetchReturnOrder()

  ReturnOrderAPI.updateById(idParam.value, {
    seen_status: true,
  }, {
    silent: true,
  })
})
</script>
