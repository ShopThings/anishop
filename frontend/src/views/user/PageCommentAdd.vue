<template>
  <div class="mb-3">
    <h2 class="text-slate-400 mb-1">
      دیدگاه شما درباره محصول
    </h2>

    <base-loading-panel
        :loading="loading"
        type="list-single"
    >
      <template #content>
        <partial-card class="border-0">
          <template #body>
            <div class="py-3 px-4">
              <div class="flex flex-col sm:flex-row gap-3 items-center">
                <div class="shrink-0">
                  <base-lazy-image
                      :alt="product?.title"
                      :lazy-src="product?.image?.path"
                      :size="FileSizes.SMALL"
                      class="!h-28 sm:!h-20 w-auto rounded"
                  />
                </div>
                <div class="grow text-sm">
                  {{ product?.title }}
                </div>
                <div class="text-sm shrink-0">
                  <router-link
                      :to="{name: 'product.detail', params: {slug: product?.slug}}"
                      class="flex items-center gap-2 text-blue-600 hover:text-opacity-90 group"
                  >
                    <span class="mx-auto">مشاهده محصول</span>
                    <ArrowLongLeftIcon class="w-6 h-6 group-hover:-translate-x-1.5 transition"/>
                  </router-link>
                </div>
              </div>
            </div>
          </template>
        </partial-card>
      </template>
    </base-loading-panel>
  </div>

  <div>
    <h2 class="text-slate-400 mb-1">
      دیدگاه شما
    </h2>

    <base-loading-panel
        :loading="loading"
        type="form"
    >
      <template #content>
        <form @submit.prevent="onSubmit">
          <partial-card class="border-0">
            <template #body>
              <div class="px-3 py-2 vue3-tags-pros-container">
                <partial-input-label :is-optional="true" title="مزایای محصول"/>
                <base-tags-input
                    :add-tag-on-keys="[13, 188]"
                    :tags="pros"
                    placeholder="وارد نمایید"
                    @on-tags-changed="(t) => {pros = t}"
                />
                <partial-input-error-message :error-message="errors.pros"/>
              </div>
              <div class="px-3 py-2 vue3-tags-cons-container">
                <partial-input-label :is-optional="true" title="معایب محصول"/>
                <base-tags-input
                    :add-tag-on-keys="[13, 188]"
                    :tags="cons"
                    placeholder="وارد نمایید"
                    @on-tags-changed="(t) => {cons = t}"
                />
                <partial-input-error-message :error-message="errors.cons"/>
              </div>
              <div class="px-3 py-2">
                <base-textarea
                    label-title="توضیحات"
                    name="description"
                    placeholder="دیدگاه خود را وارد نمایید..."
                />
              </div>

              <div class="p-3">
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

                  <span class="ml-auto">ثبت دیدگاه</span>
                </base-animated-button>
              </div>
            </template>
          </partial-card>
        </form>
      </template>
    </base-loading-panel>
  </div>
</template>

<script setup>
import {onMounted, ref} from "vue";
import {ArrowLongLeftIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import yup from "@/validation/index.js";
import BaseTextarea from "@/components/base/BaseTextarea.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import BaseTagsInput from "@/components/base/BaseTagsInput.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {getRouteParamByKey} from "@/composables/helper.js";
import {HomeProductAPI} from "@/service/APIHomePages.js";
import {UserPanelCommentAPI} from "@/service/APIUserPanel.js";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import {FileSizes} from "@/composables/file-list.js";

const slugParam = getRouteParamByKey('slug', null, false)

const loading = ref(true)
const product = ref(null)

const pros = ref([])
const cons = ref([])

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    description: yup.string().required('دیدگاه خود را وارد نمایید.'),
  }),
}, (values, actions) => {
  canSubmit.value = false

  UserPanelCommentAPI.create(slugParam.value, {
    pros: pros.value,
    cons: cons.value,
    description: values.description,
  }, {
    success() {
      actions.resetForm()
      router.push({name: 'product.detail', params: {slug: slugParam.value}})
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
  HomeProductAPI.fetchByIdMinified(slugParam.value, {
    success(response) {
      product.value = response.data
      loading.value = false
    },
  })
})
</script>

<style>
.vue3-tags-pros-container .v3ti .v3ti-tag {
  background: #0d9488 !important;
}

.vue3-tags-cons-container .v3ti .v3ti-tag {
  background: #db2777 !important;
}
</style>
