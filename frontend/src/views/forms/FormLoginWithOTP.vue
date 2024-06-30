<template>
  <base-stepy
    v-model:current-step="currentStep"
    :allow-change-steps-by-click="false"
    :loading="loading"
    :manual="true"
    :simple="true"
    :steps="steps"
    step-container-class="mt-3"
  >
    <template #mobileEntering="options">
      <form-login-o-t-p-enter-mobile :options="options"/>
    </template>

    <template #mobileConfirmation="options">
      <form-login-o-t-p-enter-code :options="options"/>
    </template>
  </base-stepy>
</template>

<script setup>
import {reactive, ref} from "vue";
import BaseStepy from "@/components/base/BaseStepy.vue";
import FormLoginOTPEnterMobile from "@/views/forms/login-steps-otp/FormLoginOTPEnterMobile.vue";
import FormLoginOTPEnterCode from "@/views/forms/login-steps-otp/FormLoginOTPEnterCode.vue";

const currentStep = ref('mobileEntering')
const loading = ref(false)
const steps = reactive({
  mobileEntering: {
    icon: 'DevicePhoneMobileIcon',
    text: 'وارد نمودن موبایل',
  },
  mobileConfirmation: {
    icon: 'CheckBadgeIcon',
    text: 'کد تایید ورود',
  },
})
</script>
