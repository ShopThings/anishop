<template>
  <div class="mb-3">
    <h2 class="text-slate-400 mb-1">
      دیدگاه شما درباره محصول
    </h2>

    <base-loading-panel
      type="list-single"
      :loading="loading"
    >
      <template #content>
        <partial-card class="border-0">
          <template #body>
            <div class="py-3 px-4">
              <div class="flex flex-col sm:flex-row gap-3 items-center">
                <div class="shrink-0">
                  <base-lazy-image
                    alt="تصویر محصول"
                    lazy-src="/src/assets/products/p1.jpg"
                    class="!h-28 sm:!h-20 w-auto"
                  />
                </div>
                <div class="grow text-sm">
                  لپتاپ خیلی باحال و کاربردی عمو فردوس
                </div>
                <div class="text-sm shrink-0">
                  <router-link
                    :to="{name: 'product.detail', params: {id: 1}}"
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

  <div
    v-if="1"
    class="mb-3"
  >
    <partial-badge-condition-comment class="w-full py-2 !text-sm"/>
  </div>

  <div>
    <h2 class="text-slate-400 mb-1">
      دیدگاه شما
    </h2>

    <base-loading-panel
      type="list-single"
      :loading="loading"
    >
      <template #content>
        <form @submit.prevent="onSubmit">
          <partial-card class="border-0">
            <template #body>
              <div class="px-3 pt-3">
                <base-message
                  type="info"
                  :has-close="false"
                  class="rounded-md"
                >
                  <div class="leading-relaxed">
                    امکان ویرایش پس از تغییر وضعیت توسط سایت، وجود ندارد.
                  </div>
                </base-message>
              </div>

              <div class="px-3 py-2 vue3-tags-pros-container">
                <partial-input-label title="مزایای محصول" :is-optional="true"/>
                <vue3-tags-input
                  placeholder="وارد نمایید"
                  :tags="pros"
                  :read-only="false"
                  @on-tags-changed="(t) => {pros = t}"
                />
              </div>
              <div class="px-3 py-2 vue3-tags-cons-container">
                <partial-input-label title="معایب محصول" :is-optional="true"/>
                <vue3-tags-input
                  placeholder="وارد نمایید"
                  :tags="cons"
                  @on-tags-changed="(t) => {cons = t}"
                />
              </div>
              <div class="px-3 py-2">
                <base-textarea
                  name="description"
                  label-title="توضیحات"
                  placeholder="دیدگاه خود را وارد نمایید..."
                  :has-edit-mode="true"
                  :is-editable="true"
                />
              </div>

              <div class="p-3">
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
import {ref} from "vue";
import {ArrowLongLeftIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import BaseLoadingPanel from "../../components/base/BaseLoadingPanel.vue";
import PartialCard from "../../components/partials/PartialCard.vue";
import BaseLazyImage from "../../components/base/BaseLazyImage.vue";
import BaseMessage from "../../components/base/BaseMessage.vue";
import {useForm} from "vee-validate";
import yup from "../../validation/index.js";
import PartialBadgeConditionComment from "../../components/partials/PartialBadgeConditionComment.vue";
import BaseTextarea from "../../components/base/BaseTextarea.vue";
import Vue3TagsInput from "vue3-tags-input";
import PartialInputLabel from "../../components/partials/PartialInputLabel.vue";
import VTransitionFade from "../../transitions/VTransitionFade.vue";
import BaseAnimatedButton from "../../components/base/BaseAnimatedButton.vue";
import LoaderCircle from "../../components/base/loader/LoaderCircle.vue";

const loading = ref(false)

const pros = ref([])
const cons = ref([])

const canSubmit = ref(true)
const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return
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
