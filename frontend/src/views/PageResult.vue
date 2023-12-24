<template>
  <div class="mb-12 px-3 max-w-4xl mx-auto">
    <app-navigation-header title="نتیجه پرداخت" container-class="text-center"/>

    <partial-card
        :class="[
                'border-2 p-6',
                isSuccessful ? 'border-emerald-400' : 'border-rose-400'
            ]"
    >
      <template #body>
        <div class="flex flex-col-reverse sm:flex-row gap-6">
          <div class="w-full">
            <template v-if="isSuccessful">
              <div class="text-slate-500 mb-3">
                ممنون از خرید شما،
              </div>
              <h1 class="text-xl md:text-2xl flex gap-2 items-center">
                <CheckCircleIcon class="w-10 h-10 text-emerald-500"/>
                <span class="font-iranyekan-light">سفارش شما با موفقیت ثبت شد</span>
              </h1>

              <div class="flex flex-col flex-wrap sm:flex-row gap-4 mt-3">
                <div class="flex gap-2 items-center">
                  <span class="text-sm text-slate-400">کد پیگیری</span>
                  <div
                      class="tracking-widest font-iranyekan-bold border-2 rounded-lg border-emerald-400 py-1 px-3">
                    00000013155698
                  </div>
                </div>

                <div class="flex gap-2 items-center">
                  <span class="text-sm text-slate-400">شماره سفارش</span>
                  <span class="tracking-widest font-iranyekan-bold">54978945131658</span>
                </div>
              </div>

              <div class="mt-6 text-center sm:text-right">
                <base-button
                    type="link"
                    :to="{name: 'user.order.detail', params: {code: 12345}}"
                    class="bg-cyan-500 px-6 m-1.5 w-full sm:w-auto"
                >
                  مشاهده جزئیات سفارش
                </base-button>
                <base-button
                    type="link"
                    :to="{name: 'home'}"
                    class="!text-black border-2 px-6 m-1.5 w-full sm:w-auto"
                >
                  بازگشت به سایت
                </base-button>
              </div>
            </template>
            <template v-else>
              <h1 class="text-xl md:text-2xl flex gap-2 items-center">
                <XCircleIcon class="w-10 h-10 text-rose-500"/>
                <span class="font-iranyekan-light">سفارش شما انجام نشد!</span>
              </h1>

              <p class="my-6 text-rose-500 font-iranyekan-bold rounded-lg bg-rose-50 py-1 px-4 sm:inline-block">
                لغو درخواست توسط کاربر
              </p>

              <div class="flex flex-col flex-wrap sm:flex-row gap-4 mt-3">
                <div class="flex gap-2 items-center">
                  <span class="text-sm text-slate-400">کد پیگیری</span>
                  <div
                      class="tracking-widest font-iranyekan-bold border-2 rounded-lg border-rose-400 py-1 px-3">
                    00000013155698
                  </div>
                </div>

                <div class="flex gap-2 items-center">
                  <span class="text-sm text-slate-400">شماره سفارش</span>
                  <span class="tracking-widest font-iranyekan-bold">54978945131658</span>
                </div>
              </div>

              <div class="mt-6 flex">
                <base-button
                    type="link"
                    :to="{name: 'home'}"
                    class="!text-black border-2 px-6 m-1.5 w-full sm:w-auto group flex items-center justify-center gap-3"
                >
                  <span>بازگشت به سایت</span>
                  <ArrowLongLeftIcon class="w-6 h-6 group-hover:-translate-x-1 transition"/>
                </base-button>
              </div>
            </template>
          </div>
          <template v-if="isSuccessful">
            <img
                src="/public/images/purchase.svg"
                alt="تصویر نتیجه پرداخت"
                class="object-contain w-56 mx-auto"
            >
          </template>
          <template v-else>
            <img
                src="/public/images/purchase-failed.svg"
                alt="تصویر نتیجه پرداخت"
                class="object-contain w-60 mx-auto"
            >
          </template>
        </div>
      </template>
    </partial-card>
  </div>
</template>

<script setup>
import {ref} from "vue";
import {CheckCircleIcon, XCircleIcon, ArrowLongLeftIcon} from "@heroicons/vue/24/outline/index.js";
import AppNavigationHeader from "../components/AppNavigationHeader.vue";
import PartialCard from "../components/partials/PartialCard.vue";
import BaseButton from "../components/base/BaseButton.vue";

const isSuccessful = ref(false)
</script>

<style scoped>

</style>
