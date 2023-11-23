<template>
    <div
        :class="containerClass"
        class="flex flex-col gap-3 shadow-md bg-white p-6 grow border border-slate-50"
    >
        <ul class="flex flex-wrap gap-4 items-center">
            <li class="text-sm text-slate-500">
                {{ comment?.creator?.first_name || 'کاربر سایت' }}
            </li>
            <li class="flex justify-center items-center">
                <span class="w-1.5 h-1.5 rounded-full bg-slate-200 inline-block"></span>
            </li>
            <li class="text-xs text-slate-400">
                ۳۰ تیر ۱۴۰۲
            </li>
            <li>
                <partial-badge-status-blog-comment/>
            </li>
        </ul>
        <p class="text-sm">
            این موضوع خیلی جالب نیست و اصلا ارزش وقت‌گذاشتن برروی آن را ندارد.
        </p>

        <div v-if="showAnswerButton" class="flex justify-end">
            <a
                href="javascript:void(0)"
                class="text-slate-500 hover:text-slate-600 transition text-sm flex items-center gap-2"
            >
                <span>پاسخ</span>
                <ArrowUturnLeftIcon class="w-5 h-5"/>
            </a>
        </div>
    </div>
</template>

<script setup>
import {UserIcon, ArrowUturnLeftIcon} from "@heroicons/vue/24/solid/index.js";
import PartialBadgeStatusBlogComment from "./PartialBadgeStatusBlogComment.vue";

defineProps({
    containerClass: String,
    comment: {
        type: Object,
        required: true,
    },
    showAnswerButton: {
        type: Boolean,
        default: true,
    },
})
</script>

<style scoped>

</style>
