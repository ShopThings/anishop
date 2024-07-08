<template>
  <partial-card>
    <template #header>
      ویرایش دسته‌بندی بلاگ
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
                  :enabled="blogCategory?.is_published"
                  label="عدم نمایش بلاگ"
                  name="is_published"
                  on-label="نمایش بلاگ"
                  sr-text="نمایش/عدم نمایش بلاگ"
                  @change="(status) => {publishStatus=status}"
                />
              </div>

              <div class="flex flex-wrap">
                <div class="w-full p-2 sm:w-1/2">
                  <base-input
                    :value="blogCategory?.name"
                    label-title="نام دسته‌بندی"
                    name="name"
                    placeholder="وارد نمایید"
                  >
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="w-full p-2 sm:w-1/2">
                  <base-input
                    :min="0"
                    :money-mask="true"
                    :value="blogCategory?.priority?.toString()"
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

              <div class="flex flex-wrap">
                <div class="w-full p-2 sm:w-1/2">
                  <base-switch
                    :enabled="blogCategory?.show_in_menu"
                    label="نمایش در منوی اصلی"
                    name="show_in_menu"
                    sr-text="نمایش/عدم نمایش در منوی اصلی"
                    @change="(status) => {showInMenuStatus=status}"
                  />
                </div>
                <div class="w-full p-2 sm:w-1/2">
                  <base-switch
                    :enabled="blogCategory?.show_in_side_menu"
                    label="نمایش در منوی کناری"
                    name="show_in_side_menu"
                    sr-text="نمایش/عدم نمایش در منوی کناری"
                    @change="(status) => {showInSideMenuStatus=status}"
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

                  <span class="ml-auto">ویرایش دسته‌بندی بلاگ</span>
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
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import yup from "@/validation/index.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import {ArrowLeftCircleIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import {useToast} from "vue-toastification";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import BaseTagsInput from "@/components/base/BaseTagsInput.vue";
import {getRouteParamByKey} from "@/composables/helper.js";
import {useFormSubmit} from "@/composables/form-submit.js";
import {BlogCategoryAPI} from "@/service/APIBlog.js";

const toast = useToast()
const slugParam = getRouteParamByKey('slug', null, false)

const loading = ref(true)
const blogCategory = ref(null)

const tags = ref([])
const publishStatus = ref(true)
const showInMenuStatus = ref(true)
const showInSideMenuStatus = ref(true)

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    name: yup.string().required('نام دسته‌بندی را وارد نمایید.'),
    priority: yup.number()
      .min(0, 'مقدار اولویت باید بزرگتر از صفر باشد.')
      .required('اولویت را وارد نمایید.'),
  }),
}, (values, actions) => {
  canSubmit.value = false

  BlogCategoryAPI.updateById(slugParam.value, {
    name: values.name,
    priority: values.priority,
    keywords: tags.value,
    is_published: publishStatus.value,
    show_in_menu: showInMenuStatus.value,
    show_in_side_menu: showInSideMenuStatus.value,
  }, {
    success(response) {
      toast.success('ویرایش اطلاعات با موفقیت انجام شد.')

      if (response.data.slug !== blogCategory.slug) {
        router.push({name: 'admin.blog.category.edit', params: {slug: response.data.slug}})
      }

      setFormFields(response.data)
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
  BlogCategoryAPI.fetchById(slugParam.value, {
    success: (response) => {
      setFormFields(response.data)
      loading.value = false
    },
  })
})

function setFormFields(item) {
  blogCategory.value = item
  tags.value = item.keywords
  showInMenuStatus.value = item.show_in_menu
  showInSideMenuStatus.value = item.show_in_side_menu
  publishStatus.value = item.is_published
}
</script>
