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
                      :current-page="attributeSelectConfig.currentPage.value"
                      :has-pagination="true"
                      :is-loading="searchAttributeLoading"
                      :is-local-search="false"
                      :last-page="attributeSelectConfig.lastPage.value"
                      :options="attributes"
                      name="attribute"
                      options-key="id"
                      options-text="title"
                      placeholder="جستجوی ویژگی جستجو..."
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
                      :current-page="categorySelectConfig.currentPage.value"
                      :has-pagination="true"
                      :is-loading="searchCategoryLoading"
                      :is-local-search="false"
                      :last-page="categorySelectConfig.lastPage.value"
                      :options="categories"
                      name="category"
                      options-key="id"
                      options-text="name"
                      placeholder="جستجوی دسته‌بندی..."
                      @change="(selected) => {selectedCategory = selected}"
                      @query="searchCategory"
                      @click-next-page="searchCategoryNextPage"
                      @click-prev-page="searchCategoryPrevPage"
                  />
                  <partial-input-error-message :error-message="errors.attribute"/>
                </div>
                <div class="w-full p-2 sm:w-1/2 lg:w-3/12 xl:w-2/12">
                  <base-input
                      :min="0"
                      :money-mask="true"
                      :value="categoryAttribute?.priority?.toString()"
                      label-title="اولویت"
                      name="priority"
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

                  <span class="ml-auto">ویرایش تخصیص ویژگی</span>
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
          </template>
        </base-loading-panel>
      </div>
    </template>
  </partial-card>
</template>

<script setup>
import {onMounted, ref} from "vue";
import yup from "@/validation/index.js";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {CheckIcon, ArrowLeftCircleIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import {useToast} from "vue-toastification";
import BaseSelectSearchable from "@/components/base/BaseSelectSearchable.vue";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import {getRouteParamByKey} from "@/composables/helper.js";
import {useFormSubmit} from "@/composables/form-submit.js";
import {CategoryAPI, ProductAttributeAPI, ProductAttributeCategoryAPI} from "@/service/APIProduct.js";
import {useSelectSearching} from "@/composables/select-searching.js";

const toast = useToast()
const idParam = getRouteParamByKey('id')

const loading = ref(true)

//---------------------------------------------------------
// Attribute operation
//---------------------------------------------------------
const attributes = ref([])
const selectedAttribute = ref(null)
const attributeSelectConfig = useSelectSearching({
  searchFn(query) {
    ProductAttributeAPI.fetchAll({
      limit: attributeSelectConfig.limit.value,
      offset: attributeSelectConfig.offset(),
      text: query
    }, {
      success(response) {
        attributes.value = response.data
        if (response.meta) {
          attributeSelectConfig.lastPage.value = response.meta?.last_page
        }
      },
      finally() {
        attributeSelectConfig.isLoading.value = false
      }
    })
  },
})
const searchAttribute = attributeSelectConfig.search
const searchAttributeLoading = attributeSelectConfig.isLoading
const searchAttributeNextPage = attributeSelectConfig.searchNextPage
const searchAttributePrevPage = attributeSelectConfig.searchPrevPage

//---------------------------------------------------------
// Category operation
//---------------------------------------------------------
const categories = ref([])
const selectedCategory = ref(null)
const categorySelectConfig = useSelectSearching({
  searchFn(query) {
    CategoryAPI.fetchAll({
      limit: categorySelectConfig.limit.value,
      offset: categorySelectConfig.offset(),
      text: query
    }, {
      success(response) {
        categories.value = response.data
        if (response.meta) {
          categorySelectConfig.lastPage.value = response.meta?.last_page
        }
      },
      finally() {
        categorySelectConfig.isLoading.value = false
      }
    })
  },
})
const searchCategory = categorySelectConfig.search
const searchCategoryLoading = categorySelectConfig.isLoading
const searchCategoryNextPage = categorySelectConfig.searchNextPage
const searchCategoryPrevPage = categorySelectConfig.searchPrevPage

//------------------------------------------------
const categoryAttribute = ref(null)

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    priority: yup.number()
        .min(0, 'مقدار اولویت باید بزرگتر از صفر باشد.')
        .required('اولویت را وارد نمایید.'),
  }),
}, (values, actions) => {
  if (!selectedAttribute.value || !selectedAttribute.value?.id) {
    actions.setFieldError('attribute', 'ویژگی را وارد نمایید.')
    return
  }

  if (!selectedCategory.value || !selectedCategory.value?.id) {
    actions.setFieldError('category', 'دسته‌بندی را وارد نمایید.')
    return
  }

  canSubmit.value = false

  ProductAttributeCategoryAPI.updateById(idParam.value, {
    attribute: selectedAttribute.value.id,
    product: selectedCategory.value.id,
    priority: values.priority,
  }, {
    success(response) {
      toast.success('ویرایش ویژگی دسته‌بندی با موفقیت انجام شد.')
      setFormFields(response.data)
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
  ProductAttributeCategoryAPI.fetchById(idParam.value, {
    success: (response) => {
      setFormFields(response.data)
      loading.value = false
    },
  })

  searchAttribute()
  searchCategory()
})

function setFormFields(item) {
  categoryAttribute.value = item
  selectedAttribute.value = item.attribute
  selectedCategory.value = item.category
}
</script>
