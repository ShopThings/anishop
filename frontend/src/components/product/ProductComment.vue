<template>
  <div class="comment-sticky-container flex flex-col lg:flex-row gap-6">
    <Vue3StickySidebar
      v-if="showAddComment && comments?.length"
      :bottom-spacing="20"
      :min-width="1024"
      :top-spacing="114"
      class="w-full lg:w-72 shrink-0"
      containerSelector=".comment-sticky-container"
      innerWrapperSelector='.sidebar__inner'
    >
      <div
        class="flex justify-start flex-col sm:justify-between sm:flex-row lg:justify-start lg:flex-col gap-3 border border-slate-100 shadow-md rounded-lg p-3">
        <div class="flex items-center">
          <ChatBubbleBottomCenterIcon class="size-12 text-gray-300 ml-3 shrink-0"/>
          <div class="text-sm leading-relaxed grow">
            دیدگاه شما پس از بررسی و تایید در سایت نمایش داده می‌شود.
          </div>
        </div>

        <div class="flex flex-col gap-3">
          <base-button
            :to="{name: 'user.comment.add', params: {slug: productSlug}}"
            class="!text-primary border-primary border-2"
            type="link"
          >
            ثبت دیدگاه
          </base-button>

          <div class="text-rose-500 text-xs">
            با دیدگاه خود به سایر کاربران در یک خرید بهتر کمک نمایید
          </div>
        </div>
      </div>
    </Vue3StickySidebar>

    <div class="grow">
      <div class="flex items-center my-4">
        <div class="grow h-0.5 bg-teal-500 rounded-full"></div>
        <span class="shrink-0 rounded-full bg-teal-100 px-4 py-1">دیدگاه کاربران</span>
        <div class="grow h-0.5 bg-teal-500 rounded-full"></div>
      </div>

      <base-paginator
        v-model:items="comments"
        :number-of-loaders="3"
        :path="getPath"
        :per-page="20"
        container-class="divide-y divide-orange-300"
        item-container-class="divide-y divide-gray-100 space-y-3 pt-3"
        pagination-theme="modern"
      >
        <template #loading>
          <div class="animate-pulse">
            <ul class="flex flex-wrap gap-4 items-center">
              <li class="bg-slate-300 w-20 h-4"></li>
              <li class="bg-slate-300 w-20 h-4"></li>
              <li class="bg-slate-300 w-20 h-4"></li>
              <li class="flex items-center">
                <FlagIcon class="w-5 h-5 text-rose-500 ml-2"/>
                <span class="bg-rose-300 w-8 h-1"></span>
              </li>
            </ul>

            <div class="my-7">
              <p class="rounded-full h-2.5 bg-slate-200 w-5/6 my-4"></p>
              <p class="rounded-full h-2.5 bg-slate-200 w-3/6 my-4"></p>
              <p class="rounded-full h-2.5 bg-slate-200 w-4/6 mt-4"></p>
            </div>

            <div>
              <ul class="mt-2">
                <li
                  v-for="i in 2"
                  :key="i"
                  class="flex items-center space-y-1"
                >
                  <PlusIcon class="w-5 h-5 text-emerald-500 ml-1"/>
                  <span class="h-1 w-24 bg-emerald-300"></span>
                </li>
              </ul>

              <ul class="mt-2">
                <li
                  v-for="i in 2"
                  :key="i"
                  class="flex items-center space-y-1"
                >
                  <MinusIcon class="w-5 h-5 text-rose-500 ml-1"/>
                  <span class="h-1 w-24 bg-rose-300"></span>
                </li>
              </ul>
            </div>

            <div class="flex flex-wrap justify-end items-center space-x-reverse space-x-3 py-3">
              <div class="flex text-gray-500">
                <span class="h-1.5 w-6 bg-slate-200 mt-2"></span>
                <HandThumbUpIcon
                  class="w-6 h-6 mr-2"/>
              </div>
              <div class="flex text-gray-500">
                <span class="h-1.5 w-6 bg-slate-200 mt-2"></span>
                <HandThumbDownIcon
                  class="w-6 h-6 mr-2"/>
              </div>
            </div>
          </div>
        </template>

        <template #empty>
          <div class="mb-3">
            <div class="text-lg text-gray-500 mb-3">دیدگاه برای محصول</div>
            <div
              v-if="productTitle?.length"
              class="leading-loose iranyekan-bold"
            >
              {{ productTitle }}
            </div>
          </div>

          <div
            v-if="showAddComment"
            class="flex justify-start flex-col sm:justify-between sm:flex-row gap-3 border border-slate-100 shadow-md rounded-lg p-3"
          >
            <div class="flex items-center">
              <ChatBubbleBottomCenterIcon class="size-12 text-gray-300 ml-3 shrink-0"/>
              <div class="text-sm leading-relaxed grow">
                دیدگاه شما پس از بررسی و تایید در سایت نمایش داده می‌شود.
              </div>
            </div>

            <div class="flex flex-col gap-3">
              <base-button
                :to="{name: 'user.comment.add', params: {slug: productSlug}}"
                class="!text-primary border-primary border-2"
                type="link"
              >
                ثبت دیدگاه
              </base-button>

              <div class="text-rose-500 text-xs">
                با دیدگاه خود به سایر کاربران در یک خرید بهتر کمک نمایید
              </div>
            </div>
          </div>
          <p
            v-else
            class="text-orange-300 text-base text-center mt-3"
          >
            هیچ دیدگاهی ثبت نشده است.
          </p>
        </template>

        <template #item="{item: comment}">
          <div class="py-2 text-sm flex flex-wrap items-center gap-3 pl-10 relative">
            <div
              v-if="allowCommentOperations"
              class="absolute left-0 top-0"
            >
              <base-floating-drop-down
                :items="[{
                      text: 'گزارش دیدگاه',
                      operation: 'report',
                  }]"
                :shift="false"
                placement="right-start"
              >
                <template #button>
                  <button
                    class="text-gray-500 p-1 transition hover:text-black"
                    type="button"
                  >
                    <EllipsisVerticalIcon class="h-6 w-6"/>
                  </button>
                </template>

                <template #item="{item, hide}">
                  <button
                    type="button"
                    class="flex items-center w-full p-2 text-sm transition hover:bg-gray-100 rounded-md"
                    @click="reportCommentHandler(comment, item, hide)"
                  >
                    <FlagIcon class="w-5 h-5 text-rose-500 ml-2"/>
                    <span class="text-sm">{{ item.text }}</span>
                  </button>
                </template>
              </base-floating-drop-down>
            </div>

            <div class="flex flex-wrap items-center gap-3">
              <span class="text-gray-400">ارسال شده در تاریخ</span>
              <span class="text-gray-500">{{ comment.created_at }}</span>
            </div>
            <div class="w-2 h-2 rounded-full bg-gray-200"></div>
            <div class="text-gray-700">
              {{
                (comment?.creator?.first_name ?? 'کاربر سایت').trim()
              }}
            </div>
          </div>

          <div class="pt-5 pb-2">
            <p>{{ comment.description }}</p>

            <div
              v-if="comment?.pros?.length"
              class="text-sm mt-3"
            >
              <h2 class="mb-2 text-emerald-600">
                نکات مثبت
              </h2>
              <ul>
                <li
                  v-for="advantage in comment.pros"
                  class="flex items-end space-y-1"
                >
                  <PlusIcon class="w-5 h-5 text-emerald-500 ml-1"/>
                  <span>{{ advantage }}</span>
                </li>
              </ul>
            </div>

            <div
              v-if="comment?.cons?.length"
              class="text-sm mt-3"
            >
              <h2 class="mb-2 text-rose-600">
                نکات منفی
              </h2>
              <ul>
                <li
                  v-for="disadvantage in comment.cons"
                  class="flex items-end space-y-1"
                >
                  <MinusIcon class="w-5 h-5 text-rose-500 ml-1"/>
                  <span>{{ disadvantage }}</span>
                </li>
              </ul>
            </div>
          </div>

          <div class="flex flex-wrap justify-end items-center space-x-reverse space-x-3 py-3 relative">
            <VTransitionFade>
              <loader-circle
                v-if="comment?.voting_operation_loading"
                big-circle-color="border-transparent"
                main-container-klass="absolute w-full h-full top-0 left-0"
              />
            </VTransitionFade>

            <div class="text-gray-500 text-sm">
              آیا این دیدگاه مفید بود؟
            </div>
            <div class="flex text-gray-500">
              <span>{{ comment.up_vote_count }}</span>
              <HandThumbUpIcon
                class="w-6 h-6 mr-2 cursor-pointer hover:text-black transition"
                @click="upVoteHandler(comment)"
              />
            </div>
            <div class="flex text-gray-500">
              <span>{{ comment.down_vote_count }}</span>
              <HandThumbDownIcon
                class="w-6 h-6 mr-2 cursor-pointer hover:text-black transition"
                @click="downVoteHandler(comment)"
              />
            </div>
          </div>
        </template>
      </base-paginator>
    </div>
  </div>
