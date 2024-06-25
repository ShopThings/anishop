<template>
  <partial-card>
    <template #header>
      ویرایش صفحه -
      <span
        v-if="page?.id"
        class="text-slate-400 text-base"
      >{{ page?.title }}</span>
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
                  :enabled="publishStatus"
                  label="عدم نمایش صفحه"
                  name="is_published"
                  on-label="نمایش صفحه"
                  sr-text="نمایش/عدم نمایش صفحه"
                  @change="(status) => {publishStatus=status}"
                />
              </div>

              <div class="flex flex-wrap">
                <div class="w-full p-2 md:w-1/2 xl:w-1/2">
                  <base-input
                    :value="page?.title"
                    label-title="عنوان"
                    name="title"
                    placeholder="وارد نمایید"
                  >
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="w-full p-2 xl:w-1/2 flex flex-col gap-2 items-end">
                  <base-input
                    :value="page?.url"
                    class="grow"
                    klass="text-left"
                    label-title="آدرس"
                    name="url"
                    placeholder="حروف لاتین"
                    :readonly="!page?.is_deletable"
                  >
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>

                  <div class="text-sm" dir="ltr">
                    <label
                      class="mr-[1px] text-gray-500"
                      dir="ltr"
                    >{{ host }}</label>
                    <span>{{ values?.url || '[page-address]' }}</span>
                  </div>
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
                <partial-input-label title="اطلاعات نمایشی صفحه"/>
                <base-editor
                  :value="page?.description"
                  name="description"
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

                  <span class="ml-auto">ویرایش صفحه</span>
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
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {ArrowLeftCircleIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import BaseEditor from "@/components/base/BaseEditor.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import BaseTagsInput from "@/components/base/BaseTagsInput.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {StaticPageAPI} from "@/service/APIPage.js";
import {getRouteParamByKey} from "@/composables/helper.js";

const idParam = getRouteParamByKey('slug', null, false)

const host = window.location.host + '/pages/'
const loading = ref(false)

const page = ref(null)
const publishStatus = ref(true)
const tags = ref([])

const {canSubmit, errors, onSubmit, values} = useFormSubmit({
  validationSchema: yup.object().shape({
    title: yup.string().required('عنوان را وارد نمایید.'),
    url: yup.string()
      .required('آدرس صفحه را وارد نمایید.')
      .matches(/[a-z]+[a-z/-][a-z]+/g, 'آدرس صفحه نامعتبر می‌باشد.'),
    is_published: yup.boolean().required('وضعیت انتشار را مشخص کنید.'),
    description: yup.string().required('نوشته بلاگ را وارد نمایید.'),
  }),
}, (values, actions) => {
  canSubmit.value = false

  let data = {
    title: values.title,
    description: values.description,
    keywords: tags.value,
    is_published: publishStatus.value,
  }

  if (!page?.is_deletable) {
    data.url = values.url
  }

  StaticPageAPI.updateById(idParam.value, {
    success(response) {
      toast.success('ویرایش اطلاعات با موفقیت انجام شد.')

      if (response.data.slug !== blog.slug) {
        router.push({name: 'admin.blog.edit', params: {slug: response.data.slug}})
      }

      tinyMCE.activeEditor.setContent(response.data.description)

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
  StaticPageAPI.fetchById(idParam.value, {
    success: (response) => {
      setFormFields(response.data)
      loading.value = false
    },
  })
})

function setFormFields(item) {
  page.value = item
  tags.value = item.keywords
  publishStatus.value = item.is_published
}
</script>
