<template>
  <partial-card ref="tableContainer">
    <template #header>
      لیست تماس‌ها
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
            <template v-slot:name="{value}">
              <template v-if="value?.created_by">
                <router-link
                    :to="{name: 'admin.user.profile', params: {id: value?.created_by.id}}"
                    class="text-blue-600 hover:text-opacity-80"
                >
                  <partial-username-label :user="value?.created_by"/>
                </router-link>
                -
                ({{ value.name }})
              </template>
              <template v-else>
                {{ value.name }}
              </template>
            </template>

            <template v-slot:is_seen="{value}">
              <partial-badge-publish
                  :publish="value.is_seen"
                  publish-text="خوانده شده"
                  unpublish-text="خوانده نشده"
              />
            </template>

            <template v-slot:created_at="{value}">
              <span v-if="value.created_at" class="text-xs">{{ value.created_at }}</span>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>

            <template v-slot:answered_by="{value}">
              <router-link
                  v-if="value.answered_by"
                  :to="{name: 'admin.user.profile', params: {id: value.answered_by.id}}"
                  class="text-blue-600 hover:text-opacity-80"
              >
                <partial-username-label :user="value.answered_by"/>
              </router-link>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>

            <template v-slot:answered_at="{value}">
              <span v-if="value.answered_at" class="text-xs">{{ value.answered_at }}</span>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>

            <template v-slot:op="{value}">
              <base-datatable-menu :container="getMenuContainer" :data="value" :items="operations"/>
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
import {computed, reactive, ref} from "vue";
import {hideAllPoppers} from "floating-vue";
import {MinusIcon} from "@heroicons/vue/24/outline/index.js";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseDatatableMenu from "@/components/base/datatable/BaseDatatableMenu.vue";
import BaseDatatable from "@/components/base/BaseDatatable.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import {ContactAPI} from "@/service/APIPage.js";
import {useConfirmToast} from "@/composables/toast-helper.js";
import PartialUsernameLabel from "@/components/partials/PartialUsernameLabel.vue";
import PartialBadgePublish from "@/components/partials/PartialBadgePublish.vue";

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
      label: "نام فرستنده",
      field: "name",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "موضوع",
      field: "title",
      sortable: true,
    },
    {
      label: "وضعیت",
      field: "is_seen",
      sortable: true,
    },
    {
      label: "تاریخ ایجاد",
      field: "created_at",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "پاسخ توسط",
      field: "answered_by",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "پاسخ در تاریخ",
      field: "answered_at",
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
      label: "نام فرستنده",
      field: "name",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "موضوع",
      field: "title",
      sortable: true,
    },
    {
      label: "وضعیت",
      field: "is_seen",
      sortable: true,
    },
    {
      label: "تاریخ ایجاد",
      field: "created_at",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "پاسخ توسط",
      field: "answered_by",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "پاسخ در تاریخ",
      field: "answered_at",
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
          name: 'admin.contact.detail',
          params: {
            id: data.id,
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
          ContactAPI.deleteById(data.id, {
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
            if (items[item].id)
              ids.push(items[item].id)
          }
        }

        if (!ids.length) {
          toast.info('ابتدا آیتم‌های مورد نیاز را انتخاب کنید و سپس دوباره تلاش نمایید.')
          return
        }

        toast.clear()

        useConfirmToast(() => {
          ContactAPI.deleteByIds(ids, {
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

  ContactAPI.fetchAll({limit, offset, order, sort, text}, {
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
