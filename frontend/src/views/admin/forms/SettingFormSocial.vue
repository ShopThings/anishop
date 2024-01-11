<template>
  <form @submit.prevent="onSubmit">
    <div class="flex mb-3 items-end">
      <div class="grow flex flex-col gap-3">
        <div
          v-for="(item, idx) in socialItems"
          :key="item.id"
          class="flex flex-wrap relative border rounded-md border-violet-500"
        >
          <partial-builder-remove-btn
            v-if="socialItems.length > 1"
            @click="removeSocialHandler(idx)"
          />

          <div class="p-2 w-full sm:w-1/3 md:w-1/4">
            <partial-input-label title="نوع شبکه اجتماعی"/>
            <base-select
              options-text="text"
              options-key="value"
              :options="socials"
              :name="'type_' + item.id"
              :selected="item.type"
              @change="(selected) => {item.type = selected}"
            />
          </div>
          <div class="p-2 w-full sm:w-2/3 md:w-3/4">
            <base-input
              v-tooltip.right="'افزودن شبکه اجتماعی جدید'"
              label-title="آدرس شبکه اجتماعی"
              placeholder="وارد نمایید"
              :name="'url_' + item.id"
              :value="item.link"
              @input="(val) => {item.link = val}"
            >
              <template #icon>
                <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
              </template>
            </base-input>
          </div>
        </div>
      </div>
      <div class="p-2">
        <button
          type="button"
          class="rounded-full p-3 flex justify-center items-center bg-emerald-400 text-white group"
          @click="newSocialHandler"
        >
          <PlusIcon class="w-6 h-6 group-hover:rotate-90 transition"/>
        </button>
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
import {reactive, ref} from "vue";
import {SETTING_KEYS, SOCIAL_NETWORKS} from "../../../composables/constants.js";
import {useForm} from "vee-validate";
import yup from "../../../validation/index.js";
import {ArrowLeftCircleIcon, CheckIcon, PlusIcon} from "@heroicons/vue/24/outline/index.js";
import VTransitionFade from "../../../transitions/VTransitionFade.vue";
import BaseAnimatedButton from "../../../components/base/BaseAnimatedButton.vue";
import LoaderCircle from "../../../components/base/loader/LoaderCircle.vue";
import BaseSelect from "../../../components/base/BaseSelect.vue";
import PartialInputLabel from "../../../components/partials/PartialInputLabel.vue";
import BaseInput from "../../../components/base/BaseInput.vue";
import PartialBuilderRemoveBtn from "../../../components/partials/PartialBuilderRemoveBtn.vue";

const props = defineProps({
  setting: {
    type: Object,
    required: true,
  },
})

//---------------------------------
// Social Accounts Stuffs
//---------------------------------
const socials = []
for (let s in SOCIAL_NETWORKS) {
  if (SOCIAL_NETWORKS.hasOwnProperty(s)) {
    socials.push({
      value: SOCIAL_NETWORKS[s].value,
      text: SOCIAL_NETWORKS[s].text,
    })
  }
}
const socialItems = reactive(props.setting[SETTING_KEYS.SOCIALS] ?? [
  {
    id: 1,
    type: null,
    link: '',
  },
])

function newSocialHandler() {
  socialItems.push({
    id: (socialItems[socialItems.length - 1]?.id || 0) + 1,
    type: null,
    link: '',
  })
}

function removeSocialHandler(idx) {
  if (socialItems[idx])
    socialItems.splice(idx, 1)
}

//---------------------------------

const canSubmit = ref(true)

const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return
})
</script>
