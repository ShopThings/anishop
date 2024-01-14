<template>
  <partial-card>
    <template #header>
      ویرایش ویژگی جستجو -
      <span
        v-if="attribute?.id"
        class="text-teal-600"
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
                    label-title="عنوان"
                    placeholder="وارد نمایید"
                    name="title"
                    :value="attribute?.title"
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
                    options-key="value"
                    options-text="name"
                    :selected="selectedType"
                    name="type"
                    @change="(t) => {selectedType = t}"
                  />
                  <partial-input-error-message :error-message="errors.type"/>
                </div>
              </div>

              <div class="px-2 py-3">
                <base-animated-button
                  type="submit"
                  class="bg-emerald-500 text-white mr-auto px-6 w-full sm:w-auto"
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
                    <CheckIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
                  </template>

                  <span class="ml-auto">ویرایش ویژگی</span>
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
import {computed, onMounted, ref} from "vue";
import {useForm} from "vee-validate";
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

const router = useRouter()
const route = useRoute()
const toast = useToast()
const idParam = computed(() => {
  const id = parseInt(route.params.id, 10)
  if (isNaN(id)) return route.params.id
  return id
})

const loading = ref(false)
const canSubmit = ref(true)

const attribute = ref(null)

const types = ref([
  {
    value: PRODUCT_ATTRIBUTE_TYPES.MULTI_SELECT.value,
    name: PRODUCT_ATTRIBUTE_TYPES.MULTI_SELECT.text,
  },
  {
    value: PRODUCT_ATTRIBUTE_TYPES.SINGLE_SELECT.value,
    name: PRODUCT_ATTRIBUTE_TYPES.SINGLE_SELECT.text,
  },
])
const selectedType = ref(null)

const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return
})

onMounted(() => {
  // useRequest(apiReplaceParams(apiRoutes.admin.productAttributeValues.show, {product_attribute_value: idParam.value}), null, {
  //     success: (response) => {
  //         attribute.value = response.data
  //         selectedType.value = {
  //             value: response.data.type,
  //             name: response.data.type_name,
  //         }
  //
  //         loading.value = false
  //     },
  // })
})
</script>
