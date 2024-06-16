<template>
  <form @submit.prevent="onSubmit">
    <div class="flex flex-wrap">
      <div class="w-full p-2 md:w-2/3">
        <partial-input-label title="دسته‌بندی"/>
        <base-select-searchable
          ref="categorySelectRef"
          :current-page="categorySelectConfig.currentPage.value"
          :has-pagination="true"
          :is-loading="loadingGetCategories"
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
        >
          <template #item="{item}">
            <div class="flex items-center gap-2">
              <span>{{ item.name }}</span>
              <div class="py-1 px-2 text-sm bg-blue-500 text-white">
                سطح
                {{ item.level }}
              </div>
            </div>
          </template>
        </base-select-searchable>
        <partial-input-error-message :error-message="errors.category"/>
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

    <div class="sm:flex sm:flex-wrap sm:flex-row-reverse">
      <div class="px-2 py-3">
        <base-animated-button
          :class="{'!cursor-not-allowed': !canSubmit}"
          :disabled="!canSubmit"
          class="bg-blue-500 border-blue-500 border-2 text-white px-6 w-full sm:w-auto"
          type="submit"
          @click="handleSubmitOperation('add')"
        >
          <VTransitionFade>
            <loader-circle
              v-if="!canSubmit && submitOperation === 'add'"
              big-circle-color="border-transparent"
              main-container-klass="absolute w-full h-full top-0 left-0"
            />
          </VTransitionFade>

          <template #icon="{klass}">
            <CheckCircleIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
          </template>

          <span class="ml-auto">افزودن محصولات دسته‌بندی</span>
        </base-animated-button>
      </div>
      <div class="px-2 py-3">
        <base-animated-button
          :class="{'!cursor-not-allowed': !canSubmit}"
          :disabled="!canSubmit"
          class="!text-pink-500 border-pink-500 border-2 px-6 w-full sm:w-auto hover:bg-pink-50"
          type="submit"
          @click="handleSubmitOperation('remove')"
        >
          <VTransitionFade>
            <loader-circle
              v-if="!canSubmit && submitOperation === 'remove'"
              big-circle-color="border-transparent"
              main-container-klass="absolute w-full h-full top-0 left-0"
            />
          </VTransitionFade>

          <template #icon="{klass}">
            <XCircleIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
          </template>

          <span class="ml-auto">حذف محصولات دسته‌بندی</span>
        </base-animated-button>
      </div>
    </div>
  </form>
</template>

<script setup>
import {onMounted, ref} from "vue";
import yup from "@/validation/index.js";
import BaseSelectSearchable from "@/components/base/BaseSelectSearchable.vue";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import {ArrowLeftCircleIcon, CheckCircleIcon, XCircleIcon} from "@heroicons/vue/24/outline/index.js";
import {useFormSubmit} from "@/composables/form-submit.js";
import {getRouteParamByKey} from "@/composables/helper.js";
import {useSelectSearching} from "@/composables/select-searching.js";
import {CategoryAPI} from "@/service/APIProduct.js";
import {FestivalAPI} from "@/service/APIShop.js";
import {useConfirmToast} from "@/composables/toast-helper.js";
import BaseInput from "@/components/base/BaseInput.vue";

const emit = defineEmits(['added', 'removed'])

const slugParam = getRouteParamByKey('slug', null, false)
const submitOperation = ref(null)

const categorySelectRef = ref(null)

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
const loadingGetCategories = categorySelectConfig.isLoading
const searchCategoryNextPage = categorySelectConfig.searchNextPage
const searchCategoryPrevPage = categorySelectConfig.searchPrevPage

//---------------------------------------------------------
function submitAdd(values, actions) {
  FestivalAPI.addCategory(slugParam.value, {
    category: selectedCategory.value.id,
    discount_percentage: values.discount_percentage,
  }, {
    success() {
      emit('added', selectedCategory.value)

      actions.resetForm()
      if (categorySelectRef.value) {
        categorySelectRef.value.removeSelectedItems()
      }
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
}

function submitRemove(values, actions) {
  FestivalAPI.removeCategoryProducts(slugParam.value, selectedCategory.value.id, {
    success() {
      emit('removed', selectedCategory.value)

      actions.resetForm()
      if (categorySelectRef.value) {
        categorySelectRef.value.removeSelectedItems()
      }
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
}

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    discount_percentage: yup.string()
      .min(1, 'حداقل درصد تخفیف بایستی از عدد ۱ شروع شود.')
      .percentage('درصد تخفیف باید عددی بین ۰ و ۱۰۰ باشد.')
      .required('درصد تخفیف را وارد نمایید.'),
  }),
}, (values, actions) => {
  if (!selectedCategory.value?.id) {
    actions.setFieldError('category', 'دسته‌بندی را انتخاب نمایید.')
    return
  }

  canSubmit.value = false

  if (submitOperation.value === 'add') {
    submitAdd(values, actions)
  } else if (submitOperation.value === 'remove') {
    useConfirmToast({
      accept() {
        submitRemove(values, actions)
      },
      decline() {
        canSubmit.value = true
      },
    }, 'حذف محصولات موجود در دسته‌بندی از جشنواره')
  }
})

function handleSubmitOperation(operation) {
  submitOperation.value = operation
  onSubmit()
}

onMounted(() => {
  searchCategory()
})
</script>
