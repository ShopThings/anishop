<template>
  <form>
    <partial-card class="mb-3 p-3 relative">
      <template #body>
        <loader-dot-orbit
          v-if="!canSubmit"
          container-bg-color="bg-blue-50 opacity-40"
          main-container-klass="absolute w-full h-full top-0 left-0 z-[2]"
        />

        <div class="p-2">
          <partial-input-label
            :is-optional="true"
            title="توضیحات"
          />
          <base-editor name="description"/>
        </div>
      </template>
    </partial-card>

    <partial-card>
      <template #body>
        <partial-stepy-next-prev-buttons
          :allow-next-step="canSubmit"
          :allow-prev-step="shouldGoPrevStep"
          :current-step="options.currentStep"
          :current-step-index="options.currentStepIndex"
          :last-step="options.lastStep"
          :loading="!canSubmit"
          :show-prev-step-button="shouldGoPrevStep"
          @next="handleNextClick(options.next)"
          @prev="() => {
            if(shouldGoPrevStep) {
              options.prev()
            }
          }"
        />

        <div
          v-if="Object.keys(errors)?.length"
          class="text-left px-3.5 mb-3"
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
      if (error?.errors && Object.keys(error.errors).length >= 1) {
        actions.setErrors(error.errors)
      }
    },
    finally() {
      canSubmit.value = true
    },
  })
})
</script>
