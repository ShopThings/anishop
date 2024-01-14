<template>
  <VTransitionScaleUp mode="out-in">
    <div v-if="showCommentDescription">
      <form
        v-if="user"
        @submit.prevent="onSubmit"
      >
        <div class="mb-3 text-center flex items-center gap-3">
          <div class="grow h-0.5 bg-blue-400 rounded-full"></div>
          <div class="text-orange-500 text-base sm:text-slate-400 sm:text-lg leading-relaxed">
            دیدگاه خود را درباره این نوشته ما با به اشتراک بگذارید
          </div>
          <div class="grow h-0.5 bg-blue-400 rounded-full"></div>
        </div>

        <div>
          <base-textarea
            name="description"
            label-title="توضیحات شما"
            placeholder="دیدگاه خود را وارد نمایید..."
          />
        </div>

        <div class="pt-3">
          <base-animated-button
            type="submit"
            class="!text-black border-2 mr-auto px-6 w-full sm:w-auto hover:bg-slate-100"
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
import {useForm} from "vee-validate";
import yup from "@/validation/index.js";
import VTransitionScaleUp from "@/transitions/VTransitionScaleUp.vue";
import {useUserAuthStore} from "@/store/StoreUserAuth.js";
import {useRoute} from "vue-router";

const userStore = useUserAuthStore()
const user = userStore.getUser

const route = useRoute()

const showCommentDescription = ref(false)

const canSubmit = ref(true)
const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return
})
</script>
