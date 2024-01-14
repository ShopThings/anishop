<template>
  <partial-card ref="tableContainer">
    <template #header>
      نمایش سفارشات کاربر -
      <span
        v-if="user?.id"
        class="text-teal-600"
      >{{
          (user?.first_name || user?.last_name) ? (user?.first_name + ' ' + user?.last_name).trim() : user.username
        }}</span>
    </template>

    <template #body>
      <base-loading-panel :loading="loading" type="table">
        <template #content>
          <base-datatable
            ref="datatable"
            :enable-search-box="true"
            :enable-multi-operation="false"
            :is-slot-mode="true"
            :is-loading="table.isLoading"
            :selection-columns="table.selectionColumns"
            :columns="table.columns"
            :rows="table.rows"
            :has-checkbox="false"
            :total="table.totalRecordCount"
            :sortable="table.sortable"
            @do-search="doSearch"
          >
            <template v-slot:code="{value}">

            </template>
            <template v-slot:receiver_info="{value}">
              <base-button
                class="text-white bg-black text-sm !py-1"
                @click="showReceiverDetails(value)"
              >
                مشاهده
              </base-button>
            </template>
            <template v-slot:order_status="{value}">

            </template>
            <template v-slot:send_status="{value}">

            </template>
            <template v-slot:op="{value}">
              <base-datatable-menu :items="operations" :data="value" :container="getMenuContainer"/>
            </template>
          </base-datatable>
        </template>
      </base-loading-panel>
    </template>
  </partial-card>
</template>

<script setup>
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseDatatable from "@/components/base/BaseDatatable.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import {useRoute, useRouter} from "vue-router";
import {useToast} from "vue-toastification";
import {computed, onMounted, reactive, ref} from "vue";
import {useRequest} from "@/composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "@/router/api-routes.js";
import BaseDatatableMenu from "@/components/base/datatable/BaseDatatableMenu.vue";
import BaseButton from "@/components/base/BaseButton.vue";

const router = useRouter()
const route = useRoute()
const toast = useToast()
const idParam = computed(() => {
  const id = parseInt(route.params.id, 10)
  if (isNaN(id)) return route.params.id
  return id
})

const user = ref(null)

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
      sortable: true,
    },
    {
      label: "اسلاعات گیرنده",
      field: "receiver_info",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "وضعیت سفارش",
      field: "order_status",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "وضعیت ارسال",
      field: "send_status",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "تاریخ سفارش",
      field: "ordered_at",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: 'عملیات',
      field: 'op',
      width: '7%',
    },
  ],
  rows: [],
  totalRecordCount: 0,
  sortable: {
    order: "id",
    sort: "desc",
  },
})

const getMenuContainer = computed(() => {
  return datatable.value?.tableContainer ?? 'body'
})

const operations = [
  {
    link: {
      text: 'مشاهده جزئیات',
      icon: 'EyeIcon',
    },
    event: {
      click: (data) => {
        router.push({
          name: 'admin.order.detail',
          params: {
            id: data.id,
          }
        })
      },
    },
  },
]

function showReceiverDetails(value) {
  // show details of receiver detail
  // ...
}

const doSearch = (offset, limit, order, sort, text) => {
  table.isLoading = true

  // useRequest(apiReplaceParams(apiRoutes.admin.orders.index, {user: idParam.value}), {
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
  loading.value = false
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

onMounted(() => {
  useRequest(apiReplaceParams(apiRoutes.admin.users.show, {user: idParam.value}), null, {
    success: (response) => {
      user.value = response.data
    },
  })
})
</script>
