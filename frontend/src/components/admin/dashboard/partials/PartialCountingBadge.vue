<template>
  <div class="flex items-center gap-2 bg-white p-1 rounded-full border border-slate-200">
    <div class="flex items-center justify-center rounded-full size-10 p-1 bg-indigo-200">
      <component
        :is="outline[icon]"
        class="size-7 text-indigo-500 transition"
      />
    </div>

    <span class="text-slate-500 text-sm line-clamp-2">{{ text }}</span>
    <span class="mr-auto text-lg px-2.5 border-r border-r-slate-200 font-iranyekan-bold">{{ count }}</span>
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
