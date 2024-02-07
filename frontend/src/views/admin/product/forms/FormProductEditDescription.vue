<template>
  <base-loading-panel
    :loading="loading"
    type="form"
  >
    <template #content>
      <form>
        <partial-card class="mb-3 p-3 relative">
          <template #body>
            <loader-dot-orbit
              v-if="!canSubmit"
              main-container-klass="absolute w-full h-full top-0 left-0 z-[2]"
              container-bg-color="bg-blue-50 opacity-40"
            />

            <div class="p-2">
              <partial-input-label
                title="توضیحات"
                :is-optional="true"
              />
              <base-editor
                name="description"
                :value="info?.description"
              />
            </div>
          </template>
        </partial-card>

        <partial-card>
          <template #body>
            <partial-stepy-next-prev-buttons
              :current-step="options.currentStep"
              :current-step-index="options.currentStepIndex"
              :last-step="options.lastStep"
              :allow-next-step="canSubmit"
              :allow-prev-step="canSubmit"
              :show-prev-step-button="canSubmit"
              :loading="!canSubmit"
              @next="handleNextClick(options.next)"
            />
          </template>
        </partial-card>
      </form>
    </template>
  </base-loading-panel>
</template>

<script setup>
import {onMounted, ref} from "vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import PartialStepyNextPrevButtons from "@/components/partials/PartialStepyNextPrevButtons.vue";
import yup from "@/validation/index.js";
import BaseEditor from "@/components/base/BaseEditor.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import LoaderDotOrbit from "@/components/base/loader/LoaderDotOrbit.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import {useToast} from "vue-toastification";
import {getRouteParamByKey} from "@/composables/helper.js";
import {useFormSubmit} from "@/composables/form-submit.js";
import {ProductAPI} from "@/service/APIProduct.js";

defineProps({
  options: {
    type: Object,
    required: true,
  },
})

const toast = useToast()
const slugParam = getRouteParamByKey('slug', null, false)

const loading = ref(true)

const info = ref(null)

let nextFn = null

function handleNextClick(next) {
  onSubmit()
  nextFn = next
}

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    description: yup.string().optional(),
  }),
}, (values, actions) => {
  if (!canSubmit.value) return

  canSubmit.value = false

  ProductAPI.updateById(slugParam.value, {
    description: values.description,
  }, {
    success() {
      toast.success('توضیحات شما درباره محصول ثبت شد.')
      if (nextFn) nextFn()
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
  ProductAPI.fetchById(slugParam.value, {
    success: (response) => {
      info.value = response.data
      loading.value = false
    },
  })
})
</script>
