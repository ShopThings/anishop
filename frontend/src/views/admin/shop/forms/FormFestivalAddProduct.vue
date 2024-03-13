<template>
  <form @submit.prevent="onSubmit">
    <div class="flex flex-wrap">
      <div class="w-full p-2 md:w-2/3">
        <partial-input-label title="انتخاب محصول"/>
        <base-select-searchable
            :current-page="productSelectConfig.currentPage.value"
            :has-pagination="true"
            :is-loading="productLoading"
            :is-local-search="false"
            :last-page="productSelectConfig.lastPage.value"
            :options="products"
            name="products"
            options-key="id"
            options-text="title"
            placeholder="جستجوی محصول..."
            @change="(selected) => {selectedProduct = selected}"
            @query="searchProduct"
            @click-next-page="searchProductNextPage"
            @click-prev-page="searchProductPrevPage"
        />
        <partial-input-error-message :error-message="errors.product"/>
      </div>
      <div class="w-full p-2 md:w-1/3">
        <base-input
            :max="100"
            :min="1"
            :money-mask="true"
            label-title="درصد تخفیف"
            name="discount_percentage"
            placeholder="وارد نمایید"
            type="text"
        >
          <template #icon>
            <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
          </template>
        </base-input>
      </div>
    </div>

    <div class="px-2 py-3">
      <base-animated-button
          :disabled="!canSubmit"
          class="bg-blue-500 border-blue-500 border-2 text-white mr-auto px-6 w-full sm:w-auto"
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
          <CheckCircleIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
        </template>

        <span class="ml-auto">افزودن محصول</span>
      </base-animated-button>
    </div>
  </form>
</template>

<script setup>
import {onMounted, ref} from "vue";
import yup from "@/validation/index.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {ArrowLeftCircleIcon, CheckCircleIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import BaseSelectSearchable from "@/components/base/BaseSelectSearchable.vue";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {useSelectSearching} from "@/composables/select-searching.js";
import {ProductAPI} from "@/service/APIProduct.js";
import {getRouteParamByKey} from "@/composables/helper.js";
import {FestivalAPI} from "@/service/APIShop.js";
import BaseInput from "@/components/base/BaseInput.vue";

const emit = defineEmits(['added'])

const slugParam = getRouteParamByKey('slug', null, false)

//----------------------
// Product search
//----------------------
const products = ref([])
const selectedProduct = ref(null)
const productSelectConfig = useSelectSearching({
  searchFn(query) {
    ProductAPI.fetchAll({
      limit: productSelectConfig.limit.value,
      offset: productSelectConfig.offset(),
      text: query
    }, {
      success(response) {
        products.value = response.data
        if (response.meta) {
          productSelectConfig.lastPage.value = response.meta?.last_page
        }
      },
      finally() {
        productSelectConfig.isLoading.value = false
      }
    })
  },
})
const searchProduct = productSelectConfig.search
const productLoading = productSelectConfig.isLoading
const searchProductNextPage = productSelectConfig.searchNextPage
const searchProductPrevPage = productSelectConfig.searchPrevPage

//----------------------
const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    discount_percentage: yup.string()
        .min(1, 'حداقل درصد تخفیف بایستی از عدد ۱ شروع شود.')
        .percentage('درصد تخفیف باید عددی بین ۰ و ۱۰۰ باشد.')
        .required('درصد تخفیف را وارد نمایید.'),
  }),
}, (values, actions) => {
  if (!selectedProduct.value?.id) {
    actions.setFieldError('product', 'محصول جشنواره را انتخاب نمایید.')
    return
  }

  canSubmit.value = false

  FestivalAPI.addProduct(slugParam.value, {
    product: selectedProduct.value.id,
    discount_percentage: values.discount_percentage,
  }, {
    success() {
      emit('added', selectedProduct.value)
      actions.resetForm()
    },
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
  searchProduct()
})
</script>
