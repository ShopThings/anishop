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
                  placeholder="وارد نمایید"
                  name="title"
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

              <span class="ml-auto">افزودن ویژگی</span>
            </base-animated-button>
          </div>
        </form>
      </div>
    </template>
  </partial-card>
</template>

<script setup>
import {ref} from "vue";
import {useForm} from "vee-validate";
import yup from "../../../validation/index.js";
import LoaderCircle from "../../../components/base/loader/LoaderCircle.vue";
import VTransitionFade from "../../../transitions/VTransitionFade.vue";
import {ArrowLeftCircleIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "../../../components/base/BaseAnimatedButton.vue";
import PartialCard from "../../../components/partials/PartialCard.vue";
import BaseInput from "../../../components/base/BaseInput.vue";
import {apiReplaceParams, apiRoutes} from "../../../router/api-routes.js";
import {useRequest} from "../../../composables/api-request.js";
import {useToast} from "vue-toastification";
import {useRouter} from "vue-router";
import BaseSelect from "../../../components/base/BaseSelect.vue";
import PartialInputErrorMessage from "../../../components/partials/PartialInputErrorMessage.vue";
import PartialInputLabel from "../../../components/partials/PartialInputLabel.vue";
import {PRODUCT_ATTRIBUTE_TYPES} from "../../../composables/constants.js";

const router = useRouter()
const toast = useToast()

const canSubmit = ref(true)

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
</script>

<style scoped>

</style>
