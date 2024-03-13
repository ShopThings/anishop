<template>
  <form class="relative" @submit.prevent="onSubmit">
    <loader-dot-orbit
        v-if="!canSubmit"
        container-bg-color="bg-blue-50 opacity-40"
        main-container-klass="absolute w-full h-full top-0 left-0 z-[2]"
    />

    <div class="mb-3 mt-12">
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
import {LockOpenIcon, LockClosedIcon} from "@heroicons/vue/24/outline/index.js";
import BaseInput from "@/components/base/BaseInput.vue";
import yup, {transformNumbersToEnglish} from "@/validation/index.js";
import LoaderDotOrbit from "@/components/base/loader/LoaderDotOrbit.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import {CheckIcon} from "@heroicons/vue/24/solid/index.js";
import {useFormSubmit} from "@/composables/form-submit.js";
import {HomeSignupAPI} from "@/service/APIHomePages.js";
import {useRouter} from "vue-router";
import {useToast, POSITION} from "vue-toastification";
import {useUserAuthStore} from "@/store/StoreUserAuth.js";

const props = defineProps({
  options: {
    type: Object,
    required: true,
  },
})

const router = useRouter()
const toast = useToast()

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

  HomeSignupAPI.assignPassword(values, {
    success(response) {
      actions.resetForm()
      toast.success('تبریک، شما با موفقیت در سایت ثبت نام شدید، از خرید خود لذت ببرید.🤩')

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
      if (error.errors && Object.keys(error.errors).length >= 1)
        actions.setErrors(error.errors)
    },
    finally() {
      canSubmit.value = true
    },
  })
})
</script>
