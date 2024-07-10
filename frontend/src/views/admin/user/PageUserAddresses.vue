<template>
  <partial-card ref="tableContainer">
    <template #header>
      نمایش آدرس‌های کاربر -
      <span
        v-if="user?.id"
        class="text-slate-400 text-base"
      ><partial-username-label v-if="user" :user="user"/></span>
    </template>

    <template #body>
      <base-loading-panel :loading="loading" type="table">
        <template #content>
          <div class="p-3">
            <base-dialog v-model:open="openDialog" @close="dialogCloseHandler">
              <template #button="{open}">
                <base-animated-button
                  class="w-full mr-auto bg-emerald-500 sm:w-auto"
                  @click="open"
                >
                  <template #icon="{klass}">
                    <BookOpenIcon :class="klass" class="w-6 h-6 ml-auto sm:ml-2"/>
                  </template>

                  <span class="ml-auto">افزودن آدرس</span>
                </base-animated-button>
              </template>

              <template #title>
                افزودن آدرس برای کاربر
              </template>

              <template #body>
                <form @submit.prevent="onSubmit">
                  <div class="flex flex-wrap">
                    <div class="p-2 w-full">
                      <base-input
                        :value="editingAddress?.full_name"
                        label-title="نام گیرنده"
                        name="full_name"
                        placeholder="وارد نمایید"
                      >
                        <template #icon>
                          <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                        </template>
                      </base-input>
                    </div>
                    <div class="p-2 w-full">
                      <base-input
                        :value="editingAddress?.mobile"
                        label-title="شماره تماس"
                        name="mobile"
                        placeholder="وارد نمایید"
                      >
                        <template #icon>
                          <DevicePhoneMobileIcon class="h-6 w-6 text-gray-400"/>
                        </template>
                      </base-input>
                    </div>
                    <div class="p-2 w-full">
                      <partial-input-label title="انتخاب استان"/>
                      <base-select-searchable
                        ref="provinceSelectRef"
                        :is-loading="provinceLoading"
                        :options="provinces"
                        :selected="selectedProvince"
                        name="province"
                        options-key="id"
                        options-text="name"
                        @change="handleProvinceChange"
                      />
                      <partial-input-error-message :error-message="errors.province"/>
                    </div>
                    <div class="p-2 w-full">
                      <partial-input-label title="انتخاب شهرستان"/>
                      <base-select-searchable
                        ref="citySelectRef"
                        :is-loading="cityLoading"
                        :options="cities"
                        :selected="selectedCity"
                        name="city"
                        options-key="id"
                        options-text="name"
                        @change="(status) => {selectedCity = status}"
                      />
                      <partial-input-error-message :error-message="errors.city"/>
                    </div>
                    <div class="p-2 w-full">
                      <base-input
                        :is-optional="true"
                        :value="editingAddress?.postal_code"
                        klass="no-spin-arrow"
                        label-title="کدپستی"
                        name="postal_code"
                        placeholder="وارد نمایید"
                        type="number"
                      >
                        <template #icon>
                          <HashtagIcon class="h-6 w-6 text-gray-400"/>
                        </template>
                      </base-input>
                    </div>
                    <div class="p-2 w-full">
                      <base-textarea
                        :value="editingAddress?.address"
                        label-title="آدرس محل سکونت"
                        name="address"
                        placeholder="آدرس داخل شهرستان را وارد نمایید"
                      >
                        <template #icon>
                          <ArrowLeftCircleIcon class="h-6 w-6 mt-3 text-gray-400"/>
                        </template>
                      </base-textarea>
                    </div>
                  </div>

                  <div class="px-2 py-3">
                    <base-animated-button
                      :disabled="!canSubmit"
                      class="bg-emerald-500 text-white mr-auto px-6 w-full sm:w-auto"
                      type="submit"
                    >
                      <VTransitionFade>
                        <loader-circle
                          v-if="!canSubmit"
                          big-circle-color="border-transparent"
                          main-container-klass="absolute w-full h-full top-0 left-0"
                        />
                      </VTransitionFade>

                      <template #icon="{klass}">
                        <CheckIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
                      </template>

                      <span v-if="editingAddress" class="ml-auto">ویرایش آدرس</span>
                      <span v-else class="ml-auto">افزودن آدرس</span>
                    </base-animated-button>
                  </div>
                </form>
              </template>
            </base-dialog>
          </div>

          <base-datatable
            ref="datatable"
            :columns="table.columns"
            :enable-multi-operation="false"
            :enable-search-box="true"
            :has-checkbox="false"
            :is-loading="table.isLoading"
            :is-slot-mode="true"
            :rows="table.rows"
            :selection-columns="table.selectionColumns"
            :sortable="table.sortable"
            :total="table.totalRecordCount"
            @do-search="doSearch"
          >
            <template #id="{index}">
              {{ index }}
            </template>

            <template #city_name="{value}">
              {{ value.city.name }}
            </template>
            <template #province_name="{value}">
              {{ value.province.name }}
            </template>

            <template v-slot:op="{value}">
              <base-button
                class="text-white bg-black text-sm !py-1"
                @click="showDetails(value)"
              >
                مشاهده جزئیات
              </base-button>
            </template>

            <template v-slot:created_at="{value}">
              <span v-if="value.created_at" class="text-xs">{{ value.created_at }}</span>
              <span v-else><MinusIcon class="h-5 w-5 text-rose-500"/></span>
            </template>

            <template v-slot:op2="{value}">
              <base-datatable-menu
                :container="getMenuContainer"
                :data="value"
                :items="operations"
                :removals="calcRemovals(value)"
              />
            </template>
          </base-datatable>

          <partial-dialog v-model:open="isDetailOpen">
            <template #title>
              جزئیات آدرس
            </template>

            <template #body>
              <ul class="divide-y">
                <li class="flex items-center gap-2 py-1.5">
                  <span class="text-slate-400 text-sm shrink-0">نام:</span>
                  <span class="grow text-sm">{{ detailItem.full_name }}</span>
                </li>
                <li class="flex items-center gap-2 py-1.5">
                  <span class="text-slate-400 text-sm shrink-0">موبایل:</span>
                  <span class="grow text-sm">{{ detailItem.mobile }}</span>
                </li>
                <li class="flex items-center gap-2 py-1.5">
                  <span class="text-slate-400 text-sm shrink-0">کدپستی:</span>
                  <span class="grow text-sm">{{ detailItem.postal_code || '-' }}</span>
                </li>
                <li class="flex items-center gap-2 py-1.5">
                  <span class="text-slate-400 text-sm shrink-0">آدرس:</span>
                  <span class="grow text-sm">{{ detailItem.address }}</span>
                </li>
                <li class="flex items-center gap-2 py-1.5">
                  <span class="text-slate-400 text-sm shrink-0">شهر:</span>
                  <span class="grow text-sm">{{ detailItem.city.name }}</span>
                </li>
                <li class="flex items-center gap-2 py-1.5">
                  <span class="text-slate-400 text-sm shrink-0">استان:</span>
                  <span class="grow text-sm">{{ detailItem.province.name }}</span>
                </li>
                <li class="flex items-center gap-2 py-1.5">
                  <span class="text-slate-400 text-sm shrink-0">ایجاد شده در تاریخ:</span>
                  <span class="grow text-sm">{{ detailItem.created_at }}</span>
                </li>
              </ul>
            </template>
          </partial-dialog>
        </template>
      </base-loading-panel>
    </template>
  </partial-card>
