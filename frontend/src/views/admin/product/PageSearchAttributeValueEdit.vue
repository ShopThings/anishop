<template>
  <base-loading-panel
      :loading="loading"
      type="form"
  >
    <template #content>
      <partial-card>
        <template #header>
          ویرایش تخصیص مقدار به ویژگی با عنوان
          <span
              v-if="attribute?.id"
              class="text-teal-600"
          >{{ attribute?.title }}</span>
        </template>
        <template #body>
          <div class="p-3">
            <form @submit.prevent="onSubmit">
              <div class="flex flex-wrap items-end justify-between">
                <div class="w-full p-2 sm:w-1/2">
                  <base-input
                      label-title="عنوان"
                      placeholder="وارد نمایید"
                      name="title"
                      :value="attributeValue?.attribute_value"
                  >
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="w-full p-2 sm:w-1/2">
                  <base-input
                      type="text"
                      :min="0"
                      :money-mask="true"
                      label-title="اولویت"
                      placeholder="وارد نمایید"
                      name="priority"
                      :value="attributeValue?.priority?.toString()"
                  >
                    <template #icon>
                      <HashtagIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
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

                  <span class="ml-auto">ویرایش مقدار</span>
                </base-animated-button>
              </div>
            </form>
          </div>
        </template>
      </partial-card>
    </template>
  </base-loading-panel>
</template>

<script setup>
import {onMounted, ref} from "vue";
import yup from "@/validation/index.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {ArrowLeftCircleIcon, CheckIcon, HashtagIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import {useToast} from "vue-toastification";
import {useFormSubmit} from "@/composables/form-submit.js";
import {getRouteParamByKey} from "@/composables/helper.js";
import {ProductAttributeAPI, ProductAttributeValueAPI} from "@/service/APIProduct.js";

const toast = useToast()
const idParam = getRouteParamByKey('id')
const valParam = getRouteParamByKey('val')

const loading = ref(true)

const attribute = ref(null)
const attributeValue = ref(null)

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    title: yup.string().required('عنوان مقدار را وارد نمایید.'),
    priority: yup.number()
        .min(0, 'مقدار اولویت باید بزرگتر از صفر باشد.')
        .required('اولویت را وارد نمایید.'),
  }),
}, (values, actions) => {
  if (!canSubmit.value) return

  canSubmit.value = false

  ProductAttributeValueAPI.updateById(valParam.value, {
    product_attribute: idParam.value,
    attribute_value: values.title,
    priority: values.priority,
  }, {
    success(response) {
      attributeValue.value = response.data
    },
    error(error) {
      if (error.errors && Object.keys(error.errors).length >= 1) {
        actions.setFieldError('title', error.errors?.attribute_value)
        actions.setFieldError('priority', error.errors?.priority)
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
      attribute.value = response.data
    },
  })

  ProductAttributeValueAPI.fetchById(valParam.value, {
    success: (response) => {
      attributeValue.value = response.data
      loading.value = false
    },
  })
})
</script>
