<template>
  <div class="mb-3">
    <template
      v-for="(comment, idx) in comments"
      :key="comment.id"
    >
      <base-feed-list
        :bullet-class="comment.is_for_current_user ? '!w-4 !h-4 !border-emerald-300 !bg-emerald-200' : ''"
        :is-last="idx === comments.length"
      >
        <template #item>
          <partial-comment-blog-single
            :comment="comment"
            :container-class="[
              '!rounded-none',
              comment.is_for_current_user ? '!bg-emerald-50' : '',
            ]"
          />
        </template>
      </base-feed-list>

      <partial-comment-blog-nested
        v-if="comment.children_count > 0"
        :parent-id="comment.id"
      />
    </template>

    <button
      v-if="config.page !== config.lastPage"
      class="mt-3 rounded-full py-2 px-3 flex items-center justify-center gap-2.5 text-xs text-blue-500 bg-blue-100 border-2 border-transparent hover:border-blue-500 transition"
      type="button"
      @click="loadMore"
    >
      <span class="flex items-center justify-center gap-1">
        <span class="font-iranyekan-bold">مشاهده نظرات بیشتر</span>
        <span class="font-iranyekan-bold py-0.5 px-1">( {{ config.total - (comments?.length || 0) }} )</span>
      </span>
      <ArrowLeftCircleIcon class="size-6"/>
    </button>
    <button
      v-else
      class="mt-3 rounded-full py-2 px-3 text-xs text-blue-500 bg-blue-100 border-2 border-transparent hover:border-blue-500 transition"
      type="button"
      @click="toggleShowCommentHandler"
    >
      <span v-if="!showComments" class="font-iranyekan-bold">مشاهده نظرات</span>
      <span v-else class="font-iranyekan-bold">بستن نظرات</span>
    </button>
  </div>
</template>

<script setup>
import {reactive, ref} from "vue";
import PartialCommentBlogSingle from "@/components/partials/PartialCommentBlogSingle.vue";
import BaseFeedList from "@/components/base/BaseFeedList.vue";
import {ArrowLeftCircleIcon} from "@heroicons/vue/24/outline/index.js";
import {HomeBlogCommentAPI} from "@/service/APIHomePages.js";

const props = defineProps({
  parentId: [Number, String],
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
    parent_id: props.parentId,
    limit: limit,
    page: config.page,
    sort: 'asc',
  }, {
    success(response) {
      if (response.data?.length) {
        for (let c in response.data) {
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

//----------------------------------------
// Toggle comment showing operations
//----------------------------------------
const showComments = ref(true)
const tmpCommentsToggling = ref([])

function toggleShowCommentHandler() {
  if (showComments.value) {
    tmpCommentsToggling.value = [...comments.value]
  } else {
    comments.value = [...tmpCommentsToggling.value]
  }

  showComments.value = !showComments.value
}
</script>
