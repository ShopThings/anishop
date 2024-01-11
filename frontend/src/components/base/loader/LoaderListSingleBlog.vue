<template>
  <div role="status"
       class="p-4 rounded-lg animate-pulse dark:divide-slate-700 dark:border-slate-700">
    <div
      :class="{
                'flex-col sm:flex-row !items-stretch sm:items-center': size === 'large',
            }"
      class="flex items-center gap-6"
    >
      <template v-if="size === 'small'">
        <div class="flex items-center justify-center h-20 w-20 bg-slate-300 rounded">
          <svg class="w-10 h-10 text-slate-200 dark:text-slate-600" aria-hidden="true"
               xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 20">
            <path
              d="M14.066 0H7v5a2 2 0 0 1-2 2H0v11a1.97 1.97 0 0 0 1.934 2h12.132A1.97 1.97 0 0 0 16 18V2a1.97 1.97 0 0 0-1.934-2ZM10.5 6a1.5 1.5 0 1 1 0 2.999A1.5 1.5 0 0 1 10.5 6Zm2.221 10.515a1 1 0 0 1-.858.485h-8a1 1 0 0 1-.9-1.43L5.6 10.039a.978.978 0 0 1 .936-.57 1 1 0 0 1 .9.632l1.181 2.981.541-1a.945.945 0 0 1 .883-.522 1 1 0 0 1 .879.529l1.832 3.438a1 1 0 0 1-.031.988Z"/>
            <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.98 2.98 0 0 0 .13 5H5Z"/>
          </svg>
        </div>
        <div>
          <div class="h-2.5 bg-slate-300 rounded-full dark:bg-slate-600 w-24 mb-2.5"></div>
          <div class="w-32 h-2 bg-slate-200 rounded-full dark:bg-slate-700"></div>
        </div>
      </template>
      <template v-else-if="size === 'large'">
        <div class="flex items-center justify-center w-full h-36 sm:w-40 bg-slate-300 rounded">
          <svg class="w-10 h-10 text-slate-200 dark:text-slate-600" aria-hidden="true"
               xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 20">
            <path
              d="M14.066 0H7v5a2 2 0 0 1-2 2H0v11a1.97 1.97 0 0 0 1.934 2h12.132A1.97 1.97 0 0 0 16 18V2a1.97 1.97 0 0 0-1.934-2ZM10.5 6a1.5 1.5 0 1 1 0 2.999A1.5 1.5 0 0 1 10.5 6Zm2.221 10.515a1 1 0 0 1-.858.485h-8a1 1 0 0 1-.9-1.43L5.6 10.039a.978.978 0 0 1 .936-.57 1 1 0 0 1 .9.632l1.181 2.981.541-1a.945.945 0 0 1 .883-.522 1 1 0 0 1 .879.529l1.832 3.438a1 1 0 0 1-.031.988Z"/>
            <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.98 2.98 0 0 0 .13 5H5Z"/>
          </svg>
        </div>
        <div class="grow">
          <div class="flex items-center justify-between sm:justify-start gap-3 mb-6">
            <div class="h-6 bg-slate-200 rounded-full dark:bg-slate-600 w-24"></div>
            <div class="w-32 h-2 bg-slate-200 rounded-full dark:bg-slate-700"></div>
          </div>

          <div class="h-2.5 bg-slate-300 rounded-full dark:bg-slate-600 w-1/2 mb-3"></div>
          <div class="h-2.5 bg-slate-300 rounded-full dark:bg-slate-600 w-3/4 mb-8"></div>

          <div class="flex items-center gap-3">
            <div>
              <UserCircleIcon class="w-8 h-8 text-slate-300"/>
            </div>
            <div class="w-32 h-2 bg-slate-200 rounded-full dark:bg-slate-700"></div>
          </div>
        </div>
      </template>
    </div>
    <span class="sr-only">در حال بارگذاری...</span>
  </div>
</template>

<script setup>
import {UserCircleIcon} from "@heroicons/vue/24/outline/index.js";

defineProps({
  size: {
    type: String,
    default: 'small',
    validator: (value) => {
      return ['small', 'large'].indexOf(value) !== -1
    },
  },
})
</script>
