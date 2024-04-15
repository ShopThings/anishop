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
      <div class="p-2 w-full md:w-1/2 lg:w-1/3">
        <base-input
            :min="0"
            :money-mask="true"
            :value="settingValues[SETTING_KEYS.MIN_FREE_POST_PRICE].toString()"
            name="min_free_post_price"
            placeholder="وارد نمایید"
            type="text"
        >
          <template #label>
            <div class="flex items-center gap-1.5 text-sm">
              <span>حداقل قیمت جهت رایگان شدن هزینه ارسال</span>
              <span class="text-xs text-pink-600">(بر حسب تومان)</span>
            </div>
          </template>
          <template #icon>
            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
          </template>
        </base-input>
        <div class="flex gap-2 items-center mt-2">
          <span class="text-slate-500 text-xl">{{ numberFormat(values?.min_free_post_price ?? 0) }}</span>
          <span class="text-xs text-slate-400">تومان</span>
        </div>
      </div>
      <div class="p-2 w-full md:w-1/2 lg:w-1/3">
        <base-input
            :min="0"
            :money-mask="true"
            :value="settingValues[SETTING_KEYS.DEFAULT_POST_PRICE].toString()"
            name="default_post_price"
            placeholder="وارد نمایید"
            type="text"
        >
          <template #label>
            <div class="flex items-center gap-1.5 text-sm">
              <span>هزینه ارسال پیش‌فرض</span>
              <span class="text-xs text-pink-600">(بر حسب تومان)</span>
            </div>
          </template>
          <template #icon>
            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
          </template>
        </base-input>
        <div class="flex gap-2 items-center mt-2">
          <span class="text-slate-500 text-xl">{{ numberFormat(values?.default_post_price ?? 0) }}</span>
          <span class="text-xs text-slate-400">تومان</span>
        </div>
      </div>
      <div class="p-2 w-full md:w-1/2 lg:w-1/3">
        <base-input
          :is-optional="true"
          :min="0"
          :money-mask="true"
          :value="settingValues[SETTING_KEYS.DIVIDE_PAYMENT_PRICE].toString()"
          name="divide_payment_price"
          placeholder="وارد نمایید"
          type="text"
        >
          <template #label>
            <div class="flex items-center gap-1.5 text-sm">
              <span>مبلغ تقسیم‌بندی پرداخت</span>
              <span class="text-xs text-pink-600">(بر حسب تومان)</span>
            </div>
          </template>
          <template #icon>
            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
          </template>
        </base-input>
        <div class="flex gap-2 items-center mt-2">
          <template v-if="values?.divide_payment_price">
            <span class="text-slate-500 text-xl">{{ numberFormat(values?.divide_payment_price) }}</span>
            <span class="text-xs text-slate-400">تومان</span>
          </template>
          <span v-else>عدم تقسیم‌بندی</span>
        </div>
      </div>
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
import {reactive} from "vue";
import {SETTING_KEYS} from "@/composables/constants.js";
import yup, {transformNumbersToEnglish} from "@/validation/index.js";
import BaseInput from "@/components/base/BaseInput.vue";
import {ArrowLeftCircleIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import {findItemByKey, numberFormat} from "@/composables/helper.js";
import LoaderDotOrbit from "@/components/base/loader/LoaderDotOrbit.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {watchImmediate} from "@vueuse/core";
import {SettingAPI} from "@/service/APIConfig.js";

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

const settingValues = reactive({})

watchImmediate(() => props.setting, () => {
  settingValues[SETTING_KEYS.MIN_FREE_POST_PRICE] = findItemByKey(props.setting, 'name', SETTING_KEYS.MIN_FREE_POST_PRICE)?.value || 0
  settingValues[SETTING_KEYS.DEFAULT_POST_PRICE] = findItemByKey(props.setting, 'name', SETTING_KEYS.DEFAULT_POST_PRICE)?.value || 0
  settingValues[SETTING_KEYS.DIVIDE_PAYMENT_PRICE] = findItemByKey(props.setting, 'name', SETTING_KEYS.DIVIDE_PAYMENT_PRICE)?.value || 0
})

const {canSubmit, values, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    min_free_post_price: yup.string()
        .transform(transformNumbersToEnglish)
        .positiveNumber('حداقل قیمت خرید برای رایگان شدن هزینه ارسال باید عددی مثبت و بیشتر از صفر باشد.', {gt: 0})
        .required('حداقل قیمت خرید برای رایگان شدن هزینه ارسال را وارد نمایید.'),
    default_post_price: yup.string()
        .transform(transformNumbersToEnglish)
        .positiveNumber('هزیته ارسال باید عددی مثبت و بیشتر از صفر باشد.', {gt: 0})
        .required('هزینه ارسال پیش فرض را وارد نمایید.'),
    divide_payment_price: yup.string()
      .transform(transformNumbersToEnglish)
      .positiveNumber('مبلغ تقسیم‌بندی پرداخت باید عددی مثبت و بیشتر از صفر باشد.', {gt: 0})
      .optional(),
  }),
}, (values, actions) => {
  if (props.isFetching) return

  canSubmit.value = false

  const updateArr = {
    [SETTING_KEYS.MIN_FREE_POST_PRICE]: values.min_free_post_price,
    [SETTING_KEYS.DEFAULT_POST_PRICE]: values.default_post_price,
    [SETTING_KEYS.DIVIDE_PAYMENT_PRICE]: values.divide_payment_price,
  }

  SettingAPI.updateSetting(updateArr, {
    success() {
      emit('updated', updateArr)
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
</script>
