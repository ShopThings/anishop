<template>
  <div
      :class="containerClass"
      class="flex flex-col gap-3 shadow-md bg-white p-6 grow border border-slate-50"
  >
    <div
        v-if="!hasCommentInfo"
        class="animate-pulse"
    >
      <ul class="flex flex-wrap gap-4 items-center">
        <li class="bg-slate-200 w-20 h-4"></li>
        <li class="bg-slate-200 w-20 h-4"></li>
        <li class="bg-slate-200 w-20 h-4"></li>
        <li class="flex items-center">
          <FlagIcon class="w-5 h-5 text-rose-500 ml-2"/>
          <span class="bg-rose-200 w-8 h-1"></span>
        </li>
      </ul>

      <div class="my-7">
        <p class="rounded-full h-2.5 bg-slate-200 w-5/6 my-4"></p>
        <p class="rounded-full h-2.5 bg-slate-200 w-3/6 my-4"></p>
        <p class="rounded-full h-2.5 bg-slate-200 w-4/6 mt-4"></p>
      </div>

      <div>
        <ul class="mt-2">
          <li
              v-for="i in 2"
              :key="i"
              class="flex items-center space-y-1"
          >
            <PlusIcon class="w-5 h-5 text-emerald-500 ml-1"/>
            <span class="h-1 w-24 bg-emerald-300"></span>
          </li>
        </ul>

        <ul class="mt-2">
          <li
              v-for="i in 2"
              :key="i"
              class="flex items-center space-y-1"
          >
            <MinusIcon class="w-5 h-5 text-rose-500 ml-1"/>
            <span class="h-1 w-24 bg-rose-300"></span>
          </li>
        </ul>
      </div>

      <div class="flex flex-wrap justify-end items-center space-x-reverse space-x-3 py-3">
        <div class="flex text-gray-500">
          <span class="h-1.5 w-6 bg-slate-200 mt-2"></span>
          <HandThumbUpIcon
              class="w-6 h-6 mr-2"/>
        </div>
        <div class="flex text-gray-500">
          <span class="h-1.5 w-6 bg-slate-200 mt-2"></span>
          <HandThumbDownIcon
              class="w-6 h-6 mr-2"/>
        </div>
      </div>
    </div>
    <template v-else>
      <ul class="flex flex-wrap gap-4 items-center">
        <li class="text-sm text-slate-500">
          {{ comment?.created_by?.first_name || 'کاربر سایت' }}
        </li>
        <li class="flex justify-center items-center">
          <span class="w-1.5 h-1.5 rounded-full bg-slate-200 inline-block"></span>
        </li>
        <li class="text-xs text-slate-400">
          {{ comment?.created_at }}
        </li>
        <li class="flex items-center gap-2.5">
          <FlagIcon class="w-5 h-5 text-rose-500 ml-2"/>
          <span class="text-sm">{{ comment?.flag_count }}</span>
        </li>
      </ul>

      <div>
        <p class="text-sm leading-relaxed">
          {{ comment?.description || '-' }}
        </p>

        <div
          v-if="comment?.pros?.length"
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
              <PlusIcon class="w-5 h-5 text-emerald-500 ml-1"/>
              <span>{{ advantage }}</span>
            </li>
          </ul>
        </div>

        <div
          v-if="comment?.cons?.length"
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
              <MinusIcon class="w-5 h-5 text-rose-500 ml-1"/>
              <span>{{ disadvantage }}</span>
            </li>
          </ul>
        </div>
      </div>

      <div class="flex flex-wrap justify-end items-center space-x-reverse space-x-3 py-3">
        <div class="text-gray-500 text-sm">
          تعداد مفید بودن دیدگاه
        </div>
        <div class="flex text-gray-500">
          <span>{{ comment.up_vote_count }}</span>
          <HandThumbUpIcon
              class="w-6 h-6 mr-2"/>
        </div>
        <div class="flex text-gray-500">
          <span>{{ comment.down_vote_count }}</span>
          <HandThumbDownIcon
              class="w-6 h-6 mr-2"/>
        </div>
      </div>
    </template>
  </div>
</template>

<script setup>
import {computed} from "vue";
import isObject from "lodash.isobject";
import {FlagIcon, HandThumbDownIcon, HandThumbUpIcon, MinusIcon, PlusIcon,} from "@heroicons/vue/24/outline/index.js";

const props = defineProps({
  containerClass: String,
  comment: {
    type: Object,
    required: true,
  },
})

const hasCommentInfo = computed(() => {
  return props.comment &&
      isObject(props.comment) &&
      Object.keys(props.comment).length
})
</script>
