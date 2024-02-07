<template>
  <base-loading-panel
    type="content"
    :loading="loading"
  >
    <template #content>
      <div class="bg-white mb-3 rounded-lg border p-3">
        نمایش سبد‌های خرید کاربر -
        <span
          v-if="user?.id"
          class="text-teal-600"
        >{{
            (user?.first_name || user?.last_name) ? (user?.first_name + ' ' + user?.last_name).trim() : user.username
          }}</span>
      </div>

      <partial-card class="mb-3">
        <template #body>
          <base-accordion>
            <template #button>
              سبد خرید شماره 1
            </template>
            <template #panel>
              <ul class="divide-y divide-gray-100">
                <li class="flex flex-col md:flex-row mb-2 p-3">
                  <div class="shrink-0">
                    <router-link
                      :to="{name: 'admin.product.detail', params: {slug: '1'}}"
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
                      :to="{name: 'admin.product.detail', params: {slug: '1'}}"
                      class="inline-block mb-2 text-blue-600 hover:text-opacity-90 leading-relaxed"
                    >
                      لپتاپ خیلی باحال و کاربردی عمو فردوس
                    </router-link>
                    <div class="flex gap-1 items-center mb-2">
                      <span class="text-xs text-gray-600">تعداد:</span>
                      <span class="text-lg">2</span>
                      <span class="text-sm text-gray-600">عدد</span>
                    </div>
                  </div>
                  <div class="shrink-0 mr-3 text-sm md:w-72">
                    <div class="mb-2 flex items-center">
                      <span class="text-gray-600 ml-2">رنگ:</span>
                      قهوه‌ای تیره
                      <span
                        v-tooltip.top="'قهوه‌ای تیره'"
                        class="inline-block w-5 h-5 rounded-full border mr-2"
                        style="background-color: #833406;"
                      ></span>
                    </div>
                    <div class="mb-2">
                      <span class="text-gray-600 ml-2">سایز:</span>
                      2XL
                    </div>
                    <div>
                      <span class="text-gray-600 ml-2 text-xs">گارانتی:</span>
                      با ضمانت سام سرویس
                    </div>
                  </div>
                </li>
                <li class="flex flex-col md:flex-row mb-2 p-3">
                  <div class="shrink-0">
                    <router-link
                      :to="{name: 'admin.product.detail', params: {slug: '1'}}"
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
                      :to="{name: 'admin.product.detail', params: {slug: '1'}}"
                      class="inline-block mb-2 text-blue-600 hover:text-opacity-90 leading-relaxed"
                    >
                      لپتاپ خیلی باحال و کاربردی عمو فردوس
                    </router-link>
                    <div class="flex gap-1 items-center mb-2">
                      <span class="text-xs text-gray-600">تعداد:</span>
                      <span class="text-lg">5</span>
                      <span class="text-sm text-gray-600">عدد</span>
                    </div>
                  </div>
                  <div class="shrink-0 mr-3 text-sm md:w-72">
                    <div class="mb-2 flex items-center">
                      <span class="text-gray-600 ml-2">رنگ:</span>
                      قهوه‌ای تیره
                      <span
                        v-tooltip.top="'قهوه‌ای تیره'"
                        class="inline-block w-5 h-5 rounded-full border mr-2"
                        style="background-color: #833406;"
                      ></span>
                    </div>
                    <div class="mb-2">
                      <span class="text-gray-600 ml-2">سایز:</span>
                      2XL
                    </div>
                    <div>
                      <span class="text-gray-600 ml-2 text-xs">گارانتی:</span>
                      با ضمانت سام سرویس
                    </div>
                  </div>
                </li>
                <li class="flex flex-col md:flex-row mb-2 p-3">
                  <div class="shrink-0">
                    <router-link
                      :to="{name: 'admin.product.detail', params: {slug: '1'}}"
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
                      :to="{name: 'admin.product.detail', params: {slug: '1'}}"
                      class="inline-block mb-2 text-blue-600 hover:text-opacity-90 leading-relaxed"
                    >
                      ماوس آبکی و مسخره‌ای که خیلی قیمت نداره ولی میتونی هر وقت خراب شد یکیشو
                      بگیری چون ارزونه مثلا
                    </router-link>
                    <div class="flex gap-1 items-center mb-2">
                      <span class="text-xs text-gray-600">تعداد:</span>
                      <span class="text-lg">1</span>
                      <span class="text-sm text-gray-600">عدد</span>
                    </div>
                  </div>
                  <div class="shrink-0 mr-3 text-sm md:w-72">
                    <div class="mb-2 flex items-center">
                      <span class="text-gray-600 ml-2">رنگ:</span>
                      قرمز
                      <span
                        v-tooltip.top="'قرمز'"
                        class="inline-block w-5 h-5 rounded-full border mr-2"
                        style="background-color: #e44444;"
                      ></span>
                    </div>
                    <div class="mb-2">
                      <span class="text-gray-600 ml-2">سایز:</span>
                      XL
                    </div>
                    <div>
                      <span class="text-gray-600 ml-2 text-xs">گارانتی:</span>
                      با ضمانت عمو سرویس
                    </div>
                  </div>
                </li>
              </ul>
            </template>
          </base-accordion>
        </template>
      </partial-card>

      <partial-card class="mb-3">
        <template #body>
          <base-accordion>
            <template #button>
              سبد خرید شماره 2
            </template>
            <template #panel>
              <ul class="divide-y divide-gray-100">
                <li class="flex flex-col md:flex-row mb-2 p-3">
                  <div class="shrink-0">
                    <router-link
                      :to="{name: 'admin.product.detail', params: {slug: '1'}}"
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
                      :to="{name: 'admin.product.detail', params: {slug: '1'}}"
                      class="inline-block mb-2 text-blue-600 hover:text-opacity-90 leading-relaxed"
                    >
                      لپتاپ خیلی باحال و کاربردی عمو فردوس
                    </router-link>
                    <div class="flex gap-1 items-center mb-2">
                      <span class="text-xs text-gray-600">تعداد:</span>
                      <span class="text-lg">2</span>
                      <span class="text-sm text-gray-600">عدد</span>
                    </div>
                  </div>
                  <div class="shrink-0 mr-3 text-sm md:w-72">
                    <div class="mb-2 flex items-center">
                      <span class="text-gray-600 ml-2">رنگ:</span>
                      قهوه‌ای تیره
                      <span
                        v-tooltip.top="'قهوه‌ای تیره'"
                        class="inline-block w-5 h-5 rounded-full border mr-2"
                        style="background-color: #833406;"
                      ></span>
                    </div>
                    <div class="mb-2">
                      <span class="text-gray-600 ml-2">سایز:</span>
                      2XL
                    </div>
                    <div>
                      <span class="text-gray-600 ml-2 text-xs">گارانتی:</span>
                      با ضمانت سام سرویس
                    </div>
                  </div>
                </li>
                <li class="flex flex-col md:flex-row mb-2 p-3">
                  <div class="shrink-0">
                    <router-link
                      :to="{name: 'admin.product.detail', params: {slug: '1'}}"
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
                      :to="{name: 'admin.product.detail', params: {slug: '1'}}"
                      class="inline-block mb-2 text-blue-600 hover:text-opacity-90 leading-relaxed"
                    >
                      لپتاپ خیلی باحال و کاربردی عمو فردوس
                    </router-link>
                    <div class="flex gap-1 items-center mb-2">
                      <span class="text-xs text-gray-600">تعداد:</span>
                      <span class="text-lg">5</span>
                      <span class="text-sm text-gray-600">عدد</span>
                    </div>
                  </div>
                  <div class="shrink-0 mr-3 text-sm md:w-72">
                    <div class="mb-2 flex items-center">
                      <span class="text-gray-600 ml-2">رنگ:</span>
                      قهوه‌ای تیره
                      <span
                        v-tooltip.top="'قهوه‌ای تیره'"
                        class="inline-block w-5 h-5 rounded-full border mr-2"
                        style="background-color: #833406;"
                      ></span>
                    </div>
                    <div class="mb-2">
                      <span class="text-gray-600 ml-2">سایز:</span>
                      2XL
                    </div>
                    <div>
                      <span class="text-gray-600 ml-2 text-xs">گارانتی:</span>
                      با ضمانت سام سرویس
                    </div>
                  </div>
                </li>
                <li class="flex flex-col md:flex-row mb-2 p-3">
                  <div class="shrink-0">
                    <router-link
                      :to="{name: 'admin.product.detail', params: {slug: '1'}}"
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
                      :to="{name: 'admin.product.detail', params: {slug: '1'}}"
                      class="inline-block mb-2 text-blue-600 hover:text-opacity-90 leading-relaxed"
                    >
                      ماوس آبکی و مسخره‌ای که خیلی قیمت نداره ولی میتونی هر وقت خراب شد یکیشو
                      بگیری چون ارزونه مثلا
                    </router-link>
                    <div class="flex gap-1 items-center mb-2">
                      <span class="text-xs text-gray-600">تعداد:</span>
                      <span class="text-lg">1</span>
                      <span class="text-sm text-gray-600">عدد</span>
                    </div>
                  </div>
                  <div class="shrink-0 mr-3 text-sm md:w-72">
                    <div class="mb-2 flex items-center">
                      <span class="text-gray-600 ml-2">رنگ:</span>
                      قرمز
                      <span
                        v-tooltip.top="'قرمز'"
                        class="inline-block w-5 h-5 rounded-full border mr-2"
                        style="background-color: #e44444;"
                      ></span>
                    </div>
                    <div class="mb-2">
                      <span class="text-gray-600 ml-2">سایز:</span>
                      XL
                    </div>
                    <div>
                      <span class="text-gray-600 ml-2 text-xs">گارانتی:</span>
                      با ضمانت عمو سرویس
                    </div>
                  </div>
                </li>
              </ul>
            </template>
          </base-accordion>
        </template>
      </partial-card>
    </template>
  </base-loading-panel>
</template>

<script setup>
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import {onMounted, ref} from "vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import {useRoute} from "vue-router";
import {useRequest} from "@/composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "@/router/api-routes.js";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import BaseAccordion from "@/components/base/BaseAccordion.vue";
import {getRouteParamByKey} from "@/composables/helper.js";

const loading = ref(false)

const route = useRoute()
const idParam = getRouteParamByKey('id')

const user = ref(null)
const carts = ref({})

onMounted(() => {
  useRequest(apiReplaceParams(apiRoutes.admin.users.show, {user: idParam.value}), null, {
    success: (response) => {
      user.value = response.data
    },
  })
})
</script>
