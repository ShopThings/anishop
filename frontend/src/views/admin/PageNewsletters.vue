<template>
  <partial-card ref="tableContainer">
    <template #header>
      لیست خبرنامه
    </template>

    <template #body>
      <base-loading-panel :loading="loading" type="table">
        <template #content>
          <div class="p-3">
            <base-dialog v-model:open="openAddDialog">
              <template #button="{open}">
                <base-animated-button
                  class="w-full mr-auto bg-emerald-500 sm:w-auto"
                  @click="open"
                >
                  <template #icon="{klass}">
                    <DevicePhoneMobileIcon :class="klass" class="w-6 h-6 ml-auto sm:ml-2"/>
                  </template>

                  <span class="ml-auto">افزودن شماره به خبرنامه</span>
                </base-animated-button>
              </template>

              <template #title>
                افزودن شماره موبایل به خبرنامه
              </template>

              <template #body>
                <form @submit.prevent="onSubmit">
                  <base-input
                    label-title="شماره موبایل"
                    name="mobile"
                    placeholder="وارد نمایید"
                  >
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>

                  <div class="py-3">
                    <base-button
                      :disabled="!canSubmit"
                      class="w-full sm:w-auto shrink-0 rounded bg-primary text-white py-2 px-6 mx-auto mr-auto sm:ml-0 flex items-center justify-center hover:bg-opacity-90 transition group text-sm"
                      type="submit"
                    >
                      <VTransitionFade>
                        <loader-circle
                          v-if="!canSubmit"
                          big-circle-color="border-transparent"
                          main-container-klass="absolute w-full h-full top-0 left-0"
                        />
                      </VTransitionFade>

                      <PlusIcon class="h-6 w-6 ml-auto sm:ml-2 group-hover:rotate-90 transition"/>
                      <span class="ml-auto">افزودن شماره</span>
                    </base-button>
                  </div>
                </form>
              </template>
            </base-dialog>
          </div>

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
            <template v-slot:mobile="{value}">
              <span class="tracking-widest font-iranyekan-bold">{{ value.mobile }}</span>
            </template>

            <template v-slot:created_at="{value}">
              <span v-if="value.created_at" class="text-xs">{{ value.created_at }}</span>
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
import {useConfirmToast} from "@/composables/toast-helper.js";
import {ArrowLeftCircleIcon, DevicePhoneMobileIcon, MinusIcon, PlusIcon,} from "@heroicons/vue/24/outline/index.js";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseDatatableMenu from "@/components/base/datatable/BaseDatatableMenu.vue";
import BaseDatatable from "@/components/base/BaseDatatable.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import BaseDialog from "@/components/base/BaseDialog.vue";
import yup, {transformNumbersToEnglish} from "@/validation/index.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {NewsletterAPI} from "@/service/APIPage.js";
import BaseButton from "@/components/base/BaseButton.vue";

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
      label: "موبایل",
      field: "mobile",
      sortable: true,
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
      label: "موبایل",
      field: "mobile",
      sortable: true,
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
      text: 'حذف',
      icon: 'TrashIcon',
      class: 'text-rose-500',
    },
    event: {
      click: (data) => {
        hideAllPoppers()
        toast.clear()

        useConfirmToast(() => {
          NewsletterAPI.deleteById(data.id, {
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
          NewsletterAPI.deleteByIds(ids, {
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

  NewsletterAPI.fetchAll({limit, offset, order, sort, text}, {
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

//---------------------------------------
// Newsletter creation form
//---------------------------------------
const openAddDialog = ref(false)

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    mobile: yup.string()
      .transform(transformNumbersToEnglish)
      .persianMobile('شماره موبایل نامعتبر است.')
      .required('موبایل را وارد نمایید.'),
  }),
}, (values, actions) => {
  canSubmit.value = false

  NewsletterAPI.create({
    mobile: values.mobile,
  }, {
    success() {
      openAddDialog.value = false
      actions.resetForm()
    },
    error(error) {
      if (error?.errors && Object.keys(error.errors).length >= 1) {
        actions.setErrors(error.errors)
      }
    },
    finally() {
      canSubmit.value = true
    },
  })
})
</script>
