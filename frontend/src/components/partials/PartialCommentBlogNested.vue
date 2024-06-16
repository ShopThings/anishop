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
        :blog-slug="blogSlug"
        :parent-id="comment.id"
      />
    </template>

    <button
      v-if="config.page !== config.lastPage || commentsLoading"
      class="mb-6 rounded-full py-2 px-3 flex items-center justify-center gap-2.5 text-xs text-blue-500 bg-blue-100 border-2 border-transparent hover:border-blue-500 transition"
      type="button"
      @click="loadMore"
    >
      <VTransitionFade v-if="isLoading">
        <loader-circle
          big-circle-color="border-transparent"
          container-bg-color=""
          main-container-klass="relative h-6 w-44 flex items-center justify-center"
          spinner-klass="!h-6 !w-6"
        />
      </VTransitionFade>
      <template v-else>
        <span class="flex items-center justify-center gap-1">
          <span class="font-iranyekan-bold">مشاهده دیدگاه‌های بیشتر</span>
          <span class="font-iranyekan-bold py-0.5 px-1">( {{ config.total - (comments?.length || 0) }} )</span>
        </span>
        <ArrowLeftCircleIcon class="size-6"/>
      </template>
    </button>
    <button
      v-else-if="!commentsLoading && (comments?.length || tmpCommentsToggling)"
      :class="{'mt-3': showComments}"
      class="mb-3 rounded-full py-2 px-3 text-xs text-blue-500 bg-blue-100 border-2 border-transparent hover:border-blue-500 transition"
      type="button"
      @click="toggleShowCommentHandler"
    >
      <span v-if="!showComments" class="font-iranyekan-bold">
        مشاهده دیدگاه‌ها
        ({{ tmpCommentsToggling.length }})
      </span>
      <span v-else class="font-iranyekan-bold">بستن دیدگاه‌ها</span>
    </button>
  </div>
</template>

<script setup>
import {onMounted, reactive, ref} from "vue";
import PartialCommentBlogSingle from "@/components/partials/PartialCommentBlogSingle.vue";
import BaseFeedList from "@/components/base/BaseFeedList.vue";
import {ArrowLeftCircleIcon} from "@heroicons/vue/24/outline/index.js";
import {HomeBlogCommentAPI} from "@/service/APIHomePages.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";

const props = defineProps({
  blogSlug: {
    type: String,
    required: true,
  },
  parentId: {
    type: [Number, String],
    required: true,
  },
})

const config = reactive({
  page: 1,
  lastPage: 1,
  total: 0,
})

const comments = ref([])
const commentsLoading = ref(true)
const isLoading = ref(false)

function loadMore() {
  if (config.page === config.lastPage && !commentsLoading.value) return

  loadComments(20)
}

function loadComments(limit, {shouldAddComments, modifyLoader} = {shouldAddComments: true, modifyLoader: true}) {
  if (modifyLoader) {
    isLoading.value = true
  }

  HomeBlogCommentAPI.fetchAll(props.blogSlug, {
    parent_id: props.parentId,
    limit: limit,
    page: config.page,
    sort: 'asc',
  }, {
    success(response) {
      if (response.data?.length) {
        if (shouldAddComments) {
          for (let c of response.data) {
            comments.value.push(c)
          }
        }

        config.lastPage = response.meta.last_page || 1
        config.total = response.meta.total || 0
      }
    },
    error() {
      return false
    },
    finally() {
      if (modifyLoader) {
        commentsLoading.value = false
        isLoading.value = false

        if (config.lastPage !== config.page) {
          config.page++
        }
      }
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
    comments.value = []
  } else {
    comments.value = [...tmpCommentsToggling.value]
    tmpCommentsToggling.value = []
  }

  showComments.value = !showComments.value
}

onMounted(() => {
  // This is just to get last page number
  loadComments(1, {shouldAddComments: false, modifyLoader: false})
})
</script>
