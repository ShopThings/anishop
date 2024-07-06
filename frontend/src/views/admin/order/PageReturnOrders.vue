<template>
  <partial-card ref="tableContainer">
    <template #header>
      لیست سفارشات مرجوعی
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
          >
            <template #code="{value}">
              <span class="tracking-widest font-iranyekan-bold">{{ value.code }}</span>
            </template>

            <template #order_code="{value}">
              <router-link
                  :to="{name: 'admin.order.detail', params: {id: value.order.code}}"
                  class="text-blue-600 hover:text-opacity-80 leading-relaxed tracking-widest font-iranyekan-bold"
              >
                {{ value.order.code }}
              </router-link>
            </template>

            <template v-slot:user="{value}">
              <router-link
                  :to="{name: 'admin.user.profile', params: {id: value.user.id}}"
                  class="text-blue-600 hover:text-opacity-80"
              >
                <partial-username-label :user="value.user"/>
              </router-link>
            </template>

            <template v-slot:status="{value}">
              <partial-badge-status-return-order
                  :color-hex="value.status.color_hex"
                  :text="value.status.text"
              />
            </template>

            <template v-slot:seen_status="{value}">
              <partial-badge-publish
                :publish="!!value.seen_status"
                  publish-text="مشاهده شده"
                  unpublish-text="مشاهده نشده"
              />
            </template>

            <template v-slot:requested_at="{value}">
              <span v-if="value.requested_at" class="text-xs">{{ value.requested_at }}</span>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>

            <template v-slot:status_changed_by="{value}">
              <router-link
                  :to="{name: 'admin.user.profile', params: {id: value.status_changed_by.id}}"
                  class="text-blue-600 hover:text-opacity-80"
              >
                <partial-username-label :user="value.status_changed_by"/>
              </router-link>
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
import {ROLES, useAdminAuthStore} from "@/store/StoreUserAuth.js";
import {ReturnOrderAPI} from "@/service/APIOrder.js";
import PartialUsernameLabel from "@/components/partials/PartialUsernameLabel.vue";
import PartialBadgeStatusReturnOrder from "@/components/partials/PartialBadgeStatusReturnOrder.vue";
import PartialBadgePublish from "@/components/partials/PartialBadgePublish.vue";

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
      label: "کد مرجوعی",
      field: "code",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "کد سفارش",
      field: "order_code",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "مرجوع کننده",
      field: "user",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "تعداد محصولات مرجوعی",
      field: "items_count",
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "وضعیت ارجاع",
      field: "status",
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "وضعیت بازدید",
      field: "seen_status",
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "تاریخ درخواست",
      field: "requested_at",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "تغییر وضعیت توسط",
      field: "status_changed_by ",
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
          name: 'admin.return_order.detail',
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

        if (!store.hasAnyRole([ROLES.DEVELOPER, ROLES.SUPER_ADMIN])) {
          toast.warning('دسترسی‌های لازم برای حذف را ندارید.')
          return
        }

        useConfirmToast(() => {
          ReturnOrderAPI.deleteById(data.id, {
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

  ReturnOrderAPI.fetchAll({limit, offset, order, sort, text}, {
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
