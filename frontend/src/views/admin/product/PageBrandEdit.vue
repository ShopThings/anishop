<template>
  <partial-card>
    <template #header>
      ویرایش برند -
      <span
        v-if="brand?.id"
        class="text-teal-600"
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
                    type="image"
                    :selected="brand?.image"
                  />
                </div>

                <div class="p-2">
                  <base-switch
                    label="عدم نمایش روش برند"
                    on-label="نمایش برند"
                    name="is_published"
                    :enabled="brand?.is_published"
                    sr-text="نمایش/عدم نمایش برند"
                    @change="(status) => {publishStatus=status}"
                  />
                </div>
              </div>

              <div class="flex flex-wrap items-end">
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input
                    label-title="نام فارسی"
                    placeholder="وارد نمایید"
                    name="name"
                    :value="brand?.name"
                  >
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input
                    label-title="نام لاتین"
                    placeholder="وارد نمایید"
                    name="latin_name"
                    :value="brand?.latin_name"
                  >
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="p-2">
                  <base-switch
                    label="نمایش در اسلایدر"
                    name="show_in_slider"
                    :enabled="brand?.show_in_slider"
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
                  @on-tags-changed="handleChangeTag"
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

                  <span class="ml-auto">ویرایش برند</span>
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
import {computed, onMounted, ref} from "vue";
import {useForm} from "vee-validate";
import yup from "../../../validation/index.js";
import Vue3TagsInput from 'vue3-tags-input';
import BaseLoadingPanel from "../../../components/base/BaseLoadingPanel.vue";
import PartialCard from "../../../components/partials/PartialCard.vue";
import PartialInputLabel from "../../../components/partials/PartialInputLabel.vue";
import BaseMediaPlaceholder from "../../../components/base/BaseMediaPlaceholder.vue";
import BaseSwitch from "../../../components/base/BaseSwitch.vue";
import LoaderCircle from "../../../components/base/loader/LoaderCircle.vue";
import VTransitionFade from "../../../transitions/VTransitionFade.vue";
import {CheckIcon, ArrowLeftCircleIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "../../../components/base/BaseAnimatedButton.vue";
import BaseInput from "../../../components/base/BaseInput.vue";
import {useRequest} from "../../../composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "../../../router/api-routes.js";
import {useRoute, useRouter} from "vue-router";
import {useToast} from "vue-toastification";

const router = useRouter()
const route = useRoute()
const toast = useToast()
const idParam = computed(() => {
  const id = parseInt(route.params.id, 10)
  if (isNaN(id)) return route.params.id
  return id
})

const loading = ref(false)
const canSubmit = ref(true)

const brand = ref(null)
const publishStatus = ref(true)
const showInSliderStatus = ref(true)
const tags = ref([])

function handleChangeTag(tags) {
  tags.value = tags;
}

const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return
})

onMounted(() => {
  // useRequest(apiReplaceParams(apiRoutes.admin.brands.show, {brand: idParam.value}), null, {
  //   success: (response) => {
  //     brand.value = response.data
  //     tags.value = response.data.keywords
  //
  //     loading.value = false
  //   },
  // })
})
</script>
