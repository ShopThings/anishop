<template>
  <VTransitionSlideFadeUpY mode="out-in">
    <div
      v-if="blog && blog?.id"
      :class="[containerClass]"
      class="w-full h-full p-3 border bg-white"
    >
      <div class="flex flex-col sm:flex-row gap-3 h-full">
        <router-link
          :to="{name: 'blog.detail', params: {id: blog.id}}"
          target="_blank"
          class="group block shrink-0"
        >
          <base-lazy-image
            :alt="blog.title"
            :lazy-src="blog.image.path"
            class="bg-white !object-cover rounded-lg transition group-hover:scale-95 w-full h-56 sm:w-20 sm:h-20"
          />
        </router-link>

        <div class="w-full px-2 flex flex-col justify-center gap-3">
          <router-link
            :to="{name: 'blog.detail', params: {id: blog.id}}"
            target="_blank"
            class="text-sm leading-loose h-[40px] ellipsis-2 hover:text-indigo-600 transition"
            :title="blog.title"
          >
            <h1 class="font-iranyekan-bold text-sm">
              {{ blog.title }}
            </h1>
          </router-link>

          <div class="text-xs text-slate-400">
            {{ blog.created_at }}
          </div>
        </div>
      </div>
    </div>
    <template v-else>
      <loader-card-blog/>
    </template>
  </VTransitionSlideFadeUpY>
</template>

<script setup>
import VTransitionSlideFadeUpY from "../../transitions/VTransitionSlideFadeUpY.vue";
import BaseLazyImage from "../base/BaseLazyImage.vue";
import LoaderCardBlog from "../base/loader/LoaderCardBlog.vue";

const props = defineProps({
  containerClass: {
    type: String,
    default: 'rounded-lg shadow-lg',
  },
  blog: {
    type: Object,
    required: true,
  },
})
</script>
