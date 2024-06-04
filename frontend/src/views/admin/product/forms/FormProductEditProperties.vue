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
              container-bg-color="bg-blue-50 opacity-40"
              main-container-klass="absolute w-full h-full top-0 left-0 z-[2]"
            />

            <div class="p-2">
              <partial-input-label title="ویژگی‌ها"/>
            </div>

            <base-property-builder v-model:properties="properties"/>

            <div v-if="errors.properties" class="p-2">
              <partial-input-error-message :error-message="errors.properties"/>
            </div>
          </template>
        </partial-card>

        <partial-card>
          <template #body>
            <partial-stepy-next-prev-buttons
              :allow-next-step="canSubmit"
              :allow-prev-step="canSubmit"
              :current-step="options.currentStep"
              :current-step-index="options.currentStepIndex"
              :last-step="options.lastStep"
              :loading="!canSubmit"
              :show-prev-step-button="canSubmit"
              @prev="options.prev"
              @finish="handleFinishClick"
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
  </base-loading-panel>
</template>

<script setup>
import {onMounted, ref} from "vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import PartialStepyNextPrevButtons from "@/components/partials/PartialStepyNextPrevButtons.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import BasePropertyBuilder from "@/components/base/BasePropertyBuilder.vue";
import LoaderDotOrbit from "@/components/base/loader/LoaderDotOrbit.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import {useToast} from "vue-toastification";
import {useFormSubmit} from "@/composables/form-submit.js";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import {ProductAPI} from "@/service/APIProduct.js";
import {getRouteParamByKey} from "@/composables/helper.js";

defineProps({
  options: {
    type: Object,
    required: true,
  },
})

const toast = useToast()
const slugParam = getRouteParamByKey('slug', null, false)

const loading = ref(true)

const properties = ref([])

function handleFinishClick() {
  onSubmit()
}

const {canSubmit, errors, onSubmit} = useFormSubmit({}, (values, actions) => {
  canSubmit.value = false

  ProductAPI.updateById(slugParam.value, {
    properties: getDefinedProperties(),
  }, {
    success() {
      toast.success('ویژگی‌های محصول ثبت شد.')
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
  ProductAPI.fetchById(slugParam.value, {
    success: (response) => {
      properties.value = response.data.properties
      loading.value = false
    },
  })
})

function getDefinedProperties() {
  const p = []

  for (let i of properties.value) {
    let children = []

    if (i.title.toString().trim() !== '' && i.children.length) {
      for (let c of i.children) {
        if (c.title.toString().trim() !== '' && c.tags.length) {
          children.push(c)
        }
      }

      if (children.length) {
        p.push({
          title: i.title,
          children: children,
        })
      }
    }
  }

  return p
}
</script>
