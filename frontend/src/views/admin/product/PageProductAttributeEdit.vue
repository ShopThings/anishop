<template>
  <partial-card>
    <template #header>
      ویرایش ویژگی جستجوی
      <span
        v-if="product?.id"
        class="text-slate-400 text-base"
      >{{ product?.title }}</span>
    </template>
    <template #body>
      <div class="p-3">
        <base-loading-panel
          :loading="loading"
          type="form"
        >
          <template #content>
            <form
              v-if="productAttributes?.length"
              @submit.prevent="onSubmit"
            >
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
                    :name="'attr' + attr.id"
                    :options="attr.attr_values"
                    :selected="attr.product_attr_value[attr.id]"
                    options-key="id"
                    options-text="product_attribute_value.attribute_value"
                    @change="(t) => {attr.product_attr_value[attr.id] = t}"
                  />
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

                  <span class="ml-auto">ویرایش ویژگی‌های محصول</span>
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

            <div v-else>
              <partial-empty-rows
                image="/images/empty-statuses/knowledge.svg"
                imageClass="!w-60"
                message="هیچ ویژگی جستجویی پیدا نشد!"
              />

              <span class="text-lg text-slate-400 mt-4">برای تغییر ویژگی‌های جستجوی محصول</span>
              <ul class="flex flex-col gap-4 my-6 pr-3">
                <li class="flex gap-2">
                  <span class="rounded-full bg-slate-200 h-7 min-w-7 text-center text-lg">1</span>
                  <div>
                    <router-link
                      :to="{name: 'admin.search.attrs'}"
                      class="text-blue-600 hover:text-opacity-90"
                    >
                      ویژگی‌های جستجوی
                    </router-link>
                    دلخواه را اضافه نمایید.
                  </div>
                </li>

                <li class="flex gap-2">
                  <span class="rounded-full bg-slate-200 h-7 min-w-7 text-center text-lg">2</span>
                  <div>
                    به ازای هر ویژگی جستجو، مقادیر مورد نظر خود را برای آن وارد کنید.
                  </div>
                </li>

                <li class="flex gap-2">
                  <span class="rounded-full bg-slate-200 h-7 min-w-7 text-center text-lg">3</span>
                  <div>
                    سپس
                    <router-link
                      :to="{name: 'admin.search.attrs.categories'}"
                      class="text-blue-600 hover:text-opacity-90"
                    >
                      ویژگی‌های جستجو را به دسته‌بندی
                    </router-link>
                    مورد نظر متصل کنید.
                  </div>
                </li>
              </ul>
            </div>
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
import PartialEmptyRows from "@/components/partials/PartialEmptyRows.vue";

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
  canSubmit.value = false

  ProductAttributeProductAPI.create({
    values: selectedAttrValues.value,
  }, {
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
  ProductAttributeProductAPI.fetchById(slugParam.value, {
    success(response) {
      productAttributes.value = response.data
      loading.value = false
    },
  })

  ProductAPI.fetchById(slugParam.value, {
    success: (response) => {
      product.value = response.data
    },
  })
})
</script>
