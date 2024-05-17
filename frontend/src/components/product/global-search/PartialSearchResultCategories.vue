<template>
  <h2 class="text-sm font-iranyekan-bold mb-2">دسته‌بندی‌ها</h2>
  <ul
    v-if="resultStore.getCategories?.length"
    class="flex flex-wrap gap-2"
  >
    <li
      v-for="item in resultStore.getCategories"
      :key="item.id"
    >
      <router-link
        :to="{name: 'search', query: {category: item.id}}"
        class="py-0.5 px-2.5 rounded-full text-sm bg-slate-200 hover:bg-slate-300 transition"
        @click="emit('navigating')"
      >
        {{ item.name }}
      </router-link>
    </li>
  </ul>
  <div v-else class="text-slate-400">
    هیچ دسته‌بندی‌ای وجود ندارد.
  </div>
</template>

<script setup>
import {useProductSearchStore} from "@/store/StoreProductSearch.js";

const emit = defineEmits(['navigating'])

const resultStore = useProductSearchStore()
</script>
