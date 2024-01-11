<template>
  <div class="mb-3">
    <h2 class="text-slate-400 mb-1">
      دیدگاه شما درباره بلاگ
    </h2>

    <base-loading-panel
      type="list-single"
      :loading="loading"
    >
      <template #content>
        <partial-card class="border-0">
          <template #body>
            <div class="py-3 px-4">
              <div class="flex flex-col sm:flex-row gap-3 items-center">
                <div class="shrink-0">
                  <base-lazy-image
                    alt="تصویر محصول"
                    lazy-src="/src/assets/blogs/b1.jpg"
                    class="!h-28 sm:!h-20 w-auto"
                  />
                </div>
                <div class="grow text-sm">
                  یه موضوع خیلی مسخره ولی جالب برای شما دوستان
                </div>
                <div class="text-sm shrink-0">
                  <router-link
                    :to="{name: 'blog.detail', params: {id: 1}}"
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
  </div>

  <div
    v-if="1"
    class="mb-3"
  >
    <partial-badge-condition-comment class="w-full py-2 !text-sm"/>
  </div>

  <div>
    <h2 class="text-slate-400 mb-1">
      دیدگاه شما
    </h2>

    <base-loading-panel
      type="list-single"
      :loading="loading"
    >
      <template #content>
        <form @submit.prevent="onSubmit">
          <partial-card class="border-0">
            <template #body>
              <div class="px-3 pt-3">
                <base-message
                  type="info"
                  :has-close="false"
                  class="rounded-md"
                >
                  <div class="leading-relaxed">
                    امکان ویرایش پس از تغییر وضعیت توسط سایت، وجود ندارد.
                  </div>
                </base-message>
              </div>

              <div class="px-3 py-2">
                <div class="mb-3">
                  <partial-input-label title="پاسخ شما به دیدگاه"/>
                  <partial-comment-blog-single
                    :comment="{}"
                    :show-answer-button="false"
                  />
                </div>

                <div class="mb-3">
                  <base-textarea
                    name="description"
                    label-title="توضیحات شما"
                    placeholder="دیدگاه خود را وارد نمایید..."
                    :has-edit-mode="true"
                    :is-editable="true"
                  />
                </div>

                <div>
                  <partial-input-label title="پاسخ به دیدگاه شما"/>
                  <base-feed-list>
                    <template #item>
                      <partial-comment-blog-single
                        :comment="{}"
                      />
                    </template>
                  </base-feed-list>
                  <base-feed-list bullet-class="border-emerald-300 !bg-emerald-200">
                    <template #item>
                      <partial-comment-blog-single
                        container-class="!bg-emerald-50"
                        :comment="{}"
                        :show-answer-button="false"
                      />
                    </template>
                  </base-feed-list>
                  <base-feed-list :is-last="true">
                    <template #item>
                      <partial-comment-blog-single
                        :comment="{}"
                      />
                    </template>
                  </base-feed-list>
                </div>
              </div>

              <div class="p-3">
                <base-animated-button
                  type="submit"
                  class="bg-emerald-500 text-white mr-auto px-6 w-full sm:w-auto"
                  :disabled="isSubmitting"
                >
                  <VTransitionFade>
                    <loader-circle
                      v-if="isSubmitting"
                      main-container-klass="absolute w-full h-full top-0 left-0"
                      big-circle-color="border-transparent"
                    />
                  </VTransitionFade>

                  <template #icon="{klass}">
                    <CheckIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
                  </template>

                  <span class="ml-auto">ثبت دیدگاه</span>
                </base-animated-button>
              </div>
            </template>
          </partial-card>
        </form>
      </template>
    </base-loading-panel>
  </div>
</template>

<script setup>
import {ref} from "vue";
import {ArrowLongLeftIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import BaseLoadingPanel from "../../components/base/BaseLoadingPanel.vue";
import PartialCard from "../../components/partials/PartialCard.vue";
import BaseLazyImage from "../../components/base/BaseLazyImage.vue";
import BaseMessage from "../../components/base/BaseMessage.vue";
import {useForm} from "vee-validate";
import yup from "../../validation/index.js";
import PartialBadgeConditionComment from "../../components/partials/PartialBadgeConditionComment.vue";
import BaseTextarea from "../../components/base/BaseTextarea.vue";
import VTransitionFade from "../../transitions/VTransitionFade.vue";
import BaseAnimatedButton from "../../components/base/BaseAnimatedButton.vue";
import LoaderCircle from "../../components/base/loader/LoaderCircle.vue";
import PartialCommentBlogSingle from "../../components/partials/PartialCommentBlogSingle.vue";
import PartialInputLabel from "../../components/partials/PartialInputLabel.vue";
import BaseFeedList from "../../components/base/BaseFeedList.vue";

const loading = ref(false)

const canSubmit = ref(true)
const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return
})
</script>