</template>

<script setup>
import {ref} from "vue";
import {
  ChatBubbleBottomCenterIcon,
  EllipsisVerticalIcon,
  FlagIcon,
  HandThumbDownIcon,
  HandThumbUpIcon,
  MinusIcon,
  PlusIcon,
} from "@heroicons/vue/24/outline/index.js";
import Vue3StickySidebar from "vue3-sticky-sidebar";
import BaseButton from "@/components/base/BaseButton.vue";
import BaseFloatingDropDown from "@/components/base/BaseFloatingDropDown.vue";
import {HomeCommentAPI} from "@/service/APIHomePages.js";
import {useConfirmToast} from "@/composables/toast-helper.js";
import BasePaginator from "@/components/base/BasePaginator.vue";
import {apiReplaceParams, apiRoutes} from "@/router/api-routes.js";
import {useUserAuthStore} from "@/store/StoreUserAuth.js";
import {useToast} from "vue-toastification";
import {COMMENT_VOTED_TYPES, COMMENT_VOTING_TYPES} from "@/composables/constants.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";

const props = defineProps({
  productTitle: String,
  productSlug: {
    type: String,
    required: true,
  },
  allowCommentOperations: {
    type: Boolean,
    default: true,
  },
  showAddComment: {
    type: Boolean,
    default: true,
  },
})

