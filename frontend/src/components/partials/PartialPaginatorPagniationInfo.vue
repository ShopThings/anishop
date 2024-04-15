<template>
  <div class="flex flex-wrap items-center justify-end gap-3 pb-3 text-slate-500 mr-auto">
    <div class="flex gap-1.5 items-center">
      <span class="text-sm font-iranyekan-light">نمایش</span>
      <span class="font-iranyekan-bold text-black bg-violet-200 rounded-md py-0.5 px-2">{{
          numberFormat(offset + 1)
        }}</span>
      <span class="text-sm font-iranyekan-light">تا</span>
      <div class="font-iranyekan-bold text-black bg-violet-200 rounded-md py-0.5 px-2">
        <template v-if="!itemsLength">
          {{ numberFormat(offset + perPage) }}
        </template>
        <template v-else>
          {{ numberFormat(offset + Math.min(itemsLength, perPage)) }}
        </template>
      </div>
    </div>
    <div class="flex gap-1.5 items-center">
      <span class="ml-1 font-iranyekan-bold">-</span>
      <span class="text-sm font-iranyekan-light">صفحه</span>
      <span class="font-iranyekan-bold text-black bg-violet-200 rounded-md py-0.5 px-2">{{
          numberFormat(currentPage)
        }}</span>
      <span class="text-sm font-iranyekan-light">از</span>
      <div class="font-iranyekan-bold text-black bg-violet-200 rounded-md py-0.5 px-2">
        {{ numberFormat(maxPage) }}
      </div>
    </div>
    <div class="rounded-md text-black bg-indigo-100 border-2 border-indigo-500 py-0.5 px-3">
      <span class="font-iranyekan-bold">{{ numberFormat(total) }}</span>
      <span class="mr-1 text-sm font-iranyekan-light">مورد</span>
    </div>
  </div>
</template>

<script setup>
import {numberFormat} from "@/composables/helper.js";

defineProps({
  offset: [Number, String],
  perPage: [Number, String],
  currentPage: [Number, String],
  maxPage: [Number, String],
  total: [Number, String],
  itemsLength: [Number, String],
})
</script>
