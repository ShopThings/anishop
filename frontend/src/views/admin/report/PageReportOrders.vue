<template>
  <base-loading-panel
    :loading="builderLoading"
    loading-text="در حال بارگذاری جستجوی پیشرفته"
    type="dot-orbit"
  >
    <template #content>
      <base-query-builder :columns="columns" :query="query"/>

      <partial-card class="mt-3">
        <template #body>
          <div class="flex flex-col sm:flex-row justify-end p-3">
            <base-button
              :disabled="filterApplyLoading"
              class="bg-primary rounded-r-lg rounded-l-lg sm:rounded-l-none border-primary text-sm my-1.5 sm:px-6"
              @click="filterQB"
            >
              <VTransitionFade>
                <loader-circle
                  v-if="filterApplyLoading"
                  big-circle-color="border-transparent"
                  main-container-klass="absolute w-full h-full top-0 left-0"
                />
              </VTransitionFade>

              <span>فیلتر اطلاعات</span>
            </base-button>

            <base-button
              :disabled="filterApplyLoading"
              class="bg-gray-200 !text-black border border-gray-300 rounded-l-lg rounded-r-lg sm:rounded-r-none text-sm my-1.5 sm:px-6"
              @click="clearQBFilter"
            >
              <VTransitionFade>
                <loader-circle
                  v-if="filterApplyLoading"
                  big-circle-color="border-transparent"
                  main-container-klass="absolute w-full h-full top-0 left-0"
                />
              </VTransitionFade>

              <span>حذف فیلتر</span>
            </base-button>
          </div>
        </template>
      </partial-card>
    </template>
  </base-loading-panel>

  <partial-card
    ref="tableContainer"
    class="mt-3"
  >
    <template #header>
      لیست سفارشات
    </template>

    <template #body>
      <div
        v-if="!loading"
        class="p-3"
      >
        <base-button
          :disabled="isDownloadExcel"
          class="bg-green-600 text-white mr-auto px-6 w-full sm:w-auto flex items-center"
          type="submit"
          @click="excelDownloadHandler"
        >
          <VTransitionFade>
            <loader-circle
              v-if="isDownloadExcel"
              big-circle-color="border-transparent"
              main-container-klass="absolute w-full h-full top-0 left-0"
            />
          </VTransitionFade>

          <TableCellsIcon class="h-6 w-6 ml-2"/>
          <span class="mx-auto">دانلود گزارش (فایل اکسل)</span>
        </base-button>
      </div>

      <base-loading-panel :loading="loading" type="table">
        <template #content>
          <base-datatable
            ref="datatable"
            :columns="table.columns"
            :enable-multi-operation="false"
            :enable-search-box="false"
            :has-checkbox="false"
            :is-loading="table.isLoading"
            :is-slot-mode="true"
            :rows="table.rows"
            :sortable="table.sortable"
            :total="table.totalRecordCount"
            @do-search="doSearch"
          >
            <template #code="{value}">
              <span class="tracking-widest font-iranyekan-bold">{{ value.code }}</span>
            </template>

            <template v-slot:user="{value}">
              <router-link
                :to="{name: 'admin.user.profile', params: {id: value.user.id}}"
                class="text-blue-600 hover:text-opacity-80"
              >
                <partial-username-label :user="value.user"/>
              </router-link>
            </template>

            <template v-slot:receiver_info="{value}">
              <base-button
                class="text-white bg-black text-sm !py-1"
                @click="showReceiverDetails(value)"
              >
                مشاهده
              </base-button>
            </template>

            <template #payment_status="{value}">
              <partial-badge-status-payment
                :color-hex="value.payment_status.color_hex"
                :text="value.payment_status.text"
              />
            </template>

            <template v-slot:send_status="{value}">
              <partial-badge-status-send
                :color-hex="value.send_status.color_hex"
                :text="value.send_status.title"
              />
            </template>

            <template v-slot:ordered_at="{value}">
              <span v-if="value.ordered_at" class="text-xs">{{ value.ordered_at }}</span>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>
          </base-datatable>

          <partial-dialog v-model:open="isDetailOpen">
            <template #title>
              اطلاعات گیرنده
            </template>

            <template #body>
              <ul class="divide-y">
                <li class="flex items-center gap-2 py-1.5">
                  <span class="text-slate-400 text-sm shrink-0">نام گیرنده:</span>
                  <span class="grow">{{ receiverInfo?.receiver_name || '-' }}</span>
                </li>
                <li class="flex items-center gap-2 py-1.5">
                  <span class="text-slate-400 text-sm shrink-0">شماره تماس:</span>
                  <span class="grow tracking-widest">{{ receiverInfo?.receiver_mobile || '-' }}</span>
                </li>
                <li class="flex items-center gap-2 py-1.5">
                  <span class="text-slate-400 text-sm shrink-0">استان:</span>
                  <span class="grow">{{ receiverInfo?.province || '-' }}</span>
                </li>
                <li class="flex items-center gap-2 py-1.5">
                  <span class="text-slate-400 text-sm shrink-0">شهر:</span>
                  <span class="grow">{{ receiverInfo?.city || '-' }}</span>
                </li>
                <li class="flex items-center gap-2 py-1.5">
                  <span class="text-slate-400 text-sm shrink-0">آدرس:</span>
                  <span class="grow">{{ receiverInfo?.address || '-' }}</span>
                </li>
                <li class="flex items-center gap-2 py-1.5">
                  <span class="text-slate-400 text-sm shrink-0">کدپستی:</span>
                  <span class="grow">{{ receiverInfo?.postal_code || '-' }}</span>
                </li>

                <li class="flex items-center gap-2 py-1.5 border-amber-300">
                  <span class="text-slate-400 text-sm shrink-0">توضیحات سفارش:</span>
                  <span class="grow">{{ receiverInfo?.description || '-' }}</span>
                </li>
              </ul>
            </template>
          </partial-dialog>
        </template>
      </base-loading-panel>
    </template>
  </partial-card>
