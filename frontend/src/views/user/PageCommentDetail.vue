<template>
  <div
    v-if="!loading"
    class="mb-3"
  >
    <base-animated-button
      :disabled="isDeleting"
      class="bg-rose-500 text-white mr-auto px-6 text-sm"
      type="button"
      @click="deleteComment"
    >
      <VTransitionFade>
        <loader-circle
          v-if="isDeleting"
          big-circle-color="border-transparent"
          main-container-klass="absolute w-full h-full top-0 left-0"
        />
      </VTransitionFade>

      <template #icon="{klass}">
        <TrashIcon :class="klass" class="size-5 sm:ml-2"/>
      </template>

      <span class="ml-auto">حذف دیدگاه</span>
    </base-animated-button>
  </div>

  <div class="mb-3">
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

  <div
    v-if="!loading"
    class="mb-3"
  >
    <partial-badge-condition-comment
      :condition="comment?.condition"
      class="w-full py-2 !text-sm"
    />
  </div>
  <div
    v-else
    class="flex gap-2 items-center justify-center bg-slate-200 animate-pulse w-full h-8 rounded my-3"
  >
    <div class="w-36 h-3 rounded bg-blue-300"></div>
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
              <div class="px-3 pt-3">
                <base-message
                  :has-close="false"
                  class="rounded-md"
                  type="info"
                >
                  <div class="leading-relaxed">
                    امکان ویرایش پس از تغییر وضعیت توسط سایت، وجود ندارد.
                  </div>
                </base-message>
              </div>

              <div class="px-3 py-2 vue3-tags-pros-container">
                <partial-input-label :is-optional="true" title="مزایای محصول"/>
                <base-tags-input
                  :add-tag-on-keys="[13, 188]"
                  :read-only="comment?.is_condition_changed"
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
                  :read-only="comment?.is_condition_changed"
                  :tags="cons"
                  placeholder="وارد نمایید"
                  @on-tags-changed="(t) => {cons = t}"
                />
                <partial-input-error-message :error-message="errors.cons"/>
              </div>
              <div class="px-3 py-2">
                <base-textarea
                  :in-edit-mode="!comment?.is_condition_changed"
                  :is-editable="!comment?.is_condition_changed"
                  :value="comment?.description"
                  label-title="توضیحات"
                  name="description"
                  placeholder="دیدگاه خود را وارد نمایید..."
                />
              </div>
              <div
                v-if="comment?.answer"
                class="px-3 py-2"
              >
                <partial-input-label title="پاسخ به شما"/>
                <p class="text-sm p-3 rounded-lg bg-blue-50">{{ comment.answer }}</p>
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
            </template>
          </partial-card>
        </form>
      </template>
    </base-loading-panel>
  </div>
</template>

<script setup>
import {onMounted, ref} from "vue";
import {ArrowLongLeftIcon, CheckIcon, TrashIcon} from "@heroicons/vue/24/outline/index.js";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import BaseMessage from "@/components/base/BaseMessage.vue";
import yup from "@/validation/index.js";
import PartialBadgeConditionComment from "@/components/partials/PartialBadgeConditionComment.vue";
import BaseTextarea from "@/components/base/BaseTextarea.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import BaseTagsInput from "@/components/base/BaseTagsInput.vue";
import {FileSizes} from "@/composables/file-list.js";
import {useFormSubmit} from "@/composables/form-submit.js";
import {UserPanelCommentAPI} from "@/service/APIUserPanel.js";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import {getRouteParamByKey} from "@/composables/helper.js";
import {useToast} from "vue-toastification";
import {useRouter} from "vue-router";
import {useConfirmToast} from "@/composables/toast-helper.js";

const router = useRouter()
const toast = useToast()
const idParam = getRouteParamByKey('id', null, false)

const loading = ref(true)
const product = ref(null)
const comment = ref(null)
const pros = ref([])
const cons = ref([])

//--------------------------------------
// Delete operation
//--------------------------------------
const isDeleting = ref(false)

function deleteComment() {
  if (isDeleting.value) return

  useConfirmToast(
    () => {
      isDeleting.value = true

      UserPanelCommentAPI.deleteById(idParam.value, {
        success() {
          toast.success('دیدگاه با موفقیت حذف شد.')
          router.push({name: 'user.comments'})
        },
        finally() {
          isDeleting.value = false
        },
      })
    },
    'آیا از حذف دیدگاه خود مطمئن هستید؟',
    'دیدگاه شما به صورت دائمی حذف خواهد شد.'
  )
}

//--------------------------------------
const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    description: yup.string().required('دیدگاه خود را وارد نمایید.'),
  }),
}, (values, actions) => {
  if (comment?.is_condition_changed) {
    toast.error('امکان تغییر دیدگاه وجود ندارد.')
    return
  }

  canSubmit.value = false

  UserPanelCommentAPI.updateById(idParam.value, {
    pros: pros.value,
    cons: cons.value,
    description: values.description,
  }, {
    success(response) {
      toast.success('ویرایش دیدگاه انجام شد.')
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
  UserPanelCommentAPI.fetchById(idParam.value, {
    success(response) {
      setFormFields(response.data)
      loading.value = false
    },
  })
})

function setFormFields(item) {
  comment.value = item
  pros.value = item.pros
  cons.value = item.cons
  product.value = item.product
}
</script>

<style>
.vue3-tags-pros-container .v3ti .v3ti-tag {
  background: #0d9488 !important;
}

.vue3-tags-cons-container .v3ti .v3ti-tag {
  background: #db2777 !important;
}
</style>
