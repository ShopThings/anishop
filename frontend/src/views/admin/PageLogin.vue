<template>
  <!-- ====== Forms Section Start -->
  <section class="py-10">
    <div class="md:container md:mx-auto">
      <div class="flex flex-wrap justify-center items-center min-h-screen">
        <div class="w-full">
          <div
              class="relative mx-auto max-w-sm overflow-hidden rounded-lg bg-white shadow-xl shadow-gray-200 py-6 px-10 text-center"
          >
            <div class="mb-6 text-center">
              <UserCircleIcon class="w-24 h-24 mx-auto text-slate-400"/>
              <h1 class="text-xl text-black">
                ورود به پنل ادمین
              </h1>
            </div>
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
              <div>
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
              <div class="border-t my-6"></div>
              <div class="mb-2">
                <v-captcha ref="captchaCom" v-model="captchaKey"/>
              </div>
              <div class="mb-6">
                <base-input
                    label-title="کد تصویر:"
                    name="captcha"
                    placeholder="کد تصویر"
                >
                  <template #icon>
                    <QrCodeIcon class="w-6 h-6 text-gray-400"/>
                  </template>
                </base-input>
              </div>
              <div class="mb-8">
                <base-button
                    :class="store.isLoading ? '!cursor-not-allowed !bg-opacity-70' : 'cursor-pointer'"
                    :disabled="store.isLoading"
                    class="relative w-full flex justify-center items-center group bg-primary border-indigo-700 text-white"
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
              </div>
            </form>

            <p class="text-base text-[#adadad]">
              طراحی و توسعه توسط
              <a class="text-primary transition-all p-2 hover:shadow" href="javascript:void(0)">
                تیم هیوا
              </a>
            </p>

            <div>
              <span class="absolute top-1 right-1">
                <svg
                    fill="none"
                    height="40"
                    viewBox="0 0 40 40"
                    width="40"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <circle
                        cx="1.39737"
                        cy="38.6026"
                        fill="#3056D3"
                        r="1.39737"
                        transform="rotate(-90 1.39737 38.6026)"
                    />
                    <circle
                        cx="1.39737"
                        cy="1.99122"
                        fill="#3056D3"
                        r="1.39737"
                        transform="rotate(-90 1.39737 1.99122)"
                    />
                    <circle
                        cx="13.6943"
                        cy="38.6026"
                        fill="#3056D3"
                        r="1.39737"
                        transform="rotate(-90 13.6943 38.6026)"
                    />
                    <circle
                        cx="13.6943"
                        cy="1.99122"
                        fill="#3056D3"
                        r="1.39737"
                        transform="rotate(-90 13.6943 1.99122)"
                    />
                    <circle
                        cx="25.9911"
                        cy="38.6026"
                        fill="#3056D3"
                        r="1.39737"
                        transform="rotate(-90 25.9911 38.6026)"
                    />
                    <circle
                        cx="25.9911"
                        cy="1.99122"
                        fill="#3056D3"
                        r="1.39737"
                        transform="rotate(-90 25.9911 1.99122)"
                    />
                    <circle
                        cx="38.288"
                        cy="38.6026"
                        fill="#3056D3"
                        r="1.39737"
                        transform="rotate(-90 38.288 38.6026)"
                    />
                    <circle
                        cx="38.288"
                        cy="1.99122"
                        fill="#3056D3"
                        r="1.39737"
                        transform="rotate(-90 38.288 1.99122)"
                    />
                    <circle
                        cx="1.39737"
                        cy="26.3057"
                        fill="#3056D3"
                        r="1.39737"
                        transform="rotate(-90 1.39737 26.3057)"
                    />
                    <circle
                        cx="13.6943"
                        cy="26.3057"
                        fill="#3056D3"
                        r="1.39737"
                        transform="rotate(-90 13.6943 26.3057)"
                    />
                    <circle
                        cx="25.9911"
                        cy="26.3057"
                        fill="#3056D3"
                        r="1.39737"
                        transform="rotate(-90 25.9911 26.3057)"
                    />
                    <circle
                        cx="38.288"
                        cy="26.3057"
                        fill="#3056D3"
                        r="1.39737"
                        transform="rotate(-90 38.288 26.3057)"
                    />
                    <circle
                        cx="1.39737"
                        cy="14.0086"
                        fill="#3056D3"
                        r="1.39737"
                        transform="rotate(-90 1.39737 14.0086)"
                    />
                    <circle
                        cx="13.6943"
                        cy="14.0086"
                        fill="#3056D3"
                        r="1.39737"
                        transform="rotate(-90 13.6943 14.0086)"
                    />
                    <circle
                        cx="25.9911"
                        cy="14.0086"
                        fill="#3056D3"
                        r="1.39737"
                        transform="rotate(-90 25.9911 14.0086)"
                    />
                    <circle
                        cx="38.288"
                        cy="14.0086"
                        fill="#3056D3"
                        r="1.39737"
                        transform="rotate(-90 38.288 14.0086)"
                    />
                </svg>
              </span>
              <span class="absolute left-1 bottom-1">
                <svg
                    fill="none"
                    height="40"
                    viewBox="0 0 29 40"
                    width="29"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <circle
                        cx="2.288"
                        cy="25.9912"
                        fill="#3056D3"
                        r="1.39737"
                        transform="rotate(-90 2.288 25.9912)"
                    />
                    <circle
                        cx="14.5849"
                        cy="25.9911"
                        fill="#3056D3"
                        r="1.39737"
                        transform="rotate(-90 14.5849 25.9911)"
                    />
                    <circle
                        cx="26.7216"
                        cy="25.9911"
                        fill="#3056D3"
                        r="1.39737"
                        transform="rotate(-90 26.7216 25.9911)"
                    />
                    <circle
                        cx="2.288"
                        cy="13.6944"
                        fill="#3056D3"
                        r="1.39737"
                        transform="rotate(-90 2.288 13.6944)"
                    />
                    <circle
                        cx="14.5849"
                        cy="13.6943"
                        fill="#3056D3"
                        r="1.39737"
                        transform="rotate(-90 14.5849 13.6943)"
                    />
                    <circle
                        cx="26.7216"
                        cy="13.6943"
                        fill="#3056D3"
                        r="1.39737"
                        transform="rotate(-90 26.7216 13.6943)"
                    />
                    <circle
                        cx="2.288"
                        cy="38.0087"
                        fill="#3056D3"
                        r="1.39737"
                        transform="rotate(-90 2.288 38.0087)"
                    />
                    <circle
                        cx="2.288"
                        cy="1.39739"
                        fill="#3056D3"
                        r="1.39737"
                        transform="rotate(-90 2.288 1.39739)"
                    />
                    <circle
                        cx="14.5849"
                        cy="38.0089"
                        fill="#3056D3"
                        r="1.39737"
                        transform="rotate(-90 14.5849 38.0089)"
                    />
                    <circle
                        cx="26.7216"
                        cy="38.0089"
                        fill="#3056D3"
                        r="1.39737"
                        transform="rotate(-90 26.7216 38.0089)"
                    />
                    <circle
                        cx="14.5849"
                        cy="1.39761"
                        fill="#3056D3"
                        r="1.39737"
                        transform="rotate(-90 14.5849 1.39761)"
                    />
                    <circle
                        cx="26.7216"
                        cy="1.39761"
                        fill="#3056D3"
                        r="1.39737"
                        transform="rotate(-90 26.7216 1.39761)"
                    />
                </svg>
                </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import {reactive, ref} from "vue";
