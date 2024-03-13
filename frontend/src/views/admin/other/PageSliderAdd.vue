<template>
  <partial-card>
    <template #header>
      افزودن اسلایدر
    </template>
    <template #body>
      <div class="p-3">
        <form @submit.prevent="onSubmit">
          <div class="flex flex-wrap items-end">
            <div class="p-2 w-full sm:w-1/2 lg:w-5/12">
              <base-input
                  label-title="عنوان"
                  name="title"
                  placeholder="وارد نمایید"
              >
                <template #icon>
                  <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                </template>
              </base-input>
            </div>
            <div class="p-2 w-full sm:w-1/2 lg:w-4/12">
              <partial-input-label title="محل قرارگیری"/>
              <base-select
                  :options="sliderPlaces"
                  options-key="value"
                  options-text="text"
                  @change="(selected) => {selectedSliderPlace = selected}"
              />
              <partial-input-error-message :error-message="errors.slider_place"/>
            </div>
            <div class="p-2 w-full sm:w-1/2 lg:w-3/12">
              <base-input
                  :min="0"
                  :money-mask="true"
                  label-title="اولویت"
                  name="priority"
                  placeholder="وارد نمایید"
                  type="text"
              >
                <template #icon>
                  <HashtagIcon class="h-6 w-6 text-gray-400"/>
                </template>
              </base-input>
            </div>
            <div class="p-2 w-full sm:w-auto">
              <base-switch
                  :enabled="true"
                  label="نمایش اسلایدر"
                  name="is_published"
                  sr-text="نمایش/عدم نمایش اسلایدر"
                  @change="(status) => {publishStatus=status}"
              />
            </div>
          </div>

          <VTransitionSlideFadeDownY mode="out-in">
            <div
                v-if="selectedSliderPlace?.value === SLIDER_PLACES.MAIN_SLIDER_IMAGES.value"
            >
              <hr class="my-3">

              <div class="p-2 w-full sm:w-1/2">
                <base-input
                    :max="4"
                    :min="1"
                    :money-mask="true"
                    label-title="تعداد تصاویر کنار هم"
                    name="beside_images"
                    placeholder="بین ۱ تا ۴ تصویر کنار هم"
                    type="text"
                >
                  <template #icon>
                    <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                  </template>
                </base-input>
              </div>
            </div>

            <div
                v-else-if="selectedSliderPlace?.value === SLIDER_PLACES.MAIN_SLIDERS.value"
            >
              <hr class="my-3">

              <div class="flex flex-wrap items-end">
                <div class="p-2 w-full sm:w-1/3 lg:w-4/12">
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
                  >
                    <template #item="{item}">
                      <div class="flex items-center gap-2">
                        <span>{{ item.name }}</span>
                        <div class="py-1 px-2 text-sm bg-blue-500 text-white">
                          {{ item.latin_name }}
                        </div>
                      </div>
                    </template>
                  </base-select-searchable>
                  <partial-input-error-message :error-message="errors.brand"/>
                </div>
                <div class="p-2 w-full sm:w-1/3 lg:w-5/12">
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
                <div class="p-2 w-full sm:w-1/3 lg:w-3/12">
                  <partial-input-label title="نوع مرتب سازی"/>
                  <base-select
                      :options="orderBy"
                      options-key="value"
                      options-text="text"
                      @change="(selected) => {selectedOrderBy = selected}"
                  />
                </div>
              </div>

              <div class="p-2">
                <base-input
                    label-title="لینک مشاهده همه"
                    name="link_all"
                    placeholder="وارد نمایید"
                >
                  <template #icon>
                    <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                  </template>
                </base-input>
              </div>

              <div class="flex flex-wrap items-center mt-4">
                <div class="p-2 w-full md:w-auto grow flex items-center">
                  <partial-input-label class="shrink-0 ml-3" title="تعداد اسلایدها"/>
                  <div class="grow">
                    <base-range-slider
                        v-model="slideCount"
                        :max="20"
                        :min="0"
                        show-tooltip="always"
                    />
                  </div>
                </div>
                <div class="p-2 w-full md:w-auto shrink-0">
                  <base-switch
                      :enabled="false"
                      label="نمایش محصولات ویژه"
                      name="is_special"
                      sr-text="نمایش/عدم نمایش محصولات ویژه"
                      @change="(status) => {isSpecialStatus=status}"
                  />
                </div>
              </div>
            </div>
          </VTransitionSlideFadeDownY>

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

              <span class="ml-auto">افزودن اسلایدر</span>
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
import {onMounted, ref} from "vue";
import yup from "@/validation/index.js";
import PartialCard from "@/components/partials/PartialCard.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {ArrowLeftCircleIcon, CheckIcon, HashtagIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import {SLIDER_OPTIONS, SLIDER_PLACES} from "@/composables/constants.js";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import BaseSelect from "@/components/base/BaseSelect.vue";
import VTransitionSlideFadeDownY from "@/transitions/VTransitionSlideFadeDownY.vue";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import BaseSelectSearchable from "@/components/base/BaseSelectSearchable.vue";
import BaseRangeSlider from "@/components/base/BaseRangeSlider.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {SliderAPI} from "@/service/APIConfig.js";
import {useSelectSearching} from "@/composables/select-searching.js";
import {BrandAPI, CategoryAPI} from "@/service/APIProduct.js";

