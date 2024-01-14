<template>
  <partial-card ref="tableContainer">
    <template #header>
      نمایش آدرس‌های کاربر -
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
            :enable-search-box="false"
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
            <template #city_name="{value}">
              {{ value.city.name }}
            </template>
            <template #province_name="{value}">
              {{ value.province.name }}
            </template>

            <template v-slot:op="{value}">
              <base-button
                class="text-white bg-black text-sm !py-1"
                @click="showDetails(value)"
              >
                مشاهده جزئیات
              </base-button>
            </template>

            <template v-slot:created_at="{value}">
              <span v-if="value.created_at" class="text-xs">{{ value.created_at }}</span>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>
          </base-datatable>

          <partial-dialog v-model:open="isDetailOpen">
            <template #title>
              جزئیات آدرس
            </template>

            <template #body>
              {{ detailItem }}
            </template>
          </partial-dialog>
        </template>
      </base-loading-panel>
    </template>
  </partial-card>
</template>

<script setup>
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseDatatable from "@/components/base/BaseDatatable.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import {MinusIcon} from "@heroicons/vue/24/outline/index.js";
import {onMounted, reactive, ref} from "vue";
import BaseButton from "@/components/base/BaseButton.vue";
import {useRoute} from "vue-router";
import {UserAddressAPI, UserAPI} from "@/service/APIUser.js";
import {getRouteParamByKey} from "@/composables/helper.js";
import PartialDialog from "@/components/partials/PartialDialog.vue";

const route = useRoute()
const idParam = getRouteParamByKey('id')

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
      label: "نام و نام خانوادگی",
      field: "full_name",
      sortable: true,
    },
    {
      label: "شماره موبایل",
      field: "mobile",
      sortable: true,
    },
    {
      label: "استان",
      field: "province_name",
      sortable: true,
    },
    {
      label: "شهر",
      field: "city_name",
      sortable: true,
    },
    {
      label: '',
      field: 'op',
      width: '7%',
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "تاریخ ایجاد",
      field: "created_at",
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

const detailItem = ref(null)
const isDetailOpen = ref(false)

function showDetails(value) {
  isDetailOpen.value = true
  detailItem.value = value
}

const doSearch = (offset, limit, order, sort, text) => {
  table.isLoading = true

  UserAddressAPI.fetchAll(idParam.value, {
    success(response) {
      table.rows = response.data
      table.totalRecordCount = response.data.length

      return false
    },
    error() {
      table.rows = []
      table.totalRecordCount = 0
    },
    finally() {
      loading.value = false
      table.isLoading = false
      table.sortable.order = order
      table.sortable.sort = sort

      if (tableContainer.value && tableContainer.value.card)
        tableContainer.value.card.scrollIntoView({behavior: "smooth"})
    },
  })
}

doSearch(0, 15, 'id', 'desc')

onMounted(() => {
  UserAPI.fetchById(idParam.value, {
    success(response) {
      user.value = response.data
    },
  })
})
</script>