import BaseInput from '@/components/base/BaseInput.vue';
import BaseButton from "@/components/base/BaseButton.vue";
import VCaptcha from "@/components/base/VCaptcha.vue";
import {ArrowLeftIcon} from '@heroicons/vue/24/solid';
import {UserCircleIcon, UserIcon, KeyIcon, QrCodeIcon} from '@heroicons/vue/24/outline';
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import {useRoute, useRouter} from "vue-router";
import BaseMessage from "@/components/base/BaseMessage.vue";
import VTransitionSlideFadeDownY from "@/transitions/VTransitionSlideFadeDownY.vue";
import yup from '@/validation/index.js';
import {useAdminAuthStore} from "@/store/StoreUserAuth.js";
import {isValidInternalRedirectLink} from "@/composables/helper.js";
import {useFormSubmit} from "@/composables/form-submit.js";

const captchaKey = ref(null)
const err = reactive({})
const captchaCom = ref(null)

const router = useRouter()
const route = useRoute()

const store = useAdminAuthStore()

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
      else router.push({name: 'admin.home'})

      return false
    },
    error(error) {
      actions.resetField('password')
      actions.resetField('captcha')

      if (error.errors && Object.keys(error.errors).length >= 1)
        actions.setErrors(error.errors)

      err.message = error.message || 'خطا در عملیات ورود!'
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
