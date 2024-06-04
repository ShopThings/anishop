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
              <div class="w-full p-2 sm:w-1/2">
                <base-input
                  :value="info?.title"
                  label-title="نام محصول"
                  name="title"
                  placeholder="وارد نمایید"
                >
                  <template #icon>
                    <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                  </template>
                </base-input>
              </div>

              <div class="w-full p-2 sm:w-1/2">
                <partial-input-label>
                  <template #label>
                    <div class="flex items-center gap-2">
                      <span>واحد محصول</span>
                      <div class="text-xs text-slate-500 flex items-center gap-1">
                        (
                        <span class="">واحد قبلی</span>
                        <span class="text-xs bg-blue-100 border border-blue-200 rounded py-0.5 px-1.5 text-blue-700">{{
                            info?.unit_name
                          }}</span>
                        )
                      </div>
                    </div>
                  </template>
                </partial-input-label>
                <base-select-searchable
                  :current-page="unitSelectConfig.currentPage.value"
                  :has-pagination="true"
                  :is-loading="loadingGetUnits"
                  :is-local-search="false"
                  :last-page="unitSelectConfig.lastPage.value"
                  :options="units"
                  :selected="selectedUnit"
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

              <div class="w-full p-2 sm:w-1/2">
                <partial-input-label title="برند"/>
                <base-select-searchable
                  :current-page="brandSelectConfig.currentPage.value"
                  :has-pagination="true"
                  :is-loading="loadingGetBrands"
                  :is-local-search="false"
                  :last-page="brandSelectConfig.lastPage.value"
                  :options="brands"
                  :selected="selectedBrand"
                  name="brand"
                  options-key="id"
                  options-text="name"
                  placeholder="جستجوی برند..."
                  @change="(selected) => {selectedBrand = selected}"
                  @query="searchBrand"
                  @click-next-page="searchBrandNextPage"
                  @click-prev-page="searchBrandPrevPage"
                >
                  <template #item="{item}">
                    <div class="flex items-center gap-3">
                      <base-lazy-image
                        v-if="item?.image?.full_path"
                        :alt="item.name"
                        :is-local="false"
                        :lazy-src="item?.image?.full_path"
                        :size="FileSizes.SMALL"
                        class="!w-16 !h-auto object-cover rounded"
                      />
                      <img
                        v-else
                        :alt="item.name"
                        class="w-16 h-auto object-cover rounded"
                        src="/image-placeholder.jpg"
                      >

                      <span class="text-sm">{{ item.name }}</span>
                    </div>
                  </template>
                </base-select-searchable>
                <partial-input-error-message :error-message="errors.brand"/>
              </div>

              <div class="w-full p-2 sm:w-1/2">
                <partial-input-label title="دسته‌بندی"/>
                <base-select-searchable
                  :current-page="categorySelectConfig.currentPage.value"
                  :has-pagination="true"
                  :is-loading="loadingGetCategories"
                  :is-local-search="false"
                  :last-page="categorySelectConfig.lastPage.value"
                  :options="categories"
                  :selected="selectedCategory"
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

            <hr class="my-3">

            <div class="flex flex-wrap">
              <div class="p-2 w-full sm:w-auto sm:grow">
                <base-switch
                  :enabled="availableStatus"
                  label="موجود"
                  name="is_available"
                  sr-text="موجود/ناموجود بودن محصول"
                  @change="(status) => {availableStatus = status}"
                />
              </div>
              <div class="p-2 w-full sm:w-auto sm:grow">
                <base-switch
                  :enabled="publishStatus"
                  label="نمایش کلی محصول"
                  name="is_published"
                  sr-text="نمایش/عدم نمایش تمامی محصولات"
                  @change="(status) => {publishStatus = status}"
                />
              </div>
              <div class="p-2 w-full sm:w-auto sm:grow">
                <base-switch
                  :enabled="allowCommentingStatus"
                  label="اجازه ارسال دیدگاه"
                  name="is_commenting_allowed"
                  sr-text="اجازه/عدم اجازه ارسال دیدگاه"
                  @change="(status) => {allowCommentingStatus = status}"
                />
              </div>
            </div>

            <hr class="my-3">

            <div class="p-2">
              <partial-input-label title="کلمات کلیدی"/>
              <base-tags-input
                :add-tag-on-keys="[13, 190]"
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
  </base-loading-panel>
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
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import BaseTagsInput from "@/components/base/BaseTagsInput.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {getRouteParamByKey} from "@/composables/helper.js";
import {BrandAPI, CategoryAPI, ProductAPI, UnitAPI} from "@/service/APIProduct.js";
import {useSelectSearching} from "@/composables/select-searching.js";
import {FileSizes} from "@/composables/file-list.js";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";

defineProps({
  options: {
    type: Object,
    required: true,
  },
})

const slugParam = getRouteParamByKey('slug', null, false)

const loading = ref(true)
const info = ref(null)

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
  let data = {
    brand: selectedBrand.value?.id,
    category: selectedCategory.value?.id,
    title: values.title,
    image: productImage.value?.full_path,
    quick_properties: getDefinedProperties(),
    unit: selectedUnit.value?.id,
    keywords: tags.value,
    is_available: availableStatus.value,
    is_commenting_allowed: allowCommentingStatus.value,
    is_published: publishStatus.value,
  }

  if (!productImage.value) {
    actions.setFieldError('image', 'تصویر را انتخاب نمایید.')
    return
  }
  if (!selectedUnit.value?.id) {
    delete data.unit
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

  ProductAPI.updateById(slugParam.value, data, {
    success(response) {
      setFormFields(response.data)

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
  productImage.value = item.image
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
