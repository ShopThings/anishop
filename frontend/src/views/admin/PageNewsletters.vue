<template>
  <partial-card ref="tableContainer">
    <template #header>
      لیست خبرنامه
    </template>

    <template #body>
      <base-loading-panel :loading="loading" type="table">
        <template #content>
          <div class="p-3">
            <base-dialog>
              <template #button="{open}">
                <base-animated-button
                    class="w-full mr-auto bg-emerald-500 sm:w-auto"
                    @click="open"
                >
                  <template #icon="{klass}">
                    <DevicePhoneMobileIcon class="w-6 h-6 ml-auto sm:ml-2" :class="klass"/>
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
                      placeholder="وارد نمایید"
                      name="mobile"
                  >
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>

                  <div class="py-3">
                    <base-animated-button
                        type="submit"
                        class="bg-blue-500 text-white mr-auto px-6 w-full sm:w-auto"
                        :disabled="isSubmitting"
                    >
                      <VTransitionFade>
                        <loader-circle
                            v-if="isSubmitting"
                            main-container-klass="absolute w-full h-full top-0 left-0"
                            big-circle-color="border-transparent"
                        />
                      </VTransitionFade>

                      <template #icon="{klass}">
                        <CheckIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
                      </template>

                      <span class="ml-auto">افزودن شماره</span>
                    </base-animated-button>
                  </div>
                </form>
              </template>
            </base-dialog>
          </div>

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
            <template v-slot:created_at="{value}">
              <span v-if="value.created_at" class="text-xs">{{ value.created_at }}</span>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>
            <template v-slot:op="{value}">
              <base-datatable-menu :items="operations" :data="value" :container="getMenuContainer"/>
            </template>
          </base-datatable>
        </template>
      </base-loading-panel>
    </template>
  </partial-card>
</template>

<script setup>
import {useRequest} from "../../composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "../../router/api-routes.js";
import {useRouter} from "vue-router";
import {useToast} from "vue-toastification";
import {computed, reactive, ref} from "vue";
import {hideAllPoppers} from "floating-vue";
import {useConfirmToast} from "../../composables/toast-confirm.js";
import {MinusIcon, DevicePhoneMobileIcon, CheckIcon, ArrowLeftCircleIcon} from "@heroicons/vue/24/outline/index.js";
import PartialCard from "../../components/partials/PartialCard.vue";
import BaseDatatableMenu from "../../components/base/datatable/BaseDatatableMenu.vue";
import BaseDatatable from "../../components/base/BaseDatatable.vue";
import BaseLoadingPanel from "../../components/base/BaseLoadingPanel.vue";
import BaseAnimatedButton from "../../components/base/BaseAnimatedButton.vue";
import BaseDialog from "../../components/base/BaseDialog.vue";
import {useForm} from "vee-validate";
import yup from "../../validation/index.js";
import LoaderCircle from "../../components/base/loader/LoaderCircle.vue";
import VTransitionFade from "../../transitions/VTransitionFade.vue";
import BaseInput from "../../components/base/BaseInput.vue";

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

        // useConfirmToast(() => {
        //     useRequest(apiReplaceParams(apiRoutes.admin.newsletters.destroy, {newsletter: data.id}), {
        //         method: 'DELETE',
        //     }, {
        //         success: () => {
        //             toast.success('عملیات با موفقیت انجام شد.')
        //             datatable.value?.refresh()
        //             datatable.value?.resetSelectionItem(data)
        //
        //             return false
        //         },
        //     })
        // })
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

        // useConfirmToast(() => {
        //     useRequest(apiRoutes.admin.newsletters.batchDestroy, {
        //         method: 'DELETE',
        //         data: {
        //             ids,
        //         },
        //     }, {
        //         success: () => {
        //             toast.success('عملیات با موفقیت انجام شد.')
        //             datatable.value?.refresh()
        //             datatable.value?.resetSelection()
        //
        //             return false
        //         },
        //     })
        // })
      },
    },
  },
]

const doSearch = (offset, limit, order, sort, text) => {
  table.isLoading = true

  // useRequest(apiRoutes.admin.newsletters.index, {
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

//---------------------------------------
// Newsletter creation form
//---------------------------------------

const canSubmit = ref(true)

const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return
})

//---------------------------------------
</script>

<style scoped>

</style>
