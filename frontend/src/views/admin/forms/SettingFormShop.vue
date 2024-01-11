<template>
  <form @submit.prevent="onSubmit">
    <div class="flex flex-wrap">
      <div class="p-2 w-full md:w-1/2">
        <base-input
          label-title="حداقل قیمت جهت رایگان شدن هزینه ارسال (به تومان)"
          placeholder="وارد نمایید"
          type="number"
          name="min_free_post_price"
          :min="0"
          klass="no-spin-arrow"
          :value="minFreePostPrice.toString()"
          @input="(val) => {
              if(val.trim() === '') minFreePostPrice = 0
              else minFreePostPrice = val
            }"
        >
          <template #icon>
            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
          </template>
        </base-input>
        <div class="flex gap-2 items-center mt-2">
          <span class="text-slate-500 text-xl">{{ formatPriceLikeNumber(minFreePostPrice) }}</span>
          <span class="text-xs text-slate-400">تومان</span>
        </div>
      </div>
      <div class="p-2 w-full md:w-1/2">
        <base-input
          label-title="هزینه ارسال پیش‌فرض (به تومان)"
          placeholder="وارد نمایید"
          type="number"
          name="default_post_price"
          :min="0"
          klass="no-spin-arrow"
          :value="defaultPostPrice.toString()"
          @input="(val) => {
              if(val.trim() === '') defaultPostPrice = 0
              else defaultPostPrice = val
            }"
        >
          <template #icon>
            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
          </template>
        </base-input>
        <div class="flex gap-2 items-center mt-2">
          <span class="text-slate-500 text-xl">{{ formatPriceLikeNumber(defaultPostPrice) }}</span>
          <span class="text-xs text-slate-400">تومان</span>
        </div>
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
import BaseInput from "../../../components/base/BaseInput.vue";
import {ArrowLeftCircleIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import VTransitionFade from "../../../transitions/VTransitionFade.vue";
import BaseAnimatedButton from "../../../components/base/BaseAnimatedButton.vue";
import LoaderCircle from "../../../components/base/loader/LoaderCircle.vue";
import {formatPriceLikeNumber} from "../../../composables/helper.js";

const props = defineProps({
  setting: {
    type: Object,
    required: true,
  },
})

const canSubmit = ref(true)

const minFreePostPrice = ref(props.setting[SETTING_KEYS.MIN_FREE_POST_PRICE] ?? 0)
const defaultPostPrice = ref(props.setting[SETTING_KEYS.DEFAULT_POST_PRICE] ?? 0)

const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return
})
</script>
