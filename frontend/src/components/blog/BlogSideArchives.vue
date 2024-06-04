<template>
  <div class="flex flex-col divide-y [&>*:last-child]:rounded-b-lg">
    <div
        v-for="i in 5"
        v-if="archivesLoading || !archives?.length"
        :key="i"
        class="p-2"
    >
      <div class="h-10 rounded bg-slate-200 animate-pulse"></div>
    </div>
    <router-link
        v-for="(item, idx) in archives"
        :key="idx"
        :to="{name: 'blog.search', query: {archive: item.year + '-' + item.month}}"
        class="text-sm font-iranyekan-light flex items-center justify-between px-3 py-3 hover:bg-slate-50 hover:shadow transition gap-3 group"
    >
      <div class="flex items-center gap-3">
        <ArchiveBoxIcon class="w-5 h-5 text-slate-300 group-hover:text-violet-500 transition"/>
        <span>{{ item.created_at }}</span>
      </div>
      <span class="rounded-full px-2.5 py-1 bg-indigo-200 text-xs">{{ item.count }}</span>
    </router-link>
  </div>
</template>

<script setup>
import {onMounted, ref} from "vue";
import {ArchiveBoxIcon} from "@heroicons/vue/24/outline/index.js";
import {HomeBlogAPI} from "@/service/APIHomePages";

const emit = defineEmits(['loaded'])

const archives = ref(null)
const archivesLoading = ref(true)

onMounted(() => {
  HomeBlogAPI.fetchBlogArchive({
    success(response) {
      archives.value = response.data
    },
    error() {
      return false
    },
    finally() {
      archivesLoading.value = false
      emit('loaded', !!archives.value?.length)
    },
  })
})
</script>
