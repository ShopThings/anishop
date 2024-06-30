<template>
  <div
    v-if="countingOrdersStore.getCounts?.length"
    class="py-5 flex flex-wrap items-center justify-center gap-3"
  >
    <router-link
      v-for="badge in countingOrdersStore.getCounts"
      :key="badge.code"
      :style="[
            'background-color:' + badge.color_hex,
            'color:' + getTextColor(badge.color_hex),
        ]"
      :to="route.path + '?badge_code=' + badge.code"
      class="flex items-center gap-2 rounded-lg py-1 px-3 text-sm"
    >
      <span>{{ badge.title }}</span>
      <span class="font-iranyekan-bold">{{ numberFormat(badge.count) }}</span>
    </router-link>
  </div>

  <partial-card ref="tableContainer">
    <template #header>
      لیست سفارشات
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
            :sortable="table.sortable"
            :total="table.totalRecordCount"
            @do-search="doSearch"
            @clear-search-filter="clearFilterHandler"
          >
            <template #code="{value}">
              <span class="tracking-widest text-lg">{{ value.code }}</span>
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

            <template v-slot:op="{value}">
              <base-datatable-menu
                :container="getMenuContainer"
                :data="value"
                :items="operations"
                :removals="!store.hasAnyRole([ROLES.DEVELOPER, ROLES.SUPER_ADMIN]) ? ['delete'] : []"
              />
            </template>
          </base-datatable>

          <partial-dialog v-model:open="isDetailOpen">
            <template #title>
              اطلاعات گیرنده
            </template>

            <template #body>
              <ul class="divide-y text-sm">
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
import {computed, inject, reactive, ref} from "vue"
import {MinusIcon} from "@heroicons/vue/24/outline/index.js"
import BaseDatatable from "@/components/base/BaseDatatable.vue"
import BaseDatatableMenu from "@/components/base/datatable/BaseDatatableMenu.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import {useRoute, useRouter} from "vue-router";
import {useToast} from "vue-toastification";
import {hideAllPoppers} from "floating-vue";
import {useConfirmToast} from "@/composables/toast-helper.js";
import {ROLES, useAdminAuthStore} from "@/store/StoreUserAuth.js";
import BaseButton from "@/components/base/BaseButton.vue";
import {OrderAPI} from "@/service/APIOrder.js";
import PartialBadgeStatusPayment from "@/components/partials/PartialBadgeStatusPayment.vue";
import PartialDialog from "@/components/partials/PartialDialog.vue";
import PartialUsernameLabel from "@/components/partials/PartialUsernameLabel.vue";
import PartialBadgeStatusSend from "@/components/partials/PartialBadgeStatusSend.vue";
import {watchImmediate} from "@vueuse/core";
import {getTextColor, numberFormat} from "@/composables/helper.js";

const router = useRouter()
const route = useRoute()
const toast = useToast()

const store = useAdminAuthStore()
const countingOrdersStore = inject('countingOrderStore')

const badgeCode = ref(null)

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

  OrderAPI.fetchAll({
    limit,
    offset,
    order,
    sort,
    text,
    with_badge_code: badgeCode.value,
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
    },
  })
}

doSearch(0, 15, 'id', 'desc')

//---------------------------------------------
// Check query param of url
//---------------------------------------------
watchImmediate(() => route.query, () => {
  if (route.query.badge_code) {
    badgeCode.value = route.query.badge_code
    doSearch(0, 15, 'id', 'desc')
  } else {
    badgeCode.value = null
  }
})

function clearFilterHandler() {
  badgeCode.value = null
  router.push(route.path)
}

//---------------------------------------------
// Receiver detail operations
//---------------------------------------------
const receiverInfo = ref(null)
const isDetailOpen = ref(false)

function showReceiverDetails(value) {
  isDetailOpen.value = true
  receiverInfo.value = value
}
</script>
