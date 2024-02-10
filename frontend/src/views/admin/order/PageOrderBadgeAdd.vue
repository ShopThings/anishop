<template>
  <partial-card>
    <template #header>
      ایجاد برچسب سفارش جدید
    </template>
    <template #body>
      <div class="p-3">
        <form @submit.prevent="onSubmit">
          <div class="flex flex-wrap items-end">
            <div class="p-2 w-full md:w-1/2">
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
            <div class="p-2 flex md:w-1/2 items-center">
              <partial-input-label
                title="انتخاب رنگ"
                class="grow sm:grow-0 mb-0"
              />
              <color-picker
                v-model:pureColor="pureColor"
                :disable-alpha="true"
                format="hex6"
                lang="En"
              />
              <partial-input-error-message :error-message="errors.color_hex"/>
            </div>
          </div>

          <div class="sm:flex sm:flex-wrap sm:justify-between">
            <div class="p-2 md:w-1/3">
              <base-switch
                label="نمایش برچسب"
                name="is_published"
                :enabled="true"
                sr-text="نمایش/عدم نمایش برچسب"
                @change="(status) => {publishStatus=status}"
              />
            </div>
            <div class="p-2 md:w-1/3">
              <base-switch
                label="برچسب، وضعیت نهایی است"
                name="is_end_badge"
                :enabled="true"
                sr-text="برچسب، وضعیت نهایی می‌باشد یا خیر"
                @change="(status) => {endBadgeStatus=status}"
              />
            </div>
            <div class="p-2 md:w-1/3">
              <base-switch
                label="بازگشت محصول به انبار"
                name="should_return_order_product"
                :enabled="true"
                sr-text="بازگشت محصول به انبار/عدم بازگشت محصول به انبار"
                @change="(status) => {shouldReturnToStockStatus=status}"
              />
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

              <span class="ml-auto">افزودن برچسب</span>
            </base-animated-button>
          </div>
        </form>
      </div>
    </template>
  </partial-card>
</template>

<script setup>
import {ref} from "vue";
import yup, {isValidColorHex} from "@/validation/index.js";
import PartialCard from "@/components/partials/PartialCard.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {ArrowLeftCircleIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {OrderBadgeAPI} from "@/service/APIOrder.js";
import {useRouter} from "vue-router";

const router = useRouter()

const publishStatus = ref(true)
const endBadgeStatus = ref(false)
const shouldReturnToStockStatus = ref(true)
const pureColor = ref('#000000')

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    title: yup.string().required('عنوان برچسب را وارد نمایید.'),
    is_published: yup.boolean().required('وضعیت انتشار را مشخص کنید.'),
    is_end_badge: yup.boolean().required('وضعیت نهایی را مشخص کنید.'),
    should_return_order_product: yup.boolean().required('وضعیت بازگشت محصول به انبار را مشخص کنید.'),
  }),
}, (values, actions) => {
  if (!canSubmit.value) return

  if (!isValidColorHex(pureColor.value)) {
    actions.setFieldError('color_hex', 'کد رنگی انتخاب شده نامعتبر می‌باشد.')
    return
  }

  canSubmit.value = false

  OrderBadgeAPI.create({
    title: values.title,
    color_hex: pureColor.value,
    should_return_order_product: shouldReturnToStockStatus.value,
    is_end_badge: endBadgeStatus.value,
    is_published: publishStatus.value,
  }, {
    success() {
      actions.resetForm()
      pureColor.value = '#000000'
      shouldReturnToStockStatus.value = false
      endBadgeStatus.value = false
      publishStatus.value = false

      router.push({name: 'admin.orders.badges'})
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
