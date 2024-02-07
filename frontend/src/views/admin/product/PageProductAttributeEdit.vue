<template>
  <partial-card>
    <template #header>
      ویرایش ویژگی جستجوی
      <span
          v-if="product?.id"
          class="text-teal-600"
      >{{ product?.title }}</span>
    </template>
    <template #body>
      <div class="p-3">
        <base-loading-panel
            :loading="loading"
            type="form"
        >
          <template #content>
            <form @submit.prevent="onSubmit">
              <partial-error-message
                  v-if="errors.values"
                  :has-close="false"
              >
                {{ errors.values }}
              </partial-error-message>

              <div class="flex flex-wrap">
                <div
                    v-for="(attr) in productAttributes"
                    class="w-full p-2 sm:w-1/2 xl:w-1/3"
                >
                  <partial-input-label :title="attr.title"/>
                  <base-select
                      :options="attr.attr_values"
                      options-key="id"
                      options-text="product_attribute_value.attribute_value"
                      :selected="attr.product_attr_value[attr.id]"
                      :name="'attr' + attr.id"
                      @change="(t) => {attr.product_attr_value[attr.id] = t}"
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

                  <span class="ml-auto">ویرایش ویژگی‌های محصول</span>
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
import yup from "@/validation/index.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {CheckIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import {useToast} from "vue-toastification";
import {useRouter} from "vue-router";
import BaseSelect from "@/components/base/BaseSelect.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import {getRouteParamByKey} from "@/composables/helper.js";
import {useFormSubmit} from "@/composables/form-submit.js";
import {ProductAPI, ProductAttributeProductAPI} from "@/service/APIProduct.js";
import PartialErrorMessage from "@/components/partials/message/PartialErrorMessage.vue";

const router = useRouter()
const toast = useToast()
const slugParam = getRouteParamByKey('slug', null, false)

const loading = ref(true)

const product = ref(null)
const productAttributes = ref(null)
const selectedAttrValues = ref([])

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({}),
}, (values, actions) => {
  if (!canSubmit.value) return

  canSubmit.value = false

  ProductAttributeProductAPI.create({
    values: selectedAttrValues.value,
  }, {
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
  ProductAttributeProductAPI.fetchById(slugParam.value, {
    success(response) {
      console.log(response.data)

      // productAttributes.value = response.data
      // loading.value = false
    },
  })

  ProductAPI.fetchById(slugParam.value, {
    success: (response) => {
      product.value = response.data
    },
  })
})
</script>
