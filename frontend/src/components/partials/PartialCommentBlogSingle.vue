<template>
  <div
      :class="containerClass"
      class="flex flex-col gap-3 shadow-md bg-white p-6 grow border border-slate-50"
  >
    <div
        v-if="!hasCommentInfo"
        class="animate-pulse"
    >
      <ul class="flex flex-wrap gap-4 items-center">
        <li class="bg-slate-300 w-20 h-4"></li>
        <li class="bg-slate-300 w-20 h-4"></li>
        <li class="bg-slate-300 w-20 h-4"></li>
        <li class="rounded bg-cyan-300 w-24 h-6 inline-block"></li>
      </ul>
      <p class="rounded-full h-2.5 bg-slate-200 w-5/6 my-4"></p>
      <p class="rounded-full h-2.5 bg-slate-200 w-3/6 my-4"></p>
      <p class="rounded-full h-2.5 bg-slate-200 w-4/6 mt-4"></p>
    </div>
    <template v-else>
      <ul class="flex flex-wrap gap-4 items-center">
        <li class="text-sm text-slate-500">
          {{ comment?.createt_by?.first_name || 'کاربر سایت' }}
        </li>
        <li class="flex justify-center items-center">
          <span class="w-1.5 h-1.5 rounded-full bg-slate-200 inline-block"></span>
        </li>
        <li class="text-xs text-slate-400">
          {{ comment?.created_at }}
        </li>
        <li>
          <partial-badge-status-blog-comment
              :color-hex="comment?.color_hex"
              :text="comment?.title"
          />
        </li>
      </ul>
      <p class="text-sm">
        {{ comment?.description || '-' }}
      </p>
    </template>

    <div v-if="showAnswerButton" class="flex flex-col items-end">
      <div
          v-if="!hasCommentInfo"
          class="animate-pulse"
      >
        <div class="rounded bg-emerald-300 w-24 h-7 inline-block"></div>
      </div>
      <template v-else>
        <button
            class="text-slate-500 hover:text-slate-600 transition text-sm flex items-center gap-2"
            type="button"
            @click="handleAnswerClick"
        >
          <span>پاسخ</span>
          <ArrowUturnLeftIcon class="w-5 h-5"/>
        </button>

        <VTransitionSlideFadeDownY>
          <div
              v-if="showAnswerForm"
              class="w-full mt-3 p-2 border-[3px] border-emerald-400 rounded-lg bg-slate-50 relative"
          >
            <base-button-close
                v-tooltip.right="'بستن فرم پاسخ'"
                class="absolute top-4 left-4"
                @click="() => {showAnswerForm = false}"
            />

            <h2 class="text-lg p-3 font-iranyekan-bold">
              پاسخ به دیدگاه
            </h2>

            <form @submit.prevent="onSubmit">
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
        </VTransitionSlideFadeDownY>
      </template>
    </div>
  </div>
</template>

<script setup>
import {ArrowUturnLeftIcon} from "@heroicons/vue/24/solid/index.js";
import PartialBadgeStatusBlogComment from "./PartialBadgeStatusBlogComment.vue";
import {ArrowUturnRightIcon, InformationCircleIcon} from "@heroicons/vue/24/outline/index.js";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import BaseTextarea from "@/components/base/BaseTextarea.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import {computed, ref} from "vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import yup from "@/validation/index.js";
import isObject from "lodash.isobject";
import BaseButtonClose from "@/components/base/BaseButtonClose.vue";
import VTransitionSlideFadeDownY from "@/transitions/VTransitionSlideFadeDownY.vue";
import {useToast} from "vue-toastification";

const props = defineProps({
  containerClass: String,
  comment: {
    type: Object,
    required: true,
  },
  api: Object,
  answerForId: [String, Number],
  showAnswerButton: {
    type: Boolean,
    default: true,
  },
})

const toast = useToast()

const hasCommentInfo = computed(() => {
  return props.comment &&
      isObject(props.comment) &&
      Object.keys(props.comment).length
})
const showAnswerForm = ref(false)

function handleAnswerClick() {
  showAnswerForm.value = true
}

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    answer: yup.string().required('پاسخ خود به دیدگاه را وارد نمایید.'),
  }),
}, (values, actions) => {
  if (!props.api || !props.answerForId) return

  canSubmit.value = false

  props.api.create(props.answerForId, {
    comment: props.comment.id,
    description: values.answer,
  }, {
    success() {
      toast.success('پاسخ شما ثبت شد و پس از بررسی، نمایش داده می‌شود.')
      showAnswerForm.value = false
      return false
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
</script>
