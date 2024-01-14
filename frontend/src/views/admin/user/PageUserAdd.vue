<template>
  <partial-card>
    <template #header>
      افزودن کاربر جدید
    </template>

    <template #body>
      <div class="p-3">
        <base-loading-panel :loading="loading" type="form">
          <template #content>
            <form @submit.prevent="onSubmit">
              <div class="flex flex-wrap">
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input label-title="نام کاربری"
                              placeholder="(شماره تلفن همراه می‌باشد)"
                              name="username">
                    <template #icon>
                      <UserIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input label-title="کلمه عبور"
                              type="password"
                              placeholder="شامل حروف و اعداد"
                              name="password">
                    <template #icon>
                      <LockClosedIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input label-title="تکرار کلمه عبور"
                              type="password"
                              placeholder="شامل حروف و اعداد"
                              name="password_confirmation">
                    <template #icon>
                      <LockClosedIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <partial-input-label title="نقش کاربر"/>
                  <base-select-searchable
                    :options="roles"
                    options-key="value"
                    options-text="name"
                    name="roles"
                    :multiple="true"
                    @change="roleChange"
                  />
                  <partial-input-error-message :error-message="errors.roles"/>
                </div>
              </div>

              <hr class="my-3">

              <div class="flex flex-wrap">
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input label-title="نام"
                              placeholder="حروف فارسی"
                              name="first_name">
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input label-title="نام خانوادگی"
                              placeholder="حروف فارسی"
                              name="last_name">
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input label-title="کد ملی"
                              placeholder="فقط شامل اعداد"
                              name="national_code">
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input label-title="شماره شبا"
                              is-optional
                              placeholder="xxxxxxxxxxxxxxxx"
                              name="shaba_number">
                    <template #icon>
                      <HashtagIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
              </div>

              <div class="px-2 py-3">
                <base-animated-button
                  type="submit"
                  class="bg-emerald-500 text-white mr-auto px-6 w-full sm:w-auto"
                  :disabled="!canSubmit"
                >
                  <VTransitionFade>
                    <loader-circle
                      v-if="!canSubmit"
                      main-container-klass="absolute w-full h-full top-0 left-0"
                      big-circle-color="border-transparent"
                    />
                  </VTransitionFade>

                  <template #icon="{klass}">
                    <CheckIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
                  </template>

                  <span class="ml-auto">ایجاد کاربر</span>
                </base-animated-button>
              </div>
            </form>
          </template>
        </base-loading-panel>
      </div>
    </template>
  </partial-card>
</template>

<script setup>
import {onMounted, ref} from "vue";
import {
  UserIcon, ArrowLeftCircleIcon, CheckIcon, LockClosedIcon, HashtagIcon
} from "@heroicons/vue/24/outline";
import BaseInput from "@/components/base/BaseInput.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import yup, {transformNumbersToEnglish} from "@/validation/index.js";
import {useRouter} from "vue-router";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import BaseSelectSearchable from "@/components/base/BaseSelectSearchable.vue";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {RoleAPI} from "@/service/APIRole.js";
import {UserAPI} from "@/service/APIUser.js";
import {useFormSubmit} from "@/composables/form-submit.js";

const loading = ref(true)
const roles = ref({})

const router = useRouter()

const selectedRole = ref(null)

function roleChange(selected) {
  selectedRole.value = selected
}

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    username: yup.string()
      .transform(transformNumbersToEnglish)
      .required('نام کاربری اجباری می‌باشد.'),
    password: yup.string()
      .transform(transformNumbersToEnglish)
      .matches(/(?=.*\d)/g, 'کلمه عبور باید شامل حداقل ۱ عدد باشد.')
      .matches(/(?=.*[a-z])/g, 'کلمه عبور باید شامل حداقل ۱ کاراکتر از حروف کوچک انگلیسی باشد.')
      .matches(/(?=.*[A-Z])/g, 'کلمه عبور باید شامل حداقل ۱ کاراکتر از حروف بزرگ انگلیسی باشد.')
      .min(9, 'کلمه عبور باید حداقل دارای ۹ کاراکتر باشد.')
      .required('کلمه عبور اجباری می‌باشد.'),
    password_confirmation: yup.string()
      .oneOf([yup.ref('password'), null], 'کلمه عبور با تکرار آن مغایرت دارد.'),
    first_name: yup.string().persian('نام باید از حروف فارسی باشد.').required('نام اجباری می‌باشد.'),
    last_name: yup.string().persian('نام خانوادگی باید از حروف فارسی باشد.').required('نام خانوادگی اجباری می‌باشد.'),
    national_code: yup.string()
      .transform(transformNumbersToEnglish)
      .persianNationalCode('کد ملی نامعتبر است.').required('کد ملی اجباری می‌باشد.'),
    shaba_number: yup.string()
      .transform(transformNumbersToEnglish)
      .optional().nullable(),
  }),
}, (values, actions) => {
  if (!canSubmit.value) return

  // validate extra inputs
  if (!selectedRole.value || selectedRole.value.length === 0) {
    actions.setFieldError('roles', 'انتخاب حداقل یک نقش اجباری می‌باشد.')
    return
  }

  if (Array.isArray(selectedRole.value)) {
    for (let i of selectedRole.value) {
      if (roles.value.map(val => val.value).indexOf(i.value) === -1) {
        actions.setFieldError('roles', 'نقش انتخاب شده نامعتبر می‌باشد.')
        return
      }
    }
    values.roles = selectedRole.value
  } else {
    if (roles.value.map(val => val.value).indexOf(selectedRole.value.value) === -1) {
      actions.setFieldError('roles', 'نقش انتخاب شده نامعتبر می‌باشد.')
      return
    }
    values.roles = [selectedRole.value]
  }
  //

  canSubmit.value = false

  UserAPI.create(values, {
    success() {
      actions.resetForm();
      router.push({name: 'admin.users'})
    },
    error(error) {
      actions.resetField('password')
      actions.resetField('password_confirmation')

      if (error.errors && Object.keys(error.errors).length >= 1)
        actions.setErrors(error.errors)

      return false
    },
    finally() {
      canSubmit.value = true
    },
  })
})

onMounted(() => {
  RoleAPI.fetchAll({
    success(response) {
      roles.value = response.data
      loading.value = false
    },
  })
})
</script>
