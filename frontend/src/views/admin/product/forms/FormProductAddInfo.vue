<template>
  <form>
    <partial-card class="mb-3 p-3 relative">
      <template #body>
        <loader-dot-orbit
          v-if="!canSubmit"
          container-bg-color="bg-blue-50 opacity-40"
          main-container-klass="absolute w-full h-full top-0 left-0 z-[2]"
        />

        <div class="p-2 flex flex-col items-center">
          <partial-input-label title="انتخاب تصویر شاخص"/>
          <base-media-placeholder
            v-model:selected="productImage"
            type="image"
          />
          <partial-input-error-message :error-message="errors.image"/>
        </div>

        <div class="flex flex-wrap">
          <div class="w-full p-2 sm:w-1/2 xl:w-5/12">
            <base-input
              label-title="نام محصول"
              name="title"
              placeholder="وارد نمایید"
            >
              <template #icon>
                <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
              </template>
            </base-input>
          </div>

          <div class="w-full p-2 sm:w-1/2 xl:w-2/12">
            <partial-input-label title="واحد محصول"/>
            <base-select-searchable
              :current-page="unitSelectConfig.currentPage.value"
              :has-pagination="true"
              :is-loading="loadingGetUnits"
              :is-local-search="false"
              :last-page="unitSelectConfig.lastPage.value"
              :options="units"
              name="unit"
              options-key="id"
              options-text="name"
              placeholder="جستجوی واحد محصول..."
              @change="(selected) => {selectedUnit = selected}"
              @query="searchUnit"
              @click-next-page="searchUnitNextPage"
              @click-prev-page="searchUnitPrevPage"
            />
            <partial-input-error-message :error-message="errors.unit"/>
          </div>

          <div class="w-full p-2 sm:w-1/2 xl:w-2/12">
            <partial-input-label title="برند"/>
            <base-select-searchable
              :current-page="brandSelectConfig.currentPage.value"
              :has-pagination="true"
              :is-loading="loadingGetBrands"
              :is-local-search="false"
              :last-page="brandSelectConfig.lastPage.value"
              :options="brands"
              name="brand"
              options-key="id"
              options-text="name"
              placeholder="جستجوی برند..."
              @change="(selected) => {selectedBrand = selected}"
              @query="searchBrand"
              @click-next-page="searchBrandNextPage"
              @click-prev-page="searchBrandPrevPage"
            />
            <partial-input-error-message :error-message="errors.brand"/>
          </div>

          <div class="w-full p-2 sm:w-1/2 xl:w-3/12">
            <partial-input-label title="دسته‌بندی"/>
            <base-select-searchable
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
            />
            <partial-input-error-message :error-message="errors.category"/>
          </div>
        </div>

        <div class="flex flex-wrap">
          <div class="p-2 w-full sm:w-auto sm:grow">
            <base-switch
              :enabled="true"
              label="موجود"
              name="is_available"
              sr-text="موجود/ناموجود بودن محصول"
              @change="(status) => {availableStatus = status}"
            />
          </div>
          <div class="p-2 w-full sm:w-auto sm:grow">
            <base-switch
              :enabled="true"
              label="نمایش کلی محصول"
              name="is_published"
              sr-text="نمایش/عدم نمایش تمامی محصولات"
              @change="(status) => {publishStatus = status}"
            />
          </div>
          <div class="p-2 w-full sm:w-auto sm:grow">
            <base-switch
              :enabled="true"
              label="اجازه ارسال دیدگاه"
              name="is_commenting_allowed"
              sr-text="اجازه/عدم اجازه ارسال دیدگاه"
              @change="(status) => {allowCommentingStatus = status}"
            />
          </div>
        </div>

        <div class="p-2">
          <partial-input-label title="کلمات کلیدی"/>
          <base-tags-input
            :tags="tags"
            placeholder="کلمات کلیدی خود را وارد نمایید"
            @on-tags-changed="(t) => {tags = t}"
          />
        </div>

        <div class="p-2">
          <partial-input-label class="mb-2" title="ویژگی‌های سریع"/>
          <partial-baby-property-builder
            v-model:properties="babyProps"
            new-button-text="ویژگی جدید"
            property-title-text="عنوان ویژگی"
            tags-text="ویژگی‌ها"
          />
        </div>
        <partial-input-error-message :error-message="errors.quick_properties"/>
      </template>
    </partial-card>

    <partial-card>
      <template #body>
        <partial-stepy-next-prev-buttons
          :allow-next-step="canSubmit"
          :allow-prev-step="canSubmit"
          :current-step="options.currentStep"
          :current-step-index="options.currentStepIndex"
          :last-step="options.lastStep"
          :loading="!canSubmit"
          @next="handleNextClick(options.next)"
        />
      </template>
    </partial-card>
  </form>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import PartialStepyNextPrevButtons from "@/components/partials/PartialStepyNextPrevButtons.vue";
