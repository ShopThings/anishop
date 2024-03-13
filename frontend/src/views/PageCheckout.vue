<template>
  <app-navigation-header title="بازنگری و پرداخت"/>

  <div class="px-3 py-5 flex gap-3 mb-6 border-t border-b bg-white">
    <StarIcon class="w-6 h-6 text-orange-600 shrink-0"/>
    <div class="text-orange-600">
      لطفا قبل از پرداخت، حتما صفحه
      <router-link
          :to="{name: 'pages', params: {url: 'how-payment-works'}}"
          class="mx-1.5 underline underline-offset-8 text-black hover:text-opacity-90 transition"
          target="_blank"
      >
        نحوه پرداخت و پرداخت‌های چند مرحله‌ای
      </router-link>
      را مطالعه نمایید.
    </div>
  </div>

  <div class="px-3 mb-12">
    <template v-if="user">
      <form v-if="items.length" @submit.prevent="onSubmit">
        <div class="flex flex-col lg:flex-row gap-6 cart-checkout-sticky-container">
          <Vue3StickySidebar
              :bottom-spacing="20"
              :min-width="1024"
              :top-spacing="114"
              class="w-full lg:w-1/2"
              containerSelector=".cart-checkout-sticky-container"
              innerWrapperSelector='.sidebar__inner'
          >
            <partial-card class="border-0 p-6 w-full ring-2 ring-inset ring-yellow-300">
              <template #body>
                <div class="text-center text-slate-400 mb-2">
                  اگر کد کوپن دارید ، لطفاً آن را در اینجا وارد کنید
                </div>
                <div class="flex flex-col sm:flex-row items-end sm:items-center">
                  <div class="p-2 w-full">
                    <base-input
                        name="receiver_name"
                        placeholder="کد را وارد نمایید"
                    />
                  </div>
                  <div class="p-2 w-full sm:w-auto shrink-0">
                    <base-button
                        class="bg-primary px-6 w-full"
                        type="button"
                    >
                      <span>اعمال کوپن</span>
                    </base-button>
                  </div>
                </div>
              </template>
            </partial-card>

            <partial-card class="border-0 p-6 w-full mt-6">
              <template #body>
                <div class="flex items-start text-lime-600 gap-3 text-sm mb-3">
                  <InformationCircleIcon class="w-6 h-6 shrink-0"/>
                  <div class="leading-relaxed">
                    در صورت فعال نمودن این گزینه، فاکتور خرید همراه مرسولات برای شما ارسال
                    میگردد.
                  </div>
                </div>
                <base-switch
                    :enabled="isNeededFactorStatus"
                    label="فاکتور برای من ارسال شود"
                    name="is_needed_factor"
                    sr-text="فاکتور برای من ارسال شود"
                />
              </template>
            </partial-card>

            <partial-card class="border-0 p-6 w-full mt-6">
              <template #body>
                <h2 class="text-slate-400 mb-2">
                  مشخصات خریدار
                </h2>
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 p-2">
                  <div class="grow">
                    <div class="flex sm:flex-col gap-2">
                      <partial-input-label title="نام:"/>
                      <base-input
                          :has-edit-mode="false"
                          :is-editable="false"
                          :value="user?.first_name"
                          name="first_name"
                      />
                    </div>
                  </div>
                  <div class="grow">
                    <div class="flex sm:flex-col gap-2">
                      <partial-input-label title="نام خانوادگی:"/>
                      <base-input
                          :has-edit-mode="false"
                          :is-editable="false"
                          :value="user?.last_name"
                          name="last_name"
                      />
                    </div>
                  </div>
                  <div class="grow">
                    <div class="flex sm:flex-col gap-2">
                      <partial-input-label title="کد ملی:"/>
                      <base-input
                          :has-edit-mode="false"
                          :is-editable="false"
                          :value="user?.national_code"
                          name="national_code"
                      />
                    </div>
                  </div>
                </div>
              </template>
            </partial-card>

            <partial-card class="border-0 p-6 w-full mt-6">
              <template #body>
                <div class="flex items-center gap-3 justify-between">
                  <h2 class="text-slate-400 mb-2 shrink-0">
                    مشخصات گیرنده
                  </h2>

                  <base-button
                      class="bg-secondary px-6"
                      type="button"
                  >
                    <span>انتخاب آدرس</span>
                  </base-button>
                </div>

                <div class="flex flex-wrap">
                  <div class="p-2 w-full sm:w-1/2 lg:w-full">
                    <base-input
                        label-title="نام"
                        name="receiver_name"
                        placeholder="حروف فارسی"
                    />
                  </div>
                  <div class="p-2 w-full sm:w-1/2 lg:w-full">
                    <base-input
                        label-title="شماره تماس"
                        name="receiver_mobile"
                        placeholder="09xxxxxxxxx"
                    />
                  </div>
                  <div class="p-2 w-full sm:w-1/2 lg:w-full">
                    <partial-input-label title="انتخاب استان"/>
                    <base-select
                        :is-loading="provinceLoading"
                        :options="provinces"
                        :selected="selectedProvince"
                        name="province"
                        options-key="id"
                        options-text="name"
                        @change="(status) => {selectedProvince = status}"
                    />
                    <partial-input-error-message :error-message="errors.province"/>
                  </div>
                  <div class="p-2 w-full sm:w-1/2 lg:w-full">
                    <partial-input-label title="انتخاب شهرستان"/>
                    <base-select
                        :is-loading="cityLoading"
                        :options="cities"
                        :selected="selectedCity"
                        name="city"
                        options-key="id"
                        options-text="name"
                        @change="(status) => {selectedCity = status}"
                    />
                    <partial-input-error-message :error-message="errors.city"/>
                  </div>
                  <div class="p-2 w-full sm:w-1/2 lg:w-full">
                    <base-input
                        :is-optional="true"
                        label-title="کد پستی"
                        name="postal_code"
                        placeholder="وارد نمایید"
                    />
                  </div>
                  <div class="p-2 w-full">
                    <base-textarea
                        label-title="آدرس محل سکونت"
                        name="address"
                    />
                  </div>
                </div>
              </template>
            </partial-card>
          </Vue3StickySidebar>

          <div class="w-full lg:w-1/2">
            <partial-card class="border-0 p-4">
              <template #body>
                <partial-general-title container-class="mb-6" title="محصولات انتخاب شده"/>
                <ul
                    class="flex flex-col divide-y divide-slate-200 max-h-[32rem] my-custom-scrollbar border border-slate-200 bg-slate-50 rounded-lg"
                >
                  <li class="relative flex flex-col gap-3 py-6 pr-3 pl-10">
                    <div class="shrink-0 flex flex-col sm:flex-row gap-3">
                      <div class="shrink-0">
                        <router-link
                            :to="{name: 'product.detail', params: {slug: 1}}"
                            class="inline-block border rounded-lg"
                        >
                          <base-lazy-image
                              alt="تصویر محصول"
                              class="!w-32 !h-32 object-contain hover:scale-95 transition rounded-lg"
                              lazy-src="/src/assets/products/p1.jpg"
                          />
                        </router-link>
                      </div>

                      <div class="flex gap-3 flex-col">
                        <router-link
                            :to="{name: 'product.detail', params: {slug: 1}}"
                            class="inline-block text-black hover:text-opacity-80 leading-relaxed text-sm"
                        >
                          لپتاپ گیمینگ عمو فردوس مدل RTX 1600 با صفحه تمام لمسی (الکی مثلا)
                        </router-link>

                        <div class="flex flex-wrap items-center">
                          <div class="flex flex-wrap items-center gap-3">
                            <div class="text-lg font-iranyekan-bold">
                              450,000
                              <span class="text-xs text-gray-400">تومان</span>
                            </div>
                          </div>
                        </div>

                        <div class="flex flex-wrap gap-2 items-center">
                          <div class="flex items-center gap-1">
                            <span class="text-lg font-iranyekan-bold text-gray-900">2</span>
                            <span class="text-sm text-gray-400">عدد</span>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div
                        class="shrink-0 text-sm flex flex-col sm:flex-row flex-wrap gap-3 sm:items-center sm:divide-x sm:divide-x-reverse">
                      <div class="flex items-center">
                        <span class="text-gray-600 ml-2 text-xs sm:hidden">رنگ:</span>
                        قهوه‌ای تیره
                        <span
                            v-tooltip.top="'قهوه‌ای تیره'"
                            class="inline-block w-5 h-5 rounded-full border mr-2"
                            style="background-color: #833406;"
                        ></span>
                      </div>
                      <div class="sm:pr-3">
                        <span class="text-gray-600 ml-2 text-xs sm:hidden">سایز:</span>
                        2XL
                      </div>
                      <div class="sm:pr-3">
                        <span class="text-gray-600 ml-2 text-xs sm:hidden">گارانتی:</span>
                        با ضمانت سام سرویس
                      </div>
                    </div>
                  </li>
                  <li class="relative flex flex-col gap-3 py-6 pr-3 pl-10">
                    <div class="shrink-0 flex flex-col sm:flex-row gap-3">
                      <div class="shrink-0">
                        <router-link
                            :to="{name: 'product.detail', params: {slug: 1}}"
                            class="inline-block border rounded-lg"
                        >
                          <base-lazy-image
                              alt="تصویر محصول"
                              class="!w-32 !h-32 object-contain hover:scale-95 transition rounded-lg"
                              lazy-src="/src/assets/products/p2.jpg"
                          />
                        </router-link>
                      </div>

                      <div class="flex gap-3 flex-col">
                        <router-link
                            :to="{name: 'product.detail', params: {slug: 1}}"
                            class="inline-block text-black hover:text-opacity-80 leading-relaxed text-sm"
                        >
                          لپتاپ گیمینگ عمو فردوس مدل RTX 1600 با صفحه تمام لمسی (الکی مثلا)
                        </router-link>

                        <div class="flex flex-wrap items-center">
                          <div class="flex flex-wrap items-center gap-3">
                            <div class="text-lg font-iranyekan-bold">
                              450,000
                              <span class="text-xs text-gray-400">تومان</span>
                            </div>
                          </div>
                        </div>

                        <div class="flex flex-wrap gap-2 items-center">
                          <div class="flex items-center gap-1">
                            <span class="text-lg font-iranyekan-bold text-gray-900">2</span>
                            <span class="text-sm text-gray-400">عدد</span>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div
                        class="shrink-0 text-sm flex flex-col sm:flex-row flex-wrap gap-3 sm:items-center sm:divide-x sm:divide-x-reverse">
                      <div class="flex items-center">
                        <span class="text-gray-600 ml-2 text-xs sm:hidden">رنگ:</span>
                        قهوه‌ای تیره
                        <span
                            v-tooltip.top="'قهوه‌ای تیره'"
                            class="inline-block w-5 h-5 rounded-full border mr-2"
                            style="background-color: #833406;"
                        ></span>
                      </div>
                      <div class="sm:pr-3">
                        <span class="text-gray-600 ml-2 text-xs sm:hidden">سایز:</span>
                        2XL
                      </div>
                      <div class="sm:pr-3">
                        <span class="text-gray-600 ml-2 text-xs sm:hidden">گارانتی:</span>
                        با ضمانت سام سرویس
                      </div>
                    </div>
                  </li>
                  <li class="relative flex flex-col gap-3 py-6 pr-3 pl-10">
                    <div class="shrink-0 flex flex-col sm:flex-row gap-3">
                      <div class="shrink-0">
                        <router-link
                            :to="{name: 'product.detail', params: {slug: 1}}"
                            class="inline-block border rounded-lg"
                        >
                          <base-lazy-image
                              alt="تصویر محصول"
                              class="!w-32 !h-32 object-contain hover:scale-95 transition rounded-lg"
                              lazy-src="/src/assets/products/p3.jpg"
                          />
                        </router-link>
                      </div>

                      <div class="flex gap-3 flex-col">
                        <router-link
                            :to="{name: 'product.detail', params: {slug: 1}}"
                            class="inline-block text-black hover:text-opacity-80 leading-relaxed text-sm"
                        >
                          لپتاپ گیمینگ عمو فردوس مدل RTX 1600 با صفحه تمام لمسی (الکی مثلا)
                        </router-link>

                        <div class="flex flex-wrap items-center">
                          <div class="flex flex-wrap items-center gap-3">
                            <div class="text-lg font-iranyekan-bold">
                              450,000
                              <span class="text-xs text-gray-400">تومان</span>
                            </div>
                          </div>
                        </div>

                        <div class="flex flex-wrap gap-2 items-center">
                          <div class="flex items-center gap-1">
                            <span class="text-lg font-iranyekan-bold text-gray-900">2</span>
                            <span class="text-sm text-gray-400">عدد</span>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div
                        class="shrink-0 text-sm flex flex-col sm:flex-row flex-wrap gap-3 sm:items-center sm:divide-x sm:divide-x-reverse">
                      <div class="flex items-center">
                        <span class="text-gray-600 ml-2 text-xs sm:hidden">رنگ:</span>
                        قهوه‌ای تیره
                        <span
                            v-tooltip.top="'قهوه‌ای تیره'"
                            class="inline-block w-5 h-5 rounded-full border mr-2"
                            style="background-color: #833406;"
                        ></span>
                      </div>
                      <div class="sm:pr-3">
                        <span class="text-gray-600 ml-2 text-xs sm:hidden">سایز:</span>
                        2XL
                      </div>
                      <div class="sm:pr-3">
                        <span class="text-gray-600 ml-2 text-xs sm:hidden">گارانتی:</span>
                        با ضمانت سام سرویس
                      </div>
                    </div>
                  </li>
                </ul>

                <div class="flex items-center gap-3 my-3">
                  <div class="grow h-0.5 bg-slate-300"></div>
                  <div class="shrink-0 rounded-full border-2 border-slate-300 w-6 h-6">

                  </div>
                  <div class="grow h-0.5 bg-slate-300"></div>
                </div>

                <div class="mt-6">
                  <partial-general-title container-class="mb-6" title="خلاصه سفارش"/>
                  <ul class="flex flex-col divide-y divide-slate-300">
                    <li class="flex flex-wrap justify-between gap-3 py-2 text-sm">
                      <div class="font-iranyekan-light leading-relaxed grow">
                        تعداد محصولات
                      </div>
                      <div class="shrink-0 mr-auto">
                        <div class="flex items-center rounded-full py-0.5 px-3 bg-violet-300">
                          <span class="text-base font-iranyekan-bold">2</span>
                          <span class="text-xs mr-2">عدد</span>
                        </div>
                      </div>
                    </li>
                    <li class="flex flex-wrap justify-between gap-3 py-2 text-sm">
                      <div class="font-iranyekan-light leading-relaxed grow">
                        قیمت محصولات
                      </div>
                      <div class="shrink-0 mr-auto">
                        <span class="font-iranyekan-bold">250,000</span>
                        <span class="text-slate-400 text-xs mr-2">تومان</span>
                      </div>
                    </li>
                    <li class="flex flex-wrap justify-between gap-3 py-2 text-sm">
                      <div class="font-iranyekan-light leading-relaxed grow">
                        هزینه ارسال
                      </div>
                      <div class="shrink-0 mr-auto">
                        محاسبه پس از انتخاب آدرس
                      </div>
                    </li>
                    <li class="flex flex-wrap justify-between gap-3 py-2 text-sm">
                      <div class="font-iranyekan-bold leading-relaxed grow">
                        قیمت کل
                      </div>
                      <div class="shrink-0 mr-auto">
                        <span class="font-iranyekan-bold">280,000</span>
                        <span class="text-slate-400 text-xs mr-2">تومان</span>
                      </div>
                    </li>
                  </ul>

                  <partial-general-title container-class="my-8" title="انتخاب روش ارسال"/>
                  <ul class="space-y-3">
                    <li class="border-2 rounded-md p-2 border-indigo-300">
                      <base-radio
                          v-model="sendMethod"
                          container-class="flex-row-reverse justify-end"
                          label-title="پست"
                          name="send_method"
                          value="post"
                      >
                        <template #text="{title}">
                          <div class="flex items-center gap-3">
                            <img
                                alt=""
                                class="w-16 h-16 object-contain rounded-lg"
                                src="/src/assets/send-methods/post.png"
                            />
                            <span>{{ title }}</span>
                          </div>
                        </template>
                      </base-radio>
                    </li>
                    <li class="border-2 rounded-md p-2 border-indigo-300">
                      <base-radio
                          v-model="sendMethod"
                          container-class="flex-row-reverse justify-end"
                          label-title="پیک موتوری (درون شهری)"
                          name="send_method"
                          value="motori"
                      >
                        <template #text="{title}">
                          <div class="flex items-center gap-3">
                            <img
                                alt=""
                                class="w-16 h-16 object-contain rounded-lg"
                                src="/src/assets/send-methods/motori.png"
                            />
                            <span>{{ title }}</span>
                          </div>
                          <div class="mt-2 text-slate-500 py-1 px-2.5">
                            این روش ارسال فقط برای ارسال‌های درون شهر شیراز می‌باشد.
                          </div>
                        </template>
                      </base-radio>
                    </li>
                    <li class="border-2 rounded-md p-2 border-indigo-300">
                      <base-radio
                          v-model="sendMethod"
                          container-class="flex-row-reverse justify-end"
                          label-title="باربری"
                          name="send_method"
                          value="barbari"
                      >
                        <template #text="{title}">
                          <div class="flex items-center gap-3">
                            <img
                                alt=""
                                class="w-16 h-16 object-contain rounded-lg"
                                src="/src/assets/send-methods/barbari.png"
                            />
                            <span>{{ title }}</span>
                          </div>
                        </template>
                      </base-radio>
                    </li>
                    <li class="border-2 rounded-md p-2 border-indigo-300">
                      <base-radio
                          v-model="sendMethod"
                          container-class="flex-row-reverse justify-end"
                          label-title="چاپار (پس کرایه)"
                          name="send_method"
                          value="chapar"
                      >
                        <template #text="{title}">
                          <div class="flex items-center gap-3">
                            <img
                                alt=""
                                class="w-16 h-16 object-contain rounded-lg"
                                src="/src/assets/send-methods/chapar.png"
                            />
                            <span>{{ title }}</span>
                          </div>
                        </template>
                      </base-radio>
                    </li>
                  </ul>

                  <partial-general-title container-class="my-8" title="انتخاب روش پرداخت"/>
                  <ul class="space-y-3">
                    <li class="border-2 rounded-md p-2 border-indigo-300">
                      <base-radio
                          v-model="paymentMethod"
                          container-class="flex-row-reverse justify-end"
                          label-title="پرداخت آیدی پی"
                          name="payment_method"
                          value="id_pay"
                      >
                        <template #text="{title}">
                          <div class="flex items-center gap-3">
                            <img
                                alt=""
                                class="w-16 h-16 object-contain rounded-lg"
                                src="/src/assets/gateways/IdPay.png"
                            />
                            <span>{{ title }}</span>
                          </div>
                        </template>
                      </base-radio>
                    </li>
                    <li class="border-2 rounded-md p-2 border-indigo-300">
                      <base-radio
                          v-model="paymentMethod"
                          container-class="flex-row-reverse justify-end"
                          label-title="پرداخت الکترونیک سداد"
                          name="payment_method"
                          value="sadad"
                      >
                        <template #text="{title}">
                          <div class="flex items-center gap-3">
                            <img
                                alt=""
                                class="w-16 h-16 object-contain rounded-lg"
                                src="/src/assets/gateways/sadad.jpg"
                            />
                            <span>{{ title }}</span>
                          </div>
                        </template>
                      </base-radio>
                    </li>
                    <li class="border-2 rounded-md p-2 border-indigo-300">
                      <base-radio
                          v-model="paymentMethod"
                          container-class="flex-row-reverse justify-end"
                          label-title="به پرداخت ملت"
                          name="payment_method"
                          value="beh_pardakht"
                      >
                        <template #text="{title}">
                          <div class="flex items-center gap-3">
                            <img
                                alt=""
                                class="w-16 h-16 object-contain rounded-lg"
                                src="/src/assets/gateways/beh-pardakht.png"
                            />
                            <span>{{ title }}</span>
                          </div>
                        </template>
                      </base-radio>
                    </li>
                  </ul>

                  <base-button
                      :disabled="isSubmitting"
                      class="bg-emerald-500 w-full mt-6 flex items-center gap-3 justify-center"
                      type="submit"
                  >
                    <VTransitionFade>
                      <loader-circle
                          v-if="isSubmitting"
                          big-circle-color="border-transparent"
                          main-container-klass="absolute w-full h-full top-0 left-0"
                      />
                    </VTransitionFade>

                    <span>ثبت سفارش و پرداخت</span>
                    <CreditCardIcon class="w-6 h-6"/>
                  </base-button>
                </div>
              </template>
            </partial-card>
          </div>
        </div>
      </form>

      <template v-else>
        <partial-empty-card/>
      </template>
    </template>

    <partial-card v-else class="border-0 p-6 text-slate-500 text-lg">
      <template #body>
        ابتدا به پنل کاربری خود
        <router-link :to="{name: 'login'}" class="mr-1 text-orange-500 hover:text-opacity-80">
          وارد شوید
        </router-link>
        و سپس برای پرداخت اقدام نمایید.
      </template>
    </partial-card>
  </div>
