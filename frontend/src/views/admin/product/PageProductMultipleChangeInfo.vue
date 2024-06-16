<template>
  <base-loading-panel
    :loading="loading"
    loading-text="در حال بارگذاری محصولات"
    type="circle"
  >
    <template #content>
      <base-accordion
        btn-class="bg-white border-2 border-blue-400 hover:shadow-lg focus-visible:ring-blue-800"
        panel-class="max-h-96 my-custom-scrollbar"
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
                        :is-local="false"
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

  <form @submit.prevent="onSubmit">
    <partial-card class="mt-3">
      <template #header>
        مشخصات اولیه
      </template>
      <template #body>
        <div class="p-3">
          <div class="flex flex-wrap">
            <div class="w-full p-2 sm:w-1/2 xl:w-3/12">
              <partial-input-label title="واحد محصول"/>
              <div class="flex">
                <div class="grow">
                  <base-select-searchable
                    ref="unitRef"
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
                <base-button-close
                  v-tooltip.right="'حذف انتخاب'"
                  class="shrink-0 mr-2 bg-orange-50 rounded px-2 border border-orange-200"
                  @click="() => {if(unitRef) unitRef.removeSelectedItems()}"
                />
              </div>
              <partial-input-error-message :error-message="errors.unit"/>
            </div>
            <div class="w-full p-2 sm:w-1/2 xl:w-4/12">
              <partial-input-label title="برند"/>
              <div class="flex">
                <div class="grow">
                  <base-select-searchable
                    ref="brandRef"
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
                  />
                  <partial-input-error-message :error-message="errors.brand"/>
                </div>
                <base-button-close
                  v-tooltip.right="'حذف انتخاب'"
                  class="shrink-0 mr-2 bg-orange-50 rounded px-2 border border-orange-200"
                  @click="() => {if(brandRef) brandRef.removeSelectedItems()}"
                />
              </div>
              <partial-input-error-message :error-message="errors.brand"/>
            </div>
            <div class="w-full p-2 sm:w-1/2 xl:w-5/12">
              <partial-input-label title="دسته‌بندی"/>
              <div class="flex">
                <div class="grow">
                  <base-select-searchable
                    ref="categoryRef"
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
                <base-button-close
                  v-tooltip.right="'حذف انتخاب'"
                  class="shrink-0 mr-2 bg-orange-50 rounded px-2 border border-orange-200"
                  @click="() => {if(categoryRef) categoryRef.removeSelectedItems()}"
                />
              </div>
              <partial-input-error-message :error-message="errors.category"/>
            </div>
          </div>
        </div>
      </template>
    </partial-card>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mt-3">
      <partial-card>
        <template #body>
          <div class="bg-indigo-300 py-2 px-4 rounded-t-lg border-b text-sm flex items-center justify-between">
            <label class="ml-2 cursor-pointer grow" for="dismissAvailability">
              عدم در نظر گرفتن موجود بودن
            </label>
            <base-checkbox
              id="dismissAvailability"
              v-model="dismissAvailableStatus"
              name="dismiss_availability"
            />
          </div>

          <div class="p-2 w-full sm:w-auto sm:grow">
            <base-switch
              :enabled="true"
              label="وضعیت موجود بودن"
              name="is_available"
              sr-text="موجود/ناموجود بودن محصول"
              @change="(status) => {availableStatus = status}"
            />
          </div>
        </template>
      </partial-card>

      <partial-card>
        <template #body>
          <div class="bg-indigo-300 py-2 px-4 rounded-t-lg border-b text-sm flex items-center justify-between">
            <label class="ml-2 cursor-pointer grow" for="dismissPublish">
              عدم در نظر گرفتن وضعیت نمایش
            </label>
            <base-checkbox
              id="dismissPublish"
              v-model="dismissPublishStatus"
              name="dismiss_publish"
            />
          </div>

          <div class="p-2 w-full sm:w-auto sm:grow">
            <base-switch
              :enabled="true"
              label="نمایش محصول در سایت"
              name="is_published"
              sr-text="نمایش/عدم نمایش تمامی محصولات"
              @change="(status) => {publishStatus = status}"
            />
          </div>
        </template>
      </partial-card>

      <partial-card>
        <template #body>
          <div class="bg-indigo-300 py-2 px-4 rounded-t-lg border-b text-sm flex items-center justify-between">
            <label class="ml-2 cursor-pointer grow" for="dismissCommenting">
              عدم در نظر گرفتن اجازه ارسال دیدگاه
            </label>
            <base-checkbox
              id="dismissCommenting"
              v-model="dismissAllowCommentingStatus"
              name="dismiss_commenting"
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
        </template>
      </partial-card>
    </div>

    <partial-card class="mt-3">
      <template #body>
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

            <span class="ml-auto">ثبت مشخصات</span>
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
      </template>
    </partial-card>
  </form>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import {useRoute} from "vue-router";
import {CheckIcon} from "@heroicons/vue/24/outline/index.js";
import PartialCard from "@/components/partials/PartialCard.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import BaseAccordion from "@/components/base/BaseAccordion.vue";
import BaseButtonClose from "@/components/base/BaseButtonClose.vue";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import BaseSelectSearchable from "@/components/base/BaseSelectSearchable.vue";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import BaseCheckbox from "@/components/base/BaseCheckbox.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {BrandAPI, CategoryAPI, ProductAPI, UnitAPI} from "@/service/APIProduct.js";
import {useSelectSearching} from "@/composables/select-searching.js";
import {FileSizes} from "@/composables/file-list.js";
import {useToast} from "vue-toastification";

const toast = useToast()
const route = useRoute()
const idsParam = computed(() => {
  return route.params.ids.split('\/')
})

const loading = ref(true)

const products = ref([])

const unitRef = ref(null)
const brandRef = ref(null)
const categoryRef = ref(null)

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
const availableStatus = ref(true)
const publishStatus = ref(true)
const allowCommentingStatus = ref(true)

const dismissAvailableStatus = ref(false)
const dismissPublishStatus = ref(false)
const dismissAllowCommentingStatus = ref(false)

function removeFromListHandler(idx) {
  products.value.splice(idx, 1)
}

const {canSubmit, errors, onSubmit} = useFormSubmit({}, (values, actions) => {
  if (!products.value || !products.value?.length) {
    toast.warning('محصولات انتخاب شده خود را مجدد بررسی کنید و سپس درخواست خود را ارسال نمایید.')
    return
  }

  let updateObj = {
    ids: idsParam.value,
  }

  if (selectedUnit.value?.id) {
    updateObj.unit = selectedUnit.value.id
  }
  if (selectedBrand.value?.id) {
    updateObj.brand = selectedBrand.value.id
  }
  if (selectedCategory.value?.id) {
    updateObj.category = selectedCategory.value.id
  }

  if (!dismissAvailableStatus.value) {
    updateObj.is_available = availableStatus.value
  }
  if (!dismissPublishStatus.value) {
    updateObj.is_published = publishStatus.value
  }
  if (!dismissAllowCommentingStatus.value) {
    updateObj.is_commenting_allowed = allowCommentingStatus.value
  }

  canSubmit.value = false

  ProductAPI.modifyBatchInfo(updateObj, {
    success() {
      actions.resetForm()

      selectedUnit.value = null
      selectedBrand.value = null
      selectedCategory.value = null

      dismissAvailableStatus.value = false
      availableStatus.value = true
      dismissPublishStatus.value = false
      publishStatus.value = true
      dismissAllowCommentingStatus.value = false
      allowCommentingStatus.value = true
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

  searchUnit()
  searchBrand()
  searchCategory()
})
</script>
