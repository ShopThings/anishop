<template>
  <div class="p-2 border-[3px] border-indigo-400 rounded-xl">
    <div class="flex items-center gap-3 bg-white p-3 rounded-lg border border-slate-100 shadow-lg">
      <div class="flex items-center justify-center rounded-lg size-10 p-1 bg-indigo-200">
        <component
          :is="outline[icon]"
          class="size-7 text-indigo-500 transition"
        />
      </div>

      <span class="text-slate-500 text-sm truncate">{{ text }}</span>
      <span class="mr-auto text-lg pr-3 border-r border-r-slate-200">{{ count }}</span>
    </div>
  </div>
</template>

<script setup>
import * as outline from "@heroicons/vue/24/outline/index.js";

defineProps({
  count: {
    type: [Number, String],
    default: 0,
    required: true,
  },
  text: {
    type: String,
    required: true,
  },
  icon: {
    type: String,
    required: true,
  },
})
</script>
