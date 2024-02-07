<template>
  <partial-card>
    <template #header>
      ویرایش دسته‌بندی -
      <span
          v-if="category?.id"
          class="text-teal-600"
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
                    label="عدم نمایش دسته‌بندی"
                    on-label="نمایش دسته‌بندی"
                    name="is_published"
                    :enabled="category?.is_published"
                    sr-text="نمایش/عدم نمایش دسته‌بندی"
                    @change="(status) => {publishStatus=status}"
                />
              </div>

              <div class="flex flex-wrap items-end">
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input
                      label-title="نام"
                      placeholder="وارد نمایید"
                      name="name"
                      :value="category?.name"
                  >
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input
                      type="text"
                      label-title="اولویت"
                      placeholder="وارد نمایید"
                      name="priority"
                      :value="category?.priority.toString()"
                      :min="0"
                      :money-mask="true"
                  >
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>

                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <partial-input-label title="دسته‌بندی والد" :is-optional="true"/>

                  <base-select-searchable
                      :options="categories"
                      options-key="id"
                      options-text="name"
                      name="parent_category"
                      :is-loading="searchLoading"
                      :is-local-search="false"
                      placeholder="جستجوی دسته‌بندی..."
                      :selected="selectedCategory"
                      :has-pagination="true"
                      :current-page="categorySelectConfig.currentPage"
                      :last-page="categorySelectConfig.lastPage"
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
                      label="نمایش در منو"
                      name="show_in_menu"
                      :enabled="category?.show_in_menu"
                      sr-text="نمایش/عدم نمایش دسته‌بندی در منو"
                      @change="(status) => {showInMenuStatus=status}"
                  />
                </div>
                <div class="p-2 w-full sm:w-auto sm:grow">
                  <base-switch
                      label="نمایش در منوی صفحه جستجو"
                      name="show_in_search_side_menu"
                      :enabled="category?.show_in_search_side_menu"
                      sr-text="نمایش/عدم نمایش دسته‌بندی در منوی صفحه جستجو"
                      @change="(status) => {showInSearchSideMenuStatus=status}"
                  />
                </div>
                <div class="p-2 w-full sm:w-auto sm:grow">
                  <base-switch
                      label="نمایش در اسلایدر"
                      name="show_in_slider"
                      :enabled="category?.show_in_slider"
                      sr-text="نمایش/عدم نمایش دسته‌بندی در اسلایدر"
                      @change="(status) => {showInSliderStatus=status}"
                  />
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

                  <span class="ml-auto">ویرایش دسته‌بندی</span>
                </base-animated-button>
              </div>
            </form>
          </template>
        </base-loading-panel>
      </div>
    </template>
  </partial-card>
</template>

<script setup>
import {onMounted, reactive, ref} from "vue";
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

const router = useRouter()
const toast = useToast()
const idParam = getRouteParamByKey('slug', null, false)

const category = ref(null)
const loading = ref(true)

const searchLoading = ref(true)
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

const publishStatus = ref(true)
const showInMenuStatus = ref(true)
const showInSearchSideMenuStatus = ref(true)
const showInSliderStatus = ref(true)

function categorySelectionChange(selected) {
  selectedCategory.value = selected
}

function searchCategory(query) {
  searchLoading.value = true
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
      searchLoading.value = false
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
  if (!canSubmit.value) return

  if (selectedCategory.value) {
    if (!selectedCategory.value.id) {
      actions.setFieldError('parent_category', 'دسته‌بندی والد نامعتبر می‌باشد.')
    } else if (selectedCategory.value.id === category.id) {
      actions.setFieldError('parent_category', 'دسته‌بندی فعلی با دسته‌بندی والد یکسان می‌باشد.')
    } else if (selectedCategory.value.level >= category.level) {
      actions.setFieldError('parent_category', 'دسته‌بندی با سطح بالاتر، نمی‌تواند والد دسته‌بندی با سطح پایین‌تر باشد.')
    }
    return
  }

  canSubmit.value = false

  CategoryAPI.updateById(idParam.value, {
    parent: selectedCategory.value?.id,
    name: values.name,
    level: selectedCategory.value?.level ?? 0,
    priority: values.priority,
    show_in_menu: showInMenuStatus.value,
    show_in_search_side_menu: showInSearchSideMenuStatus.value,
    show_in_slider: showInSliderStatus.value,
    is_published: publishStatus.value,
  }, {
    success(response) {
      setFormFields(response.data)

      if (response.data.slug !== category.slug) {
        router.push({name: 'admin.category.edit', params: {slug: response.data.slug}})
      }

      toast.success('ویرایش اطلاعات با موفقیت انجام شد.')
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
