<template>
  <partial-card>
    <template #header>
      ایجاد بلاگ جدید
    </template>
    <template #body>
      <div class="p-3">
        <form @submit.prevent="onSubmit">
          <div class="flex flex-wrap items-end justify-between">
            <div class="p-2">
              <partial-input-label title="انتخاب تصویر"/>
              <base-media-placeholder
                v-model:selected="blogImage"
                type="image"
              />
              <partial-input-error-message :error-message="errors.image"/>
            </div>

            <div class="p-2">
              <base-switch
                :enabled="true"
                label="عدم نمایش بلاگ"
                name="is_published"
                on-label="نمایش بلاگ"
                sr-text="نمایش/عدم نمایش بلاگ"
                @change="(status) => {publishStatus=status}"
              />
            </div>
          </div>

          <div class="flex flex-wrap">
            <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
              <base-input
                label-title="عنوان بلاگ"
                name="title"
                placeholder="عنوان را وارد نمایید"
              >
                <template #icon>
                  <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                </template>
              </base-input>
            </div>
            <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
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

          <div class="p-2">
            <base-switch
              :enabled="true"
              label="اجازه ارسال دیدگاه"
              name="is_commenting_allowed"
              sr-text="اجازه/عدم اجازه ارسال دیدگاه"
              @change="(status) => {allowCommentingStatus=status}"
            />
          </div>

          <div class="p-2">
            <partial-input-label
              :is-optional="true"
              title="کلمات کلیدی"
            />
            <base-tags-input
              :tags="tags"
              placeholder="کلمات کلیدی خود را وارد نمایید"
              @on-tags-changed="(t) => {tags = t}"
            />
          </div>

          <div class="p-2">
            <base-textarea
              label-title="توضیحات مختصر"
              name="brief_description"
              placeholder="تا ۳۰۰ کاراکتر خلاصه نوشته را وارد نمایید..."
            >
              <template #icon>
                <ArrowLeftCircleIcon class="h-6 w-6 mt-3 text-gray-400"/>
              </template>
            </base-textarea>
          </div>

          <div class="p-2">
            <partial-input-label title="نوشته خود را وارد کنید"/>
            <base-editor name="description"/>
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

              <span class="ml-auto">ایجاد بلاگ</span>
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
import BaseMediaPlaceholder from "@/components/base/BaseMediaPlaceholder.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import yup from "@/validation/index.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import {ArrowLeftCircleIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import BaseEditor from "@/components/base/BaseEditor.vue";
import BaseTagsInput from "@/components/base/BaseTagsInput.vue";
import BaseSelectSearchable from "@/components/base/BaseSelectSearchable.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {BlogAPI, BlogCategoryAPI} from "@/service/APIBlog.js";
import {useRouter} from "vue-router";
import {useSelectSearching} from "@/composables/select-searching.js";
import BaseTextarea from "@/components/base/BaseTextarea.vue";

const router = useRouter()

const blogImage = ref(null)
const tags = ref([])
const publishStatus = ref(true)
const allowCommentingStatus = ref(true)

//---------------------------------------------------------
// Category operation
//---------------------------------------------------------
const categories = ref([])
const selectedCategory = ref(null)
const categorySelectConfig = useSelectSearching({
  searchFn(query) {
    BlogCategoryAPI.fetchAll({
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
const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    title: yup.string().required('عنوان را وارد نمایید.'),
    is_published: yup.boolean().required('وضعیت انتشار را مشخص کنید.'),
    is_commenting_allowed: yup.boolean().required('وضعیت اجازه ارسال دیدگاه را مشخص کنید.'),
    brief_description: yup.string().required('خلاصه نوشته را وارد نمایید.'),
    description: yup.string().required('نوشته بلاگ را وارد نمایید.'),
  }),
}, (values, actions) => {
  if (!blogImage.value) {
    actions.setFieldError('image', 'تصویر را انتخاب نمایید.')
    return
  }

  if (!selectedCategory.value) {
    actions.setFieldError('category', 'دسته‌بندی را وارد نمایید.')
    return
  }

  canSubmit.value = false

  BlogAPI.create({
    category: selectedCategory.value.id,
    title: values.title,
    image: blogImage.value.full_path,
    brief_description: values.brief_description,
    description: values.description,
    keywords: tags.value,
    is_commenting_allowed: allowCommentingStatus.value,
    is_published: publishStatus.value,
  }, {
    success() {
      actions.resetForm()
      router.push({name: 'admin.blogs'})
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
  searchCategory()
})
</script>
