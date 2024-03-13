<template>
  <div class="flex flex-col divide-y">
    <div
        v-for="i in 5"
        v-if="isLoading || !categories?.length"
        :key="i"
        class="p-2"
    >
      <div class="h-10 rounded bg-slate-200 animate-pulse"></div>
    </div>
    <router-link
        v-for="item in categories"
        :key="item.id"
        :to="{name: 'blog.search', query: {category: item.id}}"
        class="text-sm font-iranyekan-light flex items-center px-3 py-3 hover:bg-slate-50 transition gap-3 group"
    >
      <div class="w-3 h-3 border rounded-full border-slate-300 group-hover:border-rose-500"></div>
      <span>{{ item.name }}</span>
    </router-link>
  </div>
</template>

<script setup>
import {HomeBlogMainPage} from "@/service/APIHomePages.js";
import {onMounted, ref} from "vue";

const emit = defineEmits(['loaded'])

const categories = ref(null)
const isLoading = ref(true)

onMounted(() => {
  HomeBlogMainPage.fetchPopularCategories({
    success(response) {
      categories.value = response.data
    },
    error() {
      return false
    },
    finally() {
      isLoading.value = false
      emit('loaded', !!categories.value?.length)
    },
  })
})
</script>
