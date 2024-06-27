<template>
  <div class="mb-6 p-3">
    <div class="flex flex-col lg:flex-row gap-6 sticky-container">
      <div class="grow">
        <base-loading-panel :loading="loading">
          <template #loader>
            <loader-content-blog/>
          </template>

          <template #content>
            <div class="flex flex-col gap-6">
              <partial-card class="border-0 p-3">
                <template #body>
                  <div class="flex flex-wrap flex-col sm:flex-row gap-3">
                    <div class="flex gap-5 items-center">
                      <div
                        v-if="blog.creator"
                        class="flex gap-2 items-center"
                      >
                        <UserCircleIcon class="w-8 h-8 text-slate-300 shrink-0"/>
                        <span class="text-xs text-slate-500">{{
                            (blog.creator.first_name + ' ' + blog.creator.last_name).trim() || 'ادمین'
                          }}</span>
                      </div>

                      <div class="flex gap-2 items-center">
                        <CalendarIcon class="w-5 h-5 text-slate-300 shrink-0"/>
                        <span class="text-xs text-slate-500">{{ blog.created_at }}</span>
                      </div>
                    </div>

                    <div class="flex gap-2 items-center sm:mr-auto">
                      <ClockIcon class="w-5 h-5 text-slate-300"/>
                      <div class="flex gap-2 items-center">
                        <span class="text-xs text-slate-400">زمان مطالعه:</span>
                        <span class="text-xs text-slate-500">
                          {{ estimateReadTime(blog.description) }}
                          دقیقه
                        </span>
                      </div>
                    </div>
                  </div>
                </template>
              </partial-card>

              <partial-card class="border-0 p-6">
                <template #body>
                  <h1 class="text-2xl font-iranyekan-bold mb-7 leading-relaxed">
                    {{ blog.title }}
                  </h1>

                  <div class="mb-4 flex flex-wrap items-center justify-end sm:justify-between gap-4">
                    <div class="flex flex-wrap items-center justify-end gap-4 relative">
                      <VTransitionFade>
                        <loader-circle
                          v-if="isVoting"
                          big-circle-color="border-transparent"
                          main-container-klass="absolute w-full h-full top-0 left-0"
                        />
                      </VTransitionFade>

                      <div>
                        <button
                          v-tooltip.bottom="'نپسندیدم'"
                          :class="[
                                blog.vote_type === BLOG_VOTING_TYPES.NOT_VOTED ? 'text-black' : 'text-slate-400',
                            ]"
                          class="hover:text-black transition flex items-center gap-2"
                          type="button"
                          @click="unlikeHandler"
                        >
                          <HandThumbDownIcon class="w-6 h-6"/>
                          <span class="flex items-center gap-0.5">
                              <span class="text-xs">(</span>
                              <span>{{ blogDownVotingCount }}</span>
                              <span class="text-xs">)</span>
                          </span>
                        </button>
                      </div>
                      <div>
                        <button
                          v-tooltip.bottom="'خوشم اومده'"
                          :class="[
                              blog.vote_type === BLOG_VOTING_TYPES.VOTED ? 'text-black' : 'text-slate-400',
                            ]"
                          class="hover:text-black transition flex items-center gap-2"
                          type="button"
                          @click="likeHandler"
                        >
                          <HandThumbUpIcon class="w-6 h-6"/>
                          <span class="flex items-center gap-0.5">
                              <span class="text-xs">(</span>
                              <span>{{ blogUpVotingCount }}</span>
                              <span class="text-xs">)</span>
                          </span>
                        </button>
                      </div>
                    </div>

                    <ul class="flex flex-wrap items-center justify-end gap-4 mr-auto">
                      <li class="pl-4 border-l-2 border-slate-200">
                        <button
                          v-tooltip.bottom="'دیدگاه‌ها'"
                          type="button"
                          class="text-slate-400 hover:text-black transition flex items-center gap-2"
                          @click="scrollToCommentSection"
                        >
                          <span class="flex items-center gap-0.5">
                            <span class="text-xs">(</span>
                            <span>{{ numberFormat(blog.comment_counts) }}</span>
                            <span class="text-xs">)</span>
                          </span>
                          <ChatBubbleLeftIcon class="w-6 h-6"/>
                        </button>
                      </li>

                      <li>
                        <a
                          v-tooltip.bottom="'اشتراک گذاری در ' + SOCIAL_NETWORKS.EMAIL.text"
                          :href="emailSharingLink"
                          target="_blank"
                          class="text-slate-400 hover:text-black transition"
                          v-html="SOCIAL_NETWORKS.EMAIL.icon"
                        ></a>
                      </li>
                      <li>
                        <a
                          v-tooltip.bottom="'اشتراک گذاری در ' + SOCIAL_NETWORKS.TELEGRAM.text"
                          :href="telegramSharingLink"
                          target="_blank"
                          class="text-slate-400 hover:text-black transition"
                          v-html="SOCIAL_NETWORKS.TELEGRAM.icon"
                        ></a>
                      </li>
                      <li>
                        <a
                          v-tooltip.bottom="'اشتراک گذاری در ' + SOCIAL_NETWORKS.X.text"
                          :href="twitterSharingLink"
                          target="_blank"
                          class="text-slate-400 hover:text-black transition"
                          v-html="SOCIAL_NETWORKS.X.icon"
                        ></a>
                      </li>
                      <li>
                        <a
                          v-tooltip.bottom="'اشتراک گذاری در ' + SOCIAL_NETWORKS.WHATSAPP.text"
                          :href="whatsappSharingLink"
                          target="_blank"
                          class="text-slate-400 hover:text-black transition"
                          v-html="SOCIAL_NETWORKS.WHATSAPP.icon"
                        ></a>
                      </li>
                    </ul>
                  </div>

                  <base-lazy-image
                    :alt="blog.title"
                    :lazy-src="blog.image.path"
                    :is-local="false"
                    class="!w-full !h-auto rounded-lg"
                  />

                  <div class="mt-6">
                    <div
                      class="styled-description"
                      v-html="blog.description"
                    ></div>
                  </div>

                  <div
                    v-if="blog.keywords?.length"
                    class="mt-8 pt-3 border-t"
                  >
                    <partial-input-label title="برچسب‌ها"/>

                    <ul class="flex flex-wrap items-center gap-3 mt-3">
                      <li
                        v-for="(keyword, idx) in blog.keywords"
                        :key="idx"
                      >
                        <router-link
                          :to="{name: 'blog.search', query: {tag: keyword}}"
                          class="py-1 text-xs px-3 rounded-md bg-gray-200 text-gray-600 hover:bg-gray-300 hover:text-black transition"
                        >
                          {{ keyword }}
                        </router-link>
                      </li>
                    </ul>
                  </div>
                </template>
              </partial-card>

              <partial-card
                ref="commentSectionRef"
                class="border-0 p-6"
              >
                <template #body>
                  <partial-general-title
                    container-class="mb-5"
                    title="دیدگاه‌ها"
                  />

                  <div class="mb-12 relative">
                    <blog-comments :blog-slug="blog.slug"/>
                  </div>

                  <form-blog-comment v-if="blog.is_commenting_allowed"/>
                </template>
              </partial-card>
            </div>
          </template>
        </base-loading-panel>
      </div>

      <Vue3StickySidebar
        v-if="hasPopularCategories || hasBlogArchives || hasMostViewsBlogs"
        :bottom-spacing="20"
        :min-width="1024"
        :top-spacing="79"
        class="shrink-0 lg:w-80"
        containerSelector=".sticky-container"
        innerWrapperSelector='.sidebar__inner'
      >
        <div class="flex flex-col gap-6">
          <partial-card
            v-if="hasPopularCategories"
            class="border-0 flex flex-col supports-[backdrop-filter]:backdrop-blur-sm supports-[backdrop-filter]:bg-opacity-80"
          >
            <template #body>
              <partial-general-title
                title="دسته‌بندی‌های پرطرفدار"
                type="side"
              />

              <app-side-categories-blog @loaded="(hasData) => {hasPopularCategories = hasData}"/>
            </template>
          </partial-card>

          <partial-card
            v-if="hasBlogArchives"
            class="border-0 flex flex-col supports-[backdrop-filter]:backdrop-blur-sm supports-[backdrop-filter]:bg-opacity-80"
          >
            <template #body>
              <partial-general-title
                title="آرشیو نوشته‌ها"
                type="side"
              />

              <blog-side-archives @loaded="(hasData) => {hasBlogArchives = hasData}"/>
            </template>
          </partial-card>

          <partial-card
            v-if="hasMostViewsBlogs"
            class="border-0 flex flex-col supports-[backdrop-filter]:backdrop-blur-sm supports-[backdrop-filter]:bg-opacity-80"
          >
            <template #body>
              <partial-general-title
                title="پربازدیدترین نوشته‌ها"
                type="side"
              />

              <blog-side-most-viewed @loaded="(hasData) => {hasMostViewsBlogs = hasData}"/>
            </template>
          </partial-card>
        </div>
      </Vue3StickySidebar>
    </div>
  </div>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import LoaderContentBlog from "@/components/base/loader/LoaderContentBlog.vue";
