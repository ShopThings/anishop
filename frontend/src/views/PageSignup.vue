<template>
  <div class="min-h-screen relative flex flex-col items-center justify-center lg:px-10">
    <div class="h-full w-full max-w-5xl grid grid-cols-2">
      <div class="h-full col-span-2 lg:col-span-1 z-[2]">
        <div
            class="max-w-sm w-full lg:max-w-none lg:bg-white lg:shadow-lg flex flex-col justify-center h-full items-center lg:items-start mx-auto lg:rounded-r-lg">
          <router-link :to="{name: 'home'}">
            <img
                src="/logo-with-type-light.png"
                alt="آیریا کالا"
                class="h-12 my-4 lg:hidden"
            />
          </router-link>

          <div
              class="w-full overflow-hidden py-6 px-10 text-center bg-white lg:bg-transparent shadow lg:shadow-none rounded-lg lg:rounded-none"
          >
            <base-stepy
                v-model:current-step="currentStep"
                :steps="steps"
                :allow-change-steps-by-click="false"
                :loading="loading"
                :manual="true"
                :simple="true"
            >
              <template #mobileEntering="options">
                <form-signup-enter-mobile :options="options"/>
              </template>

              <template #mobileConfirmation="options">
                <form-signup-enter-code :options="options"/>
              </template>

              <template #enterNewPassword="options">
                <form-signup-enter-password :options="options"/>
              </template>
            </base-stepy>
          </div>

          <div class="my-4 lg:hidden">
            <span class="text-sm text-black">قبلا ثبت نام کرده‌ام،</span>
            <router-link :to="{name: 'login'}" class="mr-1 text-white hover:text-opacity-80">
              وارد شوید
            </router-link>
          </div>
        </div>
      </div>

      <div
          class="h-full text-white px-12 absolute top-0 bg-pattern right-0 left-0 bottom-0 lg:static lg:rounded-l-lg">
        <div class="py-10 flex-col justify-center items-center h-full mx-auto relative z-[2] hidden lg:flex">
          <router-link
              :to="{name: 'home'}"
              class="mb-6"
          >
            <img
                src="/logo-light.png"
                alt="آیریا کالا"
                class="h-16"
            />
          </router-link>

          <h1 class="text-2xl py-2 bg-white bg-opacity-30 rounded-full px-4 mb-16">ثبت نام در آیریا کالا</h1>


          <hr class="w-48 h-1 mx-auto bg-slate-200 bg-opacity-60 border-0 rounded my-3">

          <div class="text-center">
            <span class="text-sm text-black">قبلا ثبت نام کرده‌ام،</span>
            <router-link :to="{name: 'login'}" class="mr-1 text-white hover:text-opacity-80">
              وارد شوید
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import {reactive, ref} from "vue";
import BaseStepy from "../components/base/BaseStepy.vue";
import FormSignupEnterMobile from "./forms/FormSignupEnterMobile.vue";
import FormSignupEnterCode from "./forms/FormSignupEnterCode.vue";
import FormSignupEnterPassword from "./forms/FormSignupEnterPassword.vue";

const currentStep = ref('mobileEntering')
const loading = ref(false)
const steps = reactive({
  mobileEntering: {
    icon: 'DevicePhoneMobileIcon',
    text: 'وارد نمودن موبایل',
  },
  mobileConfirmation: {
    icon: 'CheckBadgeIcon',
    text: 'تایید موبایل',
  },
  enterNewPassword: {
    icon: 'KeyIcon',
    text: 'انتخاب کلمه عبور',
  },
})
</script>

