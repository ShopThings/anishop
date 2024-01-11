<template>
  <form @submit.prevent="onSubmit">
    <div class="flex flex-wrap">
      <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
        <base-input label-title="کلمه عبور جدید"
                    type="password"
                    placeholder="شامل حروف و اعداد"
                    name="password">
          <template #icon>
            <LockClosedIcon class="h-6 w-6 text-gray-400"/>
          </template>
        </base-input>
      </div>
      <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
        <base-input label-title="تکرار کلمه عبور جدید"
                    type="password"
                    placeholder="شامل حروف و اعداد"
                    name="password_confirmation">
          <template #icon>
            <LockClosedIcon class="h-6 w-6 text-gray-400"/>
          </template>
        </base-input>
      </div>
    </div>

    <div class="px-2 py-3">
      <base-animated-button
        type="submit"
        class="bg-pink-500 text-white mr-auto px-6 w-full sm:w-auto"
        :disabled="isSubmitting"
      >
        <VTransitionFade>
          <loader-circle
            v-if="isSubmitting"
            main-container-klass="absolute w-full h-full top-0 left-0"
            big-circle-color="border-transparent"
          />
        </VTransitionFade>

        <template #icon="{klass}">
          <LockOpenIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
        </template>

        <span class="ml-auto">ویرایش کلمه عبور</span>
      </base-animated-button>
    </div>
  </form>
</template>

<script setup>
import LoaderCircle from "../../../../components/base/loader/LoaderCircle.vue";
import VTransitionFade from "../../../../transitions/VTransitionFade.vue";
import BaseInput from "../../../../components/base/BaseInput.vue";
import {LockClosedIcon, LockOpenIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "../../../../components/base/BaseAnimatedButton.vue";
import {useForm} from "vee-validate";
import yup, {transformNumbersToEnglish} from "../../../../validation/index.js";
import {useRequest} from "../../../../composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "../../../../router/api-routes.js";
import {computed, ref} from "vue";
import {useRoute, useRouter} from "vue-router";
import {useToast} from "vue-toastification";

const router = useRouter()
const route = useRoute()
const toast = useToast()
const idParam = computed(() => {
  return route.params.id
})

const canSubmit = ref(true)

const {handleSubmit, isSubmitting} = useForm({
  validationSchema: yup.object().shape({
    password: yup.string()
      .transform(transformNumbersToEnglish)
      .matches(/(?=.*\d)/g, 'کلمه عبور باید شامل حداقل ۱ عدد باشد.')
      .matches(/(?=.*[a-z])/g, 'کلمه عبور باید شامل حداقل ۱ کاراکتر از حروف کوچک انگلیسی باشد.')
      .matches(/(?=.*[A-Z])/g, 'کلمه عبور باید شامل حداقل ۱ کاراکتر از حروف بزرگ انگلیسی باشد.')
      .min(9, 'کلمه عبور باید حداقل دارای ۹ کاراکتر باشد.')
      .required('کلمه عبور اجباری می‌باشد.'),
    password_confirmation: yup.string()
      .oneOf([yup.ref('password'), null], 'کلمه عبور با تکرار آن مغایرت دارد.'),
  }),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return

  canSubmit.value = false

  useRequest(apiReplaceParams(apiRoutes.admin.users.update, {
    user: idParam.value,
  }), {
    method: 'PUT',
    data: values,
  }, {
    success: () => {
      toast.success('ویرایش کلمه عبور با موفقیت انجام شد.')
      router.push({name: 'admin.logout', query: {redirect: route.path}})
      return false
    },
    error: (error) => {
      if (error.errors && Object.keys(error.errors).length >= 1)
        actions.setErrors(error.errors)

      return false
    },
    finally: function () {
      actions.resetForm();
      canSubmit.value = true
    },
  })
})
</script>
