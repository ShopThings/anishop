<template>
  <base-button
    :to="{name: 'login'}"
    class="border-amber-300 hover:bg-amber-50 mt-3"
    default-class="text-black rounded-md border-2 hover:text-opacity-80 w-full"
    type="link"
  >
    استفاده از رمز یکبار مصرف
  </base-button>

  <div class="flex gap-3 items-center my-4">
    <div class="h-0.5 grow bg-slate-100"></div>
    <span class="text-slate-400">یا</span>
    <div class="h-0.5 grow bg-slate-100"></div>
  </div>

  <form class="relative" @submit.prevent="onSubmit">
    <loader-dot-orbit
      v-if="!canSubmit"
      container-bg-color="bg-blue-50 opacity-40"
      main-container-klass="absolute w-full h-full top-0 left-0 z-[2]"
    />

    <div class="mb-3">
      <base-input
        label-title="کلمه عبور جدید"
        name="password"
        placeholder="حروف و عدد"
        type="password"
      >
        <template #icon>
          <LockOpenIcon class="w-6 h-6 text-gray-400"/>
        </template>
      </base-input>
    </div>
    <div class="mb-6">
      <base-input
        label-title="تکرار کلمه عبور"
        name="password_confirmation"
        placeholder="حروف و عدد"
        type="password"
      >
        <template #icon>
          <LockClosedIcon class="w-6 h-6 text-gray-400"/>
        </template>
      </base-input>
    </div>

    <div class="mb-3">
      <base-button
        :disabled="!canSubmit"
        class="w-full flex justify-center items-center group bg-pink-500 border-pink-600 text-white"
        type="submit"
      >
        <span class="mx-auto">تایید کلمه عبور</span>
        <CheckIcon
          class="h-6 w-6 text-white opacity-60 group-hover:scale-110 transition-all"/>
      </base-button>
    </div>
  </form>
</template>

<script setup>
import {LockClosedIcon, LockOpenIcon} from "@heroicons/vue/24/outline/index.js";
import BaseInput from "@/components/base/BaseInput.vue";
import yup, {transformNumbersToEnglish} from "@/validation/index.js";
import LoaderDotOrbit from "@/components/base/loader/LoaderDotOrbit.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import {CheckIcon} from "@heroicons/vue/24/solid/index.js";
import {useFormSubmit} from "@/composables/form-submit.js";
import {HomeSignupAPI} from "@/service/APIHomePages.js";
import {useRouter} from "vue-router";
import {POSITION, useToast} from "vue-toastification";
import {useUserAuthStore} from "@/store/StoreUserAuth.js";
import {useSignupStore} from "@/store/StoreUserHome.js";
import {onMounted} from "vue";

const props = defineProps({
  options: {
    type: Object,
    required: true,
  },
})

const router = useRouter()
const toast = useToast()

const signupStore = useSignupStore()
const store = useUserAuthStore()

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    password: yup.string()
      .transform(transformNumbersToEnglish)
      .matches(/(?=.*\d)/gu, 'کلمه عبور باید شامل حداقل ۱ عدد باشد.')
      .matches(/(?=.*[a-z\u0600-\u06FF])/gu, 'کلمه عبور باید شامل حداقل ۱ کاراکتر از حروف کوچک باشد.')
      .matches(/(?=.*[A-Z\u0600-\u06FF])/gu, 'کلمه عبور باید شامل حداقل ۱ کاراکتر از حروف بزرگ باشد.')
      .min(9, 'کلمه عبور باید حداقل دارای ۹ کاراکتر باشد.')
      .required('کلمه عبور را وارد نمایید.'),
    password_confirmation: yup.string()
      .oneOf([yup.ref('password'), null], 'کلمه عبور با تکرار آن مغایرت دارد.'),
  }),
}, (values, actions) => {
  canSubmit.value = false

  HomeSignupAPI.assignPassword({
    password: values.password,
    password_confirmation: values.password_confirmation,
    username: signupStore.getMobileStep?.mobile
  }, {
    success(response) {
      signupStore.$reset()
      actions.resetForm()
      toast.success('کلمه عبور با موفقیت ثبت شد.')

      if (response.data?.token) {
        store.setUser(response.data.user)
        store.setToken(response.data.token)

        router.push({name: 'home'})
      } else {
        toast.info('به پنل کاربری خود وارد شوید.', {
          position: POSITION.BOTTOM_CENTER,
        })

        router.push({name: 'login'})
      }

      return false
    },
    error(error) {
      if (error?.errors && Object.keys(error.errors).length >= 1) {
        actions.setErrors(error.errors)
      }
    },
    finally() {
      canSubmit.value = true
    },
  })
})

onMounted(() => {
  if (!signupStore.canGoToStepPassword) {
    props.options.prev()
  }
})
</script>
