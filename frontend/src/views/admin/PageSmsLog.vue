<template>
  <partial-card ref="tableContainer">
    <template #header>
      پیامک‌های ارسال شده
    </template>

    <template #body>
      <base-loading-panel :loading="loading" type="table">
        <template #content>
          <base-datatable
              ref="datatable"
              :columns="table.columns"
              :enable-multi-operation="false"
              :enable-search-box="true"
              :has-checkbox="false"
              :is-loading="table.isLoading"
              :is-slot-mode="true"
              :rows="table.rows"
              :selection-columns="table.selectionColumns"
              :sortable="table.sortable"
              :total="table.totalRecordCount"
              @do-search="doSearch"
          >
            <template v-slot:receiver_numbers="{value}">
              <span class="tracking-widest">{{ value.receiver_numbers }}</span>
            </template>

            <template v-slot:panel_number="{value}">
              <span class="tracking-widest font-iranyekan-bold">{{ value.panel_number || '-' }}</span>
            </template>

            <template v-slot:panel_name="{value}">
              <span class="tracking-widest">{{ value.panel_name || '-' }}</span>
            </template>

            <template v-slot:body="{value}">
              <div class="leading-relaxed" v-html="convertNl2Br(value.body)"></div>
            </template>

            <template v-slot:type="{value}">
              <span class="rounded inline-block py-1 px-2.5 bg-blue-200">{{ value.type }}</span>
            </template>

            <template v-slot:sender="{value}">
              <span class="rounded inline-block py-1 px-2.5 bg-blue-200">{{ value.sender }}</span>
            </template>

            <template v-slot:created_at="{value}">
              <span v-if="value.created_at" class="text-xs">{{ value.created_at }}</span>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>

            <template v-slot:created_by="{value}">
              <router-link
                  v-if="value.created_by"
                  :to="{name: 'admin.user.profile', params: {id: value.created_by.id}}"
                  class="text-blue-600 hover:text-opacity-80"
              >
                <partial-username-label :user="value.created_by"/>
              </router-link>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>
          </base-datatable>
        </template>
      </base-loading-panel>
    </template>
  </partial-card>
</template>

<script setup>
import {useRouter} from "vue-router";
import {useToast} from "vue-toastification";
import {reactive, ref} from "vue";
import {MinusIcon} from "@heroicons/vue/24/outline/index.js";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseDatatable from "@/components/base/BaseDatatable.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import {SmsLogAPI} from "@/service/APIPage.js";
import PartialUsernameLabel from "@/components/partials/PartialUsernameLabel.vue";

const router = useRouter()
const toast = useToast()

const datatable = ref(null)
const tableContainer = ref(null)
const loading = ref(true)
const table = reactive({
  isLoading: false,
  selectionColumns: [
    {
      label: "#",
      field: "id",
      columnStyles: "width: 3%;",
      sortable: true,
      isKey: true,
    },
    {
      label: "شماره گیرنده",
      field: "receiver_numbers",
      sortable: true,
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "شماره پنل",
      field: "panel_number",
      sortable: true,
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "نام پنل",
      field: "panel_name",
      sortable: true,
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "متن",
      field: "body",
      sortable: true,
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "نوع پیامک",
      field: "type",
      sortable: true,
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "ارسال کننده",
      field: "sender",
      sortable: true,
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "تاریخ ایجاد",
      field: "created_at",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "ارسال شده توسط",
      field: "created_by",
      sortable: false,
      columnClasses: 'whitespace-nowrap',
    },
  ],
  columns: [
    {
      label: "#",
      field: "id",
      columnStyles: "width: 3%;",
      sortable: true,
      isKey: true,
    },
    {
      label: "شماره گیرنده",
      field: "receiver_numbers",
      sortable: true,
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "شماره پنل",
      field: "panel_number",
      sortable: true,
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "نام پنل",
      field: "panel_name",
      sortable: true,
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "متن",
      field: "body",
      sortable: true,
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "نوع پیامک",
      field: "type",
      sortable: true,
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "ارسال کننده",
      field: "sender",
      sortable: true,
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "تاریخ ایجاد",
      field: "created_at",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "ارسال شده توسط",
      field: "created_by",
      sortable: false,
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

function convertNl2Br(value) {
  return value?.replace(/\r\n|\r|\n/g, '<br>')
}

const doSearch = (offset, limit, order, sort, text) => {
  table.isLoading = true

  SmsLogAPI.fetchAll({limit, offset, order, sort, text}, {
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
    },
  })
}

doSearch(0, 15, 'id', 'desc')
</script>
