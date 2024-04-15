<template>
  <base-loading-panel
      :loading="loading"
      type="dot-orbit"
  >
    <template #content>
      <div class="bg-white mb-3 rounded-lg border p-3">
        نمایش سبد‌های خرید کاربر -
        <span
            v-if="user?.id"
            class="text-slate-400 text-base"
        ><partial-username-label :user="user"/></span>
      </div>

      <partial-card
        v-for="(cart, key) in carts"
        :key="key"
        class="mb-3"
      >
        <template #body>
          <base-accordion>
            <template #button>
              {{ cart.name }}
            </template>
            <template #panel>
              <ul
                v-if="cart?.length"
                class="divide-y divide-gray-100"
              >
                <li class="flex flex-col md:flex-row mb-2 p-3">
                  <div class="shrink-0">
                    <router-link
                      :to="{name: 'admin.product.detail', params: {slug: cart.product.slug}}"
                        class="inline-block"
                    >
                      <base-lazy-image
                        :alt="cart.product.title"
                        :lazy-src="cart.product.image.path"
                        :size="FileSizes.SMALL"
                          class="!w-24 h-auto hover:scale-95 transition"
                      />
                    </router-link>
                  </div>
                  <div class="grow mr-3">
                    <router-link
                      :to="{name: 'admin.product.detail', params: {slug: cart.product.slug}}"
                        class="inline-block mb-2 text-blue-600 hover:text-opacity-90 leading-relaxed"
                    >
                      {{ cart.product.title }}
                    </router-link>
                    <div class="flex items-center gap-2 mb-2">
                      <span class="font-iranyekan-bold text-black text-xl">{{
                          numberFormat(cart.price || 0)
                        }}</span>
                      <span class="text-xs text-slate-400">تومان</span>
                    </div>
                    <div class="flex gap-1 items-center mb-2">
                      <span class="text-xs text-gray-600">تعداد:</span>
                      <span class="text-black text-base">{{ cart.quantity }}</span>
                      <span class="text-xs text-slate-400">{{ cart.product.unit_name }}</span>
                    </div>
                  </div>
                  <div class="shrink-0 mr-3 text-sm md:w-72">
                    <div
                      v-if="cart?.color_name"
                      class="mb-2 flex items-center"
                    >
                      <span class="text-gray-600 ml-2">رنگ:</span>
                      {{ cart.color_name }}
                      <span
                        v-tooltip.top="cart.color_name.toString()"
                          class="inline-block w-5 h-5 rounded-full border mr-2"
                        :style="'background-color:' + cart.color_hex + ';'"
                      ></span>
                    </div>
                    <div
                      v-if="cart?.size"
                      class="mb-2"
                    >
                      <span class="text-gray-600 ml-2">سایز:</span>
                      {{ cart.size }}
                    </div>
                    <div v-if="cart?.guarantee">
                      <span class="text-gray-600 ml-2 text-xs">گارانتی:</span>
                      {{ cart.guarantee }}
                    </div>
                  </div>
                </li>
              </ul>
              <div
                v-else
                class="text-orange-300 text-base text-center pb-3"
              >
                هیچ محصولی در سبد خرید ذخیره نشده است
              </div>
            </template>
          </base-accordion>
        </template>
      </partial-card>
    </template>
  </base-loading-panel>
</template>

<script setup>
import {onMounted, ref} from "vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import BaseAccordion from "@/components/base/BaseAccordion.vue";
import {numberFormat, getRouteParamByKey} from "@/composables/helper.js";
import PartialUsernameLabel from "@/components/partials/PartialUsernameLabel.vue";
import {UserAPI, UserCartAPI} from "@/service/APIUser.js";
import {FileSizes} from "@/composables/file-list.js";

const loading = ref(true)

const idParam = getRouteParamByKey('id')

const user = ref(null)
const carts = ref({})

onMounted(() => {
  UserAPI.fetchById(idParam.value, {
    success: (response) => {
      user.value = response.data
    },
  })

  UserCartAPI.fetchAll(idParam.value, {
    success(response) {
      carts.value = response.data
      loading.value = false
    },
  })
})
</script>
