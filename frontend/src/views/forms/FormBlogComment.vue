<template>
  <VTransitionScaleUp mode="out-in">
    <div v-if="showFormDirectly || showCommentDescription">
      <form
        v-if="user"
        @submit.prevent="onSubmit"
      >
        <div class="mb-3 text-center flex items-center gap-3">
          <div class="grow h-0.5 bg-blue-400 rounded-full"></div>
          <div class="text-orange-500 text-base sm:text-slate-400 sm:text-lg leading-relaxed">
            دیدگاه خود را درباره این نوشته با ما به اشتراک بگذارید
          </div>
          <div class="grow h-0.5 bg-blue-400 rounded-full"></div>
        </div>

        <div>
          <base-textarea
            label-title="توضیحات شما"
            name="description"
            placeholder="دیدگاه خود را وارد نمایید..."
          />
        </div>

        <div class="pt-3">
          <base-animated-button
            :disabled="!canSubmit"
            class="!text-black border-2 mr-auto px-6 w-full sm:w-auto hover:bg-slate-100"
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
              <PaperAirplaneIcon :class="klass" class="h-6 w-6 ml-3"/>
            </template>

            <span class="mx-auto">ثبت دیدگاه</span>
          </base-animated-button>
        </div>
      </form>

      <div v-else>
        <p class="p-3 border-r-4 border-rose-500 bg-rose-50">
          برای ارسال نظر لطفا ابتدا به سایت
          <router-link
            :to="{name: 'login', query: {redirect: route.fullPath}}"
            class="text-blue-600 hover:text-opacity-80 transition"
          >
            وارد شوید.
          </router-link>
        </p>
      </div>
    </div>

    <div
      v-else
      class="flex items-center gap-3 cursor-pointer rounded-lg p-3 bg-slate-100 hover:bg-slate-50 transition"
      @click="showCommentDescription = true"
    >
      <PaperAirplaneIcon class="w-6 h-6 text-slate-300"/>
      <div class="text-sm text-slate-400">
        ارسال دیدگاه...
      </div>
    </div>
  </VTransitionScaleUp>
</template>

<script setup>
import {ref} from "vue";
import BaseTextarea from "@/components/base/BaseTextarea.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import {PaperAirplaneIcon} from "@heroicons/vue/24/outline/index.js";
import yup from "@/validation/index.js";
import VTransitionScaleUp from "@/transitions/VTransitionScaleUp.vue";
import {useUserAuthStore} from "@/store/StoreUserAuth.js";
import {useRoute} from "vue-router";
import {useFormSubmit} from "@/composables/form-submit.js";
import {UserPanelBlogCommentAPI} from "@/service/APIUserPanel.js";
import {useToast} from "vue-toastification";
import {getRouteParamByKey} from "@/composables/helper.js";

const props = defineProps({
  commentId: [Number, String],
  showFormDirectly: Boolean,
})

const userStore = useUserAuthStore()
const user = userStore.getUser

const route = useRoute()
const toast = useToast()
const slugParam = getRouteParamByKey('slug', null, false)

const showCommentDescription = ref(false)

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    description: yup.string().required('توضیحات خود را وارد نمایید.'),
  }),
}, (values, actions) => {
  if (!user) {
    toast.error('برای ارسال نظر لطفا ابتدا به سایت وارد شوید.')
    return
  }

  canSubmit.value = false

  let data = {
    description: values.description,
  }

  if (props.commentId) {
    data.comment = props.commentId
  }

  UserPanelBlogCommentAPI.create(slugParam.value, data, {
    success() {
      actions.resetForm()
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
</script>