</template>

<script setup>
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseDatatable from "@/components/base/BaseDatatable.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import {
  ArrowLeftCircleIcon,
  BookOpenIcon,
  CheckIcon,
  DevicePhoneMobileIcon,
  HashtagIcon,
  MinusIcon,
} from "@heroicons/vue/24/outline/index.js";
import {computed, onMounted, reactive, ref} from "vue";
import BaseButton from "@/components/base/BaseButton.vue";
import {useRoute} from "vue-router";
import {UserAddressAPI, UserAPI} from "@/service/APIUser.js";
import {getRouteParamByKey} from "@/composables/helper.js";
import PartialDialog from "@/components/partials/PartialDialog.vue";
import PartialUsernameLabel from "@/components/partials/PartialUsernameLabel.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseDialog from "@/components/base/BaseDialog.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import yup, {transformNumbersToEnglish} from "@/validation/index.js";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import BaseTextarea from "@/components/base/BaseTextarea.vue";
import BaseSelectSearchable from "@/components/base/BaseSelectSearchable.vue";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import {ProvinceAPI} from "@/service/APIShop.js";
import BaseDatatableMenu from "@/components/base/datatable/BaseDatatableMenu.vue";
import {hideAllPoppers} from "floating-vue";
import {useConfirmToast} from "@/composables/toast-helper.js";
import {PERMISSION_PLACES, PERMISSIONS, ROLES, useAdminAuthStore} from "@/store/StoreUserAuth.js";
import {useToast} from "vue-toastification";

const route = useRoute()
const toast = useToast()
const idParam = getRouteParamByKey('id')
const userStore = useAdminAuthStore()

const user = ref(null)
const editingAddress = ref(null)

