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
                                <li class="flex mb-2 p-3">
                                    <router-link
                                        :to="{name: 'admin.product.detail', params: {id: 1}}"
                                        class="shrink-0"
                                    >
                                        <base-lazy-image
                                            alt="تصویر محصول"
                                            lazy-src="/src/assets/products/p1.jpg"
                                            class="!w-24 h-auto hover:scale-95 transition"
                                        />
                                    </router-link>
                                    <div class="shrink-0 mr-3">
                                        <router-link
                                            :to="{name: 'admin.product.detail', params: {id: 1}}"
                                            class="block mb-2 text-primary hover:text-opacity-90"
                                        >
                                            لپتاپ خیلی باحال و کاربردی عمو فردوس
                                        </router-link>
                                        <span class="block">تعداد: 2 عدد</span>
                                    </div>
                                    <div class="shrink-0 mr-3 grow text-sm">
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
                                            با ضمانت سام سرویس
                                        </div>
                                    </div>
                                </li>
                                <li class="flex mb-2 p-3">
                                    <router-link
                                        :to="{name: 'admin.product.detail', params: {id: 1}}"
                                        class="shrink-0"
                                    >
                                        <base-lazy-image
                                            alt="تصویر محصول"
                                            lazy-src="/src/assets/products/p2.jpg"
                                            class="!w-24 h-auto hover:scale-95 transition"
                                        />
                                    </router-link>
                                    <div class="shrink-0 mr-3">
                                        <router-link
                                            :to="{name: 'admin.product.detail', params: {id: 1}}"
                                            class="block mb-2 text-primary hover:text-opacity-90"
                                        >
                                            لپتاپ خیلی باحال و کاربردی عمو فردوس
                                        </router-link>
                                        <span class="block">تعداد: 5 عدد</span>
                                    </div>
                                    <div class="shrink-0 mr-3 grow text-sm">
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
                                            با ضمانت سام سرویس
                                        </div>
                                    </div>
                                </li>
                                <li class="flex mb-2 p-3">
                                    <router-link
                                        :to="{name: 'admin.product.detail', params: {id: 1}}"
                                        class="shrink-0"
                                    >
                                        <base-lazy-image
                                            alt="تصویر محصول"
                                            lazy-src="/src/assets/products/p3.jpg"
                                            class="!w-24 h-auto hover:scale-95 transition"
                                        />
                                    </router-link>
                                    <div class="shrink-0 mr-3">
                                        <router-link
                                            :to="{name: 'admin.product.detail', params: {id: 1}}"
                                            class="block mb-2 text-primary hover:text-opacity-90"
                                        >
                                            لپتاپ خیلی باحال و کاربردی عمو فردوس
                                        </router-link>
                                        <span class="block">تعداد: 1 عدد</span>
                                    </div>
                                    <div class="shrink-0 mr-3 grow text-sm">
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
                                <li class="flex mb-2 p-3">
                                    <router-link
                                        :to="{name: 'admin.product.detail', params: {id: 1}}"
                                        class="shrink-0"
                                    >
                                        <base-lazy-image
                                            alt="تصویر محصول"
                                            lazy-src="/src/assets/products/p1.jpg"
                                            class="!w-24 h-auto hover:scale-95 transition"
                                        />
                                    </router-link>
                                    <div class="shrink-0 mr-3">
                                        <router-link
                                            :to="{name: 'admin.product.detail', params: {id: 1}}"
                                            class="block mb-2 text-primary hover:text-opacity-90"
                                        >
                                            لپتاپ خیلی باحال و کاربردی عمو فردوس
                                        </router-link>
                                        <span class="block">تعداد: 2 عدد</span>
                                    </div>
                                    <div class="shrink-0 mr-3 grow text-sm">
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
                                            با ضمانت سام سرویس
                                        </div>
                                    </div>
                                </li>
                                <li class="flex mb-2 p-3">
                                    <router-link
                                        :to="{name: 'admin.product.detail', params: {id: 1}}"
                                        class="shrink-0"
                                    >
                                        <base-lazy-image
                                            alt="تصویر محصول"
                                            lazy-src="/src/assets/products/p2.jpg"
                                            class="!w-24 h-auto hover:scale-95 transition"
                                        />
                                    </router-link>
                                    <div class="shrink-0 mr-3">
                                        <router-link
                                            :to="{name: 'admin.product.detail', params: {id: 1}}"
                                            class="block mb-2 text-primary hover:text-opacity-90"
                                        >
                                            لپتاپ خیلی باحال و کاربردی عمو فردوس
                                        </router-link>
                                        <span class="block">تعداد: 5 عدد</span>
                                    </div>
                                    <div class="shrink-0 mr-3 grow text-sm">
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
                                            با ضمانت سام سرویس
                                        </div>
                                    </div>
                                </li>
                                <li class="flex mb-2 p-3">
                                    <router-link
                                        :to="{name: 'admin.product.detail', params: {id: 1}}"
                                        class="shrink-0"
                                    >
                                        <base-lazy-image
                                            alt="تصویر محصول"
                                            lazy-src="/src/assets/products/p3.jpg"
                                            class="!w-24 h-auto hover:scale-95 transition"
                                        />
                                    </router-link>
                                    <div class="shrink-0 mr-3">
                                        <router-link
                                            :to="{name: 'admin.product.detail', params: {id: 1}}"
                                            class="block mb-2 text-primary hover:text-opacity-90"
                                        >
                                            لپتاپ خیلی باحال و کاربردی عمو فردوس
                                        </router-link>
                                        <span class="block">تعداد: 1 عدد</span>
                                    </div>
                                    <div class="shrink-0 mr-3 grow text-sm">
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
                            سبد خرید شماره 3
                        </template>
                        <template #panel>
                            <ul class="divide-y divide-gray-100">
                                <li class="flex mb-2 p-3">
                                    <router-link
                                        :to="{name: 'admin.product.detail', params: {id: 1}}"
                                        class="shrink-0"
                                    >
                                        <base-lazy-image
                                            alt="تصویر محصول"
                                            lazy-src="/src/assets/products/p1.jpg"
                                            class="!w-24 h-auto hover:scale-95 transition"
                                        />
                                    </router-link>
                                    <div class="shrink-0 mr-3">
                                        <router-link
                                            :to="{name: 'admin.product.detail', params: {id: 1}}"
                                            class="block mb-2 text-primary hover:text-opacity-90"
                                        >
                                            لپتاپ خیلی باحال و کاربردی عمو فردوس
                                        </router-link>
                                        <span class="block">تعداد: 2 عدد</span>
                                    </div>
                                    <div class="shrink-0 mr-3 grow text-sm">
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
                                            با ضمانت سام سرویس
                                        </div>
                                    </div>
                                </li>
                                <li class="flex mb-2 p-3">
                                    <router-link
                                        :to="{name: 'admin.product.detail', params: {id: 1}}"
                                        class="shrink-0"
                                    >
                                        <base-lazy-image
                                            alt="تصویر محصول"
                                            lazy-src="/src/assets/products/p2.jpg"
                                            class="!w-24 h-auto hover:scale-95 transition"
                                        />
                                    </router-link>
                                    <div class="shrink-0 mr-3">
                                        <router-link
                                            :to="{name: 'admin.product.detail', params: {id: 1}}"
                                            class="block mb-2 text-primary hover:text-opacity-90"
                                        >
                                            لپتاپ خیلی باحال و کاربردی عمو فردوس
                                        </router-link>
                                        <span class="block">تعداد: 5 عدد</span>
                                    </div>
                                    <div class="shrink-0 mr-3 grow text-sm">
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
                                            با ضمانت سام سرویس
                                        </div>
                                    </div>
                                </li>
                                <li class="flex mb-2 p-3">
                                    <router-link
                                        :to="{name: 'admin.product.detail', params: {id: 1}}"
                                        class="shrink-0"
                                    >
                                        <base-lazy-image
                                            alt="تصویر محصول"
                                            lazy-src="/src/assets/products/p3.jpg"
                                            class="!w-24 h-auto hover:scale-95 transition"
                                        />
                                    </router-link>
                                    <div class="shrink-0 mr-3">
                                        <router-link
                                            :to="{name: 'admin.product.detail', params: {id: 1}}"
                                            class="block mb-2 text-primary hover:text-opacity-90"
                                        >
                                            لپتاپ خیلی باحال و کاربردی عمو فردوس
                                        </router-link>
                                        <span class="block">تعداد: 1 عدد</span>
                                    </div>
                                    <div class="shrink-0 mr-3 grow text-sm">
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
import BaseLoadingPanel from "../../../components/base/BaseLoadingPanel.vue";
import {computed, onMounted, ref} from "vue";
import PartialCard from "../../../components/partials/PartialCard.vue";
import {useRoute} from "vue-router";
import {useRequest} from "../../../composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "../../../router/api-routes.js";
import BaseLazyImage from "../../../components/base/BaseLazyImage.vue";
import BaseAccordion from "../../../components/base/BaseAccordion.vue";

const loading = ref(false)

const route = useRoute()
const idParam = computed(() => {
    const id = parseInt(route.params.id, 10)
    if (isNaN(id)) return route.params.id
    return id
})

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

<style scoped>

</style>
