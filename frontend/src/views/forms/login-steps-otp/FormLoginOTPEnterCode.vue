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
      <partial-input-label>
        <template #label>
          <div class="flex flex-wrap gap-2 5 items-center">
            <span>وارد نمودن رمز ارسال شده</span>
            <div class="text-xs text-pink-600">
              (صفحه کلید باید انگلیسی باشد)
            </div>
          </div>
        </template>
      </partial-input-label>
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
          ارسال مجدد رمز یکبار مصرف
        </a>
        <div
          v-if="!canSendCode"
          ref="sendCodeTimerRef"
          class="text-black min-w-16 bg-slate-100 rounded text-center"
        ></div>
      </div>
    </div>

    <base-button
      :disabled="!canSubmit"
      class="w-full flex justify-center items-center group bg-primary border-primary text-white"
      type="submit"
    >
      <span class="mx-auto">تایید رمز</span>
      <ArrowLeftIcon
        class="h-6 w-6 text-white opacity-60 group-hover:-translate-x-1.5 transition-all"/>
    </base-button>
  </form>
</template>

<script setup>
import {onMounted, ref} from "vue";
import {useCountdown} from "@/composables/countdown-timer.js";
import {useFormSubmit} from "@/composables/form-submit.js";
import {HomeLoginOTPAPI} from "@/service/APIHomePages.js";
import {useLoginOTPStore} from "@/store/StoreUserHome.js";
import BaseInput from "@/components/base/BaseInput.vue";
import {ArrowLeftIcon, ArrowLongRightIcon} from "@heroicons/vue/24/solid/index.js";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import LoaderDotOrbit from "@/components/base/loader/LoaderDotOrbit.vue";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import {useUserAuthStore} from "@/store/StoreUserAuth.js";
import {isValidInternalRedirectLink} from "@/composables/helper.js";
import {useRoute, useRouter} from "vue-router";
import {useCartStore} from "@/store/StoreUserCart.js";

const props = defineProps({
  options: {
    type: Object,
    required: true,
  },
})

const router = useRouter()
const route = useRoute()

const userStore = useUserAuthStore()
const loginStore = useLoginOTPStore()
const cartStore = useCartStore()

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

const {canSubmit, errors, onSubmit, setFieldError} = useFormSubmit({}, (values, actions) => {
  let code = getCodeFromInputs()

  if (!/^\d{6}$/.test(code)) {
    let msg
    if (!code?.length) {
      msg = 'رمز را وارد نمایید.'
    } else {
      msg = 'رمز وارد شده نامعتبر می‌باشد!'
    }
    actions.setFieldError('code', msg)
    return
  }

  actions.resetField('code')

  canSubmit.value = false

  HomeLoginOTPAPI.verifyCode({
    code,
    username: loginStore.getMobileStep?.mobile
  }, {
    success(response) {
      actions.resetForm()
      resetFormInputs()

      userStore.setUser(response.data.user)
      userStore.setToken(response.data.token)

      if (
        route.query.redirect &&
        isValidInternalRedirectLink(route.query.redirect) &&
        ['/admin/login', '/login'].indexOf(route.query.redirect) === -1
      ) router.push(route.query.redirect)
      else router.push({name: 'user.home'})

      cartStore.save({}, cartStore.getShoppingCartName())
      cartStore.save({}, cartStore.getWishlistCartName())
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

  HomeLoginOTPAPI.resendVerifyCode({
    username: loginStore.getMobileStep?.mobile
  }, {
    success() {
      sendCodeTimer.start(checkSendCodeTime)
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
  if (!loginStore.canGoToStepCode) {
    props.options.prev()
  }

  loginStore.resetCodeStep()
})
</script>
