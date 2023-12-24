<template>
  <form>
    <partial-card class="mb-3 p-3 relative">
      <template #body>
        <loader-dot-orbit
            v-if="isSubmitting"
            main-container-klass="absolute w-full h-full top-0 left-0 z-[2]"
            container-bg-color="bg-blue-50 opacity-40"
        />

        <div class="p-2">
          <partial-input-label title="ویژگی‌ها"/>
        </div>
        <base-property-builder v-model:properties="properties"/>
      </template>
    </partial-card>

    <partial-card>
      <template #body>
        <partial-stepy-next-prev-buttons
            :current-step="options.currentStep"
            :current-step-index="options.currentStepIndex"
            :last-step="options.lastStep"
            :allow-next-step="!isSubmitting"
            :allow-prev-step="false"
            :show-prev-step-button="false"
            :loading="isSubmitting"
            @finish="handleFinishClick"
        />
      </template>
    </partial-card>
  </form>
</template>

<script setup>
import {ref} from "vue";
import PartialCard from "../../../../components/partials/PartialCard.vue";
import PartialStepyNextPrevButtons from "../../../../components/partials/PartialStepyNextPrevButtons.vue";
import {useForm} from "vee-validate";
import yup from "../../../../validation/index.js";
import PartialInputLabel from "../../../../components/partials/PartialInputLabel.vue";
import BasePropertyBuilder from "../../../../components/base/BasePropertyBuilder.vue";
import LoaderDotOrbit from "../../../../components/base/loader/LoaderDotOrbit.vue";

defineProps({
  options: {
    type: Object,
    required: true,
  },
})

const canSubmit = ref(true)

const properties = ref([])

function handleFinishClick() {
  onSubmit()
}

const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return

  return new Promise((resolve) => {
    setTimeout(() => {
      resolve()
    }, 2000)
  })
})
</script>

<style scoped>

</style>
