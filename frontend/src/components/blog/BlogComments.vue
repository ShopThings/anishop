<template>
  <template v-if="comments.length">
    <div class="mb-3">
      <partial-comment-blog-single
          :comment="{}"
          container-class="rounded-none"
      />
    </div>

    <div class="mb-3">
      <partial-comment-blog-single
          :comment="{}"
          container-class="rounded-none"
      />
    </div>
    <div class="mb-3">
      <base-feed-list>
        <template #item>
          <partial-comment-blog-single
              :comment="{}"
              container-class="rounded-none"
          />
        </template>
      </base-feed-list>
      <base-feed-list bullet-class="border-emerald-300 !bg-emerald-200">
        <template #item>
          <partial-comment-blog-single
              :comment="{}"
              container-class="rounded-none !bg-emerald-50"
          />
        </template>
      </base-feed-list>
      <base-feed-list :is-last="true">
        <template #item>
          <partial-comment-blog-single
              :comment="{}"
              container-class="rounded-none"
          />
        </template>
      </base-feed-list>
    </div>

    <div class="mb-3">
      <partial-comment-blog-single
          :comment="{}"
          container-class="rounded-none"
      />
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
import {ref} from "vue";
import {ChatBubbleLeftEllipsisIcon} from "@heroicons/vue/24/outline/index.js";
import PartialCommentBlogSingle from "@/partials/PartialCommentBlogSingle.vue";
import BaseFeedList from "@/base/BaseFeedList.vue";

const comments = ref([
  {},
])
</script>