</template>

<script setup>
import {ref} from "vue";
import AppNavigationHeader from "@/components/AppNavigationHeader.vue";
import {useAdminAuthStore, useUserAuthStore} from "@/store/StoreUserAuth.js";
import PartialCard from "@/components/partials/PartialCard.vue";
import PartialEmptyCard from "@/components/partials/pages/PartialEmptyCard.vue";
import Vue3StickySidebar from "vue3-sticky-sidebar";
import {CreditCardIcon, InformationCircleIcon, StarIcon} from "@heroicons/vue/24/outline/index.js";
import BaseButton from "@/components/base/BaseButton.vue";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import PartialGeneralTitle from "@/components/partials/PartialGeneralTitle.vue";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import BaseSelect from "@/components/base/BaseSelect.vue";
import {useForm} from "vee-validate";
import yup from "@/validation/index.js";
import BaseTextarea from "@/components/base/BaseTextarea.vue";
import BaseRadio from "@/components/base/BaseRadio.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";

const store = useUserAuthStore()
const user = store.getUser
const items = ref([
  {},
])

const isNeededFactorStatus = ref(false)

const sendMethod = ref('post')
const paymentMethod = ref('id_pay')

const provinces = ref([])
const cities = ref([])
const provinceLoading = ref(true)
const cityLoading = ref(true)

const selectedProvince = ref(null)
const selectedCity = ref(null)

const canSubmit = ref(true)

const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {

})
</script>
