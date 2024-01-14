<template>
  <base-loading-panel
    :loading="loading"
    type="content"
  >
    <template #content>
      <div class="mb-3">
        <partial-card-navigation
          :to="{name: 'admin.order.detail', params: {id: order?.id}}"
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
          class="text-teal-600"
        >{{ returnOrder?.code }}</span>
      </div>

      <div class="bg-white mb-3 rounded-lg border p-3">
        مرجوع کننده -
        <router-link
          v-if="order?.user.id"
          :to="{name: 'admin.user.profile', params: {id: order?.user.id}}"
          class="text-blue-600 hover:text-opacity-90"
        >{{
            (order?.user?.first_name || order?.user?.last_name) ? (order?.user?.first_name + ' ' + order?.user?.last_name).trim() : order?.user.username
          }}
        </router-link>
      </div>

      <partial-card class="mb-3">
        <template #header>
          تغییر وضعیت مرجوعی
        </template>
        <template #body>
          <div class="p-3">
            <form-return-order-change-status
              v-model:selected="returnStatus"
            />
          </div>
        </template>
      </partial-card>

      <div class="mb-3">
        <base-accordion :open="false">
          <template #button>
            جزئیات درخواست
          </template>
          <template #panel>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">کد مرجوعی:</span>
                    <div class="text-black tracking-widest">818308177278782</div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">وضعیت ارجاع:</span>
                    <div class="text-black ">
                      <partial-badge-status-return-order/>
                    </div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">تاریخ درخواست:</span>
                    <div class="text-black">
                      ۳۰ مرداد ۱۴۰۲ در ساعت ۱۹ و ۳۴ دقیقه
                    </div>
                  </div>
                </template>
              </partial-card>
              <partial-card class="p-3 sm:col-span-3">
                <template #body>
                  <div class="flex flex-col">
                    <span class="text-xs text-gray-400 mb-1">توضیحات کاربر:</span>
                    <div class="text-black">
                      چون یزد است و چند روز طول می کشه
                    </div>
                  </div>
                </template>
              </partial-card>
            </div>
          </template>
        </base-accordion>
      </div>

      <partial-card>
        <template #header>
          محصولات مرجوع شده
        </template>
        <template #body>
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
                <template v-slot:discounted_price="{value}">

                </template>
              </base-datatable>
            </template>
          </base-loading-panel>
        </template>
      </partial-card>
    </template>
  </base-loading-panel>
</template>

<script setup>
import {computed, onMounted, reactive, ref} from "vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import FormReturnOrderChangeStatus from "./forms/FormReturnOrderChangeStatus.vue";
import {ShoppingBagIcon} from "@heroicons/vue/24/outline/index.js";
import PartialCardNavigation from "@/components/partials/PartialCardNavigation.vue";
import {useToast} from "vue-toastification";
import {useRoute} from "vue-router";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import BaseAccordion from "@/components/base/BaseAccordion.vue";
import BaseDatatable from "@/components/base/BaseDatatable.vue";
import PartialBadgeStatusReturnOrder from "@/components/partials/PartialBadgeStatusReturnOrder.vue";

const route = useRoute()
const toast = useToast()
const idParam = computed(() => {
  const id = parseInt(route.params.id, 10)
  if (isNaN(id)) return route.params.id
  return id
})

const loading = ref(false)

const returnOrder = ref(null)
const order = ref(null)
const returnStatus = ref(null)

onMounted(() => {
  // useRequest(apiReplaceParams(apiRoutes.admin.returnOrders.show, {return_order: idParam.value}), null, {
  //     success: (response) => {
  //         returnOrder.value = response.data
  //         order.value = response.data.order
  //         returnStatus.value = response.data.status
  //
  //         loading.value = false
  //     },
  // })
})

//-----------------------------------
// Table stuffs
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
      label: "مشخصات محصول مرجوع شده",
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
