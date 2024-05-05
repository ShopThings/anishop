<template>
  <base-loading-panel
    :loading="loading"
    loading-text="در حال بارگذاری محصولات"
    type="circle"
  >
    <template #content>
      <base-accordion
        btn-class="bg-white border-2 border-blue-400 hover:shadow-lg focus-visible:ring-blue-800"
        panel-class="max-h-96 overflow-auto my-custom-scrollbar"
      >
        <template #button>
          محصولات انتخاب شده
        </template>

        <template #panel>
          <div
            v-if="products && products.length"
            class="grid grid-cols-1 gap-3"
          >
            <partial-card
              v-for="(product, idx) in products"
              :key="product.id"
            >
              <template #body>
                <div class="flex items-center justify-between">
                  <div class="flex items-center">
                    <router-link
                      :to="{name: 'admin.product.detail', params: {slug: product.slug}}"
                      class="p-2 shrink-0"
                      target="_blank"
                    >
                      <base-lazy-image
                        :alt="product.title"
                        :lazy-src="product.image.path"
                        :size="FileSizes.SMALL"
                        class="!w-20 ml-3 mb-0 h-auto hover:scale-95 transition shrink-0"
                      />
                    </router-link>
                    <router-link
                      :to="{name: 'admin.product.detail', params: {slug: product.slug}}"
                      class="px-3 py-2 text-primary hover:text-opacity-90"
                      target="_blank"
                    >
                      {{ product.title }}
                    </router-link>
                  </div>
                  <base-button-close
                    v-tooltip.right="'حذف از لیست'"
                    class="mx-3"
                    @click="removeFromListHandler(idx)"
                  />
                </div>
              </template>
            </partial-card>
          </div>

          <div
            v-else
            class="text-slate-400 text-center"
          >
            هیچ محصولی انتخاب نشده!
          </div>
        </template>
      </base-accordion>
    </template>
  </base-loading-panel>

  <partial-card class="mt-3">
    <template #header>
      تغییر قیمت به صورت درصدی
    </template>
    <template #body>
      <div class="p-3">
        <form @submit.prevent="onSubmit">
          <div class="sm:flex sm:flex-wrap sm:items-end sm:justify-between">
            <div class="w-full p-2 sm:w-1/2">
              <base-input
                :max="100"
                :min="1"
                :money-mask="true"
                label-title="درصد تغییر قیمت"
                name="percentage"
                placeholder="وارد نمایید"
                type="text"
              >
                <template #icon>
                  <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                </template>
              </base-input>
            </div>
            <div class="sm:grow sm:flex">
              <div class="p-2">
                <div class="flex items-center mb-4">
                  <base-radio
                    id="increaseDecreaseRadio1"
                    v-model="increaseDecreaseRadio"
                    :show-label="false"
                    checked
                    name="increase_decrease_radio"
                    value="increase"
                  />
                  <label
                    class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300 cursor-pointer"
                    for="increaseDecreaseRadio1">
                    افزایش قیمت
                  </label>
                </div>
              </div>
              <div class="p-2">
                <div class="flex items-center mb-4">
                  <base-radio
                    id="increaseDecreaseRadio2"
                    v-model="increaseDecreaseRadio"
                    :show-label="false"
                    checked
                    name="increase_decrease_radio"
                    value="decrease"
                  />
                  <label
                    class="mr-2 text-sm font-medium text-gray-900 dark:text-gray-300 cursor-pointer"
                    for="increaseDecreaseRadio2">
                    کاهش قیمت
                  </label>
                </div>
              </div>
            </div>
            <partial-input-error-message :error-message="errors?.change_type"/>
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

              <span class="ml-auto">اعمال تغییرات قیمت</span>
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
import {computed, onMounted, ref} from "vue";
import {useRoute} from "vue-router";
import yup from "@/validation/index.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {ArrowLeftCircleIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import BaseAccordion from "@/components/base/BaseAccordion.vue";
import BaseButtonClose from "@/components/base/BaseButtonClose.vue";
import BaseRadio from "@/components/base/BaseRadio.vue";
import {FileSizes} from "@/composables/file-list.js";
import {ProductAPI} from "@/service/APIProduct.js";
import {useFormSubmit} from "@/composables/form-submit.js";
import {useToast} from "vue-toastification";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";

const toast = useToast()
const route = useRoute()
const idsParam = computed(() => {
  return route.params.ids.split('\/')
})

const loading = ref(true)

const increaseDecreaseRadio = ref('increase')
const products = ref([])

function removeFromListHandler(idx) {
  products.value.splice(idx, 1)
}

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    percentage: yup.string()
      .min(1, 'حداقل درصد تغییرات بایستی از عدد ۱ شروع شود.')
      .percentage('درصد تغییر قیمت باید عددی بین ۰ و ۱۰۰ باشد.')
      .required('درصد تغییر قیمت را وارد نمایید.')
  })
}, (values, actions) => {
  if (!products.value || !products.value?.length) {
    toast.warning('محصولات انتخاب شده خود را مجدد بررسی کنید و سپس درخواست خود را ارسال نمایید.')
    return
  }

  if (!['increase', 'decrease'].includes(increaseDecreaseRadio.value)) {
    actions.setFieldError('change_type', 'نوع تغییرات نامعتبر است!')
    return
  }

  canSubmit.value = false

  ProductAPI.modifyBatchPrice({
    ids: idsParam.value,
    price_percentage: values.percentage,
    change_type: increaseDecreaseRadio.value,
  }, {
    success() {
      actions.resetForm()
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
  ProductAPI.fetchAll({
    ids: idsParam.value,
  }, {
    success: (response) => {
      products.value = response.data
      loading.value = false
    },
  })
})
</script>
