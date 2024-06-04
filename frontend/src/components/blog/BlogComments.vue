<template>
  <loader-circle v-if="commentsLoading" main-container-klass="h-8 my-4"/>

  <template v-else-if="comments?.length">
    <template
      v-for="comment in comments"
      :key="comment.id"
    >
      <div class="mb-3">
        <partial-comment-blog-single
          :comment="comment"
          :container-class="[
            '!rounded-none',
            comment.is_for_current_user ? '!bg-emerald-50' : '',
          ]"
        />
      </div>
      <partial-comment-blog-nested
        v-if="comment.children_count > 0"
        :parent-id="comment.id"
      />
    </template>
    <div
      v-if="config.page !== config.lastPage"
      class="mb-3"
    >
      <button
        class="rounded-full mx-auto py-2 px-3 flex items-center justify-center gap-2.5 text-blue-500 bg-blue-100 border-2 border-transparent hover:border-blue-500 transition"
        type="button"
        @click="loadMore"
      >
        <span class="flex items-center justify-center gap-1">
          <span class="font-iranyekan-bold">مشاهده نظرات بیشتر</span>
          <span class="font-iranyekan-bold py-0.5 px-1">( {{ config.total - (comments?.length || 0) }} )</span>
        </span>
        <ArrowLeftCircleIcon class="size-6"/>
      </button>
    </div>
  </template>

  <div
    v-else
    class="mb-3 flex flex-col items-center justify-center gap-3"
  >
    <ChatBubbleLeftEllipsisIcon class="w-20 h-20 text-slate-300"/>
    <span class="text-blue-300">هیچ دیدگاهی ثبت نشده است</span>
  </div>
</template>

<script setup>
import {onMounted, reactive, ref} from "vue";
import {ArrowLeftCircleIcon, ChatBubbleLeftEllipsisIcon} from "@heroicons/vue/24/outline/index.js";
import PartialCommentBlogSingle from "@/components/partials/PartialCommentBlogSingle.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import {HomeBlogCommentAPI} from "@/service/APIHomePages.js";
import PartialCommentBlogNested from "@/components/partials/PartialCommentBlogNested.vue";

const props = defineProps({
  blogSlug: {
    type: String,
    required: true,
  },
})

const limit = 20
const config = reactive({
  page: 1,
  lastPage: 1,
  total: 0,
})

const comments = ref([])
const commentsLoading = ref(true)

function loadMore() {
  if (config.page === config.lastPage) return

  config.page++
  loadComments()
}

function loadComments() {
  HomeBlogCommentAPI.fetchAll(props.blogSlug, {
    limit: limit,
    page: config.page,
    sort: 'asc',
  }, {
    success(response) {
      if (response.data?.length) {
        for (let c of response.data) {
          comments.value.push(c)
        }
        config.lastPage = response.meta.last_page || 1
        config.total = response.meta.total
      }
    },
    error() {
      return false
    },
    finally() {
      commentsLoading.value = false
    },
  })
}

onMounted(() => {
  loadComments()
})
</script>
