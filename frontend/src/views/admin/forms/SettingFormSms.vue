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
          :value="settingValues[SETTING_KEYS.SMS_SIGNUP]"
          label-title="پیامک خوش آمد گویی پس از ثبت نام"
          name="sms_signup"
      >
        <template #icon>
          <InformationCircleIcon class="h-6 w-6 mt-3 text-gray-400"/>
        </template>
      </base-textarea>
      <partial-input-lead klass="text-indigo-600 border border-r-4 rounded-none border-indigo-500 w-full">
        <template #text>
          می‌توانید از
          <button
              v-tooltip.top="'کپی کردن'"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              type="button"
              @click="copyHandler"
              v-text="`{{shop}}`"
          ></button>
          برای قرار دادن نام فروشگاه و از
          <button
              v-tooltip.top="'کپی کردن'"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              type="button"
              @click="copyHandler"
              v-text="`{{username}}`"
          ></button>
          برای قرار دادن نام کاربری استفاده نمایید.
        </template>
      </partial-input-lead>
    </div>

    <hr class="border-0 w-36 sm:w-48 h-1 rounded-full bg-slate-200 my-6 mx-auto">

    <div class="p-2">
      <base-textarea
          :value="settingValues[SETTING_KEYS.SMS_ACTIVATION]"
          label-title="پیامک فعالسازی حساب"
          name="sms_activation"
      >
        <template #icon>
          <InformationCircleIcon class="h-6 w-6 mt-3 text-gray-400"/>
        </template>
      </base-textarea>
      <partial-input-lead klass="text-indigo-600 border border-r-4 rounded-none border-indigo-500 w-full">
        <template #text>
          می‌توانید از
          <button
              v-tooltip.top="'کپی کردن'"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              type="button"
              @click="copyHandler"
              v-text="`{{shop}}`"
          ></button>
          برای قرار دادن نام فروشگاه،
          از
          <button
              v-tooltip.top="'کپی کردن'"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              type="button"
              @click="copyHandler"
              v-text="`{{username}}`"
          ></button>
          برای قرار دادن نام کاربری و از
          <button
              v-tooltip.top="'کپی کردن'"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              type="button"
              @click="copyHandler"
              v-text="`{{code}}`"
          ></button>
          برای قرار دادن کد فعالسازی استفاده نمایید.
        </template>
      </partial-input-lead>
    </div>

    <hr class="border-0 w-36 sm:w-48 h-1 rounded-full bg-slate-200 my-6 mx-auto">

    <div class="p-2">
      <base-textarea
          :value="settingValues[SETTING_KEYS.SMS_RECOVER_PASS]"
          label-title="پیامک بازگردانی کلمه عبور"
          name="sms_recover_pass"
      >
        <template #icon>
          <InformationCircleIcon class="h-6 w-6 mt-3 text-gray-400"/>
        </template>
      </base-textarea>
      <partial-input-lead klass="text-indigo-600 border border-r-4 rounded-none border-indigo-500 w-full">
        <template #text>
          می‌توانید از
          <button
              v-tooltip.top="'کپی کردن'"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              type="button"
              @click="copyHandler"
              v-text="`{{shop}}`"
          ></button>
          برای قرار دادن نام فروشگاه، از
          <button
              v-tooltip.top="'کپی کردن'"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              type="button"
              @click="copyHandler"
              v-text="`{{username}}`"
          ></button>
          برای قرار دادن نام کاربری، از
          <button
              v-tooltip.top="'کپی کردن'"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              type="button"
              @click="copyHandler"
              v-text="`{{first_name}}`"
          ></button>
          برای قرار دادن نام کاربر و از
          <button
              v-tooltip.top="'کپی کردن'"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              type="button"
              @click="copyHandler"
              v-text="`{{code}}`"
          ></button>
          برای قرار دادن کد بازگردانی استفاده نمایید.
        </template>
      </partial-input-lead>
    </div>

    <hr class="border-0 w-36 sm:w-48 h-1 rounded-full bg-slate-200 my-6 mx-auto">

    <div class="p-2">
      <base-textarea
          :value="settingValues[SETTING_KEYS.SMS_BUY]"
          label-title="پیامک ثبت سفارش"
          name="sms_buy"
      >
        <template #icon>
          <InformationCircleIcon class="h-6 w-6 mt-3 text-gray-400"/>
        </template>
      </base-textarea>
      <partial-input-lead klass="text-indigo-600 border border-r-4 rounded-none border-indigo-500 w-full">
        <template #text>
          می‌توانید از
          <button
              v-tooltip.top="'کپی کردن'"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              type="button"
              @click="copyHandler"
              v-text="`{{shop}}`"
          ></button>
          برای قرار دادن نام فروشگاه، از
          <button
              v-tooltip.top="'کپی کردن'"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              type="button"
              @click="copyHandler"
              v-text="`{{username}}`"
          ></button>
          برای قرار دادن نام کاربری، از
          <button
              v-tooltip.top="'کپی کردن'"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              type="button"
              @click="copyHandler"
              v-text="`{{first_name}}`"
          ></button>
          برای قرار دادن نام کاربر و از
          <button
              v-tooltip.top="'کپی کردن'"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              type="button"
              @click="copyHandler"
              v-text="`{{order_code}}`"
          ></button>
          برای قرار دادن کد سفارش استفاده نمایید.
        </template>
      </partial-input-lead>
    </div>

    <hr class="border-0 w-36 sm:w-48 h-1 rounded-full bg-slate-200 my-6 mx-auto">

    <div class="p-2">
      <base-textarea
          :value="settingValues[SETTING_KEYS.SMS_ORDER_STATUS]"
          label-title="پیامک تغییر وضعیت سفارش"
          name="sms_order_status"
      >
        <template #icon>
          <InformationCircleIcon class="h-6 w-6 mt-3 text-gray-400"/>
        </template>
      </base-textarea>
      <partial-input-lead klass="text-indigo-600 border border-r-4 rounded-none border-indigo-500 w-full">
        <template #text>
          می‌توانید از
          <button
              v-tooltip.top="'کپی کردن'"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              type="button"
              @click="copyHandler"
              v-text="`{{shop}}`"
          ></button>
          برای قرار دادن نام فروشگاه، از
          <button
              v-tooltip.top="'کپی کردن'"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              type="button"
              @click="copyHandler"
              v-text="`{{username}}`"
          ></button>
          برای قرار دادن نام کاربری، از
          <button
              v-tooltip.top="'کپی کردن'"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              type="button"
              @click="copyHandler"
              v-text="`{{first_name}}`"
          ></button>
          برای قرار دادن نام کاربر، از
          <button
              v-tooltip.top="'کپی کردن'"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              type="button"
              @click="copyHandler"
              v-text="`{{order_code}}`"
          ></button>
          برای قرار دادن کد سفارش و از
          <button
              v-tooltip.top="'کپی کردن'"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              type="button"
              @click="copyHandler"
              v-text="`{{status}}`"
          ></button>
          برای قرار دادن وضعیت سفارش استفاده نمایید.
        </template>
      </partial-input-lead>
    </div>

    <hr class="border-0 w-36 sm:w-48 h-1 rounded-full bg-slate-200 my-6 mx-auto">

    <div class="p-2">
      <base-textarea
          :value="settingValues[SETTING_KEYS.SMS_RETURN_ORDER]"
          label-title="پیامک مرجوع نمودن کالا"
          name="sms_return_order"
      >
        <template #icon>
          <InformationCircleIcon class="h-6 w-6 mt-3 text-gray-400"/>
        </template>
      </base-textarea>
      <partial-input-lead klass="text-indigo-600 border border-r-4 rounded-none border-indigo-500 w-full">
        <template #text>
          می‌توانید از
          <button
              v-tooltip.top="'کپی کردن'"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              type="button"
              @click="copyHandler"
              v-text="`{{shop}}`"
          ></button>
          برای قرار دادن نام فروشگاه، از
          <button
              v-tooltip.top="'کپی کردن'"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              type="button"
              @click="copyHandler"
              v-text="`{{username}}`"
          ></button>
          برای قرار دادن نام کاربری، از
          <button
              v-tooltip.top="'کپی کردن'"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              type="button"
              @click="copyHandler"
              v-text="`{{first_name}}`"
          ></button>
          برای قرار دادن نام کاربر و از
          <button
              v-tooltip.top="'کپی کردن'"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              type="button"
              @click="copyHandler"
              v-text="`{{order_code}}`"
          ></button>
          برای قرار دادن کد سفارش استفاده نمایید.
        </template>
      </partial-input-lead>
    </div>

    <hr class="border-0 w-36 sm:w-48 h-1 rounded-full bg-slate-200 my-6 mx-auto">

    <div class="p-2">
      <base-textarea
          :value="settingValues[SETTING_KEYS.SMS_RETURN_ORDER_STATUS]"
          label-title="پیامک تغییر وضعیت سفارش مرجوعی"
          name="sms_return_order_status"
      >
        <template #icon>
          <InformationCircleIcon class="h-6 w-6 mt-3 text-gray-400"/>
        </template>
      </base-textarea>
      <partial-input-lead klass="text-indigo-600 border border-r-4 rounded-none border-indigo-500 w-full">
        <template #text>
          می‌توانید از
          <button
              v-tooltip.top="'کپی کردن'"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              type="button"
              @click="copyHandler"
              v-text="`{{shop}}`"
          ></button>
          برای قرار دادن نام فروشگاه، از
          <button
              v-tooltip.top="'کپی کردن'"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              type="button"
              @click="copyHandler"
              v-text="`{{username}}`"
          ></button>
          برای قرار دادن نام کاربری، از
          <button
              v-tooltip.top="'کپی کردن'"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              type="button"
              @click="copyHandler"
              v-text="`{{first_name}}`"
          ></button>
          برای قرار دادن نام کاربر، از
          <button
              v-tooltip.top="'کپی کردن'"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              type="button"
              @click="copyHandler"
              v-text="`{{order_code}}`"
          ></button>
          برای قرار دادن کد سفارش مرجوعی و از
          <button
              v-tooltip.top="'کپی کردن'"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              type="button"
              @click="copyHandler"
              v-text="`{{status}}`"
          ></button>
          برای قرار دادن وضعیت سفارش مرجوعی استفاده نمایید.
        </template>
      </partial-input-lead>
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
import {useClipboard, watchImmediate} from "@vueuse/core";
import {SETTING_KEYS} from "@/composables/constants.js";
import yup from "@/validation/index.js";
import {CheckIcon, InformationCircleIcon} from "@heroicons/vue/24/outline/index.js";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseTextarea from "@/components/base/BaseTextarea.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import PartialInputLead from "@/components/partials/PartialInputLead.vue";
import {useToast} from "vue-toastification";
import {findItemByKey} from "@/composables/helper.js";
import {useFormSubmit} from "@/composables/form-submit.js";
import LoaderDotOrbit from "@/components/base/loader/LoaderDotOrbit.vue";
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

