<template>
  <partial-card>
    <template #header>
      ایجاد جشنواره جدید
    </template>
    <template #body>
      <div class="p-3">
        <form @submit.prevent="onSubmit">
          <div class="p-2">
            <base-switch
              label="عدم نمایش جشنواره"
              on-label="نمایش جشنواره"
              name="is_published"
              :enabled="true"
              sr-text="نمایش/عدم نمایش جشنواره"
              @change="(status) => {publishStatus=status}"
            />
          </div>

          <div class="flex flex-wrap items-end">
            <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
              <base-input
                label-title="عنوان"
                placeholder="وارد نمایید"
                name="title"
              >
                <template #icon>
                  <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                </template>
              </base-input>
            </div>
            <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
              <partial-input-label title="تاریخ شروع" :is-optional="true"/>
              <date-picker
                v-model="startDate"
                placeholder="انتخاب تاریخ شروع"
              />
              <partial-input-error-message :error-message="errors.start_at"/>
            </div>
            <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
              <partial-input-label title="تاریخ پایان" :is-optional="true"/>
              <date-picker
                v-model="endDate"
                placeholder="انتخاب تاریخ پایان"
              />
              <partial-input-error-message :error-message="errors.end_at"/>
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

              <span class="ml-auto">افزودن جشنواره</span>
            </base-animated-button>
          </div>
        </form>
      </div>
    </template>
  </partial-card>
</template>

<script setup>
import {ref} from "vue";
import yup from "@/validation/index.js";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {CheckIcon, ArrowLeftCircleIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {FestivalAPI} from "@/service/APIShop.js";
import {useRouter} from "vue-router";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";

const router = useRouter()

const publishStatus = ref(true)
const startDate = ref(null)
const endDate = ref(null)

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    is_published: yup.boolean().required('وضعیت انتشار را مشخص کنید.'),
    title: yup.string().required('عنوان جشنواره را وارد نمایید.'),
  }),
}, (values, actions) => {
  if (!canSubmit.value) return

  if (startDate.value && endDate.value) {
    const start = new Date(startDate.value)
    const end = new Date(endDate.value)

    if (start > end) {
      actions.setFieldError('start_at', 'تاریخ شروع بزرگتر تاریخ پایان می‌باشد!')
      return
    }
  }

  canSubmit.value = false

  FestivalAPI.create({
    title: values.title,
    start_at: startDate.value,
    end_at: endDate.value,
    is_published: publishStatus.value,
  }, {
    success() {
      actions.resetForm()
      router.push({name: 'admin.festivals'})
    },
    error(error) {
      if (error.errors && Object.keys(error.errors).length >= 1)
        actions.setErrors(error.errors)

      return false
    },
    finally() {
      canSubmit.value = true
    },
  })
})
</script>