import PartialGeneralTitle from "@/components/partials/PartialGeneralTitle.vue";
import AppSideCategoriesBlog from "@/components/AppSideCategoriesBlog.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import {
  CalendarIcon,
  ChatBubbleLeftIcon,
  ClockIcon,
  HandThumbDownIcon,
  HandThumbUpIcon,
  UserCircleIcon,
} from "@heroicons/vue/24/outline/index.js";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import Vue3StickySidebar from "vue3-sticky-sidebar";
import FormBlogComment from "./forms/FormBlogComment.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import BlogComments from "@/components/blog/BlogComments.vue";
import BlogSideArchives from "@/components/blog/BlogSideArchives.vue";
import BlogSideMostViewed from "@/components/blog/BlogSideMostViewed.vue";
import {BLOG_VOTING_TYPES, SOCIAL_NETWORKS} from "@/composables/constants.js";
import {estimateReadTime, getRouteParamByKey, numberFormat, titleOperations} from "@/composables/helper.js";
import {HomeBlogAPI} from "@/service/APIHomePages.js";
import {useUserAuthStore} from "@/store/StoreUserAuth.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {useToast} from "vue-toastification";
import {useSeoMeta} from "@unhead/vue";
import {useHomeSettingNoTimerStore} from "@/store/StoreSettings.js";

