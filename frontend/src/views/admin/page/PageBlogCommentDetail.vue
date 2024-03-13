<template>
  <base-loading-panel
      :loading="loading"
      type="list-single"
  >
    <template #content>
      <partial-card class="border-0 mb-3">
        <template #header>
          دیدگاه کاربر درباره بلاگ
          <span
              v-if="blog?.slug"
              class="text-slate-400 text-base"
          >{{ blog?.title }}</span>
        </template>
        <template #body>
          <div class="py-3 px-4">
            <div class="flex flex-col sm:flex-row gap-3 items-center">
              <div class="shrink-0">
                <base-lazy-image
                    :alt="blog?.title"
                    :lazy-src="blog?.image.path"
                    :size="FileSizes.SMALL"
                    class="!h-28 sm:!h-20 w-auto rounded"
                />
              </div>
              <div class="grow text-sm">
                {{ blog?.title }}
              </div>
              <div class="text-sm shrink-0">
                <router-link
                    :to="{name: 'blog.detail', params: {slug: blog?.slug}}"
                    class="flex items-center gap-2 text-blue-600 hover:text-opacity-90 group"
                >
                  <span class="mx-auto">مشاهده بلاگ</span>
                  <ArrowLongLeftIcon class="w-6 h-6 group-hover:-translate-x-1.5 transition"/>
                </router-link>
              </div>
            </div>
          </div>
        </template>
      </partial-card>
    </template>
  </base-loading-panel>

  <partial-card>
    <template #header>
      جزئیات دیدگاه
    </template>
    <template #body>
      <div class="p-3">
        <base-loading-panel
            :loading="loading"
            type="content"
        >
          <template #content>
            <div class="flex flex-wrap">
              <div class="p-2 md:w-1/2 relative">
                <VTransitionFade>
                  <loader-circle
                      v-if="badgeUpdateLoading"
                      big-circle-color="border-transparent"
                      main-container-klass="absolute w-full h-full top-0 left-0"
                  />
                </VTransitionFade>

                <partial-input-label title="تغییر برچسب دیدگاه کاربر"/>
                <base-select
                    :before-change-fn="changeUserCommentBadge"
                    :is-loading="badgeLoading"
                    :options="badges"
                    :selected="selectedUserCommentBadge"
                    options-key="id"
                    options-text="title"
                    @change="(selected) => {selectedUserCommentBadge = selected}"
                >
                  <template #item="{item}">
                    <partial-badge-color :hex="item.color_hex" :title="item.title"/>
                  </template>
                </base-select>
              </div>

              <div class="p-2 md:w-1/2 relative">
                <VTransitionFade>
                  <loader-circle
                      v-if="conditionUpdateLoading"
                      big-circle-color="border-transparent"
                      main-container-klass="absolute w-full h-full top-0 left-0"
                  />
                </VTransitionFade>

                <partial-input-label title="تغییر وضعیت دیدگاه کاربر"/>
                <base-select
                    :before-change-fn="changeUserCommentCondition"
                    :options="conditions"
                    :selected="selectedUserCommentCondition"
                    options-key="value"
                    options-text="text"
                    @change="(selected) => {selectedUserCommentCondition = selected}"
                >
                  <template #item="{item}">
                    <partial-badge-color :hex="item.color_hex" :title="item.text"/>
                  </template>
                </base-select>
              </div>
            </div>

            <div
                v-if="comment?.parent || 1"
                class="mt-3"
            >
              <partial-input-label title="پاسخ کاربر به دیدگاه"/>
              <partial-comment-blog-single
                  :comment="comment?.parent || {}"
                  :show-answer-button="false"
                  container-class="!bg-violet-50"
              />
            </div>

            <div class="mt-3">
              <partial-input-label title="دیدگاه کاربر"/>
              <partial-comment-blog-single
                  :comment="comment || {}"
                  :show-answer-button="false"
              />
            </div>

            <div class="mt-3 p-2 border-[3px] border-emerald-400 rounded-lg bg-slate-50">
              <h2 class="text-lg p-3 font-iranyekan-bold">
                پاسخ به دیدگاه
              </h2>

              <form @submit.prevent="onSubmit">
                <div class="p-2 sm:w-1/2">
                  <partial-input-label title="برچسب پاسخ دیدگاه"/>
                  <base-select
                      :is-loading="badgeLoading"
                      :options="badges"
                      options-key="id"
                      options-text="title"
                      @change="(selected) => {selectedAnswerBadge=selected}"
                  >
                    <template #item="{item}">
                      <partial-badge-color :hex="item.color_hex" :title="item.title"/>
                    </template>
                  </base-select>
                  <partial-input-error-message :error-message="errors.answer_badge"/>
                </div>

                <div class="p-2">
                  <base-textarea
                      label-title="پاسخ خود را وارد نمایید"
                      name="answer"
                  >
                    <template #icon>
                      <InformationCircleIcon class="h-6 w-6 mt-3 text-gray-400"/>
                    </template>
                  </base-textarea>
                </div>

                <div class="px-2 py-3">
                  <base-animated-button
                      :disabled="!canSubmit"
                      class="bg-emerald-500 text-white mr-auto px-6 w-full sm:w-auto"
                      type="submit"
                  >
                    <VTransitionFade>
                      <loader-circle
                          v-if="!canSubmit"
                          big-circle-color="border-transparent"
                          main-container-klass="absolute w-full h-full top-0 left-0"
                      />
                    </VTransitionFade>

                    <template #icon="{klass}">
                      <ArrowUturnRightIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
                    </template>

                    <span class="ml-auto">ثبت پاسخ</span>
                  </base-animated-button>
                </div>
              </form>
            </div>
          </template>
        </base-loading-panel>
      </div>
    </template>
  </partial-card>