const toast = useToast()

//----------------------------
// Clipboard Stuffs
//----------------------------
const {copy} = useClipboard()

function copyHandler(e) {
  copy(e.target.textContent).then(() => {
    toast.success('متن کپی شد.')
  })
}

//----------------------------
const settingValues = reactive({})

watchImmediate(() => props.setting, () => {
  settingValues[SETTING_KEYS.SMS_SIGNUP] = findItemByKey(props.setting, 'name', SETTING_KEYS.SMS_SIGNUP)?.value || ''
  settingValues[SETTING_KEYS.SMS_ACTIVATION] = findItemByKey(props.setting, 'name', SETTING_KEYS.SMS_ACTIVATION)?.value || ''
  settingValues[SETTING_KEYS.SMS_RECOVER_PASS] = findItemByKey(props.setting, 'name', SETTING_KEYS.SMS_RECOVER_PASS)?.value || ''
  settingValues[SETTING_KEYS.SMS_BUY] = findItemByKey(props.setting, 'name', SETTING_KEYS.SMS_BUY)?.value || ''
  settingValues[SETTING_KEYS.SMS_ORDER_STATUS] = findItemByKey(props.setting, 'name', SETTING_KEYS.SMS_ORDER_STATUS)?.value || ''
  settingValues[SETTING_KEYS.SMS_RETURN_ORDER] = findItemByKey(props.setting, 'name', SETTING_KEYS.SMS_RETURN_ORDER)?.value || ''
  settingValues[SETTING_KEYS.SMS_RETURN_ORDER_STATUS] = findItemByKey(props.setting, 'name', SETTING_KEYS.SMS_RETURN_ORDER_STATUS)?.value || ''
})

