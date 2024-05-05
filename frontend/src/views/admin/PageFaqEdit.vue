<template>
  <partial-card>
    <template #header>
      ویرایش سؤال
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
                  :enabled="faq?.is_published"
                  label="عدم نمایش سؤال"
                  name="is_published"
                  on-label="نمایش سؤال"
                  sr-text="نمایش/عدم نمایش سؤال"
                  @change="(status) => {publishStatus=status}"
                />
              </div>

              <div class="p-2">
                <base-input
                  :value="faq?.question"
                  label-title="سؤال"
                  name="question"
                  placeholder="وارد نمایید"
                >
                  <template #icon>
                    <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                  </template>
                </base-input>
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
                <partial-input-label title="پاسخ"/>
                <base-editor
                  :value="faq?.answer"
                  name="answer"
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

                  <span class="ml-auto">ویرایش سؤال</span>
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
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {ArrowLeftCircleIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import BaseEditor from "@/components/base/BaseEditor.vue";
import {useToast} from "vue-toastification";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import {getRouteParamByKey} from "@/composables/helper.js";
import BaseTagsInput from "@/components/base/BaseTagsInput.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {FaqAPI} from "@/service/APIPage.js";

const toast = useToast()
const idParam = getRouteParamByKey('id')

const loading = ref(false)

const faq = ref(null)
const publishStatus = ref(true)
const tags = ref([])

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    question: yup.string().required('سؤال را وارد نمایید.'),
    answer: yup.string().required('پاسخ به سؤال را وارد نمایید.'),
    is_published: yup.boolean().required('وضعیت انتشار را مشخص کنید.'),
  }),
}, (values, actions) => {
  canSubmit.value = false

  FaqAPI.updateById(idParam.value, {
    question: values.question,
    answer: values.answer,
    keywords: tags.value,
    is_published: publishStatus.value,
  }, {
    success(response) {
      toast.success('ویرایش اطلاعات با موفقیت انجام شد.')
      tinyMCE.activeEditor.setContent(response.data.answer)

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
  FaqAPI.fetchById(idParam.value, {
    success: (response) => {
      setFormFields(response.data)
      loading.value = false
    },
  })
})

function setFormFields(item) {
  faq.value = item
  tags.value = item.keywords
  publishStatus.value = item.is_published
}
</script>
