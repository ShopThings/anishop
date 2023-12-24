<template>
  <base-message
      type="warning"
      :has-close="false"
      class="rounded-lg"
  >
    <div class="leading-relaxed">
      در صورتی می‌توانید اطلاعات سفارش مرجوع شده را تغییر دهید که توسط سایت تغییر وضعیت داده نشده باشد.
    </div>
  </base-message>

  <partial-card class="border-2 border-teal-500 mb-3">
    <template #body>
      <div class="py-3 px-4">
        <div class="text-sm flex flex-col sm:flex-row flex-wrap items-center gap-2">
          <div class="flex flex-wrap items-center gap-2">
            <span class="text-gray-400 text-xs">بازگشت سفارش به کد:</span>
            <span class="tracking-widest">861247269716759</span>
          </div>
          <router-link
              :to="{name: 'user.order.detail', params: {code: 1}}"
              class="flex gap-2 items-center text-blue-600 group ml-auto mr-auto sm:ml-0"
          >
            <span>مشاهده جزئیات</span>
            <ArrowLongLeftIcon class="w-5 h-5 group-hover:-translate-x-1.5 transition"/>
          </router-link>
        </div>
      </div>
    </template>
  </partial-card>

  <div
      v-if="1"
      class="mb-3"
  >
    <partial-badge-status-return-order class="w-full py-2 !text-sm"/>
  </div>

  <form @submit.prevent="onSubmit">
    <div>
      <h2 class="text-slate-400 mb-1">
        توضیحات
      </h2>

      <base-loading-panel
          type="form"
          :loading="loading"
      >
        <template #content>
          <partial-card class="border-0">
            <template #body>
              <div class="p-3">
                <div
                    v-if="1"
                    class="mb-4 text-left"
                >
                  <base-button
                      v-if="1"
                      type="button"
                      class="bg-rose-500 px-4 text-sm !py-1"
                      @click="console.log('do something...')"
                  >
                    لغو درخواست
                  </base-button>
                </div>
                <div class="mb-3">
                  <base-textarea
                      name="description"
                      label-title="علت مرجوع نمودن محصول"
                      placeholder="توضیحات خود را وارد نمایید..."
                      :has-edit-mode="true"
                      :is-editable="true"
                      value=""
                  />
                </div>
                <div>
                  <base-textarea
                      name="not_accepted_description"
                      label-title="پاسخ درخواست"
                      :has-edit-mode="false"
                      :is-editable="false"
                      value=""
                  />
                </div>
              </div>
            </template>
          </partial-card>
        </template>
      </base-loading-panel>
    </div>

    <div class="mt-6">
      <h2 class="text-slate-400 mb-1">
        آیتم‌های سفارش
      </h2>

      <base-loading-panel
          type="list"
          :loading="loading"
      >
        <template #content>
          <partial-card class="border-0">
            <template #body>
              <ul class="divide-y divide-gray-100">
                <li class="relative flex flex-col md:flex-row mb-2 py-3 pr-3 pl-10">
                  <div class="shrink-0">
                    <router-link
                        :to="{name: 'product.detail', params: {id: 1}}"
                        class="inline-block"
                    >
                      <base-lazy-image
                          alt="تصویر محصول"
                          lazy-src="/src/assets/products/p1.jpg"
                          class="!w-24 h-auto hover:scale-95 transition"
                      />
                    </router-link>
                  </div>
                  <div class="grow mr-3">
                    <router-link
                        :to="{name: 'product.detail', params: {id: 1}}"
                        class="inline-block mb-2 text-blue-600 hover:text-opacity-90 leading-relaxed"
                    >
                      لپتاپ خیلی باحال و کاربردی عمو فردوس
                    </router-link>
                    <div class="flex flex-wrap mb-2 items-center">
                      <div class="flex flex-wrap items-center gap-3">
                        <div class="text-xl">
                          450,000
                          <span class="text-xs text-gray-400">تومان</span>
                        </div>
                      </div>
                    </div>
                    <div class="flex flex-wrap gap-2 items-center mb-2">
                      <span class="text-gray-400 text-sm">تعداد محصول بازگشتی:</span>
                      <div class="flex">
                        <base-button
                            class="bg-white !text-gray-500 rounded-l-none hover:border-indigo-500 hover:!text-black shrink-0 !py-1"
                        >
                          <PlusSmallIcon class="h-6 w-6"/>
                        </base-button>
                        <div
                            class="block py-1 px-6 text-gray-900 ring-1 ring-inset ring-gray-300 grow text-center text-lg"
                        >
                          2
                        </div>
                        <base-button
                            class="bg-white !text-gray-500 rounded-r-none hover:border-indigo-500 hover:!text-black shrink-0 !py-1"
                        >
                          <MinusSmallIcon class="h-6 w-6"/>
                        </base-button>
                      </div>
                      <span class="py-1 px-2 text-xs border-2 border-green-500 rounded-full">تایید مرجوع</span>
                    </div>
                  </div>
                  <div class="shrink-0 mr-3 text-sm md:w-72">
                    <div>
                      <span class="text-gray-600 ml-2 text-xs">گارانتی:</span>
                      با ضمانت سام سرویس
                    </div>
                  </div>
                </li>
                <li class="relative flex flex-col md:flex-row mb-2 py-3 pr-3 pl-10">
                  <div class="shrink-0">
                    <router-link
                        :to="{name: 'product.detail', params: {id: 1}}"
                        class="inline-block"
                    >
                      <base-lazy-image
                          alt="تصویر محصول"
                          lazy-src="/src/assets/products/p2.jpg"
                          class="!w-24 h-auto hover:scale-95 transition"
                      />
                    </router-link>
                  </div>
                  <div class="grow mr-3">
                    <router-link
                        :to="{name: 'product.detail', params: {id: 1}}"
                        class="inline-block mb-2 text-blue-600 hover:text-opacity-90 leading-relaxed"
                    >
                      ماوس آبکی و مسخره‌ای که خیلی قیمت نداره ولی میتونی هر وقت خراب شد یکیشو
                      بگیری
                      چون ارزونه مثلا
                    </router-link>
                    <div class="flex flex-wrap mb-2 items-center">
                      <div class="flex flex-wrap items-center gap-3">
                        <div class="text-xl">
                          270,000
                          <span class="text-xs text-gray-400">تومان</span>
                        </div>
                      </div>
                    </div>
                    <div class="flex flex-wrap gap-2 items-center mb-2">
                      <span class="text-gray-400 text-sm">تعداد محصول بازگشتی:</span>
                      <div class="flex">
                        <base-button
                            class="bg-white !text-gray-500 rounded-l-none hover:border-indigo-500 hover:!text-black shrink-0 !py-1"
                        >
                          <PlusSmallIcon class="h-6 w-6"/>
                        </base-button>
                        <div
                            class="block py-1 px-6 text-gray-900 ring-1 ring-inset ring-gray-300 grow text-center text-lg"
                        >
                          5
                        </div>
                        <base-button
                            class="bg-white !text-gray-500 rounded-r-none hover:border-indigo-500 hover:!text-black shrink-0 !py-1"
                        >
                          <MinusSmallIcon class="h-6 w-6"/>
                        </base-button>
                      </div>
                      <span class="py-1 px-2 text-xs border-2 border-rose-500 rounded-full">عدم تایید</span>
                    </div>
                  </div>
                  <div class="shrink-0 mr-3 text-sm md:w-72">
                    <div class="mb-2 flex items-center">
                      <span class="text-gray-600 ml-2 text-xs">رنگ:</span>
                      قهوه‌ای تیره
                      <span
                          v-tooltip.top="'قهوه‌ای تیره'"
                          class="inline-block w-5 h-5 rounded-full border mr-2"
                          style="background-color: #833406;"
                      ></span>
                    </div>
                    <div class="mb-2">
                      <span class="text-gray-600 ml-2 text-xs">سایز:</span>
                      2XL
                    </div>
                    <div>
                      <span class="text-gray-600 ml-2 text-xs">گارانتی:</span>
                      با ضمانت سام سرویس
                    </div>
                  </div>
                </li>
                <li class="relative flex flex-col md:flex-row mb-2 py-3 pr-3 pl-10">
                  <div class="shrink-0">
                    <router-link
                        :to="{name: 'product.detail', params: {id: 1}}"
                        class="inline-block"
                    >
                      <base-lazy-image
                          alt="تصویر محصول"
                          lazy-src="/src/assets/products/p3.jpg"
                          class="!w-24 h-auto hover:scale-95 transition"
                      />
                    </router-link>
                  </div>
                  <div class="grow mr-3">
                    <router-link
                        :to="{name: 'product.detail', params: {id: 1}}"
                        class="inline-block mb-2 text-blue-600 hover:text-opacity-90 leading-relaxed"
                    >
                      لپتاپ خیلی باحال و کاربردی عمو فردوس
                    </router-link>
                    <div class="flex flex-wrap mb-2 items-center">
                      <div class="flex flex-wrap items-center gap-3">
                        <div class="text-xl">
                          50,000
                          <span class="text-xs text-gray-400">تومان</span>
                        </div>
                      </div>
                    </div>
                    <div class="flex flex-wrap gap-2 items-center mb-2">
                      <span class="text-gray-400 text-sm">تعداد محصول بازگشتی:</span>
                      <div class="flex">
                        <base-button
                            class="bg-white !text-gray-500 rounded-l-none hover:border-indigo-500 hover:!text-black shrink-0 !py-1"
                        >
                          <PlusSmallIcon class="h-6 w-6"/>
                        </base-button>
                        <div
                            class="block py-1 px-6 text-gray-900 ring-1 ring-inset ring-gray-300 grow text-center text-lg"
                        >
                          1
                        </div>
                        <base-button
                            class="bg-white !text-gray-500 rounded-r-none hover:border-indigo-500 hover:!text-black shrink-0 !py-1"
                        >
                          <MinusSmallIcon class="h-6 w-6"/>
                        </base-button>
                      </div>
                    </div>
                  </div>
                  <div class="shrink-0 mr-3 text-sm  md:w-72">
                    <div class="mb-2 flex items-center">
                      <span class="text-gray-600 ml-2 text-xs">رنگ:</span>
                      قرمز
                      <span
                          v-tooltip.top="'قرمز'"
                          class="inline-block w-5 h-5 rounded-full border mr-2"
                          style="background-color: #e44444;"
                      ></span>
                    </div>
                    <div>
                      <span class="text-gray-600 ml-2 text-xs">گارانتی:</span>
                      با ضمانت عمو سرویس
                    </div>
                  </div>
                </li>
              </ul>
            </template>
          </partial-card>
        </template>
      </base-loading-panel>
    </div>

    <div class="mt-3">
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

        <span class="ml-auto">ثبت درخواست</span>
      </base-animated-button>
    </div>
  </form>
