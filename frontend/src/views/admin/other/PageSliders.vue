<template>
  <new-creation-guide-top route-name="admin.slider.add">
    <template #text>
      با استفاده از ستون عملیات می‌توانید اقدام به حذف و ویرایش اسلایدر نمایید
    </template>
    <template #buttonText>
      <PlusIcon class="w-6 h-6 ml-2 group-hover:rotate-90 transition"/>
      افزودن اسلایدر جدید
    </template>
  </new-creation-guide-top>

  <partial-card ref="tableContainer">
    <template #header>
      لیست اسلایدرها
    </template>

    <template #body>
      <base-loading-panel :loading="loading" type="table">
        <template #content>
          <base-datatable
            ref="datatable"
            :enable-search-box="true"
            :enable-multi-operation="true"
            :selection-operations="selectionOperations"
            :is-slot-mode="true"
            :is-loading="table.isLoading"
            :selection-columns="table.selectionColumns"
            :columns="table.columns"
            :rows="table.rows"
            :has-checkbox="true"
            :total="table.totalRecordCount"
            :sortable="table.sortable"
            @do-search="doSearch"
          >
            <template v-slot:place="{value}">

            </template>
            <template v-slot:is_published="{value}">

            </template>
            <template v-slot:op="{value}">
              <base-datatable-menu
                v-if="getMenuRemovals(value).length !== 2"
                :items="operations"
                :data="value"
                :container="getMenuContainer"
                :removals="getMenuRemovals(value)"
              />
            </template>
          </base-datatable>
        </template>
      </base-loading-panel>
    </template>
  </partial-card>
</template>

<script setup>
import {useRequest} from "../../../composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "../../../router/api-routes.js";
import {useRouter} from "vue-router";
import {useToast} from "vue-toastification";
import {computed, reactive, ref} from "vue";
import {hideAllPoppers} from "floating-vue";
import {useConfirmToast} from "../../../composables/toast-helper.js";
import {PlusIcon} from "@heroicons/vue/24/outline/index.js";
import PartialCard from "../../../components/partials/PartialCard.vue";
import BaseDatatableMenu from "../../../components/base/datatable/BaseDatatableMenu.vue";
import BaseDatatable from "../../../components/base/BaseDatatable.vue";
import BaseLoadingPanel from "../../../components/base/BaseLoadingPanel.vue";
import NewCreationGuideTop from "../../../components/admin/NewCreationGuideTop.vue";
import {SLIDER_PLACES} from "../../../composables/constants.js";

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
      label: "عنوان",
      field: "title",
      sortable: true,
    },
    {
      label: "محل قرارگیری",
      field: "place",
    },
    {
      label: "وضعیت نمایش",
      field: "is_published",
      sortable: true,
    },
    {
      label: "اولویت",
      field: "priority",
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
      sortable: true,
    },
    {
      label: "محل قرارگیری",
      field: "place",
    },
    {
      label: "وضعیت نمایش",
      field: "is_published",
      sortable: true,
    },
    {
      label: "اولویت",
      field: "priority",
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

const getMenuRemovals = computed((value) => {
  let removals = []

  if (!value.is_deletable) removals.push('delete')
  if (value.slider_place.place_in === SLIDER_PLACES.MAIN_SLIDERS.value) removals.push('edit_slides')

  return removals
})

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
          name: 'admin.slider.edit',
          params: {
            id: data.id,
          }
        })
      },
    },
  },
  {
    id: 'edit_slides',
    link: {
      text: 'ویرایش اسلایدها',
      icon: 'PencilIcon',
    },
    event: {
      click: (data) => {
        router.push({
          name: 'admin.slider.slides.edit',
          params: {
            id: data.id,
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
          useRequest(apiReplaceParams(apiRoutes.admin.sliders.destroy, {slider: data.id}), {
            method: 'DELETE',
          }, {
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
          useRequest(apiRoutes.admin.sliders.batchDestroy, {
            method: 'DELETE',
            data: {
              ids,
            },
          }, {
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

  // useRequest(apiRoutes.admin.sliders.index, {
  //     params: {limit, offset, order, sort, text},
  // }, {
  //     success: (response) => {
  //         table.rows = response.data
  //         table.totalRecordCount = response.meta.total
  //
  //         return false
  //     },
  //     error: () => {
  //         table.rows = []
  //         table.totalRecordCount = 0
  //     },
  //     finally: () => {
  loading.value = false
  table.isLoading = false
  //     table.sortable.order = order
  //     table.sortable.sort = sort
  //
  //     if (tableContainer.value && tableContainer.value.card)
  //         tableContainer.value.card.scrollIntoView({behavior: "smooth"})
  // },
  // })
}

doSearch(0, 15, 'id', 'desc')
</script>