//----------------------------
const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    sms_signup: yup.string().required('متن پیامک را وار نمایید.'),
    sms_activation: yup.string().required('متن پیامک را وار نمایید.'),
    sms_recover_pass: yup.string().required('متن پیامک را وار نمایید.'),
    sms_buy: yup.string().required('متن پیامک را وار نمایید.'),
    sms_order_status: yup.string().required('متن پیامک را وار نمایید.'),
    sms_return_order: yup.string().required('متن پیامک را وار نمایید.'),
    sms_return_order_status: yup.string().required('متن پیامک را وار نمایید.'),
  }),
}, (values, actions) => {
  if (props.isFetching) return

  canSubmit.value = false

  const updateArr = {
    [SETTING_KEYS.SMS_SIGNUP]: values.sms_signup,
    [SETTING_KEYS.SMS_ACTIVATION]: values.sms_activation,
    [SETTING_KEYS.SMS_RECOVER_PASS]: values.sms_recover_pass,
    [SETTING_KEYS.SMS_BUY]: values.sms_buy,
    [SETTING_KEYS.SMS_ORDER_STATUS]: values.sms_order_status,
    [SETTING_KEYS.SMS_RETURN_ORDER]: values.sms_return_order,
    [SETTING_KEYS.SMS_RETURN_ORDER_STATUS]: values.sms_return_order_status,
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
