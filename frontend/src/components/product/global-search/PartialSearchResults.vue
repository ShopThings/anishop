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
        <base-lazy-image
          v-if="item?.image?.path"
          :alt="item.title"
          :is-local="false"
          :lazy-src="item?.image?.path"
          :size="FileSizes.SMALL"
          class="!w-16 !h-auto object-cover rounded shrink-0"
        />
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
import {useProductSearchStore} from "@/store/StoreProductSearch.js";
import {FileSizes} from "@/composables/file-list.js";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";

const emit = defineEmits(['navigating'])

const resultStore = useProductSearchStore()
</script>
