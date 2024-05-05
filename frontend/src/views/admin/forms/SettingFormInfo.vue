<template>
  <form
    class="relative"
    @submit.prevent="onSubmit"
  >
    <loader-dot-orbit
      v-if="isFetching"
      container-bg-color="bg-blue-50 opacity-40"
      loading-text="در حال بارگذاری تنظیمات"
      main-container-klass="absolute w-full h-full top-0 left-0 z-[2]"
    />

    <div class="flex flex-wrap">
      <div class="p-2 w-full sm:w-1/2 lg:w-1/3">
        <partial-input-label title="انتخاب استان"/>
        <base-select-searchable
          :is-loading="provinceLoading"
          :options="provinces"
          :selected="selectedProvince"
          name="store_province"
          options-key="id"
          options-text="name"
          @change="handleProvinceChange"
        />
        <partial-input-error-message :error-message="errors.store_province"/>
      </div>
      <div class="p-2 w-full sm:w-1/2 lg:w-1/3">
        <partial-input-label title="انتخاب شهرستان"/>
        <base-select-searchable
          ref="citySelectRef"
          :is-loading="cityLoading"
          :options="cities"
          :selected="selectedCity"
          name="store_city"
          options-key="id"
          options-text="name"
          @change="(status) => {selectedCity = status}"
        />
        <partial-input-error-message :error-message="errors.store_city"/>
      </div>
    </div>

    <div class="p-2">
      <base-textarea
        :value="settingValues[SETTING_KEYS.ADDRESS]"
        name="address"
      >
        <template #label>
          <div class="flex items-center gap-1.5 text-sm">
            <span>آدرس محل فعالیت</span>
            <span class="text-xs text-pink-600">(به صورت کامل و با درج استان و شهر وارد نمایید.)</span>
          </div>
        </template>
        <template #icon>
          <InformationCircleIcon class="h-6 w-6 mt-3 text-gray-400"/>
        </template>
      </base-textarea>
    </div>

    <div class="p-2">
      <partial-input-label>
        <template #label>
          <div class="flex gap-2 5 items-center">
            <span>شماره‌های تماس</span>
            <div class="text-xs text-pink-600">
              (
              لطفا شماره‌های تماس را به فرمت
              <div class="py-1 px-2 rounded border-2 border-pink-400 text-black inline-block mx-1">
                <span>09xxxxxxxxx</span>
                &nbsp
                <span>نام مالک</span>
              </div>
              یا
              <div class="py-1 px-2 rounded border-2 border-pink-400 text-black inline-block mx-1">
                <span>+989xxxxxxxxx</span>
                &nbsp
                <span>نام مالک</span>
              </div>
              وارد نمایید.
              )
            </div>
          </div>
        </template>
      </partial-input-label>
      <base-tags-input
        :add-tag-on-keys="[13, 188]"
        :tags="phones"
        placeholder="وارد نمایید"
        @on-tags-changed="(t) => {phones = t}"
      />
      <partial-input-error-message :error-message="errors.phone"/>
    </div>

    <div class="flex flex-wrap mt-6 border-2 rounded-md border-orange-500">
      <div class="w-full p-3 text-sm bg-orange-50 rounded-t-md">
        از یک نقشه مانند گوگل یا پارسی مپ کمک بگیرید و
        <code class="rounded bg-sky-300 py-1 px-2">lng</code>
        و
        <code class="rounded bg-sky-300 py-1 px-2">lat</code>
        یا همان طول و عرض جغرافیفایی آدرس خود را وارد کنید.
      </div>
      <div class="p-2 w-full sm:w-1/2 lg:w-1/3">
        <base-input
          :value="mapSettings.center[1].toString()"
          label-title="طول جغرافیایی"
          name="longitude"
          placeholder="وارد نمایید"
          @input="changeLongitude"
        >
          <template #icon>
            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
          </template>
        </base-input>
      </div>
      <div class="p-2 w-full sm:w-1/2 lg:w-1/3">
        <base-input
          :value="mapSettings.center[0].toString()"
          label-title="عرض جغرافیایی"
          name="latitude"
          placeholder="وارد نمایید"
          @input="changeLatitude"
        >
          <template #icon>
            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
          </template>
        </base-input>
      </div>
    </div>

    <div class="p-2 mt-3">
      <div class="mb-3 text-slate-500">
        یا با استفاده از نقشه زیر محل فعالیت خود را مشخص نمایید
        <small class="text-rose-500 py-1 px-2 bg-rose-100 rounded mr-2">
          برای تغییر موقعیت، مکان نما را جابجا نمایید.
        </small>
      </div>

      <base-loading-panel :loading="mapLoading">
        <template #loader>
          <div
            class="px-3 py-6 h-96 flex justify-center items-center flex-col gap-3 animate-pulse">
            <MapIcon class="h-16 w-16 text-slate-400"/>
            <span class="text-orange-300">در حال بارگذاری نقشه</span>
          </div>
        </template>
        <template #content>
          <base-map
            v-model:center="mapSettings.center"
            v-model:zoom="mapSettings.zoom"
            :allow-edit-marker="mapSettings.allowEditMarker"
            :allow-find-my-location="mapSettings.allowFindMyLocation"
          />
        </template>
      </base-loading-panel>
    </div>

    <div class="px-2 py-3">
      <base-animated-button
        :disabled="!canSubmit || isFetching"
        class="bg-emerald-500 text-white mr-auto px-6 w-full sm:w-auto"
        type="submit"
      >
        <VTransitionFade>
          <loader-circle
            v-if="!canSubmit || isFetching"
            big-circle-color="border-transparent"
            main-container-klass="absolute w-full h-full top-0 left-0"
          />
        </VTransitionFade>

        <template #icon="{klass}">
          <CheckIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
        </template>

        <span class="ml-auto">ذخیره اطلاعات</span>
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
</template>

