<template>
  <div class="flex flex-col gap-3 mb-3">
    <base-accordion
      :open="false"
      open-btn-class="!bg-violet-50 !border-violet-500"
      panel-class="bg-white rounded-lg border mb-3"
    >
      <template #button>
        <span class="font-iranyekan-bold">افزودن محصول به جشنواره</span>
      </template>

      <template #panel>
        <form-festival-add-product @added="datatable.reload()"/>
      </template>
    </base-accordion>

    <base-accordion
      :open="false"
      open-btn-class="!bg-violet-50 !border-violet-500"
      panel-class="bg-white rounded-lg border mb-3"
    >
      <template #button>
        <span class="font-iranyekan-bold">افزودن/حذف محصولات دسته‌بندی به/از جشنواره</span>
      </template>

      <template #panel>
        <form-festival-add-category-products
          @added="datatable.reload()"
          @removed="datatable.reload()"
        />
      </template>
    </base-accordion>
  </div>

  <partial-card ref="tableContainer">
    <template #header>
      لیست محصولات جشنواره -
      <span
        v-if="festival?.id"
        class="text-slate-400 text-base"
      >{{ festival?.title }}</span>
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
            <template #product="{value}">
              <base-lazy-image
                :alt="value.product.title"
                :is-local="false"
                :lazy-src="value.product.image.path"
                :size="FileSizes.SMALL"
                class="!h-20 min-w-20 rounded"
              />
            </template>

            <template #title="{value}">
              {{ value.product.title }}
            </template>

            <template #discount_percentage="{value}">
              <span class="font-iranyekan-bold rounded py-0.5 px-1.5 bg-violet-200">
                {{ value.discount_percentage }}
                <span class="mr-0.5">%</span>
              </span>
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
import {computed, onMounted, reactive, ref} from "vue";
import FormFestivalAddProduct from "./forms/FormFestivalAddProduct.vue";
import FormFestivalAddCategoryProducts from "./forms/FormFestivalAddCategoryProducts.vue";
import BaseDatatableMenu from "@/components/base/datatable/BaseDatatableMenu.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseDatatable from "@/components/base/BaseDatatable.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import {useToast} from "vue-toastification";
import {hideAllPoppers} from "floating-vue";
import {useConfirmToast} from "@/composables/toast-helper.js";
import {getRouteParamByKey} from "@/composables/helper.js";
import {FestivalAPI} from "@/service/APIShop.js";
import {FileSizes} from "@/composables/file-list.js";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import BaseAccordion from "@/components/base/BaseAccordion.vue";
import {useRouter} from "vue-router";

const router = useRouter()
const toast = useToast()
const slugParam = getRouteParamByKey('slug', null, false)

const festival = ref(null)

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
      label: "تصویر محصول",
      field: "product",
    },
    {
      label: "عنوان محصول",
      field: "title",
    },
    {
      label: "درصد تخفیف",
      field: "discount_percentage",
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
      label: "تصویر محصول",
      field: "product",
    },
    {
      label: "عنوان محصول",
      field: "title",
    },
    {
      label: "درصد تخفیف",
      field: "discount_percentage",
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
      text: 'مشاهده جزئیات محصول',
      icon: 'EyeIcon',
    },
    event: {
      click: (data) => {
        window.open(router.resolve({
          name: 'admin.product.detail',
          params: {
            slug: data.product.slug,
          }
        }).href, '_blank')
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
          FestivalAPI.removeProduct(slugParam.value, data.product.slug, {
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
          FestivalAPI.removeProducts(slugParam.value, ids, {
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

  FestivalAPI.fetchProducts(slugParam.value, {limit, offset, order, sort, text}, {
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

onMounted(() => {
  FestivalAPI.fetchById(slugParam.value, {
    success(response) {
      festival.value = response.data
    },
  })
})
</script>
