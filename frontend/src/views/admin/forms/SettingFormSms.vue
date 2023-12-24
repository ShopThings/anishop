<template>
  <form @submit.prevent="onSubmit">
    <div class="p-2">
      <base-textarea
          label-title="پیامک خوش آمد گویی پس از ثبت نام"
          name="description_signup"
          :value="setting[SETTING_KEYS.SMS_SIGNUP] ?? ''"
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
              type="button"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              @click="copyHandler"
              v-text="`{{shop}}`"
          ></button>
          برای قرار دادن نام فروشگاه و از
          <button
              v-tooltip.top="'کپی کردن'"
              type="button"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
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
          label-title="پیامک فعالسازی حساب"
          name="description_activation"
          :value="setting[SETTING_KEYS.SMS_ACTIVATION] ?? ''"
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
              type="button"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              @click="copyHandler"
              v-text="`{{shop}}`"
          ></button>
          برای قرار دادن نام فروشگاه،
          از
          <button
              v-tooltip.top="'کپی کردن'"
              type="button"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              @click="copyHandler"
              v-text="`{{username}}`"
          ></button>
          برای قرار دادن نام کاربری و از
          <button
              v-tooltip.top="'کپی کردن'"
              type="button"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
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
          label-title="پیامک بازگردانی کلمه عبور"
          name="description_recover_pass"
          :value="setting[SETTING_KEYS.SMS_RECOVER_PASS] ?? ''"
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
              type="button"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              @click="copyHandler"
              v-text="`{{shop}}`"
          ></button>
          برای قرار دادن نام فروشگاه، از
          <button
              v-tooltip.top="'کپی کردن'"
              type="button"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              @click="copyHandler"
              v-text="`{{username}}`"
          ></button>
          برای قرار دادن نام کاربری، از
          <button
              v-tooltip.top="'کپی کردن'"
              type="button"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              @click="copyHandler"
              v-text="`{{first_name}}`"
          ></button>
          برای قرار دادن نام کاربر و از
          <button
              v-tooltip.top="'کپی کردن'"
              type="button"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
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
          label-title="پیامک ثبت سفارش"
          name="description_buy"
          :value="setting[SETTING_KEYS.SMS_BUY] ?? ''"
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
              type="button"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              @click="copyHandler"
              v-text="`{{shop}}`"
          ></button>
          برای قرار دادن نام فروشگاه، از
          <button
              v-tooltip.top="'کپی کردن'"
              type="button"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              @click="copyHandler"
              v-text="`{{username}}`"
          ></button>
          برای قرار دادن نام کاربری، از
          <button
              v-tooltip.top="'کپی کردن'"
              type="button"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              @click="copyHandler"
              v-text="`{{first_name}}`"
          ></button>
          برای قرار دادن نام کاربر و از
          <button
              v-tooltip.top="'کپی کردن'"
              type="button"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
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
          label-title="پیامک تغییر وضعیت سفارش"
          name="description_order_status"
          :value="setting[SETTING_KEYS.SMS_ORDER_STATUS] ?? ''"
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
              type="button"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              @click="copyHandler"
              v-text="`{{shop}}`"
          ></button>
          برای قرار دادن نام فروشگاه، از
          <button
              v-tooltip.top="'کپی کردن'"
              type="button"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              @click="copyHandler"
              v-text="`{{username}}`"
          ></button>
          برای قرار دادن نام کاربری، از
          <button
              v-tooltip.top="'کپی کردن'"
              type="button"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              @click="copyHandler"
              v-text="`{{first_name}}`"
          ></button>
          برای قرار دادن نام کاربر، از
          <button
              v-tooltip.top="'کپی کردن'"
              type="button"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              @click="copyHandler"
              v-text="`{{order_code}}`"
          ></button>
          برای قرار دادن کد سفارش و از
          <button
              v-tooltip.top="'کپی کردن'"
              type="button"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
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
          label-title="پیامک مرجوع نمودن کالا"
          name="description_return_order"
          :value="setting[SETTING_KEYS.SMS_RETURN_ORDER] ?? ''"
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
              type="button"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              @click="copyHandler"
              v-text="`{{shop}}`"
          ></button>
          برای قرار دادن نام فروشگاه، از
          <button
              v-tooltip.top="'کپی کردن'"
              type="button"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              @click="copyHandler"
              v-text="`{{username}}`"
          ></button>
          برای قرار دادن نام کاربری، از
          <button
              v-tooltip.top="'کپی کردن'"
              type="button"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              @click="copyHandler"
              v-text="`{{first_name}}`"
          ></button>
          برای قرار دادن نام کاربر و از
          <button
              v-tooltip.top="'کپی کردن'"
              type="button"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
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
          label-title="پیامک تغییر وضعیت سفارش مرجوعی"
          name="description_return_order_status"
          :value="setting[SETTING_KEYS.SMS_RETURN_ORDER_STATUS] ?? ''"
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
              type="button"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              @click="copyHandler"
              v-text="`{{shop}}`"
          ></button>
          برای قرار دادن نام فروشگاه، از
          <button
              v-tooltip.top="'کپی کردن'"
              type="button"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              @click="copyHandler"
              v-text="`{{username}}`"
          ></button>
          برای قرار دادن نام کاربری، از
          <button
              v-tooltip.top="'کپی کردن'"
              type="button"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              @click="copyHandler"
              v-text="`{{first_name}}`"
          ></button>
          برای قرار دادن نام کاربر، از
          <button
              v-tooltip.top="'کپی کردن'"
              type="button"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              @click="copyHandler"
              v-text="`{{order_code}}`"
          ></button>
          برای قرار دادن کد سفارش مرجوعی و از
          <button
              v-tooltip.top="'کپی کردن'"
              type="button"
              class="rounded py-1 px-2 mx-1 shadow border text-rose-500 cursor-pointer"
              @click="copyHandler"
              v-text="`{{status}}`"
          ></button>
          برای قرار دادن وضعیت سفارش مرجوعی استفاده نمایید.
        </template>
      </partial-input-lead>
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
import {ref} from "vue";
import {useClipboard} from "@vueuse/core";
import {SETTING_KEYS} from "../../../composables/constants.js";
import {useForm} from "vee-validate";
import yup from "../../../validation/index.js";
import {CheckIcon, InformationCircleIcon} from "@heroicons/vue/24/outline/index.js";
import VTransitionFade from "../../../transitions/VTransitionFade.vue";
import BaseTextarea from "../../../components/base/BaseTextarea.vue";
import BaseAnimatedButton from "../../../components/base/BaseAnimatedButton.vue";
import LoaderCircle from "../../../components/base/loader/LoaderCircle.vue";
import PartialInputLead from "../../../components/partials/PartialInputLead.vue";
import {useToast} from "vue-toastification";

const props = defineProps({
  setting: {
    type: Object,
    required: true,
  },
})

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

const canSubmit = ref(true)

const tags = ref([])
tags.value = props.setting[SETTING_KEYS.KEYWORDS]?.split(',') ?? []

const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return
})
</script>

<style scoped>

</style>