const toast = useToast()
const userStore = useUserAuthStore()
const slugParam = getRouteParamByKey('slug', null, false)

const blog = ref(null)
const loading = ref(true)

const hasPopularCategories = ref(true)
const hasBlogArchives = ref(true)
const hasMostViewsBlogs = ref(true)

const commentSectionRef = ref(null)

function scrollToCommentSection() {
  if (commentSectionRef.value.card) {
    const scrollToY = commentSectionRef.value.card.getBoundingClientRect().top + window.scrollY - 60;

    window.scrollTo({
      top: scrollToY,
      behavior: 'smooth'
    })
  }
}

//-----------------------------------------
// Voting operation
//-----------------------------------------
const isVoting = ref(false)

const blogDownVotingCount = computed(() => {
  return numberFormat(+blog.value?.down_vote_counts ?? 0)
})
const blogUpVotingCount = computed(() => {
  return numberFormat(+blog.value?.up_vote_counts ?? 0)
})

function setVoteCounting(data) {
  blog.value.down_vote_counts = data?.down_vote_counts || 0
  blog.value.up_vote_counts = data?.up_vote_counts || 0
  blog.value.vote_type = data?.vote_type || BLOG_VOTING_TYPES.NOT_SET
}

function unlikeHandler() {
  if (!userStore.getUser) {
    toast.error('ابتدا به پنل کاربری خود وارد شوید و سپس دوباره تلاش نمایید.')
    return
  }

  if (!blog.value?.id || isVoting.value) return

  isVoting.value = true

  let type = BLOG_VOTING_TYPES.NOT_VOTED
  if (blog.value.vote_type === BLOG_VOTING_TYPES.NOT_VOTED) {
    type = BLOG_VOTING_TYPES.NOT_SET
  }

  HomeBlogAPI.vote(blog.value.slug, {
    vote: type,
  }, {
    success(response) {
      setVoteCounting(response.data)
    },
    finally() {
      isVoting.value = false
    },
  })
}