const toast = useToast()
const userStore = useUserAuthStore()

const comments = ref([])
const getPath = apiReplaceParams(apiRoutes.comments.index, {product: props.productSlug})

function reportCommentHandler(comment, item, hide) {
  if (!item?.operation || !comment?.id || !props.allowCommentOperations) {
    hide()
    return
  }

  if (item.operation === 'report') {
    if (comment?.reporting_operation_loading) {
      hide()
      return
    }

    useConfirmToast(() => {
      comment.reporting_operation_loading = true
      hide()

      HomeCommentAPI.report(props.productSlug, comment.id, {
        finally() {
          comment.reporting_operation_loading = false
        },
      })
    }, 'گزارش دادن نظر؟')
  }
}

function upVoteHandler(comment) {
  if (!userStore.getUser) {
    toast.warning('ابتدا به پنل کاربری خود وارد شوید و سپس دوباره تلاش نمایید.')
    return
  }

  if (!comment?.id || comment?.voting_operation_loading || !props.allowCommentOperations) return

  comment.voting_operation_loading = true

  let data = {
    vote: COMMENT_VOTING_TYPES.LIKING
  }

  // check for like type according to previous vote type
  if (comment.vote_type === COMMENT_VOTED_TYPES.VOTED) {
    data.vote = COMMENT_VOTING_TYPES.UNDO_LIKING
  } else if (comment.vote_type === COMMENT_VOTED_TYPES.NOT_VOTED) {
    data.vote = COMMENT_VOTING_TYPES.FROM_DISLIKING_TO_LIKE
  }

  HomeCommentAPI.vote(props.productSlug, comment.id, data, {
    success() {
      // if previous voting was voted it means it is undo liking,
      // and it means there is no vote
      if (comment.vote_type === COMMENT_VOTED_TYPES.VOTED) {
        comment.vote_type = COMMENT_VOTED_TYPES.NOT_SET
      } else {
        comment.vote_type = COMMENT_VOTED_TYPES.VOTED
      }
    },
    finally() {
      comment.voting_operation_loading = false
    },
  })
}

function downVoteHandler(comment) {
  if (!userStore.getUser) {
    toast.error('ابتدا به پنل کاربری خود وارد شوید و سپس دوباره تلاش نمایید.')
    return
  }

  if (!comment?.id || comment?.voting_operation_loading || !props.allowCommentOperations) return

  comment.voting_operation_loading = true

  let data = {
    vote: COMMENT_VOTING_TYPES.DISLIKING
  }

  // check for dislike type according to previous vote type
  if (comment.vote_type === COMMENT_VOTED_TYPES.NOT_VOTED) {
    data.vote = COMMENT_VOTING_TYPES.UNDO_DISLIKING
  } else if (comment.vote_type === COMMENT_VOTED_TYPES.VOTED) {
    data.vote = COMMENT_VOTING_TYPES.FROM_LIKE_TO_DISLIKING
  }

  HomeCommentAPI.vote(props.productSlug, comment.id, data, {
    success() {
      // if previous voting was voted it means it is undo disliking,
      // and it means there is no vote
      if (comment.vote_type === COMMENT_VOTED_TYPES.NOT_VOTED) {
        comment.vote_type = COMMENT_VOTED_TYPES.NOT_SET
      } else {
        comment.vote_type = COMMENT_VOTED_TYPES.NOT_VOTED
      }
    },
    finally() {
      comment.voting_operation_loading = false
    },
  })
}
</script>
