<template>
  <form @submit.prevent="onSubmit">
    <div class="p-2 w-full md:w-1/2">
      <base-input
        label-title="عنوان سایت"
        placeholder="وارد نمایید"
        name="title"
        :value="setting[SETTING_KEYS.TITLE] ?? ''"
      >
        <template #icon>
          <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
        </template>
      </base-input>
    </div>

    <div class="p-2">
      <partial-input-label title="کلمات کلیدی"/>
      <base-tags-input
        :tags="tags"
        placeholder="کلمات کلیدی خود را وارد نمایید"
        @on-tags-changed="(t) => {tags = t}"
      />
    </div>

    <div class="p-2">
      <base-textarea
        label-title="توضیحات مختصر"
        name="description"
        :value="setting[SETTING_KEYS.DESCRIPTION] ?? ''"
      >
        <template #icon>
          <InformationCircleIcon class="h-6 w-6 mt-3 text-gray-400"/>
        </template>
      </base-textarea>
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
import {useForm} from "vee-validate";
import yup from "@/validation/index.js";
import BaseInput from "@/components/base/BaseInput.vue";
import {ArrowLeftCircleIcon, CheckIcon, InformationCircleIcon} from "@heroicons/vue/24/outline/index.js";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseTextarea from "@/components/base/BaseTextarea.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import {SETTING_KEYS} from "@/composables/constants.js";
import BaseTagsInput from "@/components/base/BaseTagsInput.vue";

const props = defineProps({
  setting: {
    type: Object,
    required: true,
  },
})

const canSubmit = ref(true)

const tags = ref([])
tags.value = props.setting[SETTING_KEYS.KEYWORDS]?.split(',') ?? []

const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return
})
</script>
