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
                  <base-input
                    label-title="نام کاربری"
                    name="username"
                    placeholder="(شماره تلفن همراه می‌باشد)"
                  >
                    <template #icon>
                      <UserIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input
                    label-title="کلمه عبور"
                    name="password"
                    placeholder="شامل حروف و اعداد"
                    type="password"
                  >
                    <template #icon>
                      <LockClosedIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input
                    label-title="تکرار کلمه عبور"
                    name="password_confirmation"
                    placeholder="شامل حروف و اعداد"
                    type="password"
                  >
                    <template #icon>
                      <LockClosedIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <partial-input-label title="نقش کاربر"/>
                  <base-select-searchable
                    :multiple="true"
                    :options="roles"
                    name="roles"
                    options-key="value"
                    options-text="name"
                    @change="roleChange"
                  />
                  <partial-input-error-message :error-message="errors.roles"/>
                </div>
              </div>

              <hr class="my-3">

              <div class="flex flex-wrap">
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input
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
                    is-optional
                    label-title="شماره شبا"
                    name="sheba_number"
                    mask="IR-########################"
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

                  <span class="ml-auto">ایجاد کاربر</span>
                </base-animated-button>

                <div
                  v-if="Object.keys(errors)?.length"
                  class="text-left"
                >
                  <div
                    class="w-full sm:w-auto sm:inline-block text-center text-sm border-2 border-rose-500 bg-rose-50 rounded-full py-1 px-3 mt-2"
                  >
                    (
                    <span>{{ Object.keys(errors)?.length }}</span>
                    )
                    خطا، لطفا بررسی کنید
                  </div>
                </div>
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
import {ArrowLeftCircleIcon, CheckIcon, HashtagIcon, LockClosedIcon, UserIcon} from "@heroicons/vue/24/outline";
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

const selectedRoles = ref([])

function roleChange(selected) {
  selectedRoles.value = selected
}

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    username: yup.string()
      .transform(transformNumbersToEnglish)
      .required('نام کاربری را وارد نمایید.'),
    password: yup.string()
      .transform(transformNumbersToEnglish)
      .matches(/(?=.*\d)/gu, 'کلمه عبور باید شامل حداقل ۱ عدد باشد.')
      .matches(/(?=.*[a-z\u0600-\u06FF])/gu, 'کلمه عبور باید شامل حداقل ۱ کاراکتر از حروف کوچک باشد.')
      .matches(/(?=.*[A-Z\u0600-\u06FF])/gu, 'کلمه عبور باید شامل حداقل ۱ کاراکتر از حروف بزرگ باشد.')
      .min(9, 'کلمه عبور باید حداقل دارای ۹ کاراکتر باشد.')
      .required('کلمه عبور را وارد نمایید.'),
    password_confirmation: yup.string()
      .oneOf([yup.ref('password'), null], 'کلمه عبور با تکرار آن مغایرت دارد.'),
    first_name: yup.string().persian('نام باید از حروف فارسی باشد.').required('نام را وارد نمایید.'),
    last_name: yup.string().persian('نام خانوادگی باید از حروف فارسی باشد.').required('نام خانوادگی را وارد نمایید.'),
    national_code: yup.string()
      .transform(transformNumbersToEnglish)
      .persianNationalCode('کد ملی نامعتبر است.')
      .required('کد ملی را وارد نمایید.'),
    sheba_number: yup.string()
      .transform(transformNumbersToEnglish)
      .optional().nullable(),
  }),
}, (values, actions) => {
  // validate extra inputs
  if (!selectedRoles.value?.length) {
    actions.setFieldError('roles', 'انتخاب حداقل یک نقش اجباری می‌باشد.')
    return
  }

  if (Array.isArray(selectedRoles.value)) {
    for (let i of selectedRoles.value) {
      if (roles.value.map(val => val.value).indexOf(i.value) === -1) {
        actions.setFieldError('roles', 'نقش انتخاب شده نامعتبر می‌باشد.')
        return
      }
    }
    values.roles = selectedRoles.value
  } else {
    if (roles.value.map(val => val.value).indexOf(selectedRoles.value.value) === -1) {
      actions.setFieldError('roles', 'نقش انتخاب شده نامعتبر می‌باشد.')
      return
    }
    values.roles = [selectedRoles.value]
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

      if (error?.errors && Object.keys(error.errors).length >= 1) {
        actions.setErrors(error.errors)
      }

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
