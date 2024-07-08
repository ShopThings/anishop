<template>
  <partial-card>
    <template #header>
      ویرایش برند -
      <span
          v-if="brand?.id"
          class="text-slate-400 text-base"
      >{{ brand?.name }}</span>
    </template>
    <template #body>
      <div class="p-3">
        <base-loading-panel
            :loading="loading"
            type="form"
        >
          <template #content>
            <form @submit.prevent="onSubmit">
              <div class="flex flex-wrap items-end justify-between">
                <div class="p-2">
                  <partial-input-label
                      title="انتخاب تصویر"
                  />
                  <base-media-placeholder
                      v-model:selected="brandImage"
                      type="image"
                  />
                  <partial-input-error-message :error-message="errors.image"/>
                </div>

                <div class="p-2">
                  <base-switch
                      :enabled="brand?.is_published"
                      label="عدم نمایش روش برند"
                      name="is_published"
                      on-label="نمایش برند"
                      sr-text="نمایش/عدم نمایش برند"
                      @change="(status) => {publishStatus=status}"
                  />
                </div>
              </div>

              <div class="flex flex-wrap items-start">
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input
                      :value="brand?.name"
                      label-title="نام فارسی"
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
                      :value="brand?.latin_name"
                      label-title="نام لاتین"
                      name="latin_name"
                      placeholder="وارد نمایید"
                  >
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="p-2">
                  <base-switch
                      :enabled="brand?.show_in_slider"
                      label="نمایش در اسلایدر"
                      name="show_in_slider"
                      sr-text="نمایش/عدم نمایش برند در اسلایدر"
                      @change="(status) => {showInSliderStatus=status}"
                  />
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

                  <span class="ml-auto">ویرایش برند</span>
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
import Vue3TagsInput from 'vue3-tags-input';
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import BaseMediaPlaceholder from "@/components/base/BaseMediaPlaceholder.vue";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {ArrowLeftCircleIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import {useToast} from "vue-toastification";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {BrandAPI} from "@/service/APIProduct.js";
import {getRouteParamByKey} from "@/composables/helper.js";

const toast = useToast()
const idParam = getRouteParamByKey('id')

const loading = ref(true)

const brand = ref(null)
const brandImage = ref(null)
const publishStatus = ref(true)
const showInSliderStatus = ref(true)
const tags = ref([])

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    name: yup.string().required('نام برند را وارد نمایید.'),
    latin_name: yup.string().required('نام لاتین را وارد نمایید.'),
  }),
}, (values, actions) => {
  if (!brandImage.value) {
    actions.setFieldError('image', 'تصویر را انتخاب نمایید.')
    return
  }

  BrandAPI.updateById(idParam.value, {
    name: values.name,
    latin_name: values.latin_name,
    image: brandImage.value.full_path,
    keywords: tags.value,
    show_in_slider: showInSliderStatus.value,
    is_published: publishStatus.value,
  }, {
    success(response) {
      setFormFields(response.data)
      toast.success('ویرایش اطلاعات با موفقیت انجام شد.')
    },
    finally() {
      canSubmit.value = true
    },
  })
})

onMounted(() => {
  BrandAPI.fetchById(idParam.value, {
    success(response) {
      setFormFields(response.data)
      loading.value = false
    },
  })
})

function setFormFields(item) {
  brand.value = item
  brandImage.value = item.image
  publishStatus.value = item.is_published
  showInSliderStatus.value = item.show_in_slider
  tags.value = item.keywords
}
</script>
