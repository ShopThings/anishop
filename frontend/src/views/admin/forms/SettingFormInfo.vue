<template>
  <form @submit.prevent="onSubmit">
    <div class="flex flex-wrap">
      <div class="p-2 w-full sm:w-1/2 lg:w-1/3">
        <partial-input-label title="انتخاب استان"/>
        <base-select
          :options="provinces"
          options-key="id"
          options-text="name"
          :is-loading="provinceLoading"
          :selected="selectedProvince"
          name="province"
          @change="(status) => {selectedProvince = status}"
        />
        <partial-input-error-message :error-message="errors.province"/>
      </div>
      <div class="p-2 w-full sm:w-1/2 lg:w-1/3">
        <partial-input-label title="انتخاب شهرستان"/>
        <base-select
          :options="cities"
          options-key="id"
          options-text="name"
          :is-loading="cityLoading"
          :selected="selectedCity"
          name="city"
          @change="(status) => {selectedCity = status}"
        />
        <partial-input-error-message :error-message="errors.city"/>
      </div>
    </div>

    <div class="p-2">
      <base-textarea
        label-title="آدرس محل فعالیت"
        name="address"
        :value="setting[SETTING_KEYS.ADDRESS] ?? ''"
      >
        <template #icon>
          <InformationCircleIcon class="h-6 w-6 mt-3 text-gray-400"/>
        </template>
      </base-textarea>
    </div>

    <div class="p-2">
      <partial-input-label title="شماره‌های تماس"/>
      <base-tags-input
        :tags="phones"
        placeholder="وارد نمایید"
        @on-tags-changed="(t) => {phones = t}"
      />
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
          label-title="طول جغرافیایی"
          placeholder="وارد نمایید"
          name="longitude"
          :value="mapSettings.center[1].toString()"
        >
          <template #icon>
            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
          </template>
        </base-input>
      </div>
      <div class="p-2 w-full sm:w-1/2 lg:w-1/3">
        <base-input
          label-title="عرض جغرافیایی"
          placeholder="وارد نمایید"
          name="latitude"
          :value="mapSettings.center[0].toString()"
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
        type="submit"
        class="bg-emerald-500 text-white mr-auto px-6 w-full sm:w-auto"
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

        <span class="ml-auto">ذخیره اطلاعات</span>
      </base-animated-button>
    </div>
  </form>
</template>

<script setup>
import {onMounted, reactive, ref} from "vue";
import {SETTING_KEYS} from "@/composables/constants.js";
import {useForm} from "vee-validate";
import yup from "@/validation/index.js";
import {ArrowLeftCircleIcon, CheckIcon, InformationCircleIcon, MapIcon} from "@heroicons/vue/24/outline/index.js";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import BaseTextarea from "@/components/base/BaseTextarea.vue";
import BaseSelect from "@/components/base/BaseSelect.vue";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import BaseMap from "@/components/base/BaseMap.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseTagsInput from "@/components/base/BaseTagsInput.vue";

const props = defineProps({
  setting: {
    type: Object,
    required: true,
  },
})

const canSubmit = ref(true)

const phones = ref([])
phones.value = props.setting[SETTING_KEYS.PHONES]?.split(',') ?? []

const provinces = ref([])
const cities = ref([])
const provinceLoading = ref(true)
const cityLoading = ref(true)

const selectedProvince = ref(null)
const selectedCity = ref(null)

const mapLoading = ref(true)
const mapSettings = reactive({
  center: [35.7057502, 51.3097834],
  zoom: 14,
  allowEditMarker: true,
  allowFindMyLocation: true,
})

const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return
})

onMounted(() => {
  const latlng = props.setting[SETTING_KEYS.LAT_LNG]
  if (latlng) mapSettings.center = latlng

  setTimeout(() => {
    mapLoading.value = false
  }, 1000)
})
</script>
