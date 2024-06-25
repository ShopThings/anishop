<template>
  <base-loading-panel :loading="loading" type="table">
    <template #content>
      <base-semi-datatable
          :columns="table.columns"
          :is-loading="table.isLoading"
          :rows="table.rows"
          :total="table.total"
          :sortable="table.sortable"
          pagination-theme="modern"
          @do-search="doSearch"
      >
        <template #emptyTableRows>
          <partial-empty-rows
            image="/images/empty-statuses/empty-contact.svg"
              image-class="w-60"
              message="شما هیچ پیامی ارسال نکرده‌اید"
          />
        </template>

        <template #id="{index}">
          <span class="text-sm">{{ index }}</span>
        </template>

        <template #title="{value}">
          <span class="text-sm">{{ value.title }}</span>
        </template>

        <template #answered_at="{value}">
          <span v-if="value.answered_at" class="text-emerald-500 text-sm border-b-2 border-emerald-500 pb-1">
              پاسخ داده شده
          </span>
          <span v-else class="text-rose-500 text-sm border-b-2 border-rose-500 pb-1">پاسخ داده نشده</span>
        </template>

        <template #created_at="{value}">
          <span v-if="value.created_at" class="text-xs">{{ value.created_at }}</span>
          <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
        </template>

        <template #op="{value}">
          <div class="flex flex-wrap gap-3 items-center">
            <router-link
                :to="{name: 'user.contact.detail', params: {id: value.id}}"
                class="text-blue-600 hover:text-opacity-80 text-sm"
            >
              مشاهده جزئیات
            </router-link>
            <button
                v-if="!value.answered_at"
                class="text-rose-600 hover:text-opacity-80 text-sm"
                type="button"
                @click="removeContactHandler(value)"
            >
              حذف پیام
            </button>
          </div>
        </template>
      </base-semi-datatable>
    </template>
  </base-loading-panel>
</template>

<script setup>
import {reactive, ref} from "vue";
import BaseSemiDatatable from "@/components/base/BaseSemiDatatable.vue";
import PartialEmptyRows from "@/components/partials/PartialEmptyRows.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import {UserPanelContactAPI} from "@/service/APIUserPanel.js";
import {useConfirmToast} from "@/composables/toast-helper.js";
import {useToast} from "vue-toastification";
import {MinusIcon} from "@heroicons/vue/24/outline/index.js";

const toast = useToast()

function removeContactHandler(item) {
  if (!item.id) return

  useConfirmToast(() => {
    UserPanelContactAPI.deleteById(item.id, {
      success() {
        toast.success('پیام شما با موفقیت حذف شد.')
      },
    })
  }, 'حذف پیام شما')
}

const loading = ref(true)
const table = reactive({
  isLoading: true,
  columns: [
    {
      field: 'id',
      label: '#',
      columnStyles: "width: 3%;",
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'title',
      label: 'عنوان',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'answered_at',
      label: 'وضعیت پاسخ',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'created_at',
      label: 'تاریخ ارسال',
      columnClasses: 'whitespace-nowrap',
    },
    {
      field: 'op',
      label: 'عملیات',
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

const doSearch = (offset, limit, order, sort, text) => {
  table.isLoading = true

  UserPanelContactAPI.fetchAll({limit, offset, order, sort, text}, {
    success: (response) => {
      table.rows = response.data
      table.total = response.meta.total

      return false
    },
    error: () => {
      table.rows = []
      table.total = 0
    },
    finally: () => {
      loading.value = false
      table.isLoading = false
    },
  })
}

doSearch(0, 15)
</script>
