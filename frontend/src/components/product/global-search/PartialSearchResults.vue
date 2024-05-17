<template>
  <h2 class="text-sm font-iranyekan-bold mb-4">نتایج جستجو</h2>
  <ul
    v-if="resultStore.getProducts?.length"
    class="divide-y divide-slate-100"
  >
    <li
      v-for="item in resultStore.getProducts"
      :key="item.slug"
      class="py-1"
    >
      <router-link
        :to="{name: 'product.detail', params: {slug: item.slug}}"
        class="w-full p-2 rounded text-sm flex items-center gap-1.5 group hover:bg-slate-100 transition"
        @click="emit('navigating')"
      >
        <ArrowLeftCircleIcon class="size-5 shrink-0 text-slate-500 group-hover:text-blue-500 transition"/>
        <span class="leading-relaxed">{{ item.title }}</span>
      </router-link>
    </li>
  </ul>
  <div v-else>
    <div class="text-slate-400">
      هیچ نتیجه‌ای پیدا نشد.
    </div>
    <div class="text-sm text-slate-600 mt-2">
      برای جستجوی بیشتر،
      <span class="font-iranyekan-bold">نمایش نتایج بیشتر</span>
      در بالا را کلیک نمایید.
    </div>
  </div>
</template>

<script setup>
import {ArrowLeftCircleIcon} from "@heroicons/vue/24/outline/index.js";
import {useProductSearchStore} from "@/store/StoreProductSearch.js";

const emit = defineEmits(['navigating'])

const resultStore = useProductSearchStore()
</script>
