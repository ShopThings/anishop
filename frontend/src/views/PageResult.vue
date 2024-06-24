<template>
  <div class="mb-12 px-3 max-w-4xl mx-auto">
    <app-navigation-header container-class="text-center" title="نتیجه پرداخت"/>

    <template v-if="!resultLoading && result">
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
                      class="tracking-widest font-iranyekan-bold border-2 rounded-lg border-emerald-400 py-1 px-3"
                    >
                      {{ result.receipt }}
                    </div>
                  </div>

                  <div class="flex gap-2 items-center">
                    <span class="text-sm text-slate-400">کد سفارش</span>
                    <span class="tracking-widest font-sans font-semibold" dir="ltr">{{ result.order_code }}</span>
                  </div>
                </div>

                <div
                  class="mt-6 text-center sm:text-right flex flex-wrap flex-col sm:flex-row items-center justify-start">
                  <base-button
                    :to="{name: 'user.order.detail', params: {code: result.order_code}}"
                    class="border-cyan-500 bg-cyan-500 px-6 m-1.5 w-full sm:grow lg:grow-0 sm:w-auto"
                    type="link"
                  >
                    مشاهده جزئیات سفارش
                  </base-button>
                  <base-button
                    :to="{name: 'home'}"
                    class="!text-black border-2 px-6 m-1.5 w-full sm:grow lg:grow-0 sm:w-auto"
                    type="link"
                  >
                    بازگشت به صفحه اصلی
                  </base-button>
                </div>
              </template>
              <template v-else>
                <h1 class="text-xl md:text-2xl flex gap-2 items-center">
                  <XCircleIcon class="w-10 h-10 text-rose-500"/>
                  <span class="font-iranyekan-light">سفارش شما انجام نشد!</span>
                </h1>

                <p
                  v-if="result.message"
                  class="my-6 text-rose-500 font-iranyekan-bold rounded-lg bg-rose-50 py-1 px-4 sm:inline-block"
                >
                  {{ result.message }}
                </p>

                <div class="flex flex-col flex-wrap sm:flex-row gap-4 mt-3">
                  <div
                    v-if="result.receipt"
                    class="flex gap-2 items-center"
                  >
                    <span class="text-sm text-slate-400">کد پیگیری</span>
                    <div class="tracking-widest font-iranyekan-bold border-2 rounded-lg border-rose-400 py-1 px-3">
                      {{ result.receipt }}
                    </div>
                  </div>

                  <div
                    v-if="result.order_code"
                    class="flex gap-2 items-center"
                  >
                    <span class="text-sm text-slate-400">کد سفارش</span>
                    <span class="tracking-widest font-sans font-semibold" dir="ltr">{{ result.order_code }}</span>
                  </div>
                </div>

                <div class="mt-6 flex flex-wrap flex-col sm:flex-row items-center justify-start">
                  <base-button
                    :to="{name: 'home'}"
                    class="!text-black border-2 px-6 m-1.5 w-full group flex items-center justify-center gap-3 sm:grow lg:grow-0 sm:w-auto"
                    type="link"
                  >
                    <span>بازگشت به صفحه اصلی</span>
                    <ArrowLongLeftIcon class="w-6 h-6 group-hover:-translate-x-1 transition"/>
                  </base-button>
                </div>
              </template>
            </div>
            <template v-if="isSuccessful">
              <img
                alt="تصویر نتیجه پرداخت"
                class="object-contain w-56 mx-auto"
                src="/public/images/purchase.svg"
              >
            </template>
            <template v-else>
              <img
                alt="تصویر نتیجه پرداخت"
                class="object-contain w-60 mx-auto"
                src="/public/images/purchase-failed.svg"
              >
            </template>
          </div>
        </template>
      </partial-card>
    </template>

    <partial-card
      v-else
      class="border-2 p-6"
    >
      <template #body>
        <div class="flex flex-col-reverse sm:flex-row gap-6 animate-pulse">
          <div class="grow flex flex-col gap-8 items-center sm:items-start">
            <div class="rounded bg-slate-200 w-2/3 h-5"></div>

            <div class="flex flex-col gap-3 w-full items-center sm:items-start">
              <div class="rounded bg-slate-200 w-2/4 h-3"></div>
              <div class="rounded bg-slate-200 w-3/4 h-3"></div>
              <div class="rounded bg-slate-200 w-1/4 h-3"></div>
            </div>

            <div class="rounded-lg border-[3px] border-slate-200 w-32 h-12 flex items-center justify-center px-5">
              <div class="rounded bg-slate-200 h-2 w-full"></div>
            </div>
          </div>
          <div class="w-60 h-48 mx-auto flex items-center justify-center bg-slate-200 rounded-lg">
            <PhotoIcon class="size-10 text-slate-400"/>
          </div>
        </div>
      </template>
    </partial-card>
  </div>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import {ArrowLongLeftIcon, CheckCircleIcon, PhotoIcon, XCircleIcon,} from "@heroicons/vue/24/outline/index.js";
import AppNavigationHeader from "@/components/AppNavigationHeader.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import {useRoute} from "vue-router";
import {HomeCheckoutAPI} from "@/service/APIHomePages.js";

const route = useRoute()

const result = ref(null)
const resultLoading = ref(true)
const isSuccessful = computed(() => {
  return !!result.value?.status
})

onMounted(() => {
  const payId = route.query?.g

  if (payId) {
    HomeCheckoutAPI.payOrderResult(payId, {
      success(response) {
        result.value = response.data
      },
      finally() {
        resultLoading.value = false
      },
    })
  }
})
</script>
