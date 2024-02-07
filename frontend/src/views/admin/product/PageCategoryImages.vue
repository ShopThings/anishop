<template>
  <new-creation-guide-top>
    <template #text>
      با کلیک بر روی تصویر موجود در جدول اقدام به افزودن/ویرایش تصویر دسته‌بندی نمایید
    </template>
  </new-creation-guide-top>

  <partial-card ref="tableContainer">
    <template #header>
      لیست تصاویر دسته‌بندی‌ها
    </template>

    <template #body>
      <base-loading-panel :loading="loading" type="table">
        <template #content>
          <base-datatable
              ref="datatable"
              :enable-search-box="true"
              :enable-multi-operation="false"
              :is-slot-mode="true"
              :is-loading="table.isLoading"
              :columns="table.columns"
              :rows="table.rows"
              :has-checkbox="false"
              :total="table.totalRecordCount"
              :sortable="table.sortable"
              @do-search="doSearch"
          >
            <template v-slot:image="{value}">
              <div class="relative">
                <VTransitionFade>
                  <loader-circle
                      v-if="operationLoading"
                      main-container-klass="absolute w-[calc(100%+1rem)] h-[calc(100%+1rem)] -top-2 -left-2"
                      big-circle-color="border-transparent"
                  />
                </VTransitionFade>

                <base-media-placeholder
                    type="image"
                    v-model:selected="value.image"
                    :has-clear-button="true"
                    :clear-check-fn="(selectedFile) => {return handleImageClear(value, selectedFile)}"
                    @file-changed="(selectedFile) => {handleImageSelection(value, selectedFile)}"
                />
              </div>
            </template>

            <template #name="{value}">
              <span>{{ value.name }}</span>
              <div class="mr-2 rounded-lg py-1 px-2 text-sm bg-blue-100 inline-block">
                <span class="text-slate-500 ml-2 text-xs">سطح</span>
                {{ value.level }}
              </div>
            </template>
          </base-datatable>
        </template>
      </base-loading-panel>
    </template>
  </partial-card>
</template>

<script setup>
import {reactive, ref} from "vue"
import BaseDatatable from "@/components/base/BaseDatatable.vue"
import NewCreationGuideTop from "@/components/admin/NewCreationGuideTop.vue"
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import {useRouter} from "vue-router";
import {useToast} from "vue-toastification";
import BaseMediaPlaceholder from "@/components/base/BaseMediaPlaceholder.vue";
import {CategoryImageAPI} from "@/service/APIProduct.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {useConfirmToast} from "@/composables/toast-helper.js";

const router = useRouter()
const toast = useToast()

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
      label: "تصویر",
      columnClasses: 'w-28',
      field: "image",
    },
    {
      label: "نام",
      field: "name",
      columnClasses: 'whitespace-nowrap',
      sortable: true,
    },
  ],
  rows: [],
  totalRecordCount: 0,
  sortable: {
    order: "id",
    sort: "desc",
  },
})

const doSearch = (offset, limit, order, sort, text) => {
  table.isLoading = true

  CategoryImageAPI.fetchAll({limit, offset, order, sort, text}, {
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

//------------------------------------------------
// Category image operations
//------------------------------------------------
const operationLoading = ref(false)

function handleImageSelection(item, file) {
  if (operationLoading.value || !file) return

  operationLoading.value = true

  if (item.category_image_id) {
    CategoryImageAPI.updateById(item.category_image_id, {
      category: item.id,
      image: file.full_path,
    }, {
      success(response) {
        item.category_image_id = response.data.id
        item.updated_by = response.data.updated_by
        item.updated_at = response.data.updated_at

        toast.success('ویرایش تصویر انجام شد.')
      },
      finally() {
        operationLoading.value = false
      },
    })
  } else {
    CategoryImageAPI.create({
      category: item.id,
      image: file.full_path,
    }, {
      success(response) {
        item.category_image_id = response.data.id
        item.created_by = response.data.created_by
        item.created_at = response.data.created_at
      },
      finally() {
        operationLoading.value = false
      },
    })
  }
}

function handleImageClear(item, file) {
  if (operationLoading.value || !item.category_image_id) return

  useConfirmToast(() => {
    operationLoading.value = true

    CategoryImageAPI.deleteById(item.category_image_id, {
      success() {
        item.image = null
        toast.success('حذف تصویر با موفقیت انجام شد.')
      },
      finally() {
        operationLoading.value = false
      },
    })
  })

  return false
}

//------------------------------------------------
</script>
