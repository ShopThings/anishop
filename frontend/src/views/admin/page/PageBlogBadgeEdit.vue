<template>
  <partial-card>
    <template #header>
      ویرایش برچسب دیدگاه
    </template>
    <template #body>
      <div class="p-3">
        <base-loading-panel
          :loading="loading"
          type="form"
        >
          <template #content>
            <form @submit.prevent="onSubmit">
              <div class="p-2">
                <base-switch
                  :enabled="badge?.is_published"
                  label="عدم نمایش برچسب"
                  name="is_published"
                  on-label="نمایش برچسب"
                  sr-text="نمایش/عدم نمایش برچسب دیدگاه"
                  @change="(status) => {publishStatus=status}"
                />
              </div>

              <div class="flex flex-wrap items-end">
                <div class="w-full p-2 sm:w-1/2">
                  <base-input
                    :value="badge?.title"
                    label-title="نام"
                    name="title"
                    placeholder="وارد نمایید"
                  >
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="flex p-2">
                  <partial-input-label title="انتخاب رنگ"/>
                  <color-picker
                    v-model:pureColor="pureColor"
                    :disable-alpha="true"
                    format="hex6"
                    lang="En"
                  />
                  <partial-input-error-message :error-message="errors.color_hex"/>
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

                  <span class="ml-auto">ایجاد برچسب</span>
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
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import yup, {isValidColorHex} from "@/validation/index.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import {ArrowLeftCircleIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import {useToast} from "vue-toastification";
import {useFormSubmit} from "@/composables/form-submit.js";
import {BlogBadgeAPI} from "@/service/APIBlog.js";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import {getRouteParamByKey} from "@/composables/helper.js";

const toast = useToast()
const idParam = getRouteParamByKey('id')

const loading = ref(true)
const badge = ref(null)

const publishStatus = ref(true)
const pureColor = ref('#000000')

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    title: yup.string().required('عنوان برچسب را وارد نمایید.'),
    is_published: yup.boolean().required('وضعیت انتشار را مشخص کنید.'),
  }),
}, (values, actions) => {
  if (!isValidColorHex(pureColor.value)) {
    actions.setFieldError('color_hex', 'کد رنگی انتخاب شده نامعتبر می‌باشد.')
    return
  }

  canSubmit.value = false

  BlogBadgeAPI.create({
    title: values.title,
    color_hex: pureColor.value,
    is_published: publishStatus.value,
  }, {
    success(response) {
      setFormFields(response.data)
      toast.success('ویرایش اطلاعات با موفقیت انجام شد.')
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
  BlogBadgeAPI.fetchById(idParam.value, {
    success: (response) => {
      setFormFields(response.data)
      loading.value = false
    },
  })
})

function setFormFields(item) {
  badge.value = item
  pureColor.value = item.color_hex
  publishStatus.value = item.is_published
}
</script>
