<template>
  <partial-card>
    <template #header>
      افزودن ویژگی جستجو
    </template>
    <template #body>
      <div class="p-3">
        <form @submit.prevent="onSubmit">
          <div class="flex flex-wrap">
            <div class="w-full p-2 sm:w-1/2">
              <base-input
                label-title="عنوان"
                name="title"
                placeholder="وارد نمایید"
              >
                <template #icon>
                  <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                </template>
              </base-input>
            </div>
            <div class="w-full p-2 sm:w-1/2">
              <partial-input-label title="نوع ویژگی"/>
              <base-select
                :options="types"
                name="type"
                options-key="value"
                options-text="name"
                @change="(t) => {selectedType = t}"
              />
              <partial-input-error-message :error-message="errors.type"/>
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

              <span class="ml-auto">افزودن ویژگی</span>
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
      </div>
    </template>
  </partial-card>
</template>

<script setup>
import {ref} from "vue";
import yup from "@/validation/index.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {ArrowLeftCircleIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import {useRouter} from "vue-router";
import BaseSelect from "@/components/base/BaseSelect.vue";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import {PRODUCT_ATTRIBUTE_TYPES} from "@/composables/constants.js";
import {useFormSubmit} from "@/composables/form-submit.js";
import {ProductAttributeAPI} from "@/service/APIProduct.js";

const router = useRouter()

const types = [
  {
    value: PRODUCT_ATTRIBUTE_TYPES.MULTI_SELECT.value,
    name: PRODUCT_ATTRIBUTE_TYPES.MULTI_SELECT.text,
  },
  {
    value: PRODUCT_ATTRIBUTE_TYPES.SINGLE_SELECT.value,
    name: PRODUCT_ATTRIBUTE_TYPES.SINGLE_SELECT.text,
  },
]
const selectedType = ref(null)

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    title: yup.string().required('عنوان ویژگی را وارد نمایید.'),
  }),
}, (values, actions) => {
  if (!selectedType.value || types.findIndex(item => item.value === selectedType.value?.value) === -1) {
    actions.setFieldError('type', 'نوع ویژگی را انتخاب نمایید.')
    return
  }

  canSubmit.value = false

  ProductAttributeAPI.create({
    title: values.title,
    type: selectedType.value.value,
  }, {
    success() {
      actions.resetForm();
      router.push({name: 'admin.search.attrs'})
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
</script>
