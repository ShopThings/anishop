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
          <partial-username-label v-if="returnOrder?.user" :user="returnOrder?.user"/>
        </router-link>
      </div>

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
                :description="returnOrder?.not_accepted_description"
                :selected="returnStatus"
                @updated="fetchReturnOrder"
            />
          </div>
        </template>
      </partial-card>

      <div class="mb-3">
        <base-accordion :open="true">
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
                    <div class="text-black ">
                      <partial-badge-status-return-order
                          :color-hex="returnOrder?.status.color_hex"
                          :text="returnOrder?.status.text"
                      />
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
          >
            <template v-slot:product="{value}">
              <div class="flex flex-col gap-3">
                <partial-show-image :item="value.order_item.image"/>
                <span>{{ value.order_item.product_title }}</span>

                <ul class="flex flex-col gap-2.5">
                  <li v-if="value.order_item.color_name">
                    <partial-badge-color
                        :hex="value.order_item.color_hex"
                        :title="value.order_item.color_name"
                    />
                  </li>
                  <li v-if="value.order_item.size">
                    <partial-badge-size :title="value.order_item.size"/>
                  </li>
                  <li v-if="value.order_item.guarantee">
                    {{ value.guarantee }}
                  </li>
                </ul>
              </div>
            </template>

            <template v-slot:is_accepted="{value}">
              <partial-badge-publish
                  :publish="value.is_accepted"
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
import {numberFormat, getRouteParamByKey} from "@/composables/helper.js";
import {ReturnOrderAPI} from "@/service/APIOrder.js";
import PartialBadgeColor from "@/components/partials/PartialBadgeColor.vue";
import PartialBadgeSize from "@/components/partials/PartialBadgeSize.vue";
import PartialShowImage from "@/components/partials/filemanager/PartialShowImage.vue";
import PartialBadgePublish from "@/components/partials/PartialBadgePublish.vue";

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
function fetchReturnOrder() {
  ReturnOrderAPI.fetchById(idParam.value, {
    success: (response) => {
      returnOrder.value = response.data
      order.value = response.data.order_detail
      returnStatus.value = response.data.status

      loading.value = false
    },
  })
}

onMounted(() => {
  fetchReturnOrder()
})
</script>
