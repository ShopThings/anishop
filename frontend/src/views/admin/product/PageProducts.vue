<template>
  <new-creation-guide-top route-name="admin.product.add">
    <template #text>
      با استفاده از ستون عملیات می‌توانید اقدامات لازم برای محصول را مشاهده نمایید
    </template>
    <template
      v-if="userStore.hasPermission(PERMISSION_PLACES.PRODUCT, PERMISSIONS.CREATE)"
      #buttonText
    >
      <PlusIcon class="w-6 h-6 ml-2 group-hover:rotate-90 transition"/>
      افزودن محصول جدید
    </template>
  </new-creation-guide-top>

  <partial-card ref="tableContainer">
    <template #header>
      لیست محصولات
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
            <template v-slot:image="{value}">
              <base-lazy-image
                :alt="value.title"
                :lazy-src="value.image.path"
                :size="FileSizes.SMALL"
                :is-local="false"
                class="!h-20 min-w-20 rounded"
              />
            </template>

            <template v-slot:brand="{value}">
              {{ value.brand.name }}
            </template>

            <template v-slot:category="{value}">
              {{ value.category.name }}
            </template>

            <template v-slot:unit_name="{value}">
              <span class="text-xs text-slate-400">{{ value.unit_name }}</span>
            </template>

            <template v-slot:is_published="{value}">
              <partial-badge-publish :publish="!!value.is_published"/>
            </template>

            <template v-slot:is_available="{value}">
              <partial-badge-publish
                :publish="!!value.is_available"
                publish-text="موجود"
                unpublish-text="ناموجود"
              />
            </template>

            <template v-slot:updated_at="{value}">
              <span v-if="value.updated_at" class="text-xs">{{ value.updated_at }}</span>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
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
import {hideAllPoppers} from "floating-vue";
import {useConfirmToast} from "@/composables/toast-helper.js";
import {MinusIcon, PlusIcon} from "@heroicons/vue/24/outline/index.js";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseDatatableMenu from "@/components/base/datatable/BaseDatatableMenu.vue";
import BaseDatatable from "@/components/base/BaseDatatable.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import NewCreationGuideTop from "@/components/admin/NewCreationGuideTop.vue";
import PartialBadgePublish from "@/components/partials/PartialBadgePublish.vue";
import {ProductAPI} from "@/service/APIProduct.js";
import {FileSizes} from "@/composables/file-list.js";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
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
      label: "تصویر",
      field: "image",
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "عنوان",
      field: "title",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "برند",
      field: "brand",
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "دسته‌بندی",
      field: "category",
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "واحد محصول",
      field: "unit_name",
      sortable: true,
    },
    {
      label: "وضعیت نمایش",
      field: "is_published",
      sortable: true,
    },
    {
      label: "وضعیت موجودی",
      field: "is_available",
      sortable: true,
    },
    {
      label: "تاریخ ویرایش",
      field: "updated_at",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
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
      label: "تصویر",
      field: "image",
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "عنوان",
      field: "title",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
    {
      label: "برند",
      field: "brand",
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "دسته‌بندی",
      field: "category",
      columnClasses: 'whitespace-nowrap',
    },
    {
      label: "واحد محصول",
      field: "unit_name",
      sortable: true,
    },
    {
      label: "وضعیت نمایش",
      field: "is_published",
      sortable: true,
    },
    {
      label: "وضعیت موجودی",
      field: "is_available",
      sortable: true,
    },
    {
      label: "تاریخ ویرایش",
      field: "updated_at",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
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

function calcRemovals() {
  let removals = []

  if (!userStore.hasPermission(PERMISSION_PLACES.PRODUCT, PERMISSIONS.DELETE)) {
    removals.push('delete')
  }
  if (!userStore.hasPermission(PERMISSION_PLACES.PRODUCT, PERMISSIONS.UPDATE)) {
    removals.push('edit')
  }
  if (!userStore.hasPermission(PERMISSION_PLACES.PRODUCT_ATTRIBUTE, PERMISSIONS.READ)) {
    removals.push('attributes')
  }
  if (!userStore.hasPermission(PERMISSION_PLACES.PRODUCT_COMMENT, PERMISSIONS.READ)) {
    removals.push('showComments')
  }

  return removals
}

const getMenuContainer = computed(() => {
  return datatable.value?.tableContainer ?? 'body'
})

const operations = [
  {
    id: 'show',
    link: {
      text: 'مشاهده جزئیات',
      icon: 'EyeIcon',
    },
    event: {
      click: (data) => {
        router.push({
          name: 'admin.product.detail',
          params: {
            slug: data.slug,
          }
        })
      },
    },
  },
  {
    id: 'edit',
    link: {
      text: 'ویرایش',
      icon: 'PencilIcon',
    },
    event: {
      click: (data) => {
        router.push({
          name: 'admin.product.edit',
          params: {
            slug: data.slug,
          }
        })
      },
    },
  },
  {
    id: 'attributes',
    link: {
      text: 'ویژگی‌های جستجو',
      icon: 'VariableIcon',
    },
    event: {
      click: (data) => {
        router.push({
          name: 'admin.product.attrs.edit',
          params: {
            slug: data.slug,
          }
        })
      },
    },
  },
  {
    id: 'showComments',
    link: {
      text: 'مشاهده دیدگاه‌ها',
      icon: 'ChatBubbleLeftRightIcon',
    },
    event: {
      click: (data) => {
        router.push({
          name: 'admin.product.comments',
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

        useConfirmToast(() => {
          ProductAPI.deleteById(data.slug, {
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
      tooltip: 'تغییر دسته‌جمعی قیمت',
      icon: 'ArrowTrendingUpIcon',
      class: 'bg-violet-100 border-violet-300 !text-black hover:bg-violet-200',
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

        router.push({name: 'admin.products.change.price', params: {ids: ids.join('/')}})
      },
    },
  },
  {
    btn: {
      tooltip: 'تغییر دسته‌جمعی مشخصات',
      icon: 'PencilIcon',
      class: 'bg-orange-100 border-orange-300 !text-black hover:bg-orange-200',
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

        router.push({name: 'admin.products.change.info', params: {ids: ids.join('/')}})
      },
    },
  },
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
          ProductAPI.deleteByIds(ids, {
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

  ProductAPI.fetchAll({limit, offset, order, sort, text}, {
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
