<template>
  <new-creation-guide-top route-name="admin.festival.add">
    <template #text>
      با استفاده از ستون عملیات می‌توانید اقدام به حذف و ویرایش جشنواره نمایید
    </template>
    <template #buttonText>
      <PlusIcon class="w-6 h-6 ml-2 group-hover:rotate-90 transition"/>
      افزودن جشنواره جدید
    </template>
  </new-creation-guide-top>

  <partial-card ref="tableContainer">
    <template #header>
      لیست جشنواره‌ها
    </template>

    <template #body>
      <base-loading-panel :loading="loading" type="table">
        <template #content>
          <base-datatable
              ref="datatable"
              :columns="table.columns"
              :enable-multi-operation="true"
              :enable-search-box="true"
              :has-checkbox="true"
              :is-loading="table.isLoading"
              :is-slot-mode="true"
              :rows="table.rows"
              :selection-columns="table.selectionColumns"
              :selection-operations="selectionOperations"
              :sortable="table.sortable"
              :total="table.totalRecordCount"
              @do-search="doSearch"
          >
            <template v-slot:start_at="{value}">
              <span v-if="value.start_at" class="text-xs">{{ value.start_at }}</span>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>

            <template v-slot:end_at="{value}">
              <span v-if="value.end_at" class="text-xs">{{ value.end_at }}</span>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>

            <template v-slot:is_published="{value}">
              <partial-badge-publish :publish="value.is_published"/>
            </template>

            <template v-slot:created_at="{value}">
              <span v-if="value.created_at" class="text-xs">{{ value.created_at }}</span>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>

            <template v-slot:op="{value}">
              <base-datatable-menu
                  :container="getMenuContainer"
                  :data="value"
                  :items="operations"
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
import {PlusIcon, MinusIcon} from "@heroicons/vue/24/outline/index.js"
import BaseDatatable from "@/components/base/BaseDatatable.vue"
import NewCreationGuideTop from "@/components/admin/NewCreationGuideTop.vue"
import BaseDatatableMenu from "@/components/base/datatable/BaseDatatableMenu.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import {useRouter} from "vue-router";
import {useToast} from "vue-toastification";
import {hideAllPoppers} from "floating-vue";
import {useConfirmToast} from "@/composables/toast-helper.js";
import PartialBadgePublish from "@/components/partials/PartialBadgePublish.vue";
import {FestivalAPI} from "@/service/APIShop.js";

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
      isKey: true,
      sortable: true,
    },
    {
      label: "عنوان",
      field: "title",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "تاریخ شروع",
      field: "start_at",
      cellClasses: (row) => {
        if (!row.normal_end_at) return ''

        const end = new Date(row.normal_end_at)
        if (end < (new Date)) {
          return 'bg-rose-100'
        } else {
          return 'bg-emerald-100'
        }
      },
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "تاریخ پایان",
      field: "end_at",
      cellClasses: (row) => {
        if (!row.normal_end_at) return ''

        const end = new Date(row.normal_end_at)
        if (end < (new Date)) {
          return 'bg-rose-100'
        } else {
          return 'bg-emerald-100'
        }
      },
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "وضعیت نمایش",
      field: "is_published",
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "تاریخ ایجاد",
      field: "created_at",
      columnClasses: 'whitespace-nowrap',
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
      label: "عنوان",
      field: "title",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "تاریخ شروع",
      field: "start_at",
      cellClasses: (row) => {
        if (!row.normal_end_at) return ''

        const end = new Date(row.normal_end_at)
        if (end < (new Date)) {
          return 'bg-rose-100'
        } else {
          return 'bg-emerald-100'
        }
      },
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "تاریخ پایان",
      field: "end_at",
      cellClasses: (row) => {
        if (!row.normal_end_at) return ''

        const end = new Date(row.normal_end_at)
        if (end < (new Date)) {
          return 'bg-rose-100'
        } else {
          return 'bg-emerald-100'
        }
      },
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "وضعیت نمایش",
      field: "is_published",
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "تاریخ ایجاد",
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
    link: {
      text: 'ویرایش محصولات جشنواره',
      icon: 'PencilSquareIcon',
    },
    event: {
      click: (data) => {
        router.push({
          name: 'admin.festival.products',
          params: {
            slug: data.slug,
          }
        })
      },
    },
  },
  {
    link: {
      text: 'ویرایش',
      icon: 'PencilIcon',
    },
    event: {
      click: (data) => {
        router.push({
          name: 'admin.festival.edit',
          params: {
            slug: data.slug,
          }
        })
      },
    },
  },
  {
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
          FestivalAPI.deleteById(data.slug, {
            success() {
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

const selectionOperations = [
  {
    btn: {
      tooltip: 'حذف موارد انتخاب شده',
      icon: 'TrashIcon',
      class: 'bg-rose-500 border-rose-600',
    },
    event: {
      click: (items) => {
        const slugs = []
        for (const item in items) {
          if (items.hasOwnProperty(item)) {
            if (items[item].slug)
              slugs.push(items[item].slug)
          }
        }

        if (!slugs.length) {
          toast.info('ابتدا آیتم‌های مورد نیاز را انتخاب کنید و سپس دوباره تلاش نمایید.')
          return
        }

        toast.clear()

        useConfirmToast(() => {
          FestivalAPI.deleteByIds(slugs, {
            success() {
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

  FestivalAPI.fetchAll({limit, offset, order, sort, text}, {
    success(response) {
      table.rows = response.data
      table.totalRecordCount = response.meta.total

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
</script>