const openDialog = ref(false)

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
      label: "نام و نام خانوادگی",
      field: "full_name",
      sortable: true,
    },
    {
      label: "شماره موبایل",
      field: "mobile",
      sortable: true,
    },
    {
      label: "استان",
      field: "province_name",
      sortable: true,
    },
    {
      label: "شهر",
      field: "city_name",
      sortable: true,
    },
    {
      label: '',
      field: 'op',
      width: '7%',
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
      field: 'op2',
      width: '7%',
      show: userStore.hasPermission(PERMISSION_PLACES.ADDRESS_USER, [PERMISSIONS.UPDATE, PERMISSIONS.DELETE])
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

  if (
    !userStore.hasRole(ROLES.DEVELOPER) ||
    !userStore.hasPermission(PERMISSION_PLACES.ADDRESS_USER, PERMISSIONS.DELETE)
  ) {
    removals.push('delete')
  }

  if (!userStore.hasPermission(PERMISSION_PLACES.ADDRESS_USER, PERMISSIONS.UPDATE)) {
    removals.push('edit')
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
        editingAddress.value = data
        selectedProvince.value = data.province
        selectedCity.value = data.city

        loadCities(false)

        openDialog.value = true
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
          UserAddressAPI.deleteById(idParam.value, data.id, {
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

const detailItem = ref(null)
const isDetailOpen = ref(false)

function showDetails(value) {
  isDetailOpen.value = true
  detailItem.value = value
}

const doSearch = (offset, limit, order, sort, text) => {
  table.isLoading = true

  UserAddressAPI.fetchAll(idParam.value, {
    success(response) {
      table.rows = response.data
      table.totalRecordCount = response.data.length

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


//------------------------------------------
// City operations
//------------------------------------------
const provinces = ref([])
const provinceLoading = ref(true)
const selectedProvince = ref(null)
const provinceSelectRef = ref(null)

const cities = ref([])
const cityLoading = ref(false)
const selectedCity = ref(null)
const citySelectRef = ref(null)

function loadCities(clearSelection) {
  if (selectedProvince.value && selectedProvince.value?.id) {
    if (citySelectRef.value && clearSelection !== false) {
      citySelectRef.value.removeSelectedItems()
    }
    cityLoading.value = true

    ProvinceAPI.fetchCities(selectedProvince.value.id, {
      success: (response) => {
        cities.value = response.data
        cityLoading.value = false
      },
    })
  }
}

//------------------------------------------
function handleProvinceChange(selected) {
  selectedProvince.value = selected
  loadCities()
}

//---------------------------------------
// Address form operation
//---------------------------------------
const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    address: yup.string().required('آدرس خود را وارد نمایید.'),
    postal_code: yup.string()
      .optional()
      .transform(transformNumbersToEnglish)
      .justNumber('کدپستی باید از نوع عددی باشد.'),
    full_name: yup.string()
      .persian('نام باید از حروف فارسی باشد.')
      .required('نام را وارد نمایید.'),
    mobile: yup.string()
      .transform(transformNumbersToEnglish)
      .persianMobile('شماره موبایل نامعتبر است.')
      .required('موبایل را وارد نمایید.'),
  }),
}, (values, actions) => {
  if (!selectedProvince.value || !selectedProvince.value?.id) {
    actions.setFieldError('province', 'استان را انتخاب نمایید.')
    return
  }

  if (!selectedCity.value || !selectedCity.value?.id) {
    actions.setFieldError('city', 'شهر را انتخاب نمایید.')
    return
  }

  canSubmit.value = false

  const data = {
    full_name: values.full_name,
    mobile: values.mobile,
    address: values.address,
    postal_code: values?.postal_code,
    province: selectedProvince.value.id,
    city: selectedCity.value.id,
  }
  const callbacks = {
    success() {
      actions.resetForm()
      openDialog.value = false

      datatable.value?.refresh()
      datatable.value?.resetSelectionItem(data)
    },
    error(error) {
      if (error?.errors && Object.keys(error.errors).length >= 1) {
        actions.setErrors(error.errors)
      }
    },
    finally() {
      canSubmit.value = true
    },
  }

  if (editingAddress.value) {
    UserAddressAPI.updateById(idParam.value, editingAddress.value.id, data, callbacks)
  } else {
    UserAddressAPI.create(idParam.value, data, callbacks)
  }
})

function dialogCloseHandler() {
  editingAddress.value = null
  provinceSelectRef.value.removeSelectedItems()
  citySelectRef.value.removeSelectedItems()
}

//---------------------------------------
onMounted(() => {
  UserAPI.fetchById(idParam.value, {
    success(response) {
      user.value = response.data
    },
  })

  ProvinceAPI.fetchAll({
    success: (response) => {
      provinces.value = response.data
      provinceLoading.value = false
    },
  })
})
</script>
