<template>
  <form @submit.prevent="onSubmit">
    <div class="flex flex-wrap">
      <div class="p-2 w-full md:w-1/2">
        <base-input
            label-title="تعداد نمایش کالا در هر صفحه"
            placeholder="وارد نمایید"
            type="number"
            name="product_each_page"
            :min="0"
            :max="25"
            klass="no-spin-arrow"
            :value="productEachPage.toString()"
            @input="(val) => {
              if(val.trim() === '') productEachPage = 0
              else productEachPage = val
            }"
        >
          <template #icon>
            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
          </template>
        </base-input>
      </div>
      <div class="p-2 w-full md:w-1/2">
        <base-input
            label-title="تعداد نمایش بلاگ در هر صفحه"
            placeholder="وارد نمایید"
            type="number"
            name="blog_each_page"
            :min="0"
            :max="25"
            klass="no-spin-arrow"
            :value="blogEachPage.toString()"
            @input="(val) => {
              if(val.trim() === '') blogEachPage = 0
              else blogEachPage = val
            }"
        >
          <template #icon>
            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
          </template>
        </base-input>
      </div>
    </div>

    <div class="px-2 py-3">
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

        <span class="ml-auto">ذخیره اطلاعات</span>
      </base-animated-button>
    </div>
  </form>
</template>

<script setup>
import {ref} from "vue";
import {SETTING_KEYS} from "../../../composables/constants.js";
import {useForm} from "vee-validate";
import yup from "../../../validation/index.js";
import {ArrowLeftCircleIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import VTransitionFade from "../../../transitions/VTransitionFade.vue";
import BaseAnimatedButton from "../../../components/base/BaseAnimatedButton.vue";
import LoaderCircle from "../../../components/base/loader/LoaderCircle.vue";
import BaseInput from "../../../components/base/BaseInput.vue";

const props = defineProps({
  setting: {
    type: Object,
    required: true,
  },
})

const canSubmit = ref(true)

const productEachPage = ref(props.setting[SETTING_KEYS.PRODUCT_EACH_PAGE] ?? 0)
const blogEachPage = ref(props.setting[SETTING_KEYS.BLOG_EACH_PAGE] ?? 0)

const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return
})
</script>

<style scoped>

</style>
