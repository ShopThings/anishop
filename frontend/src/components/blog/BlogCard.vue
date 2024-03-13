<template>
  <VTransitionSlideFadeUpY mode="out-in">
    <div
        v-if="blog && blog?.slug"
        :class="[
          type === 'vertical' ? 'min-h-[370px]' : '',
          containerClass,
      ]"
        class="w-full h-full p-3 border bg-white"
    >
      <template v-if="type === 'vertical'">
        <router-link
            :to="{name: 'blog.detail', params: {slug: blog.slug}}"
            class="group relative block"
            target="_blank"
        >
          <base-lazy-image
              :alt="blog.title"
              :lazy-src="blog.image.path"
              class="w-full h-56 bg-white !object-cover rounded-lg transition group-hover:scale-95"
          />

          <router-link
              class="rounded-full bg-indigo-600 text-white py-1.5 px-3 text-xs absolute bottom-2 left-2 z-[1] shadow hover:bg-indigo-500 transition"
              to="#">
            {{ blog.category.name }}
          </router-link>
        </router-link>

        <div class="mt-3 flex flex-col justify-center gap-3">
          <h1>
            <router-link
                :title="blog.title"
                :to="{name: 'blog.detail', params: {slug: blog.slug}}"
                class="text-sm leading-loose h-[56px] ellipsis-2 hover:text-indigo-600 transition font-iranyekan-bold"
                target="_blank"
            >
              {{ blog.title }}
            </router-link>
          </h1>

          <div class="flex gap-3 items-center justify-between mt-3">
            <div class="flex gap-2 items-center">
              <UserCircleIcon class="w-8 h-8 text-slate-300 shrink-0"/>
              <span class="text-xs text-slate-400">{{
                  (blog.creator.first_name + ' ' + blog.creator.last_name).trim()
                }}</span>
            </div>

            <div class="text-xs text-slate-400 mr-auto shrink-0">
              {{ blog.created_at }}
            </div>
          </div>
        </div>
      </template>

      <template v-else>
        <div class="flex flex-col sm:flex-row gap-3 h-full">
          <router-link
              :to="{name: 'blog.detail', params: {slug: blog.slug}}"
              class="group block shrink-0"
              target="_blank"
          >
            <base-lazy-image
                :alt="blog.title"
                :lazy-src="blog.image.path"
                :size="FileSizes.LARGE"
                class="bg-white !object-cover rounded-lg transition group-hover:scale-95 w-full h-56 sm:w-40 sm:h-full"
            />
          </router-link>

          <div class="grow px-2 flex flex-col justify-center gap-3">
            <div class="flex flex-wrap gap-6 items-center">
              <div class="shrink-0">
                <router-link
                    :to="{name: 'blog.search', query: {category: blog.category.id}}"
                    class="rounded-full bg-slate-100 py-1.5 px-3 text-xs hover:bg-slate-200 transition">
                  {{ blog.category.name }}
                </router-link>
              </div>
              <div class="text-xs text-slate-400 mr-auto sm:mr-0 shrink-0">
                {{ blog.created_at }}
              </div>
            </div>

            <router-link
                :title="blog.title"
                :to="{name: 'blog.detail', params: {slug: blog.slug}}"
                class="text-sm leading-loose h-[56px] ellipsis-2 hover:text-indigo-600 transition"
                target="_blank"
            >
              <h1 class="font-iranyekan-bold">
                {{ blog.title }}
              </h1>
            </router-link>

            <div class="flex gap-2 items-center">
              <UserCircleIcon class="w-8 h-8 text-slate-300 shrink-0"/>
              <span class="text-xs text-slate-400">{{
                  (blog.creator?.first_name + ' ' + blog.creator?.last_name).trim() || 'ادمین'
                }}</span>
            </div>
          </div>
        </div>
      </template>
    </div>
    <template v-else>
      <loader-card-blog v-if="type === 'vertical'"/>
      <loader-list-single-blog v-else size="large"/>
    </template>
  </VTransitionSlideFadeUpY>
</template>

<script setup>
import {UserCircleIcon} from "@heroicons/vue/24/outline/index.js";
import VTransitionSlideFadeUpY from "@/transitions/VTransitionSlideFadeUpY.vue";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import LoaderCardBlog from "@/components/base/loader/LoaderCardBlog.vue";
import {FileSizes} from "@/composables/file-list.js";
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
  type: {
    type: String,
    default: 'horizontal',
    validator: (value) => {
      return ['horizontal', 'vertical'].indexOf(value) !== -1
    },
  },
})
</script>