import BaseMediaPlaceholder from "@/components/base/BaseMediaPlaceholder.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import {ArrowLeftCircleIcon} from "@heroicons/vue/24/outline/index.js";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import yup from "@/validation/index.js";
import BaseSelectSearchable from "@/components/base/BaseSelectSearchable.vue";
import PartialBabyPropertyBuilder from "@/components/partials/PartialBabyPropertyBuilder.vue";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import LoaderDotOrbit from "@/components/base/loader/LoaderDotOrbit.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {BrandAPI, CategoryAPI, ProductAPI, UnitAPI} from "@/service/APIProduct.js";
import {useToast} from "vue-toastification";
import BaseTagsInput from "@/components/base/BaseTagsInput.vue";
import {useCreationProductStore} from "@/store/StoreProduct.js";
import {useSelectSearching} from "@/composables/select-searching.js";

defineProps({
  options: {
    type: Object,
    required: true,
  },
})

const toast = useToast()

//---------------------------------------------------------
// Unit operation
//---------------------------------------------------------
const units = ref([])
const selectedUnit = ref(null)
const unitSelectConfig = useSelectSearching({
  searchFn(query) {
    UnitAPI.fetchAll({
      limit: unitSelectConfig.limit.value,
      offset: unitSelectConfig.offset(),
      text: query
    }, {
      success(response) {
        units.value = response.data
        if (response.meta) {
          unitSelectConfig.lastPage.value = response.meta?.last_page
        }
      },
      finally() {
        unitSelectConfig.isLoading.value = false
      }
    })
  },
})
const searchUnit = unitSelectConfig.search
const loadingGetUnits = unitSelectConfig.isLoading
const searchUnitNextPage = unitSelectConfig.searchNextPage
const searchUnitPrevPage = unitSelectConfig.searchPrevPage

//---------------------------------------------------------
// Brand operation
//---------------------------------------------------------
const brands = ref([])
const selectedBrand = ref(null)
const brandSelectConfig = useSelectSearching({
  searchFn(query) {
    BrandAPI.fetchAll({
      limit: brandSelectConfig.limit.value,
      offset: brandSelectConfig.offset(),
      text: query
    }, {
      success(response) {
        brands.value = response.data
        if (response.meta) {
          brandSelectConfig.lastPage.value = response.meta?.last_page
        }
      },
      finally() {
        brandSelectConfig.isLoading.value = false
      }
    })
  },
})
const searchBrand = brandSelectConfig.search
const loadingGetBrands = brandSelectConfig.isLoading
const searchBrandNextPage = brandSelectConfig.searchNextPage
const searchBrandPrevPage = brandSelectConfig.searchPrevPage

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
const productStore = useCreationProductStore()

const productImage = ref(null)
const availableStatus = ref(true)
const publishStatus = ref(true)
const allowCommentingStatus = ref(true)

const babyProps = ref([{
  title: '',
  tags: [],
}])
const tags = ref([])

const hasBabyProps = computed(() => {
  return !!getDefinedProperties().length
})

let nextFn = null

function handleNextClick(next) {
  nextFn = next
  onSubmit()
}

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    title: yup.string().required('نام محصول را وارد نمایید.'),
    is_published: yup.boolean().required('وضعیت انتشار را مشخص کنید.'),
    is_available: yup.boolean().required('وضعیت موجودی را مشخص کنید.'),
    is_commenting_allowed: yup.boolean().required('وضعیت ارسال نظر را مشخص کنید.'),
  }),
}, (values, actions) => {
  if (!productImage.value) {
    actions.setFieldError('image', 'تصویر را انتخاب نمایید.')
    return
  }
  if (!selectedUnit.value?.id) {
    actions.setFieldError('unit', 'واحد محصول را انتخاب نمایید.')
    return
  }
  if (!selectedBrand.value?.id) {
    actions.setFieldError('brand', 'برند را انتخاب نمایید.')
    return
  }
  if (!selectedCategory.value?.id) {
    actions.setFieldError('category', 'دسته‌بندی را انتخاب نمایید.')
    return
  }

  if (!hasBabyProps.value) {
    actions.setFieldError('quick_properties', 'وارد نمودن حداقل یک ویژگی سریع الزامی می‌باشد.')
    return
  }

  canSubmit.value = false

  ProductAPI.create({
    brand: selectedBrand.value?.id,
    category: selectedCategory.value?.id,
    title: values.title,
    image: productImage.value.full_path,
    quick_properties: getDefinedProperties(),
    unit: selectedUnit.value?.id,
    keywords: tags.value,
    is_available: availableStatus.value,
    is_commenting_allowed: allowCommentingStatus.value,
    is_published: publishStatus.value,
  }, {
    success(response) {
      productStore.setProduct(response.data)

      actions.resetForm()
      toast.success('محصول با موفقیت ثبت شد.')
      if (nextFn) nextFn()
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
  searchUnit()
  searchBrand()
  searchCategory()
})

function getDefinedProperties() {
  const p = []

  for (let i of babyProps.value) {
    if (i.title.toString().trim() !== '' && i.tags.length) {
      p.push(i)
    }
  }

  return p
}
</script>
