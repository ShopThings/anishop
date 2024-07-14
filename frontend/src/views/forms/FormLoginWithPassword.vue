<template>
  <VTransitionSlideFadeDownY>
    <base-message v-if="err.message && err.type" :type="err.type" @close="closeAlert">
      {{ err.message }}
    </base-message>
  </VTransitionSlideFadeDownY>
  <form @submit.prevent="onSubmit">
    <div class="mb-3">
      <base-input
        label-title="نام کاربری:"
        name="username"
        placeholder="نام کاربری"
      >
        <template #icon>
          <UserIcon class="w-6 h-6 text-gray-400"/>
        </template>
      </base-input>
    </div>
    <div class="mb-3">
      <base-input
        label-title="کلمه عبور:"
        name="password"
        placeholder="کلمه عبور"
        type="password"
      >
        <template #icon>
          <KeyIcon class="w-6 h-6 text-gray-400"/>
        </template>
      </base-input>
    </div>

    <div class="mb-6 text-right lg:hidden">
      <router-link
        :to="{name: 'forget_password'}"
        class="mr-1 text-blue-600 hover:text-opacity-80 transition"
      >
        کلمه عبور خود را فراموش کرده‌ام!
      </router-link>
    </div>

    <div class="mb-2">
      <v-captcha ref="captchaCom" v-model="captchaKey"/>
    </div>
    <div class="mb-6">
      <base-input
        klass="no-spin-arrow"
        type="number"
        label-title="کد تصویر:"
        name="captcha"
        placeholder="کد تصویر"
      >
        <template #icon>
          <QrCodeIcon class="w-6 h-6 text-gray-400"/>
        </template>
      </base-input>
    </div>
    <base-button
      :class="store.isLoading ? '!cursor-not-allowed !bg-opacity-70' : 'cursor-pointer'"
      :disabled="store.isLoading"
      class="w-full flex justify-center items-center group bg-primary border-primary text-white"
      type="submit"
    >
      <loader-circle
        v-if="store.isLoading"
        big-circle-color="border-transparent"
        container-bg-color=""
        main-container-klass="absolute h-6 w-6 right-3"
        small-circle-color="border-t-white"
      />

      <span class="mr-auto">وارد شوید</span>
      <ArrowLeftIcon
        class="h-6 w-6 text-white opacity-60 mr-auto group-hover:-translate-x-1.5 transition-all"/>
    </base-button>
  </form>
</template>

<script setup>
import BaseInput from "@/components/base/BaseInput.vue";
import {KeyIcon, QrCodeIcon, UserIcon} from "@heroicons/vue/24/outline/index.js";
import VTransitionSlideFadeDownY from "@/transitions/VTransitionSlideFadeDownY.vue";
import VCaptcha from "@/components/base/VCaptcha.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import BaseMessage from "@/components/base/BaseMessage.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import {ArrowLeftIcon} from "@heroicons/vue/24/solid/index.js";
import {useUserAuthStore} from "@/store/StoreUserAuth.js";
import {useFormSubmit} from "@/composables/form-submit.js";
import yup from "@/validation/index.js";
import {isValidInternalRedirectLink} from "@/composables/helper.js";
import {reactive, ref} from "vue";
import {useRoute, useRouter} from "vue-router";
import {useCartStore} from "@/store/StoreUserCart.js";

const router = useRouter()
const route = useRoute()

const store = useUserAuthStore()
const cartStore = useCartStore()

const captchaKey = ref(null)
const err = reactive({})
const captchaCom = ref(null)

function closeAlert() {
  err.message = null
  err.type = null
}

const {canSubmit, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    username: yup.string().required('نام کاربری را وارد نمایید.'),
    password: yup.string().required('کلمه عبور را وارد نمایید.'),
    captcha: yup.string().required('کد تصویر را وارد نمایید.'),
  }),
}, (values, actions) => {
  if (store.isLoading) return

  closeAlert()

  if (!captchaKey.value) {
    err.message = 'تصویر را دوباره بارگذاری نمایید.'
    err.type = 'error'
    return
  }

  values.key = captchaKey.value

  store.login(values, {
    success() {
      actions.resetForm();

      if (
        route.query.redirect &&
        isValidInternalRedirectLink(route.query.redirect) &&
        ['/admin/login', '/login'].indexOf(route.query.redirect) === -1
      ) router.push(route.query.redirect)
      else router.push({name: 'user.home'})

      cartStore.save({}, cartStore.getShoppingCartName())
      cartStore.save({}, cartStore.getWishlistCartName())

      return false
    },
    error(error) {
      actions.resetField('password')
      actions.resetField('captcha')

      err.message = error?.message || 'خطا در عملیات ورود!'
      err.type = 'error'
      return false
    },
    finally() {
      if (captchaCom.value)
        captchaCom.value.getCaptcha()
    },
  })
})
</script>
