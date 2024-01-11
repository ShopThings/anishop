<template>
  <partial-card>
    <template #header>
      ویرایش صفحه -
      <span
        v-if="page?.id"
        class="text-teal-600"
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
                  label="عدم نمایش صفحه"
                  on-label="نمایش صفحه"
                  name="is_published"
                  :enabled="page?.is_published"
                  sr-text="نمایش/عدم نمایش صفحه"
                  @change="(status) => {publishStatus=status}"
                />
              </div>

              <div class="flex flex-wrap">
                <div class="w-full p-2 md:w-1/2 xl:w-1/3">
                  <base-input
                    label-title="عنوان"
                    placeholder="وارد نمایید"
                    name="title"
                    :value="page?.title"
                  >
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="w-full p-2 md:w-1/2 xl:w-1/3">
                  <div class="flex items-end">
                    <base-input
                      label-title="آدرس"
                      placeholder="حروف لاتین"
                      name="url"
                      class="grow"
                      klass="text-left"
                      :value="page?.url"
                    >
                      <template #icon>
                        <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                      </template>
                    </base-input>
                    <label
                      dir="ltr"
                      class="mb-3 mr-1 shrink-0 text-gray-500"
                    >{{ host }}</label>
                  </div>
                </div>
              </div>

              <div class="p-2">
                <partial-input-label title="کلمات کلیدی"/>
                <vue3-tags-input
                  :tags="tags"
                  placeholder="کلمات کلیدی خود را وارد نمایید"
                  @on-tags-changed="(t) => {tags = t}"
                />
              </div>

              <div class="p-2">
                <partial-input-label title="اطلاعات نمایشی صفحه"/>
                <base-editor
                  name="description"
                  :value="page?.description"
                />
              </div>

              <div class="px-2 py-3">
                <base-animated-button
                  type="submit"
                  class="bg-emerald-500 text-white mr-auto px-6 w-full sm:w-auto"
                  :disabled="isSubmitting"
                >
                  <VTransitionFade>
                    <loader-circle
                      v-if="isSubmitting"
                      main-container-klass="absolute w-full h-full top-0 left-0"
                      big-circle-color="border-transparent"
                    />
                  </VTransitionFade>

                  <template #icon="{klass}">
                    <CheckIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
                  </template>

                  <span class="ml-auto">ویرایش صفحه</span>
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
import {ref} from "vue";
import {useForm} from "vee-validate";
import yup from "../../../validation/index.js";
import PartialCard from "../../../components/partials/PartialCard.vue";
import BaseSwitch from "../../../components/base/BaseSwitch.vue";
import LoaderCircle from "../../../components/base/loader/LoaderCircle.vue";
import VTransitionFade from "../../../transitions/VTransitionFade.vue";
import {ArrowLeftCircleIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "../../../components/base/BaseAnimatedButton.vue";
import BaseInput from "../../../components/base/BaseInput.vue";
import Vue3TagsInput from "vue3-tags-input";
import PartialInputLabel from "../../../components/partials/PartialInputLabel.vue";
import BaseEditor from "../../../components/base/BaseEditor.vue";
import BaseLoadingPanel from "../../../components/base/BaseLoadingPanel.vue";

const host = window.location.host + '/pages/'
const loading = ref(false)
const canSubmit = ref(true)

const page = ref(null)

const publishStatus = ref(true)
const tags = ref([])

const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return
})

// onMounted(() => {
//     useRequest(apiReplaceParams(apiRoutes.admin.staticPages.show, {static_page: idParam.value}), null, {
//         success: (response) => {
//             page.value = response.data
//             tags.value = response.data.keywords
//
//             loading.value = false
//         }
//     })
// })
</script>
