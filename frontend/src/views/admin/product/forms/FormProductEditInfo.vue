<template>
  <base-loading-panel
    :loading="loading"
    type="form"
  >
    <template #content>
      <form>
        <partial-card class="mb-3 p-3 relative">
          <template #body>
            <loader-dot-orbit
              v-if="!canSubmit"
              main-container-klass="absolute w-full h-full top-0 left-0 z-[2]"
              container-bg-color="bg-blue-50 opacity-40"
            />

            <div class="p-2 flex flex-col items-center">
              <partial-input-label title="انتخاب تصویر شاخص"/>
              <base-media-placeholder
                type="image"
                v-model:selected="productImage"
              />
              <partial-input-error-message :error-message="errors.image"/>
            </div>

            <div class="flex flex-wrap">
              <div class="w-full p-2 sm:w-1/2 xl:w-5/12">
                <base-input
                  label-title="نام محصول"
                  placeholder="وارد نمایید"
                  name="title"
                  :value="info?.title"
                >
                  <template #icon>
                    <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                  </template>
                </base-input>
              </div>

              <div class="w-full p-2 sm:w-1/2 xl:w-2/12">
                <partial-input-label title="واحد محصول"/>
                <base-select-searchable
                  :options="units"
                  options-key="id"
                  options-text="name"
                  name="unit"
                  :is-loading="loadingGetUnits"
                  :is-local-search="false"
                  placeholder="جستجوی واحد محصول..."
                  :has-pagination="true"
                  :current-page="unitSelectConfig.currentPage"
                  :last-page="unitSelectConfig.lastPage"
                  :selected="selectedUnit"
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
                  :options="brands"
                  options-key="id"
                  options-text="name"
                  name="brand"
                  :is-loading="loadingGetBrands"
                  :is-local-search="false"
                  placeholder="جستجوی برند..."
                  :has-pagination="true"
                  :current-page="brandSelectConfig.currentPage"
                  :last-page="brandSelectConfig.lastPage"
                  :selected="selectedBrand"
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
                  :options="categories"
                  options-key="id"
                  options-text="name"
                  name="category"
                  :is-loading="loadingGetCategories"
                  :is-local-search="false"
                  placeholder="جستجوی دسته‌بندی..."
                  :has-pagination="true"
                  :current-page="categorySelectConfig.currentPage"
                  :last-page="categorySelectConfig.lastPage"
                  :selected="selectedCategory"
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
                  label="موجود"
                  name="is_available"
                  :enabled="availableStatus"
                  sr-text="موجود/ناموجود بودن محصول"
                  @change="(status) => {availableStatus = status}"
                />
              </div>
              <div class="p-2 w-full sm:w-auto sm:grow">
                <base-switch
                  label="نمایش کلی محصول"
                  name="is_published"
                  :enabled="publishStatus"
                  sr-text="نمایش/عدم نمایش تمامی محصولات"
                  @change="(status) => {publishStatus = status}"
                />
              </div>
              <div class="p-2 w-full sm:w-auto sm:grow">
                <base-switch
                  label="اجازه ارسال دیدگاه"
                  name="is_commenting_allowed"
                  :enabled="allowCommentingStatus"
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
              <partial-input-label title="ویژگی‌های سریع" class="mb-2"/>
              <partial-baby-property-builder
                v-model:properties="babyProps"
                property-title-text="عنوان ویژگی"
                tags-text="ویژگی‌ها"
                new-button-text="ویژگی جدید"
              />
            </div>
            <partial-input-error-message :error-message="errors.quick_properties"/>
          </template>
        </partial-card>

        <partial-card>
          <template #body>
            <partial-stepy-next-prev-buttons
              :current-step="options.currentStep"
              :current-step-index="options.currentStepIndex"
              :last-step="options.lastStep"
              :allow-next-step="canSubmit"
              :allow-prev-step="canSubmit"
              :loading="!canSubmit"
              @next="handleNextClick(options.next)"
            />
          </template>
        </partial-card>
      </form>
    </template>
  </base-loading-panel>
</template>

<script setup>
import {computed, onMounted, reactive, ref} from "vue";
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
import {useToast} from "vue-toastification";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import BaseTagsInput from "@/components/base/BaseTagsInput.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {getRouteParamByKey} from "@/composables/helper.js";
import {BrandAPI, CategoryAPI, ProductAPI, UnitAPI} from "@/service/APIProduct.js";

