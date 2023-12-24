<template>
  <div class="min-h-screen relative flex flex-col items-center justify-center lg:px-10 bg-pattern">
    <router-link
        :to="{name: 'home'}"
        class="rounded-full my-4"
    >
      <img
          src="/logo-with-type.png"
          alt="آیریا کالا"
          class="h-12 object-contain"
      />
    </router-link>

    <div class="bg-white bg-opacity-90 rounded-lg max-w-md w-full p-6 shadow-lg">
      <div class="text-center">
        <h1 class="mb-3 text-xl border-b-2 inline-block pb-2 border-primary">
          بازگردانی کلمه عبور
        </h1>
      </div>

      <base-stepy
          v-model:current-step="currentStep"
          :steps="steps"
          :allow-change-steps-by-click="false"
          :loading="loading"
          :manual="true"
          :simple="true"
      >
        <template #mobileEntering="options">
          <form-forget-password-enter-mobile :options="options"/>
        </template>

        <template #mobileConfirmation="options">
          <form-forget-password-enter-code :options="options"/>
        </template>

        <template #enterNewPassword="options">
          <form-forget-password-enter-new-password :options="options"/>
        </template>
      </base-stepy>
    </div>

    <div class="my-4 flex gap-3 text-sm items-center">
      <router-link :to="{name: 'login'}" class="mr-1 text-orange-500 hover:text-opacity-80">
        وارد شوید
      </router-link>
      <span class="text-slate-400">یا</span>
      <router-link :to="{name: 'signup'}" class="mr-1 text-orange-500 hover:text-opacity-80">
        ثبت نام کنید
      </router-link>
    </div>
  </div>
</template>

<script setup>
import {reactive, ref} from "vue";
import BaseStepy from "../components/base/BaseStepy.vue";
import FormForgetPasswordEnterCode from "./forms/FormForgetPasswordEnterCode.vue";
import FormForgetPasswordEnterMobile from "./forms/FormForgetPasswordEnterMobile.vue";
import FormForgetPasswordEnterNewPassword from "./forms/FormForgetPasswordEnterNewPassword.vue";

const currentStep = ref('mobileEntering')
const loading = ref(false)
const steps = reactive({
  mobileEntering: {
    icon: 'DevicePhoneMobileIcon',
    text: 'وارد نمودن موبایل',
  },
  mobileConfirmation: {
    icon: 'CheckBadgeIcon',
    text: 'وارد نمودن کد',
  },
  enterNewPassword: {
    icon: 'KeyIcon',
    text: 'انتخاب کلمه عبور جدید',
  },
})
</script>

<style scoped>
.bg-pattern {
  background-color: transparent;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1600 900'%3E%3Cdefs%3E%3ClinearGradient id='a' x1='0' x2='0' y1='1' y2='0' gradientTransform='rotate(207,0.5,0.5)'%3E%3Cstop offset='0' stop-color='%230FF'/%3E%3Cstop offset='1' stop-color='%23CF6'/%3E%3C/linearGradient%3E%3ClinearGradient id='b' x1='0' x2='0' y1='0' y2='1' gradientTransform='rotate(60,0.5,0.5)'%3E%3Cstop offset='0' stop-color='%23FF31BE'/%3E%3Cstop offset='1' stop-color='%23DA7DFF'/%3E%3C/linearGradient%3E%3C/defs%3E%3Cg fill='%23FFF' fill-opacity='0' stroke-miterlimit='10'%3E%3Cg stroke='url(%23a)' stroke-width='21.779999999999998'%3E%3Cpath transform='translate(-17.15 0) rotate(-1.0499999999999998 1409 581) scale(0.9858529999999999)' d='M1409 581 1450.35 511 1490 581z'/%3E%3Ccircle stroke-width='7.26' transform='translate(-24.5 14) rotate(0.7000000000000002 800 450) scale(1.000988)' cx='500' cy='100' r='40'/%3E%3Cpath transform='translate(1.3999999999999995 -10.5) rotate(3.5 401 736) scale(1.000988)' d='M400.86 735.5h-83.73c0-23.12 18.74-41.87 41.87-41.87S400.86 712.38 400.86 735.5z'/%3E%3C/g%3E%3Cg stroke='url(%23b)' stroke-width='6.6'%3E%3Cpath transform='translate(84 1.4000000000000004) rotate(-0.3500000000000001 150 345) scale(0.9999509999999999)' d='M149.8 345.2 118.4 389.8 149.8 434.4 181.2 389.8z'/%3E%3Crect stroke-width='14.52' transform='translate(-17.5 -38.5)' x='1039' y='709' width='100' height='100'/%3E%3Cpath transform='translate(-44.8 11.2) scale(0.965)' d='M1426.8 132.4 1405.7 168.8 1363.7 168.8 1342.7 132.4 1363.7 96 1405.7 96z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  background-attachment: fixed;
  background-size: cover;
}
</style>
