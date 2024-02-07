<template>
  <partial-card>
    <template #header>
      ایجاد رنگ جدید
    </template>
    <template #body>
      <div class="p-3">
        <form @submit.prevent="onSubmit">
          <div class="flex flex-wrap items-end justify-between">
            <div class="w-full p-2 sm:w-1/2">
              <base-input
                  label-title="نام رنگ"
                  placeholder="نام رنگ را وارد نمایید"
                  name="name"
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
                  :enabled="true"
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

              <span class="ml-auto">افزودن رنگ</span>
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
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {EyeDropperIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import {ColorAPI} from "@/service/APIProduct.js";
import {useRouter} from "vue-router";

const router = useRouter()

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

  ColorAPI.create({
    name: values.name,
    hex: pureColor.value,
    is_published: publishStatus.value,
  }, {
    success() {
      actions.resetForm()
      router.push({name: 'admin.colors'});
    },
    finally() {
      canSubmit.value = true
    },
  })
})
</script>
