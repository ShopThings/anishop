<template>
  <partial-card>
    <template #header>
      ویرایش ویژگی جستجو -
      <span
        v-if="attribute?.id"
        class="text-slate-400 text-base"
      >{{ attribute?.title }}</span>
    </template>
    <template #body>
      <div class="p-3">
        <base-loading-panel
          :loading="loading"
          type="form"
        >
          <template #content>
            <form @submit.prevent="onSubmit">
              <div class="flex flex-wrap">
                <div class="w-full p-2 sm:w-1/2">
                  <base-input
                    :value="attribute?.title"
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
                    :selected="selectedType"
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

                  <span class="ml-auto">ویرایش ویژگی</span>
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
import yup from "@/validation/index.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {ArrowLeftCircleIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import {useToast} from "vue-toastification";
import {useRoute, useRouter} from "vue-router";
import BaseSelect from "@/components/base/BaseSelect.vue";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import {PRODUCT_ATTRIBUTE_TYPES} from "@/composables/constants.js";
import {useFormSubmit} from "@/composables/form-submit.js";
import {getRouteParamByKey} from "@/composables/helper.js";
import {ProductAttributeAPI} from "@/service/APIProduct.js";

const router = useRouter()
const route = useRoute()
const toast = useToast()
const idParam = getRouteParamByKey('id')

const loading = ref(true)
const attribute = ref(null)

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
  if (!selectedType.value || !types.includes(selectedType.value)) {
    actions.setFieldError('type', 'نوع ویژگی را انتخاب نمایید.')
    return
  }

  canSubmit.value = false

  ProductAttributeAPI.create({
    title: values.title,
    type: selectedType.value.value,
  }, {
    success(response) {
      toast.success('ویرایش ویژگی با موفقیت انجام شد.')
      setFormFields(response.data)
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
  ProductAttributeAPI.fetchById(idParam.value, {
    success: (response) => {
      setFormFields(response.data)
      loading.value = false
    },
  })
})

function setFormFields(item) {
  attribute.value = item
  selectedType.value = {
    value: item.type,
    name: item.type_name,
  }
}
</script>
