<template>
  <div
      v-if="latestBlogsLoading || !latestBlogs?.length"
      class="flex flex-wrap"
  >
    <div
        v-for="i in 3"
        :key="i"
        class="w-full lg:w-1/3 p-3 rounded-lg"
    >
      <loader-card-blog/>
    </div>
  </div>
  <template v-else>
    <partial-general-title
        container-class="mb-5 mt-6 p-2"
        title="آخرین نوشته‌ها"
        title-size="text-xl"
        type="side"
    />

    <div class="flex flex-wrap">
      <div
          v-for="blog in latestBlogs"
          :key="blog.id"
          class="w-full lg:w-1/3 p-3 rounded-lg"
      >
        <blog-card-main :blog="blog"/>
      </div>
    </div>
  </template>
</template>

<script setup>
import {onMounted, ref} from "vue";
import BlogCardMain from "@/components/blog/BlogCardMain.vue";
import PartialGeneralTitle from "@/components/partials/PartialGeneralTitle.vue";
import {HomeMainPageAPI} from "@/service/APIHomePages.js";
import LoaderCardBlog from "@/components/base/loader/LoaderCardBlog.vue";

const emit = defineEmits(['loaded'])

const latestBlogs = ref(null)
const latestBlogsLoading = ref(true)

onMounted(() => {
  HomeMainPageAPI.fetchLatestBlogs({
    success(response) {
      latestBlogs.value = response.data
    },
    error() {
      return false
    },
    finally() {
      latestBlogsLoading.value = false
      emit('loaded', !!latestBlogs.value?.length)
    },
  })
})
</script>
