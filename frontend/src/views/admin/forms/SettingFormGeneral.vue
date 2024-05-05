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
      <div class="p-2 w-full md:w-1/2">
        <base-input
          :max="25"
          :min="0"
          :money-mask="true"
          :value="settingValues[SETTING_KEYS.PRODUCT_EACH_PAGE]?.toString()"
          klass="no-spin-arrow"
          label-title="تعداد نمایش کالا در هر صفحه"
          name="product_each_page"
          placeholder="وارد نمایید"
          type="text"
        >
          <template #icon>
            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
          </template>
        </base-input>
      </div>
      <div class="p-2 w-full md:w-1/2">
        <base-input
          :max="25"
          :min="0"
          :money-mask="true"
          :value="settingValues[SETTING_KEYS.BLOG_EACH_PAGE]?.toString()"
          klass="no-spin-arrow"
          label-title="تعداد نمایش بلاگ در هر صفحه"
          name="blog_each_page"
          placeholder="وارد نمایید"
          type="text"
        >
          <template #icon>
            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
          </template>
        </base-input>
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
import {ArrowLeftCircleIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import LoaderDotOrbit from "@/components/base/loader/LoaderDotOrbit.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {SettingAPI} from "@/service/APIConfig.js";
import {watchImmediate} from "@vueuse/core";
import {findItemByKey} from "@/composables/helper.js";

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
  settingValues[SETTING_KEYS.PRODUCT_EACH_PAGE] = findItemByKey(props.setting, 'name', SETTING_KEYS.PRODUCT_EACH_PAGE)?.value || 0
  settingValues[SETTING_KEYS.BLOG_EACH_PAGE] = findItemByKey(props.setting, 'name', SETTING_KEYS.BLOG_EACH_PAGE)?.value || 0
})

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    product_each_page: yup.string()
      .transform(transformNumbersToEnglish)
      .positiveNumber('تعداد محصول برای نمایش باید عددی مثبت و بیشتر از ۱ باشد.', {gt: 1})
      .required('تعداد محصول برای نمایش را وارد نمایید.'),
    blog_each_page: yup.string()
      .transform(transformNumbersToEnglish)
      .positiveNumber('تعداد بلاگ برای نمایش باید عددی مثبت و بیشتر از ۱ باشد.', {gt: 1})
      .required('تعداد بلاگ برای نمایش را وارد نمایید.'),
  }),
}, (values, actions) => {
  if (props.isFetching) return

  canSubmit.value = false

  const updateArr = {
    [SETTING_KEYS.PRODUCT_EACH_PAGE]: values.product_each_page,
    [SETTING_KEYS.BLOG_EACH_PAGE]: values.blog_each_page,
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
</script>
