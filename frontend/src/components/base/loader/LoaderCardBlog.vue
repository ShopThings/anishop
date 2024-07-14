<template>
  <div
      class="bg-white w-full p-4 mx-auto border border-slate-200 rounded-lg shadow animate-pulse md:p-6"
      role="status">
    <div class="flex items-center justify-center h-48 mb-6 bg-slate-200 rounded">
      <svg aria-hidden="true" class="w-10 h-10 text-slate-300"
           fill="currentColor" viewBox="0 0 16 20" xmlns="http://www.w3.org/2000/svg">
        <path
            d="M14.066 0H7v5a2 2 0 0 1-2 2H0v11a1.97 1.97 0 0 0 1.934 2h12.132A1.97 1.97 0 0 0 16 18V2a1.97 1.97 0 0 0-1.934-2ZM10.5 6a1.5 1.5 0 1 1 0 2.999A1.5 1.5 0 0 1 10.5 6Zm2.221 10.515a1 1 0 0 1-.858.485h-8a1 1 0 0 1-.9-1.43L5.6 10.039a.978.978 0 0 1 .936-.57 1 1 0 0 1 .9.632l1.181 2.981.541-1a.945.945 0 0 1 .883-.522 1 1 0 0 1 .879.529l1.832 3.438a1 1 0 0 1-.031.988Z"/>
        <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.98 2.98 0 0 0 .13 5H5Z"/>
      </svg>
    </div>
    <div class="h-2.5 bg-slate-200 rounded-full w-48 mb-6"></div>
    <div class="h-2 bg-slate-200 rounded-full mb-3"></div>
    <div class="h-2 bg-slate-200 rounded-full mb-3"></div>
    <div class="flex items-center justify-between mt-6 space-x-3 space-x-reverse">
      <div>
        <UserCircleIcon class="w-10 h-10 text-slate-200"/>
      </div>
      <div class="grow">
        <div class="h-2.5 bg-slate-200 rounded-full w-28"></div>
      </div>
    </div>
    <span class="sr-only">در حال بارگذاری...</span>
  </div>
</template>

<script setup>
import {UserCircleIcon} from "@heroicons/vue/24/outline/index.js";
</script>