function likeHandler() {
  if (!userStore.getUser) {
    toast.error('ابتدا به پنل کاربری خود وارد شوید و سپس دوباره تلاش نمایید.')
    return
  }

  if (!blog.value?.id || isVoting.value) return

  isVoting.value = true

  let type = BLOG_VOTING_TYPES.VOTED
  if (blog.value.vote_type === BLOG_VOTING_TYPES.VOTED) {
    type = BLOG_VOTING_TYPES.NOT_SET
  }

  HomeBlogAPI.vote(blog.value.slug, {
    vote: type,
  }, {
    success(response) {
      setVoteCounting(response.data)
    },
    finally() {
      isVoting.value = false
    },
  })
}

//-----------------------------------------
// Sharing operation
//-----------------------------------------
const host = window.location.host + '/blog'
const blogShortUrl = computed(() => {
  if (!blog.value?.id) return ''
  return host + '?id=' + blog.value.id
})
const emailSharingLink = computed(() => {
  const emailSubject = 'این محصول رو مشاهده کن'
  const emailBody = 'به نظرم اومد این محصول برات جالب باشه' + ' ' + blogShortUrl.value

  return `mailto:?subject=${encodeURIComponent(emailSubject)}&body=${encodeURIComponent(emailBody)}`
})
const twitterSharingLink = computed(() => {
  return `https://twitter.com/intent/tweet?url=${encodeURIComponent(blogShortUrl.value)}&text=${encodeURIComponent(blog.value?.title || '')}`
})
const telegramSharingLink = computed(() => {
  return `https://telegram.me/share/url?url=${encodeURIComponent(blogShortUrl.value)}&text=${encodeURIComponent(blog.value?.title || '')}`
})
const whatsappSharingLink = computed(() => {
  return `https://wa.me/?text=${encodeURIComponent(blog.value?.title || '')}%3A%20${encodeURIComponent(blogShortUrl.value)}`
})

//-----------------------------------------
const settingStore = useHomeSettingNoTimerStore()

const localDescription = ref(settingStore.getDescription)
const localKeywords = ref(settingStore.getKeywords)
const title = ref('جزئیات بلاگ')
const briefDescription = ref('')
const keywords = ref([])

useSeoMeta({
  title: title,
  description: briefDescription.value.trim() !== '' ? [localDescription, briefDescription] : localDescription,
  keywords: keywords.value?.length
    ? [
      Array.isArray(localKeywords) ? localKeywords.join(', ') : localKeywords,
      keywords.value.join(', ')
    ]
    : Array.isArray(localKeywords) ? localKeywords.join(', ') : localKeywords,
})

onMounted(() => {
  HomeBlogAPI.fetchById(slugParam.value, {
    success(response) {
      blog.value = response.data
      loading.value = false

      localDescription.value = settingStore.getDescription
      localKeywords.value = settingStore.getKeywords

      title.value = titleOperations.join(['بلاگ', blog.value.title])
      briefDescription.value = blog.value.brief_description
      keywords.value = blog.value.keywords
    },
  })
})
</script>

<style scoped>
@import "../assets/css/skeleton/normalize.css";
@import "../assets/css/skeleton/skeleton.css";
</style>