const sliderPlaces = []
for (const p in SLIDER_PLACES) {
  if (SLIDER_PLACES.hasOwnProperty(p) && SLIDER_PLACES[p].is_creatable) {
    sliderPlaces.push({
      value: SLIDER_PLACES[p].value,
      text: SLIDER_PLACES[p].text,
    })
  }
}

const orderBy = [
  {
    value: 'asc',
    text: 'صعودی',
  },
  {
    value: 'desc',
    text: 'نزولی',
  },
]

const selectedSliderPlace = ref(null)
const publishStatus = ref(true)

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
const selectedOrderBy = ref(null)
const slideCount = ref(10)
const isSpecialStatus = ref(false)

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    title: yup.string().required('عنوان اسلایدر را وارد نمایید.'),
    priority: yup.number()
        .min(0, 'مقدار اولویت باید بزرگتر از صفر باشد.')
        .required('اولویت را وارد نمایید.'),
    beside_images: yup.string()
        .when([], (inputValue, schema) => {
          return selectedSliderPlace.value?.value === SLIDER_PLACES.MAIN_SLIDER_IMAGES.value
              ? schema
                  .min(1, 'حداقل تعداد تصاویر کنار هم، ۱ عدد می‌باشد.')
                  .max(4, 'حداکثر تعداد تصاویر کنار هم، ۴ عدد می‌باشد.')
                  .required('تعداد تصاویر کنار هم را وارد نمایید.')
              : schema.optional()
        }),
    link_all: yup.string().optional(),
  }),
}, (values, actions) => {
  if (!selectedSliderPlace.value?.value) {
    actions.setFieldError('slider_place', 'محل نمایش اسلایدر را انتخاب نمایید.')
    return
  }

  let options = {}

  if (selectedSliderPlace.value?.value === SLIDER_PLACES.MAIN_SLIDER_IMAGES.value) {
    options[SLIDER_OPTIONS.BESIDE_IMAGES.value] = values.beside_images
  } else if (selectedSliderPlace.value?.value === SLIDER_PLACES.MAIN_SLIDERS.value) {
    options[SLIDER_OPTIONS.SHOW_ALL_LINK.value] = values.link_all?.trim()

    if (selectedBrand.value?.id) {
      options[SLIDER_OPTIONS.BRAND_ID.value] = selectedBrand.value.id
    }

    if (selectedCategory.value?.id) {
      options[SLIDER_OPTIONS.CATEGORY_ID.value] = selectedCategory.value.id
    }

    options[SLIDER_OPTIONS.ORDER_BY.value] = selectedOrderBy.value.value
    options[SLIDER_OPTIONS.IS_SPECIAL.value] = isSpecialStatus.value
    options[SLIDER_OPTIONS.COUNT.value] = slideCount.value
  }

  canSubmit.value = false

  SliderAPI.create({
    slider_place: selectedSliderPlace.value.value,
    title: values.title,
    priority: values.priority,
    options,
    is_published: publishStatus.value,
  }, {
    success() {
      actions.resetForm()
      router.push({name: 'admin.sliders'})
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
  searchCategory()
  searchBrand()
})
</script>
