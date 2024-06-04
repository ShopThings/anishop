<template>
  <VTransitionSlideFadeUpY mode="out-in">
    <div
        v-if="blog?.slug"
        :class="[containerClass]"
        class="w-full h-full p-3 border bg-white"
    >
      <div class="flex flex-col sm:flex-row gap-3 h-full">
        <router-link
            :to="{name: 'blog.detail', params: {slug: blog.slug}}"
            class="group block shrink-0"
            target="_blank"
        >
          <base-lazy-image
              :alt="blog.title"
              :lazy-src="blog.image.path"
              :is-local="false"
              class="bg-white !object-cover rounded-lg transition group-hover:scale-95 w-full h-56 sm:w-20 sm:h-20"
          />
        </router-link>

        <div class="w-full px-2 flex flex-col justify-center gap-3">
          <router-link
              :title="blog.title"
              :to="{name: 'blog.detail', params: {slug: blog.slug}}"
              class="text-sm leading-loose h-[40px] line-clamp-2 hover:text-indigo-600 transition"
              target="_blank"
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
      <loader-list-single-blog/>
    </template>
  </VTransitionSlideFadeUpY>
</template>

<script setup>
import VTransitionSlideFadeUpY from "@/transitions/VTransitionSlideFadeUpY.vue";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import LoaderListSingleBlog from "@/components/base/loader/LoaderListSingleBlog.vue";

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
