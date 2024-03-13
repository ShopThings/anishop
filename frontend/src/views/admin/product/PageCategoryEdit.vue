<template>
  <partial-card>
    <template #header>
      ویرایش دسته‌بندی -
      <span
          v-if="category?.id"
          class="text-slate-400 text-base"
      >{{ category?.name }}</span>
    </template>
    <template #body>
      <div class="p-3">
        <base-loading-panel
            :loading="loading"
            type="form"
        >
          <template #content>
            <form @submit.prevent="onSubmit">
              <div class="p-2">
                <base-switch
                    :enabled="category?.is_published"
                    label="عدم نمایش دسته‌بندی"
                    name="is_published"
                    on-label="نمایش دسته‌بندی"
                    sr-text="نمایش/عدم نمایش دسته‌بندی"
                    @change="(status) => {publishStatus=status}"
                />
              </div>

              <div class="flex flex-wrap">
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input
                      :value="category?.name"
                      label-title="نام"
                      name="name"
                      placeholder="وارد نمایید"
                  >
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input
                      :min="0"
                      :money-mask="true"
                      :value="category?.priority?.toString()"
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

                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <partial-input-label :is-optional="true" title="دسته‌بندی والد"/>

                  <base-select-searchable
                      :current-page="categorySelectConfig.currentPage.value"
                      :has-pagination="true"
                      :is-loading="searchLoading"
                      :is-local-search="false"
                      :last-page="categorySelectConfig.lastPage.value"
                      :options="categories"
                      :selected="selectedCategory"
                      name="parent_category"
                      options-key="id"
                      options-text="name"
                      placeholder="جستجوی دسته‌بندی..."
                      @change="categorySelectionChange"
                      @query="searchCategory"
                      @click-next-page="searchCategoryNextPage"
                      @click-prev-page="searchCategoryPrevPage"
                  >
                    <template #item="{item}">
                      <div class="flex items-center">
                        <span>{{ item.name }}</span>
                        <span
                            class="py-1 px-2 text-xs bg-blue-500 text-white mr-auto rounded-md"
                        >
                            سطح
                            {{ item.level }}
                        </span>
                      </div>
                    </template>
                  </base-select-searchable>
                  <partial-input-error-message :error-message="errors.category"/>
                </div>
              </div>

              <hr class="my-3">

              <div class="flex flex-wrap">
                <div class="p-2 w-full sm:w-auto sm:grow">
                  <base-switch
                      :enabled="category?.show_in_menu"
                      label="نمایش در منو"
                      name="show_in_menu"
                      sr-text="نمایش/عدم نمایش دسته‌بندی در منو"
                      @change="(status) => {showInMenuStatus=status}"
                  />
                </div>
                <div class="p-2 w-full sm:w-auto sm:grow">
                  <base-switch
                      :enabled="category?.show_in_search_side_menu"
                      label="نمایش در منوی صفحه جستجو"
                      name="show_in_search_side_menu"
                      sr-text="نمایش/عدم نمایش دسته‌بندی در منوی صفحه جستجو"
                      @change="(status) => {showInSearchSideMenuStatus=status}"
                  />
                </div>
                <div class="p-2 w-full sm:w-auto sm:grow">
                  <base-switch
                      :enabled="category?.show_in_slider"
                      label="نمایش در اسلایدر"
                      name="show_in_slider"
                      sr-text="نمایش/عدم نمایش دسته‌بندی در اسلایدر"
                      @change="(status) => {showInSliderStatus=status}"
                  />
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

                  <span class="ml-auto">ویرایش دسته‌بندی</span>
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
import PartialCard from "@/components/partials/PartialCard.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {CheckIcon, ArrowLeftCircleIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import BaseSelectSearchable from "@/components/base/BaseSelectSearchable.vue";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import {useToast} from "vue-toastification";
import {CategoryAPI} from "@/service/APIProduct.js";
import {useFormSubmit} from "@/composables/form-submit.js";
import {getRouteParamByKey} from "@/composables/helper.js";
import {useRouter} from "vue-router";
import {useSelectSearching} from "@/composables/select-searching.js";

const router = useRouter()
const toast = useToast()
const idParam = getRouteParamByKey('slug', null, false)

const category = ref(null)
const loading = ref(true)

const publishStatus = ref(true)
const showInMenuStatus = ref(true)
const showInSearchSideMenuStatus = ref(true)
const showInSliderStatus = ref(true)

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
const searchLoading = categorySelectConfig.isLoading
const searchCategoryNextPage = categorySelectConfig.searchNextPage
const searchCategoryPrevPage = categorySelectConfig.searchPrevPage

function categorySelectionChange(selected) {
  selectedCategory.value = selected
}

//------------------------------------------------

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    is_published: yup.boolean().required('وضعیت انتشار را مشخص کنید.'),
    name: yup.string().required('نام دسته‌بندی را وارد نمایید.'),
    priority: yup.number()
        .min(0, 'مقدار اولویت باید بزرگتر از صفر باشد.')
        .required('اولویت را وارد نمایید.'),
    show_in_menu: yup.boolean().required('وضعیت نمایش در منو را مشخص کنید.'),
    show_in_search_side_menu: yup.boolean().required('وضعیت نمایش در منوی جستجو را مشخص کنید.'),
    show_in_slider: yup.boolean().required('وضعیت نمایش در اسلایدر را مشخص کنید.'),
  }),
}, (values, actions) => {
  if (selectedCategory.value) {
    if (!selectedCategory.value.id) {
      actions.setFieldError('parent_category', 'دسته‌بندی والد نامعتبر می‌باشد.')
      return
    } else if (selectedCategory.value.id === category.id) {
      actions.setFieldError('parent_category', 'دسته‌بندی فعلی با دسته‌بندی والد یکسان می‌باشد.')
      return
    } else if (selectedCategory.value.level >= category.level) {
      actions.setFieldError('parent_category', 'دسته‌بندی با سطح بالاتر، نمی‌تواند والد دسته‌بندی با سطح پایین‌تر باشد.')
      return
    }
  }

  canSubmit.value = false

  CategoryAPI.updateById(idParam.value, {
    parent: selectedCategory.value?.id ?? null,
    name: values.name,
    priority: values.priority,
    show_in_menu: showInMenuStatus.value,
    show_in_search_side_menu: showInSearchSideMenuStatus.value,
    show_in_slider: showInSliderStatus.value,
    is_published: publishStatus.value,
  }, {
    success(response) {
      toast.success('ویرایش اطلاعات با موفقیت انجام شد.')

      if (response.data.slug !== category.slug) {
        router.push({name: 'admin.category.edit', params: {slug: response.data.slug}})
      }

      setFormFields(response.data)
    },
    finally() {
      canSubmit.value = true
    },
  })
})

onMounted(() => {
  CategoryAPI.fetchById(idParam.value, {
    success(response) {
      setFormFields(response.data)
      loading.value = false
    },
  })

  searchCategory()
})

function setFormFields(item) {
  category.value = item

  showInMenuStatus.value = item.show_in_menu_status
  showInSearchSideMenuStatus.value = item.show_in_search_side_menu_status
  showInSliderStatus.value = item.show_in_slider_status
  publishStatus.value = item.is_published

  if (item.parent) {
    selectedCategory.value = item.parent
  }
}
</script>
