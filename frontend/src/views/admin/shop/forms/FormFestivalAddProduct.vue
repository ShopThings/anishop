<template>
  <partial-card>
    <template #header>
      افزودن محصول به جشنواره
    </template>

    <template #body>
      <form @submit.prevent="onSubmit">
        <div class="w-full p-2">
          <partial-input-label title="انتخاب محصول"/>
          <base-select-searchable
              :options="products"
              options-key="id"
              options-text="title"
              name="product"
              :multiple="true"
              :is-loading="loading"
              :is-local-search="false"
              placeholder="جستجوی محصول..."
              @change="productSelectionChange"
              @query="searchProduct"
          />
          <partial-input-error-message :error-message="errors.product"/>
        </div>

        <div class="px-2 py-3">
          <base-animated-button
              type="submit"
              class="bg-blue-500 text-white mr-auto px-6 w-full sm:w-auto"
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
              <CheckCircleIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
            </template>

            <span class="ml-auto">افزودن محصول</span>
          </base-animated-button>
        </div>
      </form>
    </template>
  </partial-card>
</template>

<script setup>
import {ref} from "vue";
import {useForm} from "vee-validate";
import yup from "../../../../validation/index.js";
import PartialCard from "../../../../components/partials/PartialCard.vue";
import LoaderCircle from "../../../../components/base/loader/LoaderCircle.vue";
import VTransitionFade from "../../../../transitions/VTransitionFade.vue";
import {CheckCircleIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "../../../../components/base/BaseAnimatedButton.vue";
import BaseSelectSearchable from "../../../../components/base/BaseSelectSearchable.vue";
import PartialInputErrorMessage from "../../../../components/partials/PartialInputErrorMessage.vue";
import PartialInputLabel from "../../../../components/partials/PartialInputLabel.vue";
import {apiRoutes} from "../../../../router/api-routes.js";
import {useRequest} from "../../../../composables/api-request.js";

const loading = ref(true)
const canSubmit = ref(true)

const products = ref({})
const selectedProduct = ref(null)

function productSelectionChange(selected) {
  selectedProduct.value = selected
}

function searchProduct(query) {
  // useRequest(apiRoutes.admin.products.index, {
  //     data: {
  //         query,
  //     },
  // }, {
  //     success: (response) => {
  //         products.value = response.data
  //     },
  //     finally: () => {
  //         loading.value = false
  //     }
  // })
}

const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return
})
</script>

<style scoped>

</style>
