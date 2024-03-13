<template>
  <partial-card>
    <template #header>
      افزودن هزینه ارسال برحسب شهرستان
    </template>
    <template #body>
      <div class="p-3">
        <form @submit.prevent="onSubmit">
          <div class="flex flex-wrap">
            <div class="p-2 w-full sm:w-1/2 lg:w-1/3">
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
            <div class="p-2 w-full sm:w-1/2 lg:w-1/3">
              <partial-input-label title="انتخاب شهرستان"/>
              <base-select-searchable
                  ref="citySelectRef"
                  :is-loading="cityLoading"
                  :options="cities"
                  :selected="selectedCity"
                  name="city"
                  options-key="id"
                  options-text="name"
                  @change="(selected) => {selectedCity = selected}"
              />
              <partial-input-error-message :error-message="errors.city"/>
            </div>
            <div class="p-2 w-full sm:w-1/2 lg:w-1/3">
              <base-input
                  :min="0"
                  :money-mask="true"
                  name="post_price"
                  placeholder="وارد نمایید"
                  type="text"
              >
                <template #label>
                  <div class="flex items-center gap-1.5 text-sm">
                    <span>هزینه ارسال</span>
                    <span class="text-xs text-pink-600">(بر حسب تومان)</span>
                  </div>
                </template>
                <template #icon>
                  <CurrencyDollarIcon class="h-6 w-6 text-gray-400"/>
                </template>
              </base-input>
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

              <span class="ml-auto">افزودن هزینه ارسال</span>
            </base-animated-button>

            <div
                v-if="Object.keys(errors)?.length"
                class="text-left"
            >
              <div
                  class="w-full sm:w-auto sm:inline-block text-center text-sm border-2 border-rose-500 bg-rose-50 rounded-full py-1 px-3 mt-2"
              >
                (
                <span>{{ Object.keys(errors)?.length }}</span>
                )
                خطا، لطفا بررسی کنید
              </div>
            </div>
          </div>
        </form>
      </div>
    </template>
  </partial-card>
</template>

<script setup>
import {onMounted, ref} from "vue";
import yup, {transformNumbersToEnglish} from "@/validation/index.js";
import PartialCard from "@/components/partials/PartialCard.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {CheckIcon, CurrencyDollarIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {CityPostPriceAPI, ProvinceAPI} from "@/service/APIShop.js";
import {useRouter} from "vue-router";
import BaseSelectSearchable from "@/components/base/BaseSelectSearchable.vue";

const router = useRouter()

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
    if (citySelectRef.value) {
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

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    post_price: yup.string()
        .transform(transformNumbersToEnglish)
        .positiveNumber('هزیته ارسال باید عددی مثبت و بیشتر از صفر باشد.', {gt: 0})
        .required('هزینه ارسال را وارد نمایید.'),
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

  CityPostPriceAPI.create({
    province: selectedProvince.value.id,
    city: selectedCity.value.id,
    post_price: values.post_price,
  }, {
    success() {
      actions.resetForm()
      router.push({name: 'admin.post_prices.cities'})
    },
    error(error) {
      if (error.errors && Object.keys(error.errors).length >= 1)
        actions.setErrors(error.errors)
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
