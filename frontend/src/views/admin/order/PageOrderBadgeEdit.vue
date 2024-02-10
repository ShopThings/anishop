<template>
  <partial-card>
    <template #header>
      ویرایش برچسب سفارش -
      <span
        v-if="badge?.id"
        class="text-teal-600"
      >{{ badge?.title }}</span>
    </template>
    <template #body>
      <div class="p-3">
        <base-loading-panel
          :loading="loading"
          type="form"
        >
          <template #content>
            <form @submit.prevent="onSubmit">
              <div class="flex flex-wrap items-end">
                <div class="p-2 w-full md:w-1/2">
                  <base-input
                    label-title="عنوان"
                    placeholder="وارد نمایید"
                    name="title"
                    :value="badge?.title"
                    :has-edit-mode="false"
                    :is-editable="badge?.is_title_editable"
                  >
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                    <template #editModeLabel="{value}">
                      <span class="py-1 px-3 border-2 border-sky-300 rounded bg-sky-100">{{ value }}</span>
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
                    :enabled="badge?.is_published"
                    sr-text="نمایش/عدم نمایش برچسب"
                    @change="(status) => {publishStatus=status}"
                  />
                </div>
                <div class="p-2 md:w-1/3">
                  <base-switch
                    label="برچسب، وضعیت نهایی است"
                    name="is_end_badge"
                    :enabled="badge?.is_end_badge"
                    sr-text="برچسب، وضعیت نهایی می‌باشد یا خیر"
                    @change="(status) => {endBadgeStatus=status}"
                  />
                </div>
                <div class="p-2 md:w-1/3">
                  <base-switch
                    label="بازگشت محصول به انبار"
                    name="should_return_order_product"
                    :enabled="badge?.should_return_order_product"
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

                  <span class="ml-auto">ویرایش برچسب</span>
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
import yup, {isValidColorHex} from "@/validation/index.js";
import PartialCard from "@/components/partials/PartialCard.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {ArrowLeftCircleIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import {useToast} from "vue-toastification";
import {useFormSubmit} from "@/composables/form-submit.js";
import {getRouteParamByKey} from "@/composables/helper.js";
import {OrderBadgeAPI} from "@/service/APIOrder.js";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";

const toast = useToast()
const idParam = getRouteParamByKey('id')

const loading = ref(true)
const badge = ref(null)

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

  OrderBadgeAPI.updateById(idParam.value, {
    title: values.title,
    color_hex: pureColor.value,
    should_return_order_product: shouldReturnToStockStatus.value,
    is_end_badge: endBadgeStatus.value,
    is_published: publishStatus.value,
  }, {
    success(response) {
      setFormFields(response.data)
      toast.success('ویرایش اطلاعات با موفقیت انجام شد.')
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

onMounted(() => {
  OrderBadgeAPI.fetchById(idParam.value, {
    success: (response) => {
      setFormFields(response.data)
      loading.value = false
    },
  })
})

function setFormFields(item) {
  badge.value = item
  pureColor.value = item.color_hex
  shouldReturnToStockStatus.value = item.should_return_order_product
  endBadgeStatus.value = item.is_end_badge
  publishStatus.value = item.is_published
}
</script>
