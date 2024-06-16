<template>
  <partial-card ref="tableContainer">
    <template #header>
      نمایش سفارشات کاربر -
      <span
          v-if="user?.id"
          class="text-slate-400 text-base"
      ><partial-username-label v-if="user" :user="user"/></span>
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
                :color-hex="value.send_status_color_hex"
                :text="value.send_status_title"
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
import {computed, onMounted, reactive, ref} from "vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseDatatable from "@/components/base/BaseDatatable.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import {useRouter} from "vue-router";
import {useToast} from "vue-toastification";
import BaseDatatableMenu from "@/components/base/datatable/BaseDatatableMenu.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import PartialUsernameLabel from "@/components/partials/PartialUsernameLabel.vue";
import {UserAPI, UserPurchaseAPI} from "@/service/APIUser.js";
import {getRouteParamByKey} from "@/composables/helper.js";
import PartialDialog from "@/components/partials/PartialDialog.vue";
import {ROLES} from "@/store/StoreUserAuth.js";
import PartialBadgeStatusPayment from "@/components/partials/PartialBadgeStatusPayment.vue";
import {MinusIcon} from "@heroicons/vue/24/outline/index.js";
import PartialBadgeStatusSend from "@/components/partials/PartialBadgeStatusSend.vue";
import {hideAllPoppers} from "floating-vue";
import {useConfirmToast} from "@/composables/toast-helper.js";
import {OrderAPI} from "@/service/APIOrder.js";

const router = useRouter()
const toast = useToast()
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

  UserPurchaseAPI.fetchAll(idParam.value, {limit, offset, order, sort, text}, {
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

onMounted(() => {
  UserAPI.fetchById(idParam.value, {
    success: (response) => {
      user.value = response.data
    },
  })
})
</script>
