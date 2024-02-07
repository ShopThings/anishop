<template>
  <partial-card>
    <template #header>
      تخصیص ویژگی به دسته‌بندی
    </template>
    <template #body>
      <div class="p-3">
        <form @submit.prevent="onSubmit">
          <div class="flex flex-wrap items-end">
            <div class="w-full p-2 sm:w-1/2 lg:w-5/12 xl:w-5/12">
              <partial-input-label title="انتخاب ویژگی"/>
              <base-select-searchable
                  :options="attributes"
                  options-key="id"
                  options-text="title"
                  name="attribute"
                  placeholder="جستجوی ویژگی جستجو..."
                  :is-loading="searchAttributeLoading"
                  :is-local-search="false"
                  :has-pagination="true"
                  :current-page="attributeSelectConfig.currentPage"
                  :last-page="attributeSelectConfig.lastPage"
                  @change="(selected) => {selectedAttribute = selected}"
                  @query="searchAttribute"
                  @click-next-page="searchAttributeNextPage"
                  @click-prev-page="searchAttributePrevPage"
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
                  placeholder="جستجوی دسته‌بندی..."
                  :is-loading="searchCategoryLoading"
                  :is-local-search="false"
                  :has-pagination="true"
                  :current-page="categorySelectConfig.currentPage"
                  :last-page="categorySelectConfig.lastPage"
                  @change="(selected) => {selectedCategory = selected}"
                  @query="searchCategory"
                  @click-next-page="searchCategoryNextPage"
                  @click-prev-page="searchCategoryPrevPage"
              />
              <partial-input-error-message :error-message="errors.category"/>
            </div>
            <div class="w-full p-2 sm:w-1/2 lg:w-3/12 xl:w-2/12">
              <base-input
                  type="text"
                  :min="0"
                  :money-mask="true"
                  label-title="اولویت"
                  placeholder="وارد نمایید"
                  name="priority"
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

              <span class="ml-auto">تخصیص ویژگی</span>
            </base-animated-button>
          </div>
        </form>
      </div>
    </template>
  </partial-card>
</template>

<script setup>
import {onMounted, reactive, ref} from "vue";
import yup from "@/validation/index.js";
import PartialCard from "@/components/partials/PartialCard.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {CheckIcon, ArrowLeftCircleIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseSelectSearchable from "@/components/base/BaseSelectSearchable.vue";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {CategoryAPI, ProductAttributeAPI, ProductAttributeCategoryAPI} from "@/service/APIProduct.js";

//---------------------------------------------------------
// Attribute operation
//---------------------------------------------------------
const searchAttributeLoading = ref(true)
const attributes = ref([])
const selectedAttribute = ref(null)
const attributeSelectConfig = reactive({
  limit: 15,
  currentPage: 1,
  lastPage: null,
  offset: () => {
    return (attributeSelectConfig.currentPage - 1) * attributeSelectConfig.limit;
  },
})

function searchAttribute(query) {
  searchAttributeLoading.value = true
  ProductAttributeAPI.fetchAll({
    limit: attributeSelectConfig.limit,
    offset: attributeSelectConfig.offset(),
    text: query
  }, {
    success(response) {
      attributes.value = response.data
      if (response.meta) {
        attributeSelectConfig.lastPage = response.meta?.last_page
      }
    },
    finally() {
      searchAttributeLoading.value = false
    }
  })
}

function searchAttributeNextPage(query) {
  if (attributeSelectConfig.currentPage < attributeSelectConfig.lastPage) {
    attributeSelectConfig.currentPage++
    searchAttribute(query)
  }
}

function searchAttributePrevPage(query) {
  if (attributeSelectConfig.currentPage > 1) {
    attributeSelectConfig.currentPage--
    searchAttribute(query)
  }
}

//---------------------------------------------------------
// Category operation
//---------------------------------------------------------
const searchCategoryLoading = ref(true)
const categories = ref([])
const selectedCategory = ref(null)
const categorySelectConfig = reactive({
  limit: 15,
  currentPage: 1,
  lastPage: null,
  offset: () => {
    return (categorySelectConfig.currentPage - 1) * categorySelectConfig.limit;
  },
})

function searchCategory(query) {
  searchCategoryLoading.value = true
  CategoryAPI.fetchAll({
    limit: categorySelectConfig.limit,
    offset: categorySelectConfig.offset(),
    text: query
  }, {
    success(response) {
      categories.value = response.data
      if (response.meta) {
        categorySelectConfig.lastPage = response.meta?.last_page
      }
    },
    finally() {
      searchCategoryLoading.value = false
    }
  })
}

function searchCategoryNextPage(query) {
  if (categorySelectConfig.currentPage < categorySelectConfig.lastPage) {
    categorySelectConfig.currentPage++
    searchCategory(query)
  }
}

function searchCategoryPrevPage(query) {
  if (categorySelectConfig.currentPage > 1) {
    categorySelectConfig.currentPage--
    searchCategory(query)
  }
}

//------------------------------------------------
const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    priority: yup.number()
        .min(0, 'مقدار اولویت باید بزرگتر از صفر باشد.')
        .required('اولویت را وارد نمایید.'),
  }),
}, (values, actions) => {
  if (!canSubmit.value) return

  if (!selectedAttribute.value || !selectedAttribute.value?.id) {
    actions.setFieldError('attribute', 'ویژگی را وارد نمایید.')
    return
  }

  if (!selectedCategory.value || !selectedCategory.value?.id) {
    actions.setFieldError('category', 'دسته‌بندی را وارد نمایید.')
    return
  }

  canSubmit.value = false

  ProductAttributeCategoryAPI.create({
    attribute: selectedAttribute.value.id,
    product: selectedCategory.value.id,
    priority: values.priority,
  }, {
    success() {
      selectedAttribute.value = null
      selectedCategory.value = null
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
  searchAttribute()
  searchCategory()
})
</script>