defineProps({
  options: {
    type: Object,
    required: true,
  },
})

const toast = useToast()
const slugParam = getRouteParamByKey('slug', null, false)

const loading = ref(true)
const info = ref(null)

//---------------------------------------------------------
// Unit operation
//---------------------------------------------------------

const loadingGetUnits = ref(true)
const units = ref([])
const selectedUnit = ref(null)
const unitSelectConfig = reactive({
  limit: 15,
  currentPage: 1,
  lastPage: null,
  offset: () => {
    return (unitSelectConfig.currentPage - 1) * unitSelectConfig.limit;
  },
})

function searchUnit(query) {
  loadingGetUnits.value = true
  UnitAPI.fetchAll({
    limit: unitSelectConfig.limit,
    offset: unitSelectConfig.offset(),
    text: query
  }, {
    success(response) {
      units.value = response.data
      if (response.meta) {
        unitSelectConfig.lastPage = response.meta?.last_page
      }
    },
    finally() {
      loadingGetUnits.value = false
    }
  })
}

function searchUnitNextPage(query) {
  if (unitSelectConfig.currentPage < unitSelectConfig.lastPage) {
    unitSelectConfig.currentPage++
    searchUnit(query)
  }
}

function searchUnitPrevPage(query) {
  if (unitSelectConfig.currentPage > 1) {
    unitSelectConfig.currentPage--
    searchUnit(query)
  }
}

//---------------------------------------------------------

//---------------------------------------------------------
// Brand operation
//---------------------------------------------------------

const loadingGetBrands = ref(true)
const brands = ref([])
const selectedBrand = ref(null)
const brandSelectConfig = reactive({
  limit: 15,
  currentPage: 1,
  lastPage: null,
  offset: () => {
    return (brandSelectConfig.currentPage - 1) * brandSelectConfig.limit;
  },
})

function searchBrand(query) {
  loadingGetBrands.value = true
  BrandAPI.fetchAll({
    limit: brandSelectConfig.limit,
    offset: brandSelectConfig.offset(),
    text: query
  }, {
    success(response) {
      brands.value = response.data
      if (response.meta) {
        brandSelectConfig.lastPage = response.meta?.last_page
      }
    },
    finally() {
      loadingGetBrands.value = false
    }
  })
}

function searchBrandNextPage(query) {
  if (brandSelectConfig.currentPage < brandSelectConfig.lastPage) {
    brandSelectConfig.currentPage++
    searchBrand(query)
  }
}

function searchBrandPrevPage(query) {
  if (brandSelectConfig.currentPage > 1) {
    brandSelectConfig.currentPage--
    searchBrand(query)
  }
}

//---------------------------------------------------------

//---------------------------------------------------------
// Category operation
//---------------------------------------------------------

const loadingGetCategories = ref(true)
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
  loadingGetCategories.value = true
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
      loadingGetCategories.value = false
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

//---------------------------------------------------------

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
  onSubmit()
  nextFn = next
}

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    title: yup.string().required('نام محصول را وارد نمایید.'),
    is_published: yup.boolean().required('وضعیت انتشار را مشخص کنید.'),
    is_available: yup.boolean().required('وضعیت موجودی را مشخص کنید.'),
    is_commenting_allowed: yup.boolean().required('وضعیت ارسال نظر را مشخص کنید.'),
  }),
}, (values, actions) => {
  if (!canSubmit.value) return

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

  ProductAPI.updateById(slugParam.value, {
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
      setFormFields(response.data)
      toast.success('ویرایش اطلاعات با موفقیت انجام شد.')

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
})

onMounted(() => {
  ProductAPI.fetchById(slugParam.value, {
    success: (response) => {
      setFormFields(response.data)
      loading.value = false
    },
  })

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

function setFormFields(item) {
  info.value = item
  productImage.value = item.image.full_path
  tags.value = item.keywords
  babyProps.value = item.quick_properties

  selectedUnit.value = item.unit
  selectedBrand.value = item.brand
  selectedCategory.value = item.category

  availableStatus.value = item.is_available
  publishStatus.value = item.is_published
  allowCommentingStatus.value = item.is_commenting_allowed
}
</script>
