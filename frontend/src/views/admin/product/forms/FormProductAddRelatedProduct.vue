<template>
  <form>
    <partial-card class="mb-3 p-3 relative">
      <template #body>
        <loader-dot-orbit
            v-if="!canSubmit"
            container-bg-color="bg-blue-50 opacity-40"
            main-container-klass="absolute w-full h-full top-0 left-0 z-[2]"
        />

        <div class="w-full p-2">
          <partial-input-label title="انتخاب محصول مرتبط"/>
          <base-select-searchable
              :current-page="productSelectConfig.currentPage.value"
              :has-pagination="true"
              :is-loading="productLoading"
              :is-local-search="false"
              :last-page="productSelectConfig.lastPage.value"
              :multiple="true"
              :options="products"
              name="products"
              options-key="id"
              options-text="title"
              placeholder="جستجوی محصول..."
              @change="(selected) => {selectedProducts = selected}"
              @query="searchProduct"
              @click-next-page="searchProductNextPage"
              @click-prev-page="searchProductPrevPage"
          />
          <partial-input-error-message :error-message="errors.products"/>
        </div>

        <div v-if="selectedProducts && selectedProducts.length">
          <partial-input-label title="محصولات انتخاب شده"/>
          <div
              class="mt-3 p-2 py-1 border-2 border-dashed rounded-lg border-indigo-200 mb-3 relative flex flex-wrap"
          >
            <div
                v-for="(product, idx) in products"
                class="rounded bg-blue-100 text-sm text-blue-700 py-1 px-2 flex items-center ml-2 my-1"
            >
              <span class="ml-3">{{ product?.title }}</span>
              <base-button-close v-tooltip.top="'حذف از لیست'" @click="removeProduct(idx)"/>
            </div>
          </div>
        </div>
      </template>
    </partial-card>

    <partial-card>
      <template #body>
        <partial-stepy-next-prev-buttons
            :allow-next-step="canSubmit"
            :allow-prev-step="shouldGoPrevStep"
            :current-step="options.currentStep"
            :current-step-index="options.currentStepIndex"
            :last-step="options.lastStep"
            :loading="!canSubmit"
            :show-prev-step-button="shouldGoPrevStep"
            @next="handleNextClick(options.next)"
            @prev="() => {
            if(shouldGoPrevStep) {
              options.prev()
            }
          }"
        />

        <div
          v-if="Object.keys(errors)?.length"
          class="text-left px-3.5 mb-3"
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
      </template>
    </partial-card>
  </form>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import PartialStepyNextPrevButtons from "@/components/partials/PartialStepyNextPrevButtons.vue";
import BaseSelectSearchable from "@/components/base/BaseSelectSearchable.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import BaseButtonClose from "@/components/base/BaseButtonClose.vue";
import LoaderDotOrbit from "@/components/base/loader/LoaderDotOrbit.vue";
import {ProductAPI} from "@/service/APIProduct.js";
import {useFormSubmit} from "@/composables/form-submit.js";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import {useCreationProductStore} from "@/store/StoreProduct.js";
import {useToast} from "vue-toastification";
import {useSelectSearching} from "@/composables/select-searching.js";

defineProps({
  options: {
    type: Object,
    required: true,
  },
})

const toast = useToast()
const productStore = useCreationProductStore()
const shouldGoPrevStep = computed(() => {
  return !(!!productStore.getProductSlug)
})

let nextFn = null

function handleNextClick(next) {
  onSubmit()
  nextFn = next
}

const {canSubmit, errors, onSubmit} = useFormSubmit({}, (values, actions) => {
      let productsIds = selectedProducts.value?.map((product) => {
        product.id
      })

      if (!productsIds || !productsIds.length) {
        if (nextFn) nextFn()
        return
      }

      canSubmit.value = false

      ProductAPI.createRelativeProducts(productStore.getProductSlug, {
        products: productsIds,
      }, {
        success() {
          if (nextFn) nextFn()
        },
        error(error) {
          if (error.errors && Object.keys(error.errors).length >= 1)
            actions.setErrors(error.errors)
        },
        finally() {
          canSubmit.value = true
        },
      })
    }
)

//----------------------
// Product search
//----------------------
const products = ref([])
const selectedProducts = ref(null)
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
function removeProduct(idx) {
  if (Array.isArray(selectedProducts.value))
    selectedProducts.value.splice(idx, 1)
}

onMounted(() => {
  searchProduct()
})
</script>