<style scoped>
.bg-pattern {
  background-color: #4ADE80;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 2000 1500'%3E%3Cdefs%3E%3CradialGradient id='a' gradientUnits='objectBoundingBox'%3E%3Cstop offset='0' stop-color='%23BEF264'/%3E%3Cstop offset='1' stop-color='%234ADE80'/%3E%3C/radialGradient%3E%3ClinearGradient id='b' gradientUnits='userSpaceOnUse' x1='0' y1='750' x2='1550' y2='750'%3E%3Cstop offset='0' stop-color='%2384e872'/%3E%3Cstop offset='1' stop-color='%234ADE80'/%3E%3C/linearGradient%3E%3Cpath id='s' fill='url(%23b)' d='M1549.2 51.6c-5.4 99.1-20.2 197.6-44.2 293.6c-24.1 96-57.4 189.4-99.3 278.6c-41.9 89.2-92.4 174.1-150.3 253.3c-58 79.2-123.4 152.6-195.1 219c-71.7 66.4-149.6 125.8-232.2 177.2c-82.7 51.4-170.1 94.7-260.7 129.1c-90.6 34.4-184.4 60-279.5 76.3C192.6 1495 96.1 1502 0 1500c96.1-2.1 191.8-13.3 285.4-33.6c93.6-20.2 185-49.5 272.5-87.2c87.6-37.7 171.3-83.8 249.6-137.3c78.4-53.5 151.5-114.5 217.9-181.7c66.5-67.2 126.4-140.7 178.6-218.9c52.3-78.3 96.9-161.4 133-247.9c36.1-86.5 63.8-176.2 82.6-267.6c18.8-91.4 28.6-184.4 29.6-277.4c0.3-27.6 23.2-48.7 50.8-48.4s49.5 21.8 49.2 49.5c0 0.7 0 1.3-0.1 2L1549.2 51.6z'/%3E%3Cg id='g'%3E%3Cuse href='%23s' transform='scale(0.12) rotate(60)'/%3E%3Cuse href='%23s' transform='scale(0.2) rotate(10)'/%3E%3Cuse href='%23s' transform='scale(0.25) rotate(40)'/%3E%3Cuse href='%23s' transform='scale(0.3) rotate(-20)'/%3E%3Cuse href='%23s' transform='scale(0.4) rotate(-30)'/%3E%3Cuse href='%23s' transform='scale(0.5) rotate(20)'/%3E%3Cuse href='%23s' transform='scale(0.6) rotate(60)'/%3E%3Cuse href='%23s' transform='scale(0.7) rotate(10)'/%3E%3Cuse href='%23s' transform='scale(0.835) rotate(-40)'/%3E%3Cuse href='%23s' transform='scale(0.9) rotate(40)'/%3E%3Cuse href='%23s' transform='scale(1.05) rotate(25)'/%3E%3Cuse href='%23s' transform='scale(1.2) rotate(8)'/%3E%3Cuse href='%23s' transform='scale(1.333) rotate(-60)'/%3E%3Cuse href='%23s' transform='scale(1.45) rotate(-30)'/%3E%3Cuse href='%23s' transform='scale(1.6) rotate(10)'/%3E%3C/g%3E%3C/defs%3E%3Cg transform='translate(2000 0)'%3E%3Cg %3E%3Ccircle fill='url(%23a)' r='3000'/%3E%3Cg opacity='0.5'%3E%3Ccircle fill='url(%23a)' r='2000'/%3E%3Ccircle fill='url(%23a)' r='1800'/%3E%3Ccircle fill='url(%23a)' r='1700'/%3E%3Ccircle fill='url(%23a)' r='1651'/%3E%3Ccircle fill='url(%23a)' r='1450'/%3E%3Ccircle fill='url(%23a)' r='1250'/%3E%3Ccircle fill='url(%23a)' r='1175'/%3E%3Ccircle fill='url(%23a)' r='900'/%3E%3Ccircle fill='url(%23a)' r='750'/%3E%3Ccircle fill='url(%23a)' r='500'/%3E%3Ccircle fill='url(%23a)' r='380'/%3E%3Ccircle fill='url(%23a)' r='250'/%3E%3C/g%3E%3Cg transform='rotate(-295.2 0 0)'%3E%3Cuse href='%23g' transform='rotate(10)'/%3E%3Cuse href='%23g' transform='rotate(120)'/%3E%3Cuse href='%23g' transform='rotate(240)'/%3E%3C/g%3E%3Ccircle fill-opacity='0.03' fill='url(%23a)' r='3000'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  background-attachment: fixed;
  background-size: cover;
}
</style>