</template>

<script setup>
import {inject, onMounted, reactive, ref} from "vue";
import BaseQueryBuilder from "@/components/base/BaseQueryBuilder.vue";
import {MinusIcon, TableCellsIcon} from "@heroicons/vue/24/outline/index.js"
import BaseDatatable from "@/components/base/BaseDatatable.vue"
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import {useToast} from "vue-toastification";
import BaseButton from "@/components/base/BaseButton.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import {ReportAPI} from "@/service/APIReport.js";
import PartialUsernameLabel from "@/components/partials/PartialUsernameLabel.vue";
import PartialBadgeStatusPayment from "@/components/partials/PartialBadgeStatusPayment.vue";
import PartialBadgeStatusSend from "@/components/partials/PartialBadgeStatusSend.vue";
import PartialDialog from "@/components/partials/PartialDialog.vue";

const toast = useToast()

const notificationStore = inject('notificationStore')

const builderLoading = ref(false)
const filterApplyLoading = ref(false)

const query = ref([])
const columns = ref([])

//-----------------------------------
// Export excel from query
//-----------------------------------
const isDownloadExcel = ref(false)

function excelDownloadHandler() {
  if (isDownloadExcel.value) return

  isDownloadExcel.value = true;
  ReportAPI.exportOrders({
    query: query.value,
  }, {
    success() {
      setTimeout(() => {
        notificationStore.$reset()
      }, 2000)
    },
    finally() {
      isDownloadExcel.value = false
    },
  })
}

//-----------------------------------
// Table stuffs
//-----------------------------------
const datatable = ref(null)
const tableContainer = ref(null)
const loading = ref(true)
const table = reactive({
  isLoading: false,
  columns: [
    {
      label: "#",
      field: "id",
      columnStyles: "width: 3%;",
      sortable: true,
      isKey: true,
    },
    {
      label: "کد سفارش",
      field: "code",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "سفارش دهنده",
      field: "user",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "اطلاعات گیرنده",
      field: "receiver_info",
    },
    {
      label: "وضعیت سفارش",
      field: "payment_status",
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "وضعیت ارسال",
      field: "send_status",
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "تاریخ سفارش",
      field: "ordered_at",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
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

  ReportAPI.fetchOrders({
    limit, offset, order, sort, text, query: query.value,
  }, {
    success: (response) => {
      table.rows = response.data
      table.totalRecordCount = response.meta.total

      return false
    },
    error: () => {
      table.rows = []
      table.totalRecordCount = 0
    },
    finally: () => {
      loading.value = false
      table.isLoading = false
      table.sortable.order = order
      table.sortable.sort = sort

      if (tableContainer.value && tableContainer.value.card)
        tableContainer.value.card.scrollIntoView({behavior: "smooth"})

      filterApplyLoading.value = false
    },
  })
}

doSearch(0, 15, 'id', 'desc')

//-----------------------------------
// Query-Builder stuffs
//-----------------------------------
function filterQB() {
  if (filterApplyLoading.value) return

  filterApplyLoading.value = true

  if (datatable.value?.reload) {
    datatable.value.reload()
  } else {
    doSearch(0, 15, 'id', 'desc')
  }
}

function clearQBFilter() {
  if (filterApplyLoading.value) return

  query.value = null

  // this time send empty query to apply no filters on records
  filterQB()
}

//---------------------------------------------
// Receiver detail operations
//---------------------------------------------
const receiverInfo = ref(null)
const isDetailOpen = ref(false)

function showReceiverDetails(value) {
  receiverInfo.value = value
  isDetailOpen.value = true
}

//---------------------------------------------
onMounted(() => {
  ReportAPI.getOrdersQB({
    success(response) {
      columns.value = response.data
    },
    finally() {
      builderLoading.value = false
    },
  })
})
</script>
