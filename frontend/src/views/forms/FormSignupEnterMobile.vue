<template>
  <form class="relative" @submit.prevent="onSubmit">
    <loader-dot-orbit
      v-if="!canSubmit"
      container-bg-color="bg-blue-50 opacity-40"
      main-container-klass="absolute w-full h-full top-0 left-0 z-[2]"
    />

    <VTransitionSlideFadeDownY>
      <base-message v-if="err.message && err.type" :type="err.type" @close="closeAlert">
        {{ err.message }}
      </base-message>
    </VTransitionSlideFadeDownY>

    <div class="mb-3 mt-12">
      <base-input
        label-title="شماره موبایل"
        name="username"
        placeholder="شماره موبایل"
      >
        <template #icon>
          <DevicePhoneMobileIcon class="w-6 h-6 text-gray-400"/>
        </template>
      </base-input>
    </div>

    <div class="mb-2">
      <v-captcha ref="captchaCom" v-model="captchaKey"/>
    </div>
    <div class="mb-3">
      <base-input
        klass="no-spin-arrow"
        label-title="کد تصویر"
        name="captcha"
        placeholder="کد تصویر"
        type="number">
        <template #icon>
          <QrCodeIcon class="w-6 h-6 text-gray-400"/>
        </template>
      </base-input>
    </div>
    <div class="mb-8">
      <div class="flex flex-row-reverse justify-end items-start gap-2">
        <partial-input-label id="usageAgreement">
          <template #label>
            من با
            <router-link
              :to="{name: 'pages', params: {url: 'privacy-policy'}}"
              class="text-orange-500 hover:text-opacity-80 transition"
              target="_blank"
            >
              شرایط و سیاست‌های سایت
            </router-link>
            موافقم
          </template>
        </partial-input-label>
        <base-checkbox
          id="usageAgreement"
          v-model="agreementStatus"
          name="usage_agreement"
        />
      </div>
      <partial-input-error-message :error-message="errors?.agreement"/>
    </div>
    <div class="mb-3">
      <base-button
        :disabled="!canSubmit"
        class="w-full flex justify-center items-center group bg-primary border-primary text-white"
        type="submit"
      >
        <span class="mx-auto">ارسال کد</span>
        <ArrowLeftIcon
          class="h-6 w-6 text-white opacity-60 group-hover:-translate-x-1.5 transition-all"/>
      </base-button>
    </div>
  </form>
</template>

<script setup>
import {onMounted, reactive, ref} from "vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import {DevicePhoneMobileIcon, QrCodeIcon} from "@heroicons/vue/24/outline/index.js";
import BaseInput from "@/components/base/BaseInput.vue";
import yup, {transformNumbersToEnglish} from "@/validation/index.js";
import LoaderDotOrbit from "@/components/base/loader/LoaderDotOrbit.vue";
import BaseCheckbox from "@/components/base/BaseCheckbox.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import VCaptcha from "@/components/base/VCaptcha.vue";
import {ArrowLeftIcon} from "@heroicons/vue/24/solid/index.js";
import isFunction from "lodash.isfunction";
import {useFormSubmit} from "@/composables/form-submit.js";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import VTransitionSlideFadeDownY from "@/transitions/VTransitionSlideFadeDownY.vue";
import BaseMessage from "@/components/base/BaseMessage.vue";
import {HomeSignupAPI} from "@/service/APIHomePages.js";
import {useSignupStore} from "@/store/StoreUserHome.js";

const props = defineProps({
  options: {
    type: Object,
    required: true,
  },
})

const signupStore = useSignupStore()

const agreementStatus = ref(false)

const captchaKey = ref(null)
const err = reactive({})
const captchaCom = ref(null)

function closeAlert() {
  err.message = null
  err.type = null
}

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    username: yup.string()
      .transform(transformNumbersToEnglish)
      .persianMobile('شماره موبایل نامعتبر است.')
      .required('شماره موبایل خود را وارد نمایید.'),
    captcha: yup.string().required('کد تصویر را وارد نمایید.'),
  }),
}, (values, actions) => {
  if (!agreementStatus.value) {
    actions.setFieldError('agreement', 'ابتدا شرایط و سیاست‌های سایت را مطالعه نمایید و در صورت قبول نمودن آن می‌توانید از خدمات سایت بهره‌مند شوید.')
    return
  }

  if (!captchaKey.value) {
    err.message = 'تصویر را دوباره بارگذاری نمایید.'
    err.type = 'error'
    return
  }

  canSubmit.value = false

  HomeSignupAPI.storeMobile({
    username: values.username,
    captcha: values.captcha,
    key: captchaKey.value,
  }, {
    success() {
      actions.resetForm();

      if (isFunction(props.options?.next)) {
        signupStore.setMobileStep({
          mobile: values.username,
        })

        props.options.next()
      }
    },
    error(error) {
      actions.resetField('captcha')

      if (error?.errors && Object.keys(error.errors).length >= 1) {
        actions.setErrors(error.errors)
      }

      err.message = error?.message || 'خطا در ثبت نام!'
      err.type = 'error'
      return false
    },
    finally() {
      if (captchaCom.value) {
        captchaCom.value.getCaptcha()
      }
      canSubmit.value = true
    },
  })
})

onMounted(() => {
  signupStore.$reset()
})
</script>
