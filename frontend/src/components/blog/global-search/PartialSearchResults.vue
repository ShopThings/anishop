<template>
  <h2 class="text-sm font-iranyekan-bold mb-4">نتایج جستجو</h2>
  <ul
    v-if="resultStore.getBlogs?.length"
    class="grid grid-cols-1 sm:grid-cols-2 gap-5"
  >
    <li
      v-for="item in resultStore.getBlogs"
      :key="item.slug"
    >
      <router-link
        :to="{name: 'blog.detail', params: {slug: item.slug}}"
        class="group"
        @click="emit('navigating')"
      >
        <base-lazy-image
          :alt="item.title"
          :lazy-src="item.image.path"
          :is-local="false"
          :size="FileSizes.MEDIUM"
          class="w-full h-36 sm:h-32 bg-white !object-cover rounded-lg transition group-hover:scale-95"
        />
        <span
          class="font-iranyekan-bold text-sm leading-relaxed mt-2 line-clamp-2 group-hover:text-blue-600 transition">{{
            item.title
          }}</span>
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
import {useBlogSearchStore} from "@/store/StoreBlogSearch.js";
import {FileSizes} from "@/composables/file-list.js";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";

const emit = defineEmits(['navigating'])

const resultStore = useBlogSearchStore()
</script>
