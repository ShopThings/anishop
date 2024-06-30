<template>
  <form class="relative text-right" @submit.prevent="onSubmit">
    <loader-dot-orbit
      v-if="!canSubmit"
      container-bg-color="bg-blue-50 opacity-40"
      main-container-klass="absolute w-full h-full top-0 left-0 z-[2]"
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
            :id="'codeInput' + index"
            :ref="(el) => (codeInputs[index] = el)"
            :has-clear-button="false"
            :name="'number' + index"
            klass="text-center !text-2xl text-slate-400 no-spin-arrow !py-2.5"
            mask="#"
            type="text"
            @focus="handleNumberInputFocus"
            @keydown="handleInputNumberKeyDown"
            @keyup="handleInputNumberKeyUp"
          />
        </template>
      </div>

      <div v-if="errors?.code">
        <partial-input-error-message :error-message="errors?.code"/>
      </div>

      <div class="flex flex-wrap gap-2 items-center justify-start mt-2">
        <a
          :class="[
              canSendCode ? 'hover:text-opacity-80' : 'cursor-not-allowed opacity-50',
            ]"
          class="text-orange-500 transition text-sm relative py-0.5"
          href="javascript:void(0)"
          @click="sendAnotherCodeToUser"
        >
          ارسال مجدد کد
        </a>
        <div
          v-if="!canSendCode"
          ref="sendCodeTimerRef"
          class="text-black min-w-16 bg-slate-100 rounded text-center"
        ></div>
      </div>
    </div>

    <div class="mb-3">
      <base-button
        :disabled="!canSubmit"
        class="w-full flex justify-center items-center group bg-primary border-primary text-white"
        type="submit"
      >
        <span class="mx-auto">تایید موبایل</span>
        <ArrowLeftIcon
          class="h-6 w-6 text-white opacity-60 group-hover:-translate-x-1.5 transition-all"/>
      </base-button>
    </div>
  </form>
</template>

<script setup>
import {onMounted, ref} from "vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import LoaderDotOrbit from "@/components/base/loader/LoaderDotOrbit.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import {ArrowLeftIcon, ArrowLongRightIcon} from "@heroicons/vue/24/solid/index.js";
import isFunction from "lodash.isfunction";
import {useFormSubmit} from "@/composables/form-submit.js";
import {HomeSignupAPI} from "@/service/APIHomePages.js";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import {useCountdown} from "@/composables/countdown-timer.js";
import {useSignupStore} from "@/store/StoreUserHome.js";
import {useToast} from "vue-toastification";

const props = defineProps({
  options: {
    type: Object,
    required: true,
  },
})

const toast = useToast()
const signupStore = useSignupStore()

//----------------------------------------------
// Handle dynamic input validation and focusing
//----------------------------------------------
const codeInputs = ref([])

function handleInputNumberKeyDown(text, event) {
  let target = event.target

  if (event.keyCode === 8 || event.key === 'Backspace') {
    const id = target.getAttribute('id')
    const idStr = id.replace(/[0-9]/gi, "")
    const idNum = parseInt(id.replace(/[^0-9]/gi, ""), 10)

    if (!isNaN(idNum) && idNum > 1 && text.length === 0) {
      document.getElementById(idStr + (idNum - 1))?.focus()
    }
  } else {
    const numberRegex = /\D/gi

    if (target.value.length >= 1) {
      target.value = target.value.at(0)
    }

    if (numberRegex.test(target.value)) {
      target.value = text.replace(numberRegex, "")

      event.preventDefault()
      return false
    }
  }
}

function handleInputNumberKeyUp(text, event) {
  const numberRegex = /\D/gi
  let target = event.target
  target.value = text.replace(numberRegex, "")

  if (target.value.length > 1) {
    target.value = target.value.at(0)
  }

  if (!(event.inputType === 'deleteContentBackward')) {
    if (target.value.trim() === '') {
      event.preventDefault()
      return false
    }

    const id = target.getAttribute('id')
    const idStr = id.replace(/\d/gi, "")
    const idNum = parseInt(id.replace(/\D/gi, ""), 10)

    if (!isNaN(idNum) && idNum < 6) {
      document.getElementById(idStr + (idNum + 1))?.focus()
    }
  }
}

function handleNumberInputFocus(text, event) {
  event.target.select()
}

//----------------------------------------------
const sendCodeTimerRef = ref(null)
const sendCodeTimer = useCountdown(60, sendCodeTimerRef)
const canSendCode = ref(true)

const {canSubmit, errors, onSubmit} = useFormSubmit({}, (values, actions) => {
  let code = getCodeFromInputs()

  if (!/^\d{6}$/.test(code)) {
    let msg
    if (!code?.length) {
      msg = 'کد تایید را وارد نمایید.'
    } else {
      msg = 'کد وارد شده نامعتبر می‌باشد!'
    }
    actions.setFieldError('code', msg)
    return
  }

  actions.resetField('code')

  canSubmit.value = false

  HomeSignupAPI.verifyCode({
    code,
    username: signupStore.getMobileStep?.mobile
  }, {
    success() {
      toast.success('تبریک، شما با موفقیت در سایت ثبت نام شدید، از خرید خود لذت ببرید.🤩')

      actions.resetForm()
      resetFormInputs()

      if (isFunction(props.options?.next)) {
        signupStore.setCodeStep({
          code,
        })

        props.options.next()
      }
    },
    error(error) {
      actions.setFieldError('code', error.message || 'خطای غیر قابل پیش‌بینی')
      return false
    },
    finally() {
      canSubmit.value = true
    },
  })
})

function checkSendCodeTime() {
  canSendCode.value = true
  sendCodeTimer.stop()
}

function sendAnotherCodeToUser() {
  if (!canSendCode.value) return
  canSendCode.value = false

  sendCodeTimer.start(checkSendCodeTime)

  HomeSignupAPI.resendVerifyCode({
    username: signupStore.getMobileStep?.mobile
  }, {
    success() {
      sendCodeTimer.start(checkSendCodeTime)
    },
    error(error) {
      errors.code = error.message || 'خطای غیر قابل پیش‌بینی'
      return false
    },
  })
}

function getCodeFromInputs() {
  if (!codeInputs.value) return ''

  let code = ''
  for (let inp of codeInputs.value) {
    if (inp?.input) {
      code += inp.input.value.at(0)
    }
  }

  return code
}

function resetFormInputs() {
  if (!codeInputs.value) return

  for (let inp of codeInputs.value) {
    if (inp?.input) {
      inp.input.value = ''
    }
  }
}

onMounted(() => {
  if (!signupStore.canGoToStepCode) {
    props.options.prev()
  }

  signupStore.resetCodeStep()
})
</script>
