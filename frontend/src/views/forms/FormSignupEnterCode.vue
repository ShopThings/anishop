<template>
  <form class="relative text-right" @submit.prevent="onSubmit">
    <loader-dot-orbit
      v-if="isSubmitting"
      main-container-klass="absolute w-full h-full top-0 left-0 z-[2]"
      container-bg-color="bg-blue-50 opacity-40"
    />

    <base-button
      class="mt-10 text-sm group flex gap-2 items-center border-2 !text-black !py-1"
      @click="options.prev"
    >
      <ArrowLongRightIcon class="w-6 h-6 group-hover:translate-x-1 transition"/>
      <span class="mx-auto">بازگشت</span>
    </base-button>

    <div class="mt-6 mb-6">
      <partial-input-label title="وارد نمودن کد ارسال شده"/>
      <div class="flex gap-2 flex-row-reverse">
        <template v-for="index in 6" :key="index">
          <base-input
            :name="'number' + index"
            :id="'codeInput' + index"
            type="number"
            klass="text-center !text-2xl text-slate-400 no-spin-arrow !py-2.5"
            @input="goToNextInput"
            @keydown="handleNumberDelete"
          />
        </template>
      </div>

      <a
        href="javascript:void(0)"
        class="text-orange-500 hover:text-opacity-80 transition inline-block mt-2 text-sm"
        @click="sendAnotherCodeToUser"
      >
        ارسال مجدد کد
      </a>
    </div>

    <div class="mb-3">
      <base-button
        type="submit"
        class="w-full flex justify-center items-center group bg-primary border-primary text-white"
        :disabled="isSubmitting"
      >
        <span class="mx-auto">تایید موبایل</span>
        <ArrowLeftIcon
          class="h-6 w-6 text-white opacity-60 group-hover:-translate-x-1.5 transition-all"/>
      </base-button>
    </div>
  </form>
</template>

<script setup>
import {ref} from "vue";
import PartialInputLabel from "../../components/partials/PartialInputLabel.vue";
import BaseInput from "../../components/base/BaseInput.vue";
import {useForm} from "vee-validate";
import yup from "../../validation/index.js";
import LoaderDotOrbit from "../../components/base/loader/LoaderDotOrbit.vue";
import BaseButton from "../../components/base/BaseButton.vue";
import {ArrowLeftIcon, ArrowLongRightIcon} from "@heroicons/vue/24/solid/index.js";
import isFunction from "lodash.isfunction";

const props = defineProps({
  options: {
    type: Object,
    required: true,
  },
})

function goToNextInput(text, event) {
  const numberRegex = /[^0-9]/gi
  let target = event.target
  target.value = text.replace(numberRegex, "")

  if (target.value.length > 1)
    target.value = target.value.at(0)

  if (!(event.inputType === 'deleteContentBackward')) {
    const id = target.getAttribute('id')
    const idStr = id.replace(/[0-9]/gi, "")
    const idNum = parseInt(id.replace(/[^0-9]/gi, ""), 10)

    if (!isNaN(idNum) && (idNum + 1) <= 6) {
      document.getElementById(idStr + (idNum + 1))?.focus()
    }
  }
}

function handleNumberDelete(text, event) {
  if (event.keyCode === 8 || event.key === 'Backspace') {
    let target = event.target
    const id = target.getAttribute('id')
    const idStr = id.replace(/[0-9]/gi, "")
    const idNum = parseInt(id.replace(/[^0-9]/gi, ""), 10)

    if (!isNaN(idNum) && (idNum - 1) >= 1 && text.length === 0) {
      document.getElementById(idStr + (idNum - 1))?.focus()
    }
  }
}

const canSendCode = ref(true)

function sendAnotherCodeToUser() {
  if (!canSendCode.value) return
  canSendCode.value = false

  // send code to mobile
  // ...
}

const canSubmit = ref(true)

const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return

  return new Promise((resolve) => {
    setTimeout(() => {
      resolve()
      if (isFunction(props.options?.next))
        props.options.next()
    }, 2000)
  })
})
</script>
