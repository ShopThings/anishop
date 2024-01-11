<template>
  <partial-card>
    <template #header>
      ویرایش واحد محصول -
      <span
        v-if="unit?.id"
        class="text-teal-600"
      >{{ unit?.name }}</span>
    </template>
    <template #body>
      <div class="p-3">
        <base-loading-panel
          :loading="loading"
          type="form"
        >
          <template #content>
            <form @submit.prevent="onSubmit">
              <div class="flex flex-wrap items-end justify-between">
                <div class="w-full p-2 sm:w-1/2">
                  <base-input
                    label-title="عنوان واحد"
                    placeholder="عنوان واحد را وارد نمایید"
                    name="name"
                    :value="unit?.name"
                  >
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="p-2">
                  <base-switch
                    label="عدم نمایش واحد"
                    on-label="نمایش واحد"
                    name="is_published"
                    :enabled="unit?.is_published"
                    sr-text="نمایش/عدم نمایش واحد"
                    @change="(status) => {publishStatus=status}"
                  />
                </div>
              </div>

              <div class="px-2 py-3">
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

                  <span class="ml-auto">ویرایش واحد</span>
                </base-animated-button>
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
import {useForm} from "vee-validate";
import yup from "../../../validation/index.js";
import LoaderCircle from "../../../components/base/loader/LoaderCircle.vue";
import VTransitionFade from "../../../transitions/VTransitionFade.vue";
import {ArrowLeftCircleIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "../../../components/base/BaseAnimatedButton.vue";
import BaseLoadingPanel from "../../../components/base/BaseLoadingPanel.vue";
import PartialCard from "../../../components/partials/PartialCard.vue";
import BaseInput from "../../../components/base/BaseInput.vue";
import BaseSwitch from "../../../components/base/BaseSwitch.vue";
import {useRequest} from "../../../composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "../../../router/api-routes.js";

const loading = ref(false)
const canSubmit = ref(true)

const unit = ref(null)
const publishStatus = ref(true)

const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return
})

onMounted(() => {
  // useRequest(apiReplaceParams(apiRoutes.admin.units.show, {unit: idParam.value}), null, {
  //     success: (response) => {
  //         unit.value = response.data
  //
  //         loading.value = false
  //     },
  // })
})
</script>
