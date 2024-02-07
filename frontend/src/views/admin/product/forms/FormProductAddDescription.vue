<template>
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
          <base-editor name="description"/>
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
          :allow-prev-step="shouldGoPrevStep"
          :show-prev-step-button="shouldGoPrevStep"
          :loading="!canSubmit"
          @next="handleNextClick(options.next)"
          @prev="() => {
            if(shouldGoPrevStep) {
              options.prev()
            }
          }"
        />
      </template>
    </partial-card>
  </form>
</template>

<script setup>
import {computed} from "vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import PartialStepyNextPrevButtons from "@/components/partials/PartialStepyNextPrevButtons.vue";
import yup from "@/validation/index.js";
import BaseEditor from "@/components/base/BaseEditor.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import LoaderDotOrbit from "@/components/base/loader/LoaderDotOrbit.vue";
import {useCreationProductStore} from "@/store/StoreProduct.js";
import {useFormSubmit} from "@/composables/form-submit.js";
import {ProductAPI} from "@/service/APIProduct.js";
import {useToast} from "vue-toastification";

defineProps({
  options: {
    type: Object,
    required: true,
  },
})

const toast = useToast()
const productStore = useCreationProductStore()
const shouldGoPrevStep = computed(() => {
  return !(!!productStore.getProductSlug)
})

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

  ProductAPI.updateById(productStore.getProductSlug, {
    description: values.description,
  }, {
    success(response) {
      toast.success('توضیحات شما درباره محصول ثبت شد.')

      productStore.setProduct(response.data)
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
</script>
