<template>
  <partial-card>
    <template #header>
      ویرایش رنگ -
      <span
          v-if="color?.id"
          class="text-teal-600"
      >{{ color?.name }}</span>
    </template>
    <template #body>
      <div class="p-3">
        <base-loading-panel
            :loading="loading"
            type="form"
        >
          <template #content>
            <form @submit.prevent="onSubmit">
              <div class="flex flex-wrap items-end justify-between">
                <div class="w-full p-2 sm:w-1/2">
                  <base-input
                      label-title="نام رنگ"
                      placeholder="نام رنگ را وارد نمایید"
                      name="name"
                      :value="color?.name"
                  >
                    <template #icon>
                      <EyeDropperIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="p-2">
                  <base-switch
                      label="عدم نمایش رنگ"
                      on-label="نمایش رنگ"
                      name="is_published"
                      :enabled="color?.is_published"
                      sr-text="نمایش/عدم نمایش رنگ"
                      @change="(status) => {publishStatus=status}"
                  />
                </div>
              </div>

              <div class="p-2 flex">
                <partial-input-label title="انتخاب رنگ"/>
                <color-picker
                    v-model:pureColor="pureColor"
                    :disable-alpha="true"
                    format="hex6"
                    lang="En"
                />
                <partial-input-error-message :error-message="errors.color_hex"/>
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

                  <span class="ml-auto">ویرایش رنگ</span>
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
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {EyeDropperIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import {useToast} from "vue-toastification";
import {useFormSubmit} from "@/composables/form-submit.js";
import {ColorAPI} from "@/service/APIProduct.js";
import {getRouteParamByKey} from "@/composables/helper.js";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";

const toast = useToast()
const idParam = getRouteParamByKey('id')

const loading = ref(true)

const color = ref(null)
const publishStatus = ref(true)
const pureColor = ref('#ffffff')

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    name: yup.string().required('نام رنگ را وارد نمایید.'),
    is_published: yup.boolean().required('وضعیت انتشار را مشخص کنید.'),
  }),
}, (values, actions) => {
  if (!canSubmit.value) return

  if (!isValidColorHex(pureColor.value)) {
    actions.setFieldError('color_hex', 'کد رنگی انتخاب شده نامعتبر می‌باشد.')
    return
  }

  canSubmit.value = false

  ColorAPI.updateById(idParam.value, {
    name: values.name,
    hex: pureColor.value,
    is_published: publishStatus.value,
  }, {
    success(response) {
      setFormFields(response.data)
      toast.success('ویرایش اطلاعات با موفقیت انجام شد.')
    },
    finally() {
      canSubmit.value = true
    },
  })
})

onMounted(() => {
  ColorAPI.fetchById(idParam.value, {
    success(response) {
      setFormFields(response.data)
      loading.value = false
    },
  })
})

function setFormFields(item) {
  color.value = item
  pureColor.value = item.hex
}
</script>
