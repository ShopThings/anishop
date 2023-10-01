<template>
    <div
        v-if="comments && comments.length"
        class="comment-sticky-container flex flex-col lg:flex-row gap-6"
    >
        <Vue3StickySidebar
            class="w-full lg:w-72 shrink-0"
            containerSelector=".comment-sticky-container"
            innerWrapperSelector='.sidebar__inner'
            :top-spacing="20"
            :bottom-spacing="20"
            :min-width="1024"
        >
            <div
                class="flex justify-start flex-col sm:justify-between sm:flex-row lg:justify-start lg:flex-col gap-3 border-2 border-violet-400 border-dashed rounded-lg p-3">
                <div class="flex items-center">
                    <ChatBubbleBottomCenterIcon class="w-14 h-14 text-gray-300 ml-3 shrink-0"/>
                    <div class="text-sm leading-relaxed grow">
                        نظر شما پس از بررسی و تایید در سایت نمایش داده می‌شود
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <base-button
                        type="link"
                        to="#"
                        class="!text-primary border-primary border-2"
                    >
                        ثبت نظر
                    </base-button>

                    <div class="text-rose-500 text-xs">
                        با نظر خود به سایر کاربران در یک خرید بهتر کمک نمایید
                    </div>
                </div>
            </div>
        </Vue3StickySidebar>

        <div class="grow">
            <div class="flex items-center my-4">
                <div class="grow h-0.5 bg-teal-500 rounded-full"></div>
                <span class="shrink-0 rounded-full bg-teal-100 px-4 py-1">نظر کاربران</span>
                <div class="grow h-0.5 bg-teal-500 rounded-full"></div>
            </div>

            <ul class="divide-y divide-orange-300">
                <li
                    v-for="comment in comments"
                    class="divide-y divide-gray-100 space-y-3 pt-3"
                >
                    <div class="py-2 text-sm flex flex-wrap items-center gap-3">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="text-gray-400">ارسال شده در تاریخ</span>
                            <span class="text-gray-500">{{ comment.created_at }}</span>
                        </div>
                        <div class="w-2 h-2 rounded-full bg-gray-200"></div>
                        <div class="text-gray-700">
                            {{
                                (comment.creator.first_name || comment.creator.last_name) ? (comment.creator.first_name + ' ' + comment.creator.last_name).trim() : 'کاربر سایت'
                            }}
                        </div>
                    </div>

                    <div class="pt-3">
                        <p>{{ comment.description }}</p>

                        <div
                            v-if="comment?.pros && comment.pros.length"
                            class="text-sm mt-3"
                        >
                            <h2 class="mb-2 text-emerald-600">
                                نکات مثبت
                            </h2>
                            <ul>
                                <li
                                    v-for="advantage in comment.pros"
                                    class="flex items-end space-y-1"
                                >
                                    <PlusSmallIcon class="w-5 h-5 text-emerald-500 ml-1"/>
                                    <span>{{ advantage }}</span>
                                </li>
                            </ul>
                        </div>

                        <div
                            v-if="comment?.cons && comment.cons.length"
                            class="text-sm mt-3"
                        >
                            <h2 class="mb-2 text-rose-600">
                                نکات منفی
                            </h2>
                            <ul>
                                <li
                                    v-for="disadvantage in comment.cons"
                                    class="flex items-end space-y-1"
                                >
                                    <MinusSmallIcon class="w-5 h-5 text-rose-500 ml-1"/>
                                    <span>{{ disadvantage }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex flex-wrap justify-end items-center space-x-reverse space-x-3 py-3">
                        <div class="text-gray-500 text-sm">
                            آیا این نظر مفید بود؟
                        </div>
                        <div class="flex text-gray-500">
                            <span>{{ comment.up_vote_count }}</span>
                            <HandThumbUpIcon
                                class="w-6 h-6 mr-2 cursor-pointer hover:text-black transition"/>
                        </div>
                        <div class="flex text-gray-500">
                            <span>{{ comment.down_vote_count }}</span>
                            <HandThumbDownIcon
                                class="w-6 h-6 mr-2 cursor-pointer hover:text-black transition"/>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <div
        v-else
    >
        <div class="mb-3">
            <div class="text-lg text-gray-500 mb-3">نظر برای محصول</div>
            <div class="leading-loose">{{ currentMainProduct.title }}</div>
        </div>

        <div
            class="flex justify-start flex-col sm:justify-between sm:flex-row gap-3 border-2 border-violet-400 border-dashed rounded-lg p-3">
            <div class="flex items-center">
                <ChatBubbleBottomCenterIcon class="w-14 h-14 text-gray-300 ml-3 shrink-0"/>
                <div class="text-sm leading-relaxed grow">
                    نظر شما پس از بررسی و تایید در سایت نمایش داده می‌شود
                </div>
            </div>

            <div class="flex flex-col gap-3">
                <base-button
                    type="link"
                    to="#"
                    class="!text-primary border-primary border-2"
                >
                    ثبت نظر
                </base-button>

                <div class="text-rose-500 text-xs">
                    با نظر خود به سایر کاربران در یک خرید بهتر کمک نمایید
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref} from "vue";
import {
    ChatBubbleBottomCenterIcon,
    HandThumbDownIcon,
    HandThumbUpIcon,
    MinusSmallIcon,
    PlusSmallIcon,
} from "@heroicons/vue/24/outline/index.js";
import Vue3StickySidebar from "vue3-sticky-sidebar";
import BaseButton from "../base/BaseButton.vue";

defineProps({
    productId: {
        type: Number,
        required: true,
    },
})

const comments = ref([
    {
        id: 1,
        product_id: 1,
        user_id: 1,
        up_vote_count: 1,
        down_vote_count: 2,
        creator: {
            id: 1,
            first_name: 'محمد مهدی',
            last_name: 'دهقان منشادی',
        },
        pros: [],
        cons: [],
        description: 'بسیار عالی و به موقع به دستم رسید',
        created_at: '۱۲ مهر ۱۴۰۲',
    },
    {
        id: 2,
        product_id: 1,
        user_id: 1,
        up_vote_count: 1,
        down_vote_count: 2,
        creator: {
            id: 1,
        },
        pros: [],
        cons: ['نداشتن شارژر'],
        description: 'در شگفت‌انگیز و تخفیفات با توجه به نوع گوشی ارزش خرید دارد، ولی به نوع کار خودتان و نیازتان گوشی انتخاب کنید',
        created_at: '۲۳ شهریور ۱۴۰۲',
    },
    {
        id: 3,
        product_id: 1,
        user_id: 2,
        up_vote_count: 12,
        down_vote_count: 1,
        creator: {
            id: 1,
            first_name: 'سعید',
            last_name: 'گرامی فر',
        },
        pros: ['سایر امکانات', 'صفحه نمایش', 'قلم s pen'],
        cons: [],
        description: 'در شگفت‌انگیز و تخفیفات با توجه به نوع گوشی ارزش خرید دارد، ولی به نوع کار خودتان و نیازتان گوشی انتخاب کنید',
        created_at: '۶ شهریور ۱۴۰۲',
    },
])
</script>

<style scoped>

</style>
