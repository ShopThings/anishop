<template>
  <partial-card class="border-0 p-3">
    <template #body>
      <form @submit.prevent="onSubmit">
        <div class="flex flex-wrap">
          <div class="p-2 w-full sm:w-1/2 xl:w-1/3">
            <base-input
              label-title="نام گیرنده"
              name="full_name"
              placeholder="وارد نمایید"
            >
              <template #icon>
                <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
              </template>
            </base-input>
          </div>
          <div class="p-2 w-full sm:w-1/2 xl:w-1/3">
            <base-input
              label-title="شماره تماس"
              name="mobile"
              placeholder="وارد نمایید"
            >
              <template #icon>
                <DevicePhoneMobileIcon class="h-6 w-6 text-gray-400"/>
              </template>
            </base-input>
          </div>
          <div class="p-2 w-full sm:w-1/2 xl:w-1/3">
            <partial-input-label title="انتخاب استان"/>
            <base-select-searchable
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
          <div class="p-2 w-full sm:w-1/2 xl:w-1/3">
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
          <div class="p-2 w-full sm:w-1/2 xl:w-1/3">
            <base-input
              label-title="کدپستی"
              :is-optional="true"
              klass="no-spin-arrow"
              name="postal_code"
              type="number"
              placeholder="وارد نمایید"
            >
              <template #icon>
                <HashtagIcon class="h-6 w-6 text-gray-400"/>
              </template>
            </base-input>
          </div>
          <div class="p-2 w-full">
            <base-textarea
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

            <span class="ml-auto">افزودن آدرس</span>
          </base-animated-button>
        </div>
      </form>
    </template>
  </partial-card>
</template>

<script setup>
import {inject, onMounted, ref} from "vue";
import yup, {transformNumbersToEnglish} from "@/validation/index.js";
import BaseInput from "@/components/base/BaseInput.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import {ArrowLeftCircleIcon, CheckIcon, DevicePhoneMobileIcon, HashtagIcon,} from "@heroicons/vue/24/outline/index.js";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseTextarea from "@/components/base/BaseTextarea.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {ProvinceAPI} from "@/service/APIShop.js";
import BaseSelectSearchable from "@/components/base/BaseSelectSearchable.vue";
import {UserPanelAddressAPI} from "@/service/APIUserPanel.js";
import {useRouter} from "vue-router";

const router = useRouter()
const countingStore = inject('countingStore')

const provinces = ref([])
const provinceLoading = ref(true)
const selectedProvince = ref(null)

//------------------------------------------
// City operations
//------------------------------------------
const cities = ref([])
const cityLoading = ref(false)
const selectedCity = ref(null)
const citySelectRef = ref(null)

function loadCities() {
  if (selectedProvince.value && selectedProvince.value?.id) {
    citySelectRef.value.removeSelectedItems()
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

  UserPanelAddressAPI.create({
    full_name: values.full_name,
    mobile: values.mobile,
    address: values.address,
    postal_code: values?.postal_code,
    province: selectedProvince.value.id,
    city: selectedCity.value.id,
  }, {
    success() {
      actions.resetForm()
      countingStore.$reset()

      router.push({name: 'user.addresses'})
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

onMounted(() => {
  ProvinceAPI.fetchAll({
    success: (response) => {
      provinces.value = response.data
      provinceLoading.value = false
    },
  })
})
</script>
