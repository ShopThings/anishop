<template>
  <form @submit.prevent="onSubmit">
    <div class="flex flex-wrap">
      <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
        <base-input
            :has-edit-mode="false"
            :is-editable="false"
            :value="user?.username"
            name="username"
            placeholder="(معمولا شماره تلفن همراه می‌باشد)"
        >
          <template #icon>
            <UserIcon class="h-6 w-6 text-gray-400"/>
          </template>

          <template #editModeLabel="{value}">
            <div class="flex flex-wrap gap-3 items-center">
              <span class="text-sm">نام کاربری</span>
              <span class="tracking-widest text-slate-500 py-1 px-2.5 border-2 rounded-lg">{{ value }}</span>
            </div>
          </template>
        </base-input>
      </div>
    </div>

    <hr class="my-3">

    <div class="flex flex-wrap">
      <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
        <base-input
            :has-edit-mode="false"
            :is-editable="!(!!user?.first_name)"
            :value="user?.first_name"
            label-title="نام"
            name="first_name"
            placeholder="حروف فارسی"
        >
          <template #icon>
            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
          </template>
        </base-input>
      </div>
      <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
        <base-input
            :has-edit-mode="false"
            :is-editable="!(!!user?.last_name)"
            :value="user?.last_name"
            label-title="نام خانوادگی"
            name="last_name"
            placeholder="حروف فارسی"
        >
          <template #icon>
            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
          </template>
        </base-input>
      </div>
      <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
        <base-input
            :has-edit-mode="false"
            :is-editable="!(!!user?.national_code)"
            :value="user?.national_code"
            label-title="کد ملی"
            name="national_code"
            placeholder="فقط شامل اعداد"
        >
          <template #icon>
            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
          </template>
        </base-input>
      </div>
      <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
        <base-input
            :has-edit-mode="false"
            :value="user?.shaba_number"
            is-optional
            label-title="شماره شبا"
            name="shaba_number"
            placeholder="xxxxxxxxxxxxxxxx"
        >
          <template #icon>
            <HashtagIcon class="h-6 w-6 text-gray-400"/>
          </template>
        </base-input>
      </div>
    </div>

    <div class="px-2 py-3">
      <base-animated-button
          :disabled="!canSubmit"
          class="bg-emerald-500 text-white mr-auto px-6 w-full sm:w-auto"
          type="submit"
      >
        <VTransitionFade>
          <loader-circle
              v-if="!canSubmit"
              big-circle-color="border-transparent"
              main-container-klass="absolute w-full h-full top-0 left-0"
          />
        </VTransitionFade>

        <template #icon="{klass}">
          <CheckIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
        </template>

        <span class="ml-auto">ویرایش اطلاعات</span>
      </base-animated-button>
    </div>
  </form>
</template>

<script setup>
import {ArrowLeftCircleIcon, CheckIcon, HashtagIcon, UserIcon} from "@heroicons/vue/24/outline/index.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import yup, {transformNumbersToEnglish} from "@/validation/index.js";
import {useUserAuthStore} from "@/store/StoreUserAuth.js";
import {useFormSubmit} from "@/composables/form-submit.js";
import {UserPanelInfoAPI} from "@/service/APIUserPanel.js";

const store = useUserAuthStore()
const user = store.getUser

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    first_name: yup.string()
        .persian('نام باید از حروف فارسی باشد.')
        .required('نام را وارد نمایید.'),
    last_name: yup.string()
        .persian('نام خانوادگی باید از حروف فارسی باشد.')
        .required('نام خانوادگی را وارد نمایید.'),
    national_code: yup.string()
        .transform(transformNumbersToEnglish)
        .persianNationalCode('کد ملی نامعتبر است.')
        .required('کد ملی را وارد نمایید.'),
    shaba_number: yup.string()
        .transform(transformNumbersToEnglish)
        .optional().nullable(),
  }),
  keepValuesOnUnmount: true,
}, (values, actions) => {
  canSubmit.value = false

  UserPanelInfoAPI.updateInfo(values, {
    success: (response) => {
      toast.success('ویرایش اطلاعات با موفقیت انجام شد.')
      store.setUser(response.data)

      return false
    },
    error: (error) => {
      if (error.errors && Object.keys(error.errors).length >= 1)
        actions.setErrors(error.errors)

      return false
    },
    finally: function () {
      canSubmit.value = true
    },
  })
})
</script>