</template>

<script setup>
import {onMounted, ref} from "vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseTextarea from "@/components/base/BaseTextarea.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {ArrowLongLeftIcon, ArrowUturnRightIcon, InformationCircleIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import yup from "@/validation/index.js";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import {getRouteParamByKey} from "@/composables/helper.js";
import {useFormSubmit} from "@/composables/form-submit.js";
import {BlogBadgeAPI, BlogCommentAPI} from "@/service/APIBlog.js";
import PartialCommentBlogSingle from "@/components/partials/PartialCommentBlogSingle.vue";
import {useToast} from "vue-toastification";
import {COMMENT_SEEN_STATUSES, COMMENT_STATUSES} from "@/composables/constants.js";
import BaseSelect from "@/components/base/BaseSelect.vue";
import PartialBadgeColor from "@/components/partials/PartialBadgeColor.vue";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import {useConfirmToast} from "@/composables/toast-helper.js";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import {useRouter} from "vue-router";
import {FileSizes} from "@/composables/file-list.js";

const router = useRouter()
const toast = useToast()
const slugParam = getRouteParamByKey('slug', null, false)
const commentId = getRouteParamByKey('detail', null, false)

const loading = ref(true)

const comment = ref(null)
const blog = ref(null)

//--------------------------------------
// answer badge operation
//--------------------------------------
const badges = ref([])
const selectedAnswerBadge = ref(null)
const badgeLoading = ref(true)

onMounted(() => {
  BlogBadgeAPI.fetchAll({}, {
    success(response) {
      badges.value = response.data
      badgeLoading.value = false
    },
  })
})

//--------------------------------------
const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    answer: yup.string().required('پاسخ خود را وارد نمایید.'),
  }),
}, (values, actions) => {
  if (!selectedAnswerBadge.value) {
    actions.setFieldError('answer_badge', 'نوع برچسب پاسخ را وارد نمایید.')
    return
  }

  canSubmit.value = false

  BlogCommentAPI.create(slugParam.value, {
    badge: selectedAnswerBadge.value.id,
    comment: commentId.value,
    description: values.answer,
  }, {
    success() {
      router.push({name: 'admin.blog.comments', params: {slug: slugParam.value}})
    },
    error(error) {
      if (error.errors && Object.keys(error.errors).length >= 1)
        actions.setErrors(error.errors)
    },
    finally() {
      canSubmit.value = true
    },
  })
})

//--------------------------------------
// user's comment badge operations
//--------------------------------------
const badgeUpdateLoading = ref(false)
const selectedUserCommentBadge = ref(null)

function changeUserCommentBadge() {
  return new Promise((resolve, reject) => {
    if (badgeUpdateLoading.value) {
      reject(false)
    }

    useConfirmToast({
      accept() {
        badgeUpdateLoading.value = true

        BlogCommentAPI.updateById(slugParam.value, commentId.value, {
          badge: selectedUserCommentBadge.value.id,
        }, {
          success(response) {
            comment.badge = response.data.badge
            return false
          },
          finally() {
            badgeUpdateLoading.value = false
          },
        })
      },
      decline() {
        reject(false)
      },
    }, 'تغییر برچسب دیدگاه کاربر')
  })
}

//--------------------------------------
// user's comment condition operations
//--------------------------------------
const conditions = ref([])
const conditionUpdateLoading = ref(false)
const selectedUserCommentCondition = ref(null)

for (let s in COMMENT_STATUSES) {
  if (COMMENT_STATUSES.hasOwnProperty(s)) {
    conditions.value.push({
      text: COMMENT_STATUSES[s].text,
      value: COMMENT_STATUSES[s].value,
      color_hex: COMMENT_STATUSES[s].color_hex,
    })
  }
}

function changeUserCommentCondition() {
  return new Promise((resolve, reject) => {
    if (conditionUpdateLoading.value) {
      reject(false)
    }

    useConfirmToast({
      accept() {
        conditionUpdateLoading.value = true

        BlogCommentAPI.updateById(slugParam.value, commentId.value, {
          condition: selectedUserCommentCondition.value.value,
        }, {
          success(response) {
            comment.condition = response.data.condition
            return false
          },
          finally() {
            conditionUpdateLoading.value = false
          },
        })
      },
      decline() {
        reject(false)
      },
    }, 'تغییر وضعیت دیدگاه کاربر')
  })
}

//--------------------------------------
onMounted(() => {
  BlogCommentAPI.fetchById(slugParam.value, commentId.value, {
    success: (response) => {
      comment.value = response.data
      blog.value = response.data.blog

      // set current badge
      selectedUserCommentBadge.value = comment.badge

      // set current condition
      selectedUserCommentCondition.value = conditions.value.filter((item) => {
        return item.value === comment.value.condition.value
      }).shift()

      if (response.data.status.value === COMMENT_SEEN_STATUSES.UNREAD.value) {
        BlogCommentAPI.updateById(slugParam, commentId.value, {
          status: COMMENT_SEEN_STATUSES.READ.value,
        }, {
          success(response2) {
            comment.status = response2.data.status
            return false
          },
          error() {
            return false
          }
        })
      }

      loading.value = false
    }
  })
})
</script>
