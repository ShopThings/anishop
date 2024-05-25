<template>
  <new-creation-guide-top>
    <template #text>
      با استفاده از ستون عملیات می‌توانید اقدام به حذف و ویرایش منو نمایید
    </template>
  </new-creation-guide-top>

  <partial-card ref="tableContainer">
    <template #header>
      لیست منوها
    </template>

    <template #body>
      <base-loading-panel :loading="loading" type="table">
        <template #content>
          <base-datatable
              ref="datatable"
              :columns="table.columns"
              :enable-multi-operation="true"
              :enable-search-box="true"
              :has-checkbox="false"
              :is-loading="table.isLoading"
              :is-slot-mode="true"
              :rows="table.rows"
              :selection-columns="table.selectionColumns"
              :selection-operations="selectionOperations"
              :sortable="table.sortable"
              :total="table.totalRecordCount"
              @do-search="doSearch"
          >
            <template v-slot:place_in="{value}">
              {{ value.place_in.text }}
            </template>

            <template v-slot:is_published="{value}">
              <base-switch-confirmation
                  :id="value.id"
                  v-model="value.is_published"
                  :api="MenuAPI"
                  off-label="عدم انتشار"
                  on-label="انتشار"
                  update-key="is_published"
                  @success="() => {datatable?.refresh()}"
              />
            </template>

            <template v-slot:op="{value}">
              <base-datatable-menu
                  :container="getMenuContainer"
                  :data="value"
                  :items="operations"
                  :removals="calcRemovals(value)"
              />
            </template>
          </base-datatable>
        </template>
      </base-loading-panel>
    </template>
  </partial-card>
</template>

<script setup>
import {computed, reactive, ref} from "vue";
import {useRouter} from "vue-router";
import {useToast} from "vue-toastification";
import {useConfirmToast} from "@/composables/toast-helper.js";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseDatatableMenu from "@/components/base/datatable/BaseDatatableMenu.vue";
import BaseDatatable from "@/components/base/BaseDatatable.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import NewCreationGuideTop from "@/components/admin/NewCreationGuideTop.vue";
import {MenuAPI} from "@/service/APIConfig.js";
import BaseSwitchConfirmation from "@/components/base/BaseSwitchConfirmation.vue";
import {PERMISSION_PLACES, PERMISSIONS, useAdminAuthStore} from "@/store/StoreUserAuth.js";

const router = useRouter()
const toast = useToast()

const userStore = useAdminAuthStore()

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
      label: "نام",
      field: "title",
      sortable: true,
    },
    {
      label: "محل قرارگیری",
      field: "place_in",
    },
    {
      label: "وضعیت نمایش",
      field: "is_published",
      sortable: true,
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
      label: "نام",
      field: "title",
      sortable: true,
    },
    {
      label: "محل قرارگیری",
      field: "place_in",
    },
    {
      label: "وضعیت نمایش",
      field: "is_published",
      sortable: true,
    },
    {
      label: 'عملیات',
      field: 'op',
      width: '7%',
      show: userStore.hasPermission(PERMISSION_PLACES.MENU, [PERMISSIONS.UPDATE, PERMISSIONS.DELETE])
    },
  ],
  rows: [],
  totalRecordCount: 0,
  sortable: {
    order: "id",
    sort: "desc",
  },
})

function calcRemovals(row) {
  let removals = []

  if (!row.is_deletable || !userStore.hasPermission(PERMISSION_PLACES.MENU, PERMISSIONS.DELETE)) {
    removals.push(['delete'])
  }
  if (!userStore.hasPermission(PERMISSION_PLACES.MENU, PERMISSIONS.UPDATE)) {
    removals.push(['edit'])
  }

  return removals
}

const getMenuContainer = computed(() => {
  return datatable.value?.tableContainer ?? 'body'
})

const operations = [
  {
    id: 'edit',
    link: {
      text: 'ویرایش',
      icon: 'PencilIcon',
    },
    event: {
      click: (data) => {
        router.push({
          name: 'admin.menu.edit',
          params: {
            id: data.id,
          }
        })
      },
    },
  },
]

const selectionOperations = [
  {
    btn: {
      tooltip: 'حذف موارد انتخاب شده',
      icon: 'TrashIcon',
      class: 'bg-rose-500 border-rose-600',
    },
    event: {
      click: (items) => {
        const ids = []
        for (const item in items) {
          if (items.hasOwnProperty(item)) {
            if (items[item].id && items[item].is_deletable)
              ids.push(items[item].id)
          }
        }

        if (!ids.length) {
          toast.info('ابتدا آیتم‌های مورد نیاز را انتخاب کنید و سپس دوباره تلاش نمایید.')
          return
        }

        toast.clear()

        useConfirmToast(() => {
          MenuAPI.deleteByIds(ids, {
            success: () => {
              toast.success('عملیات با موفقیت انجام شد.')
              datatable.value?.refresh()
              datatable.value?.resetSelection()

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

  MenuAPI.fetchAll({limit, offset, order, sort, text}, {
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
