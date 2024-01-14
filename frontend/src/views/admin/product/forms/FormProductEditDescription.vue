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
              v-if="isSubmitting"
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
              :allow-next-step="!isSubmitting"
              :allow-prev-step="false"
              :show-prev-step-button="false"
              :loading="isSubmitting"
              @next="handleNextClick(options.next)"
            />
          </template>
        </partial-card>
      </form>
    </template>
  </base-loading-panel>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import PartialStepyNextPrevButtons from "@/components/partials/PartialStepyNextPrevButtons.vue";
import {useForm} from "vee-validate";
import yup from "@/validation/index.js";
import BaseEditor from "@/components/base/BaseEditor.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import LoaderDotOrbit from "@/components/base/loader/LoaderDotOrbit.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import {useRoute} from "vue-router";
import {useToast} from "vue-toastification";

defineProps({
  options: {
    type: Object,
    required: true,
  },
})

const route = useRoute()
const toast = useToast()
const idParam = computed(() => {
  const id = parseInt(route.params.id, 10)
  if (isNaN(id)) return route.params.id
  return id
})

const loading = ref(false)
const canSubmit = ref(true)

const info = ref(null)

let nextFn = null

function handleNextClick(next) {
  onSubmit()
  nextFn = next
}

const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return

  return new Promise((resolve) => {
    setTimeout(() => {
      resolve()
      if (nextFn)
        nextFn()
    }, 2000)
  })
})

onMounted(() => {
  // useRequest(apiReplaceParams(apiRoutes.admin.products.show, {product: idParam.value}), null, {
  //     success: (response) => {
  //         info.value = response.data
  //
  //         loading.value = false
  //     },
  // })
})
</script>
