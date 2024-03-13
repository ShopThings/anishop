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

    <div class="p-2">
      <base-textarea
          :value="settingValues[SETTING_KEYS.FOOTER_DESCRIPTION]"
          label-title="توضیحات مختصر"
          name="footer_description"
      >
        <template #icon>
          <InformationCircleIcon class="h-6 w-6 mt-3 text-gray-400"/>
        </template>
      </base-textarea>
    </div>

    <hr class="border-0 w-36 sm:w-48 h-1 rounded-full bg-slate-200 my-6 mx-auto">

    <div class="p-2">
      <base-input
          :value="settingValues[SETTING_KEYS.FOOTER_COPYRIGHT]"
          label-title="حق مالکیت/کپی‌رایت"
          name="footer_copyright"
          placeholder="وارد نمایید"
      >
        <template #icon>
          <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
        </template>
      </base-input>
    </div>

    <hr class="border-0 w-36 sm:w-48 h-1 rounded-full bg-slate-200 my-6 mx-auto">

    <partial-input-error-message :error-message="errors.footer_namads"/>
    <div class="flex mb-3 items-end">
      <div class="grow flex flex-col gap-3">
        <div
            v-for="(item, idx) in namads"
            :key="item.id"
            class="relative border rounded-md border-violet-500"
        >
          <partial-builder-remove-btn
              v-if="namads.length > 1"
              @click="removeNamadHandler(idx)"
          />

          <div class="p-2">
            <base-textarea
                :label-title="'لینک نماد - نماد شماره ' + (item.id)"
                :name="'footer_namad_' + item.id"
                :value="item.link"
                @input="(val) => {item.link = val}"
            >
              <template #icon>
                <InformationCircleIcon class="h-6 w-6 mt-3 text-gray-400"/>
              </template>
            </base-textarea>
          </div>
        </div>
      </div>
      <div class="p-2">
        <button
            v-tooltip.right="'افزودن نماد جدید'"
            class="rounded-full p-3 flex justify-center items-center bg-emerald-400 text-white group"
            type="button"
            @click="addNamadHandler"
        >
          <PlusIcon class="w-6 h-6 group-hover:rotate-90 transition"/>
        </button>
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
import {reactive, ref} from "vue";
import {SETTING_KEYS} from "@/composables/constants.js";
import yup from "@/validation/index.js";
import {ArrowLeftCircleIcon, CheckIcon, InformationCircleIcon, PlusIcon} from "@heroicons/vue/24/outline/index.js";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import BaseTextarea from "@/components/base/BaseTextarea.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import PartialBuilderRemoveBtn from "@/components/partials/PartialBuilderRemoveBtn.vue";
import LoaderDotOrbit from "@/components/base/loader/LoaderDotOrbit.vue";
import {SettingAPI} from "@/service/APIConfig.js";
import {useFormSubmit} from "@/composables/form-submit.js";
import {watchImmediate} from "@vueuse/core";
import {findItemByKey} from "@/composables/helper.js";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";

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

//---------------------------
// Namads Stuffs
//---------------------------
const namads = ref([])

function addNamadHandler() {
  namads.value.push({
    id: (namads.value[namads.value.length - 1]?.id || 0) + 1,
    link: '',
  })
}

function removeNamadHandler(idx) {
  if (namads.value[idx])
    namads.value.splice(idx, 1)
}

//---------------------------
const settingValues = reactive({})

watchImmediate(() => props.setting, () => {
  settingValues[SETTING_KEYS.FOOTER_DESCRIPTION] = findItemByKey(props.setting, 'name', SETTING_KEYS.FOOTER_DESCRIPTION)?.value || ''
  settingValues[SETTING_KEYS.FOOTER_COPYRIGHT] = findItemByKey(props.setting, 'name', SETTING_KEYS.FOOTER_COPYRIGHT)?.value || ''

  namads.value = findItemByKey(props.setting, 'name', SETTING_KEYS.FOOTER_NAMADS)?.value
  if (!namads.value?.length) {
    namads.value = [
      {
        id: 1,
        link: '',
      },
    ]
  }
})

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    footer_description: yup.string().required('توضیحات فوتر/پاورقی را وارد نمایید.'),
    footer_copyright: yup.string().required('توضیحات حق مالکیت را وارد نمایید.'),
  }),
}, (values, actions) => {
  if (props.isFetching) return

  canSubmit.value = false

  const updateArr = {
    [SETTING_KEYS.FOOTER_DESCRIPTION]: values.footer_description,
    [SETTING_KEYS.FOOTER_COPYRIGHT]: values.footer_copyright,
    [SETTING_KEYS.FOOTER_NAMADS]: getDefinedItems(),
  }

  SettingAPI.updateSetting(updateArr, {
    success() {
      emit('updated', updateArr)
    },
    error(error) {
      if (error.errors && Object.keys(error.errors).length >= 1)
        actions.setErrors(error.errors)

      if (error?.footer_namads) {
        actions.setFieldError('footer_namads', error.footer_namads)
      }
    },
    finally() {
      canSubmit.value = true
    },
  })
})

function getDefinedItems() {
  const items = []

  for (let i of namads.value) {
    if (i.link?.trim() !== '') {
      items.push(i)
    }
  }

  return items
}
</script>