</template>

<script setup>
import {ref} from "vue";
import {CheckIcon, ArrowLongLeftIcon, MinusSmallIcon, PlusSmallIcon} from "@heroicons/vue/24/outline/index.js";
import BaseMessage from "../../components/base/BaseMessage.vue";
import BaseLoadingPanel from "../../components/base/BaseLoadingPanel.vue";
import PartialCard from "../../components/partials/PartialCard.vue";
import {useForm} from "vee-validate";
import yup from "../../validation/index.js";
import BaseTextarea from "../../components/base/BaseTextarea.vue";
import PartialBadgeStatusReturnOrder from "../../components/partials/PartialBadgeStatusReturnOrder.vue";
import BaseLazyImage from "../../components/base/BaseLazyImage.vue";
import BaseButton from "../../components/base/BaseButton.vue";
import VTransitionFade from "../../transitions/VTransitionFade.vue";
import LoaderCircle from "../../components/base/loader/LoaderCircle.vue";
import BaseAnimatedButton from "../../components/base/BaseAnimatedButton.vue";
import BaseSelect from "../../components/base/BaseSelect.vue";
import PartialInputLabel from "../../components/partials/PartialInputLabel.vue";

const loading = ref(false)

const canSubmit = ref(true)
const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return
})
</script>

<style scoped>

</style>
