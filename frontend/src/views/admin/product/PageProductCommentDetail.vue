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
            v-if="product?.slug"
            class="text-slate-400 text-base"
          >{{ product?.title }}</span>
        </template>
        <template #body>
          <div class="py-3 px-4">
            <div class="flex flex-col sm:flex-row gap-3 items-center">
              <div class="shrink-0">
                <base-lazy-image
                  :alt="product?.title"
                  :lazy-src="product?.image.path"
                  :size="FileSizes.SMALL"
                  class="!h-28 sm:!h-20 w-auto rounded"
                />
              </div>
              <div class="grow text-sm">
                {{ product?.title }}
              </div>
              <div class="text-sm shrink-0">
                <router-link
                  :to="{name: 'admin.product.detail', params: {slug: product?.slug}}"
                  class="flex items-center gap-2 text-blue-600 hover:text-opacity-90 group"
                >
                  <span class="mx-auto">مشاهده محصول</span>
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
      جزئیات دیدگاه برای محصول -
      <span
        v-if="product?.id"
        class="text-slate-400 text-base"
      >{{ product?.title }}</span>
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

            <div class="mt-3">
              <partial-input-label title="دیدگاه کاربر"/>
              <partial-comment-product-single :comment="comment || {}"/>
            </div>

            <div class="mt-3 p-2 border-[3px] border-emerald-400 rounded-lg bg-slate-50">
              <h2 class="text-lg p-3 font-iranyekan-bold">
                پاسخ به دیدگاه
              </h2>
              <form @submit.prevent="onSubmit">
                <div class="p-2">
                  <partial-input-label title="پاسخ خود را وارد نمایید"/>
                  <base-editor
                    :value="comment?.answer"
                    name="answer"
                  />
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
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {ArrowLongLeftIcon, ArrowUturnRightIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import yup from "@/validation/index.js";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import {FileSizes} from "@/composables/file-list.js";
import BaseEditor from "@/components/base/BaseEditor.vue";
import {getRouteParamByKey} from "@/composables/helper.js";
import {useFormSubmit} from "@/composables/form-submit.js";
import {CommentAPI} from "@/service/APIProduct.js";
import {COMMENT_SEEN_STATUSES, COMMENT_STATUSES} from "@/composables/constants.js";
import {BlogCommentAPI} from "@/service/APIBlog.js";
import {useConfirmToast} from "@/composables/toast-helper.js";
import PartialBadgeColor from "@/components/partials/PartialBadgeColor.vue";
import BaseSelect from "@/components/base/BaseSelect.vue";
import PartialCommentProductSingle from "@/components/partials/PartialCommentProductSingle.vue";

const slugParam = getRouteParamByKey('slug', null, false)
const commentId = getRouteParamByKey('detail')

const loading = ref(true)

const comment = ref(null)
const product = ref(null)

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    answer: yup.string().when([], (inputValue, schema) => {
      return !!(comment.value?.created_by)
        ? schema.required('پاسخ خود را وارد نمایید.')
        : schema.optional()
    }),
  }),
}, (values, actions) => {
  canSubmit.value = false

  CommentAPI.updateById(slugParam.value, commentId.value, {
    answer: values.answer,
  }, {
    success(response) {
      toast.success('پاسخ شما ثبت شد.')
      setFormFields(response.data)
    },
    error(error) {
      if (error?.errors && Object.keys(error.errors).length >= 1) {
        actions.setErrors(error.errors)
      }
    },
    finally() {
      canSubmit.value = true
    },
  })
})

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

        CommentAPI.updateById(slugParam.value, commentId.value, {
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
  CommentAPI.fetchById(slugParam.value, commentId.value, {
    success: (response) => {
      comment.value = item
      product.value = item.product

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
