<template>
  <partial-card ref="tableContainer">
    <template #header>
      لیست سفارشات
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
              :columns="table.columns"
              :rows="table.rows"
              :has-checkbox="false"
              :total="table.totalRecordCount"
              :sortable="table.sortable"
              @do-search="doSearch"
          >
            <template v-slot:user="{value}">
              <template v-if="value.user.first_name || value.user.last_name">
                {{
                  (value.user.first_name + ' ' + value.user.last_name).trim()
                }}
              </template>
              <template v-else>
                {{ value.user.username }}
              </template>
            </template>

            <template v-slot:receiver_info="{value}">
              <base-button
                  class="text-white bg-black text-sm !py-1"
                  @click="showReceiverDetails(value)"
              >
                مشاهده
              </base-button>
            </template>

            <template v-slot:send_status="{value}">
              <partial-badge-status-payment
                  :text="value.send_status_title"
                  :color-hex="value.send_status_color_hex"
              />
            </template>

            <template v-slot:created_at="{value}">
              <span v-if="value.created_at" class="text-xs">{{ value.created_at }}</span>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>

            <template v-slot:op="{value}">
              <base-datatable-menu
                  :items="operations"
                  :data="value"
                  :container="getMenuContainer"
                  :removals="!store.hasAnyRole([ROLES.DEVELOPER, ROLES.SUPER_ADMIN]) ? ['delete'] : []"
              />
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
                  <span class="text-slate-400 text-sm shrink-0">کد پستی:</span>
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
import {computed, reactive, ref} from "vue"
import {MinusIcon} from "@heroicons/vue/24/outline/index.js"
import BaseDatatable from "@/components/base/BaseDatatable.vue"
import BaseDatatableMenu from "@/components/base/datatable/BaseDatatableMenu.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import {useRouter} from "vue-router";
import {useToast} from "vue-toastification";
import {hideAllPoppers} from "floating-vue";
import {useConfirmToast} from "@/composables/toast-helper.js";
import {useAdminAuthStore, ROLES} from "@/store/StoreUserAuth.js";
import BaseButton from "@/components/base/BaseButton.vue";
import {OrderAPI} from "@/service/APIOrder.js";
import PartialBadgeStatusPayment from "@/components/partials/PartialBadgeStatusPayment.vue";
import PartialDialog from "@/components/partials/PartialDialog.vue";

const router = useRouter()
const toast = useToast()

const store = useAdminAuthStore()

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
    },
    {
      label: "وضعیت ارسال",
      field: "send_status",
    },
    {
      label: "تاریخ سفارش",
      field: "created_at",
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
    id: 'detail',
    link: {
      text: 'مشاهده جزئیات',
      icon: 'EyeIcon',
    },
    event: {
      click: (data) => {
        router.push({
          name: 'admin.order.detail',
          params: {
            id: data.code,
          }
        })
      },
    },
  },
  {
    id: 'delete',
    link: {
      text: 'حذف',
      icon: 'TrashIcon',
      class: 'text-rose-500',
    },
    event: {
      click: (data) => {
        hideAllPoppers()
        toast.clear()

        if (!data.is_deletable)
          toast.warning('این آیتم قابل حذف نمی‌باشد.')

        useConfirmToast(() => {
          OrderAPI.deleteById(data.id, {
            success: () => {
              toast.success('عملیات با موفقیت انجام شد.')
              datatable.value?.refresh()
              datatable.value?.resetSelectionItem(data)

              return false
            },
          })
        })
      },
    },
  },
]

const doSearch = (offset, limit, order, sort, text) => {
  table.isLoading = true

  OrderAPI.fetchAll({limit, offset, order, sort, text}, {
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

//---------------------------------------------
// Receiver detail operations
//---------------------------------------------
const receiverInfo = ref(null)
const isDetailOpen = ref(false)

function showReceiverDetails(value) {
  receiverInfo.value = value
}
</script>
