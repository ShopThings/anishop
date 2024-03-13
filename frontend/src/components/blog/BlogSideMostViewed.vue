<template>
  <div class="flex flex-col divide-y">
    <loader-list-single-blog
        v-if="mostViewsBlogsLoading || !mostViewsBlogs?.length"
        v-for="i in 6"
        :key="i"
        size="small"
    />
    <div
        v-for="blog in mostViewsBlogs"
        v-else
        :key="blog.id"
        class="w-full py-2"
    >
      <blog-card-small
          :blog="blog"
          container-class="border-0 !bg-transparent"
      />
    </div>
  </div>
</template>

<script setup>
import {onMounted, ref} from "vue";
import LoaderListSingleBlog from "@/components/base/loader/LoaderListSingleBlog.vue";
import BlogCardSmall from "@/components/blog/BlogCardSmall.vue";
import {HomeBlogMainPage} from "@/service/APIHomePages.js";

const emit = defineEmits(['loaded'])

const mostViewsBlogs = ref(null)
const mostViewsBlogsLoading = ref(null)

onMounted(() => {
  HomeBlogMainPage.fetchMostViewedBlogs({
    success(response) {
      mostViewsBlogs.value = response.data
    },
    error() {
      return false
    },
    finally() {
      mostViewsBlogsLoading.value = false
      emit('loaded', !!mostViewsBlogs.value?.length)
    },
  })
})
</script>
