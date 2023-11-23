<template>
    <app-navigation-header title="سبد خرید"/>

    <div class="px-3 mb-12">
        <div v-if="items.length" class="flex flex-col lg:flex-row gap-6 cart-info-sticky-container">
            <partial-card class="border-0 p-4 w-full">
                <template #body>
                    <ul class="flex flex-col divide-y divide-slate-200">
                        <li class="relative flex flex-col gap-3 py-6 pr-3 pl-10">
                            <div class="absolute left-0 top-1.5">
                                <a
                                    v-tooltip.right="'حذف از سبد خرید'"
                                    href="javascript:void(0)"
                                    class="group p-1 inline-block"
                                >
                                    <XMarkIcon class="w-6 h-6 text-gray-400 group-hover:text-rose-500 transition"/>
                                </a>
                            </div>

                            <div class="shrink-0 flex flex-col sm:flex-row gap-3">
                                <div class="shrink-0">
                                    <router-link
                                        :to="{name: 'product.detail', params: {id: 1}}"
                                        class="inline-block border rounded-lg"
                                    >
                                        <base-lazy-image
                                            alt="تصویر محصول"
                                            lazy-src="/src/assets/products/p1.jpg"
                                            class="!w-36 !h-36 sm:!w-28 sm:!h-28 object-contain hover:scale-95 transition rounded-lg"
                                        />
                                    </router-link>
                                </div>

                                <div class="flex gap-3 flex-col justify-between">
                                    <router-link
                                        :to="{name: 'product.detail', params: {id: 1}}"
                                        class="inline-block text-black hover:text-opacity-80 leading-relaxed text-sm"
                                    >
                                        لپتاپ گیمینگ عمو فردوس مدل RTX 1600 با صفحه تمام لمسی (الکی مثلا)
                                    </router-link>

                                    <div class="flex flex-wrap gap-2 items-center">
                                        <div class="flex">
                                            <base-button
                                                class="bg-white !text-gray-500 rounded-l-none hover:border-indigo-500 hover:!text-black shrink-0 !py-1"
                                            >
                                                <PlusSmallIcon class="h-6 w-6"/>
                                            </base-button>
                                            <div
                                                class="py-1 px-6 ring-1 ring-inset ring-gray-300 grow text-center flex items-center gap-2"
                                            >
                                                <span class="text-lg font-iranyekan-bold text-gray-900">2</span>
                                                <span class="text-sm text-gray-400">عدد</span>
                                            </div>
                                            <base-button
                                                class="bg-white !text-gray-500 rounded-r-none hover:border-indigo-500 hover:!text-black shrink-0 !py-1"
                                            >
                                                <MinusSmallIcon class="h-6 w-6"/>
                                            </base-button>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap items-center">
                                        <div
                                            class="rounded-lg bg-rose-500 text-white py-0.5 px-2 my-1 ml-3 flex items-center justify-center">
                                            <span class="text-xs">%</span>
                                            <div class="mr-1 inline-block text-sm">10</div>
                                        </div>
                                        <div class="flex flex-wrap items-center gap-3">
                                            <div class="text-lg font-iranyekan-bold">
                                                450,000
                                                <span class="text-xs text-gray-400">تومان</span>
                                            </div>

                                            <div class="relative text-center">
                                                <span
                                                    class="absolute top-1/2 -translate-y-1/2 left-0 h-[1px] w-full bg-slate-500 -rotate-3"></span>
                                                <div class="text-slate-500 text-sm font-iranyekan-bold">
                                                    500,000
                                                    <span class="text-xs text-gray-400">تومان</span>
                                                </div>
                                            </div>
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
                            <div class="absolute left-0 top-1.5">
                                <a
                                    v-tooltip.right="'حذف از سبد خرید'"
                                    href="javascript:void(0)"
                                    class="group p-1 inline-block"
                                >
                                    <XMarkIcon class="w-6 h-6 text-gray-400 group-hover:text-rose-500 transition"/>
                                </a>
                            </div>

                            <div class="shrink-0 flex flex-col sm:flex-row gap-3">
                                <div class="shrink-0">
                                    <router-link
                                        :to="{name: 'product.detail', params: {id: 1}}"
                                        class="inline-block border rounded-lg"
                                    >
                                        <base-lazy-image
                                            alt="تصویر محصول"
                                            lazy-src="/src/assets/products/p2.jpg"
                                            class="!w-36 !h-36 sm:!w-28 sm:!h-28 object-contain hover:scale-95 transition rounded-lg"
                                        />
                                    </router-link>
                                </div>

                                <div class="flex gap-3 flex-col justify-between">
                                    <router-link
                                        :to="{name: 'product.detail', params: {id: 1}}"
                                        class="inline-block text-black hover:text-opacity-80 leading-relaxed text-sm"
                                    >
                                        لپتاپ گیمینگ عمو فردوس مدل RTX 1600 با صفحه تمام لمسی (الکی مثلا)
                                    </router-link>

                                    <div class="flex flex-wrap gap-2 items-center">
                                        <div class="flex">
                                            <base-button
                                                class="bg-white !text-gray-500 rounded-l-none hover:border-indigo-500 hover:!text-black shrink-0 !py-1"
                                            >
                                                <PlusSmallIcon class="h-6 w-6"/>
                                            </base-button>
                                            <div
                                                class="py-1 px-6 ring-1 ring-inset ring-gray-300 grow text-center flex items-center gap-2"
                                            >
                                                <span class="text-lg font-iranyekan-bold text-gray-900">2</span>
                                                <span class="text-sm text-gray-400">عدد</span>
                                            </div>
                                            <base-button
                                                class="bg-white !text-gray-500 rounded-r-none hover:border-indigo-500 hover:!text-black shrink-0 !py-1"
                                            >
                                                <MinusSmallIcon class="h-6 w-6"/>
                                            </base-button>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap items-center">
                                        <div
                                            class="rounded-lg bg-rose-500 text-white py-0.5 px-2 my-1 ml-3 flex items-center justify-center">
                                            <span class="text-xs">%</span>
                                            <div class="mr-1 inline-block text-sm">10</div>
                                        </div>
                                        <div class="flex flex-wrap items-center gap-3">
                                            <div class="text-lg font-iranyekan-bold">
                                                450,000
                                                <span class="text-xs text-gray-400">تومان</span>
                                            </div>

                                            <div class="relative text-center">
                                                <span
                                                    class="absolute top-1/2 -translate-y-1/2 left-0 h-[1px] w-full bg-slate-500 -rotate-3"></span>
                                                <div class="text-slate-500 text-sm font-iranyekan-bold">
                                                    500,000
                                                    <span class="text-xs text-gray-400">تومان</span>
                                                </div>
                                            </div>
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
                            <div class="absolute left-0 top-1.5">
                                <a
                                    v-tooltip.right="'حذف از سبد خرید'"
                                    href="javascript:void(0)"
                                    class="group p-1 inline-block"
                                >
                                    <XMarkIcon class="w-6 h-6 text-gray-400 group-hover:text-rose-500 transition"/>
                                </a>
                            </div>

                            <div class="shrink-0 flex flex-col sm:flex-row gap-3">
                                <div class="shrink-0">
                                    <router-link
                                        :to="{name: 'product.detail', params: {id: 1}}"
                                        class="inline-block border rounded-lg"
                                    >
                                        <base-lazy-image
                                            alt="تصویر محصول"
                                            lazy-src="/src/assets/products/p3.jpg"
                                            class="!w-36 !h-36 sm:!w-28 sm:!h-28 object-contain hover:scale-95 transition rounded-lg"
                                        />
                                    </router-link>
                                </div>

                                <div class="flex gap-3 flex-col justify-between">
                                    <router-link
                                        :to="{name: 'product.detail', params: {id: 1}}"
                                        class="inline-block text-black hover:text-opacity-80 leading-relaxed text-sm"
                                    >
                                        لپتاپ گیمینگ عمو فردوس مدل RTX 1600 با صفحه تمام لمسی (الکی مثلا)
                                    </router-link>

                                    <div class="flex flex-wrap gap-2 items-center">
                                        <div class="flex">
                                            <base-button
                                                class="bg-white !text-gray-500 rounded-l-none hover:border-indigo-500 hover:!text-black shrink-0 !py-1"
                                            >
                                                <PlusSmallIcon class="h-6 w-6"/>
                                            </base-button>
                                            <div
                                                class="py-1 px-6 ring-1 ring-inset ring-gray-300 grow text-center flex items-center gap-2"
                                            >
                                                <span class="text-lg font-iranyekan-bold text-gray-900">2</span>
                                                <span class="text-sm text-gray-400">عدد</span>
                                            </div>
                                            <base-button
                                                class="bg-white !text-gray-500 rounded-r-none hover:border-indigo-500 hover:!text-black shrink-0 !py-1"
                                            >
                                                <MinusSmallIcon class="h-6 w-6"/>
                                            </base-button>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap items-center">
                                        <div
                                            class="rounded-lg bg-rose-500 text-white py-0.5 px-2 my-1 ml-3 flex items-center justify-center">
                                            <span class="text-xs">%</span>
                                            <div class="mr-1 inline-block text-sm">10</div>
                                        </div>
                                        <div class="flex flex-wrap items-center gap-3">
                                            <div class="text-lg font-iranyekan-bold">
                                                450,000
                                                <span class="text-xs text-gray-400">تومان</span>
                                            </div>

                                            <div class="relative text-center">
                                                <span
                                                    class="absolute top-1/2 -translate-y-1/2 left-0 h-[1px] w-full bg-slate-500 -rotate-3"></span>
                                                <div class="text-slate-500 text-sm font-iranyekan-bold">
                                                    500,000
                                                    <span class="text-xs text-gray-400">تومان</span>
                                                </div>
                                            </div>
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

                    <div class="text-left mb-4">
                        <base-button
                            type="button"
                            class="!text-black border-2 mt-6 px-6 w-full sm:w-auto hover:!bg-slate-50"
                        >
                            بروزرسانی سبد خرید
                        </base-button>
                    </div>
                </template>
            </partial-card>

            <div class="flex items-center gap-3 my-3 lg:hidden">
                <div class="grow h-0.5 bg-slate-300"></div>
                <div class="shrink-0">
                    <ShoppingCartIcon class="w-12 h-12 text-gray-300"/>
                </div>
                <div class="grow h-0.5 bg-slate-300"></div>
            </div>

            <Vue3StickySidebar
                class="w-full shrink-0 md:w-1/2 lg:w-1/3 md:mr-auto lg:mr-0"
                containerSelector=".cart-info-sticky-container"
                innerWrapperSelector='.sidebar__inner'
                :top-spacing="114"
                :bottom-spacing="20"
                :min-width="1024"
            >
                <partial-card class="border-0 p-8 !bg-slate-200 w-full">
                    <template #body>
                        <partial-general-title title="خلاصه سفارش" container-class="mb-6"/>

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
                                    وابسته به آدرس
                                </div>
                            </li>
                            <li
                                class="flex flex-wrap justify-between gap-3 py-2 text-sm"
                            >
                                <div class="font-iranyekan-bold leading-relaxed grow">
                                    قیمت کل
                                </div>
                                <div class="shrink-0 mr-auto">
                                    <span class="font-iranyekan-bold">280,000</span>
                                    <span class="text-slate-400 text-xs mr-2">تومان</span>
                                </div>
                            </li>
                        </ul>

                        <base-button
                            type="link"
                            :to="{name: 'checkout'}"
                            class="bg-primary w-full mt-6"
                        >
                            بازنگری و پرداخت
                        </base-button>
                    </template>
                </partial-card>
            </Vue3StickySidebar>
        </div>

        <template v-else>
            <partial-empty-card/>
        </template>
    </div>
</template>

<script setup>
import {ref} from "vue";
import {
    XMarkIcon,
    MinusSmallIcon,
    PlusSmallIcon,
    ShoppingCartIcon,
} from "@heroicons/vue/24/outline/index.js";
import AppNavigationHeader from "../components/AppNavigationHeader.vue";
import PartialCard from "../components/partials/PartialCard.vue";
import Vue3StickySidebar from "vue3-sticky-sidebar";
import PartialGeneralTitle from "../components/partials/PartialGeneralTitle.vue";
import BaseButton from "../components/base/BaseButton.vue";
import BaseLazyImage from "../components/base/BaseLazyImage.vue";
import PartialEmptyCard from "../components/partials/pages/PartialEmptyCard.vue";

const items = ref([
    {},
])
</script>

<style scoped>

</style>