<script setup>
import {onMounted, reactive, ref} from "vue";
import {SETTING_KEYS} from "@/composables/constants.js";
import yup from "@/validation/index.js";
import {ArrowLeftCircleIcon, CheckIcon, InformationCircleIcon, MapIcon} from "@heroicons/vue/24/outline/index.js";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import BaseTextarea from "@/components/base/BaseTextarea.vue";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import BaseMap from "@/components/base/BaseMap.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseTagsInput from "@/components/base/BaseTagsInput.vue";
import LoaderDotOrbit from "@/components/base/loader/LoaderDotOrbit.vue";
import {watchImmediate} from "@vueuse/core";
import {findItemByKey} from "@/composables/helper.js";
import {SettingAPI} from "@/service/APIConfig.js";
import {useFormSubmit} from "@/composables/form-submit.js";
import {ProvinceAPI} from "@/service/APIShop.js";
import BaseSelectSearchable from "@/components/base/BaseSelectSearchable.vue";

const props = defineProps({
  setting: {
    type: Object,
    required: true,
  },
  isFetching: {
    type: Boolean,
    default: false,
  },
})
const emit = defineEmits(['updated'])


//------------------------------------------
// Map operations
//------------------------------------------
const mapLoading = ref(true)
const mapSettings = reactive({
  center: [35.7057502, 51.3097834],
  zoom: 14,
  allowEditMarker: true,
  allowFindMyLocation: true,
})

function changeLongitude(text) {
  let t = parseFloat(text)
  if (!isNaN(t)) {
    mapSettings.center = [mapSettings.center[0], t]
  }
}

function changeLatitude(text) {
  let t = parseFloat(text)
  if (!isNaN(t)) {
    mapSettings.center = [t, mapSettings.center[1]]
  }
}

//------------------------------------------
const phones = ref([])
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

const settingValues = reactive({})

watchImmediate(() => props.setting, () => {
  selectedProvince.value = findItemByKey(props.setting, 'name', SETTING_KEYS.STORE_PROVINCE)?.value || null
  selectedCity.value = findItemByKey(props.setting, 'name', SETTING_KEYS.STORE_CITY)?.value || null
  loadCities(false)

  phones.value = findItemByKey(props.setting, 'name', SETTING_KEYS.PHONES)?.value || []

  const latlng = findItemByKey(props.setting, 'name', SETTING_KEYS.LAT_LNG)?.value || []
  if (latlng?.length) mapSettings.center = latlng

  settingValues[SETTING_KEYS.ADDRESS] = findItemByKey(props.setting, 'name', SETTING_KEYS.ADDRESS)?.value || ''
})

//------------------------------------------
const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    address: yup.string().required('آدرس محل فعالیت را وارد نمایید.'),
    longitude: yup.string().required('طول جغرافیایی را وارد نمایید.'),
    latitude: yup.string().required('عرض جغرافیایی را وارد نمایید.'),
  }),
}, (values, actions) => {
  if (props.isFetching) return

  if (!selectedProvince.value || !selectedProvince.value?.id) {
    actions.setFieldError('store_province', 'استان محل فعالیت فروشگاه را انتخاب نمایید.')
    return
  }

  if (!selectedCity.value || !selectedCity.value?.id) {
    actions.setFieldError('store_city', 'شهر محل فعالیت فروشگاه را انتخاب نمایید.')
    return
  }

  if (!phones.value?.length) {
    actions.setFieldError('phones', 'وارد نمودن حداقل یک شماره تماس الزامی می‌باشد.')
    return
  }

  canSubmit.value = false

  const updateArr = {
    [SETTING_KEYS.STORE_PROVINCE]: selectedProvince.value.id,
    [SETTING_KEYS.STORE_CITY]: selectedCity.value.id,
  }

  SettingAPI.updateSetting(updateArr, {
    success() {
      emit('updated', updateArr)
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
  setTimeout(() => {
    mapLoading.value = false
  }, 1000)

  ProvinceAPI.fetchAll({
    success: (response) => {
      provinces.value = response.data
      provinceLoading.value = false
    },
  })
})
</script>
