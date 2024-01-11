<template>
  <partial-card>
    <template #header>
      ویرایش تخصیص ویژگی به دسته‌بندی
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
                <div class="w-full p-2 sm:w-1/2 lg:w-5/12 xl:w-5/12">
                  <partial-input-label title="انتخاب ویژگی"/>
                  <base-select-searchable
                    :options="attributes"
                    options-key="id"
                    options-text="title"
                    name="attribute"
                    @change="(selected) => {selectedAttr = selected}"
                  />
                  <partial-input-error-message :error-message="errors.attribute"/>
                </div>
                <div class="w-full p-2 sm:w-1/2 lg:w-4/12 xl:w-5/12">
                  <partial-input-label title="انتخاب دسته‌بندی"/>
                  <base-select-searchable
                    :options="categories"
                    options-key="id"
                    options-text="name"
                    name="category"
                    @change="(selected) => {selectedCategory = selected}"
                  />
                  <partial-input-error-message :error-message="errors.attribute"/>
                </div>
                <div class="w-full p-2 sm:w-1/2 lg:w-3/12 xl:w-2/12">
                  <base-input
                    type="number"
                    :min="0"
                    label-title="اولویت"
                    placeholder="وارد نمایید"
                    name="priority"
                    :value="categoryAttribute?.priority"
                  >
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
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

                  <span class="ml-auto">ویرایش تخصیص ویژگی</span>
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
import yup from "../../../validation/index.js";
import BaseLoadingPanel from "../../../components/base/BaseLoadingPanel.vue";
import PartialCard from "../../../components/partials/PartialCard.vue";
import PartialInputLabel from "../../../components/partials/PartialInputLabel.vue";
import LoaderCircle from "../../../components/base/loader/LoaderCircle.vue";
import VTransitionFade from "../../../transitions/VTransitionFade.vue";
import {CheckIcon, ArrowLeftCircleIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "../../../components/base/BaseAnimatedButton.vue";
import BaseInput from "../../../components/base/BaseInput.vue";
import {useRequest} from "../../../composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "../../../router/api-routes.js";
import {useRoute, useRouter} from "vue-router";
import {useToast} from "vue-toastification";
import BaseSelectSearchable from "../../../components/base/BaseSelectSearchable.vue";
import PartialInputErrorMessage from "../../../components/partials/PartialInputErrorMessage.vue";

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

const categoryAttribute = ref(null)

const attributes = ref([])
const selectedAttr = ref(null)

const categories = ref([])
const selectedCategory = ref(null)

const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return
})

onMounted(() => {
  // useRequest(apiReplaceParams(apiRoutes.admin.productAttributeCategories.show, {product_attribute_category: idParam.value}), null, {
  //     success: (response) => {
  //         categoryAttribute.value = response.data
  //         selectedAttr.value = response.data.attribute
  //         selectedCategory.value = response.data.category
  //
  //         loading.value = false
  //     },
  // })
  //
  // useRequest(apiRoutes.admin.productAttributes.index, null, {
  //     success: (response) => {
  //         attributes.value = response.data
  //     },
  // })
  //
  // useRequest(apiRoutes.admin.categories.index, null, {
  //     success: (response) => {
  //         categories.value = response.data
  //
  //     },
  // })
})
</script>
