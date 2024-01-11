<template>
  <form @submit.prevent="onSubmit">
    <div class="p-2">
      <base-textarea
        label-title="توضیحات مختصر"
        name="footer_description"
        :value="setting[SETTING_KEYS.FOOTER_DESCRIPTION] ?? ''"
      >
        <template #icon>
          <InformationCircleIcon class="h-6 w-6 mt-3 text-gray-400"/>
        </template>
      </base-textarea>
    </div>

    <hr class="border-0 w-36 sm:w-48 h-1 rounded-full bg-slate-200 my-6 mx-auto">

    <div class="p-2">
      <base-input
        label-title="حق مالکیت/کپی‌رایت"
        placeholder="وارد نمایید"
        name="copyright"
        :value="setting[SETTING_KEYS.FOOTER_COPYRIGHT] ?? ''"
      >
        <template #icon>
          <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
        </template>
      </base-input>
    </div>

    <hr class="border-0 w-36 sm:w-48 h-1 rounded-full bg-slate-200 my-6 mx-auto">

    <div class="flex mb-3 items-end">
      <div class="grow flex flex-col gap-3">
        <div
          v-for="(item, idx) in namads"
          :key="item.id"
          class="relative border rounded-md border-violet-500"
        >
          <partial-builder-remove-btn
            v-if="namads.length > 1"
            @click="removeNamadHandler(idx)"
          />

          <div class="p-2">
            <base-textarea
              :label-title="'لینک نماد - نماد شماره ' + (item.id)"
              :name="'footer_namad_' + item.id"
              :value="item.link"
              @input="(val) => {item.link = val}"
            >
              <template #icon>
                <InformationCircleIcon class="h-6 w-6 mt-3 text-gray-400"/>
              </template>
            </base-textarea>
          </div>
        </div>
      </div>
      <div class="p-2">
        <button
          v-tooltip.right="'افزودن نماد جدید'"
          type="button"
          class="rounded-full p-3 flex justify-center items-center bg-emerald-400 text-white group"
          @click="addNamadHandler"
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
import {SETTING_KEYS} from "../../../composables/constants.js";
import {useForm} from "vee-validate";
import yup from "../../../validation/index.js";
import {ArrowLeftCircleIcon, CheckIcon, InformationCircleIcon, PlusIcon} from "@heroicons/vue/24/outline/index.js";
import VTransitionFade from "../../../transitions/VTransitionFade.vue";
import BaseAnimatedButton from "../../../components/base/BaseAnimatedButton.vue";
import LoaderCircle from "../../../components/base/loader/LoaderCircle.vue";
import BaseTextarea from "../../../components/base/BaseTextarea.vue";
import BaseInput from "../../../components/base/BaseInput.vue";
import PartialBuilderRemoveBtn from "../../../components/partials/PartialBuilderRemoveBtn.vue";

const props = defineProps({
  setting: {
    type: Object,
    required: true,
  },
})

//---------------------------
// Namads Stuffs
//---------------------------
const namads = reactive(props.setting[SETTING_KEYS.FOOTER_NAMADS] ?? [
  {
    id: 1,
    link: null,
  },
])

function addNamadHandler() {
  namads.push({
    id: (namads[namads.length - 1]?.id || 0) + 1,
    link: null,
  })
}

function removeNamadHandler(idx) {
  if (namads[idx])
    namads.splice(idx, 1)
}

//---------------------------

const canSubmit = ref(true)

const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return
})
</script>
