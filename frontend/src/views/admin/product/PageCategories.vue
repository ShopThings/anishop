<template>
  <new-creation-guide-top route-name="admin.category.add">
    <template #text>
      با استفاده از ستون عملیات می‌توانید اقدام به حذف و ویرایش دسته‌بندی نمایید
    </template>
    <template
      v-if="userStore.hasPermission(PERMISSION_PLACES.CATEGORY, PERMISSIONS.CREATE)"
      #buttonText
    >
      <PlusIcon class="w-6 h-6 ml-2 group-hover:rotate-90 transition"/>
      افزودن دسته‌بندی جدید
    </template>
  </new-creation-guide-top>

  <div
    v-if="userStore.hasPermission(PERMISSION_PLACES.CATEGORY_IMAGE, PERMISSIONS.READ)"
    class="flex flex-wrap gap-3 mb-3"
  >
    <div class="grow">
      <partial-card-navigation
        :to="{name: 'admin.category_images'}"
        bg-color="bg-gradient-to-r from-cyan-500 to-indigo-500"
      >
        <span class="text-white text-lg grow">تصاویر دسته‌بندی‌ها</span>
        <PhotoIcon class="h-12 w-12 text-white text-opacity-50 mr-3"/>
      </partial-card-navigation>
    </div>
  </div>

  <partial-card ref="tableContainer">
    <template #header>
      لیست دسته‌بندی‌ها
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
              <span>{{ value.name }}</span>
              <div class="mr-2 rounded-lg py-1 px-2 text-sm bg-blue-100 inline-block">
                <span class="text-slate-500 ml-2 text-xs">سطح</span>
                <span class="font-iranyekan-bold">{{ value.level }}</span>
              </div>
            </template>

            <template v-slot:parent_id="{value}">
              <span v-if="!value.parent">-</span>
              <span v-else>{{ value.parent.name }}</span>
            </template>

            <template v-slot:show_in_menu="{value}">
              <partial-badge-publish
                :publish="!!value.show_in_menu"
                publish-text="نمایش در منو"
                unpublish-text="عدم نمایش در منو"
              />
            </template>

            <template v-slot:show_in_search_side_menu="{value}">
              <partial-badge-publish
                :publish="!!value.show_in_search_side_menu"
                publish-text="نمایش در منوی کنار جستجو"
                unpublish-text="عدم نمایش در منوی کنار جستجو"
              />
            </template>

            <template v-slot:show_in_slider="{value}">
              <partial-badge-publish
                :publish="!!value.show_in_slider"
                publish-text="نمایش در اسلایدر"
                unpublish-text="عدم نمایش در اسلایدر"
              />
            </template>

            <template v-slot:is_published="{value}">
              <partial-badge-publish :publish="!!value.is_published"/>
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
import {computed, reactive, ref} from "vue"
import {PhotoIcon, PlusIcon} from "@heroicons/vue/24/outline/index.js"
import BaseDatatable from "@/components/base/BaseDatatable.vue"
import NewCreationGuideTop from "@/components/admin/NewCreationGuideTop.vue"
import BaseDatatableMenu from "@/components/base/datatable/BaseDatatableMenu.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import {useRouter} from "vue-router";
import {useToast} from "vue-toastification";
import {hideAllPoppers} from "floating-vue";
import {useConfirmToast} from "@/composables/toast-helper.js";
import PartialCardNavigation from "@/components/partials/PartialCardNavigation.vue";
import {CategoryAPI} from "@/service/APIProduct.js";
import PartialBadgePublish from "@/components/partials/PartialBadgePublish.vue";
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
      isKey: true,
      sortable: true,
    },
    {
      label: "نام",
      field: "name",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "نام والد",
      field: "parent_id",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "اولویت",
      field: "priority",
    },
    {
      label: "نمایش در منو",
      field: "show_in_menu",
    },
    {
      label: "نمایش در منو صفحه جستجو",
      field: "show_in_search_side_menu",
    },
    {
      label: "نمایش در اسلایدر دسته‌بندی‌ها",
      field: "show_in_slider",
    },
    {
      label: "وضعیت نمایش",
      field: "is_published",
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
      field: "name",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "نام والد",
      field: "parent_id",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "اولویت",
      field: "priority",
    },
    {
      label: "نمایش در منو",
      field: "show_in_menu",
    },
    {
      label: "نمایش در منو صفحه جستجو",
      field: "show_in_search_side_menu",
    },
    {
      label: "نمایش در اسلایدر دسته‌بندی‌ها",
      field: "show_in_slider",
    },
    {
      label: "وضعیت نمایش",
      field: "is_published",
    },
    {
      label: 'عملیات',
      field: 'op',
      width: '7%',
      show: userStore.hasPermission(PERMISSION_PLACES.CATEGORY, [PERMISSIONS.UPDATE, PERMISSIONS.DELETE])
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

  if (!row.is_deletable || !userStore.hasPermission(PERMISSION_PLACES.CATEGORY, PERMISSIONS.DELETE)) {
    removals.push(['delete'])
  }
  if (!userStore.hasPermission(PERMISSION_PLACES.CATEGORY, PERMISSIONS.UPDATE)) {
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
          name: 'admin.category.edit',
          params: {
            slug: data.slug,
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

        if (!data.is_deletable) {
          toast.warning('این آیتم قابل حذف نمی‌باشد.')
          return
        }

        useConfirmToast(() => {
          CategoryAPI.deleteById(data.slug, {
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
          CategoryAPI.deleteByIds(slugs, {
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

  CategoryAPI.fetchAll({limit, offset, order, sort, text}, {
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
