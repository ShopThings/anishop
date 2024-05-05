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

    <div class="p-2 w-full md:w-1/2">
      <base-input
        :value="settingValues[SETTING_KEYS.TITLE]"
        label-title="عنوان سایت"
        name="title"
        placeholder="وارد نمایید"
      >
        <template #icon>
          <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
        </template>
      </base-input>
    </div>

    <div class="p-2">
      <partial-input-label title="کلمات کلیدی"/>
      <base-tags-input
        :add-tag-on-keys="[13, 188]"
        :tags="tags"
        placeholder="کلمات کلیدی خود را وارد نمایید"
        @on-tags-changed="(t) => {tags = t}"
      />
      <partial-input-error-message :error-message="errors.keywords"/>
    </div>

    <div class="p-2">
      <base-textarea
        :value="settingValues[SETTING_KEYS.DESCRIPTION]"
        label-title="توضیحات مختصر"
        name="description"
      >
        <template #icon>
          <InformationCircleIcon class="h-6 w-6 mt-3 text-gray-400"/>
        </template>
      </base-textarea>
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
import {nextTick, reactive, ref} from "vue";
import yup from "@/validation/index.js";
import BaseInput from "@/components/base/BaseInput.vue";
import {ArrowLeftCircleIcon, CheckIcon, InformationCircleIcon} from "@heroicons/vue/24/outline/index.js";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseTextarea from "@/components/base/BaseTextarea.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import {SETTING_KEYS} from "@/composables/constants.js";
import BaseTagsInput from "@/components/base/BaseTagsInput.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {findItemByKey} from "@/composables/helper.js";
import {watchImmediate} from "@vueuse/core";
import {SettingAPI} from "@/service/APIConfig.js";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import LoaderDotOrbit from "@/components/base/loader/LoaderDotOrbit.vue";

const props = defineProps({
  setting: {
    type: Array,
    required: true,
  },
  isFetching: {
    type: Boolean,
    default: false,
  },
})
const emit = defineEmits(['updated'])

const tags = ref([])
const settingValues = reactive({})

watchImmediate(() => props.setting, () => {
  nextTick(() => {
    settingValues[SETTING_KEYS.TITLE] = findItemByKey(props.setting, 'name', SETTING_KEYS.TITLE)?.value || ''
    settingValues[SETTING_KEYS.DESCRIPTION] = findItemByKey(props.setting, 'name', SETTING_KEYS.DESCRIPTION)?.value || ''
    tags.value = findItemByKey(props.setting, 'name', SETTING_KEYS.KEYWORDS)?.value || []
  })
})

//----------------------------
const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    title: yup.string().required('عنوان را وارد نمایید.'),
    description: yup.string().required('توضیحات مختصر را وارد نمایید.'),
  }),
}, (values, actions) => {
  if (props.isFetching) return

  if (!tags.value?.length) {
    actions.setFieldError('keywords', 'کلمات کلیدی را وارد نمایید.')
    return
  }

  canSubmit.value = false

  const updateArr = {
    [SETTING_KEYS.TITLE]: values.title,
    [SETTING_KEYS.DESCRIPTION]: values.description,
    [SETTING_KEYS.KEYWORDS]: tags.value,
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
