<template>
  <VTransitionSlideFadeUpY mode="out-in">
    <div v-if="blog && blog?.id">
      <div class="relative w-full pt-36 lg:pt-60 overflow-hidden group rounded-3xl">
        <div class="absolute -z-[2] w-full h-full top-0 lef-0 group-hover:scale-125 transition duration-1000">
          <img
            :src="blog.image.path"
            :alt="blog.title"
            class="w-full h-full object-cover rounded-3xl"
          >
        </div>

        <div
          class="absolute top-0 left-0 bg-gradient-to-t from-slate-950/90 w-full h-full rounded-3xl -z-[1]"></div>

        <div class="pt-6 px-6 pb-9 flex flex-col gap-4 z-[1]">
          <div class="flex flex-wrap gap-6 items-center">
            <div class="shrink-0">
              <router-link
                to="#"
                class="rounded-full bg-indigo-500 text-white py-1.5 px-3 text-xs hover:text-black hover:bg-slate-200 transition">
                {{ blog.category.name }}
              </router-link>
            </div>
            <div class="text-sm text-slate-200 text-shadow shrink-0">
              {{ blog.created_at }}
            </div>
          </div>

          <h1>
            <router-link
              :to="{name: 'blog.detail', params: {id: blog.id}}"
              target="_blank"
              class="font-iranyekan-bold text-shadow leading-relaxed h-[52px] ellipsis-2 hover:text-indigo-400 transition text-white"
              :title="blog.title"
            >
              {{ blog.title }}
            </router-link>
          </h1>

          <div class="flex gap-2 items-center">
            <UserCircleIcon class="w-8 h-8 text-slate-300 shrink-0"/>
            <span class="text-xs text-slate-200 text-shadow">{{
                (blog.creator.first_name + ' ' + blog.creator.last_name).trim()
              }}</span>
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
import LoaderCardBlog from "../base/loader/LoaderCardBlog.vue";
import {UserCircleIcon} from "@heroicons/vue/24/outline/index.js";

defineProps({
  blog: {
    type: Object,
    required: true,
  },
})
</script>
