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
                    :disabled="!canSubmit"
                >
                  <VTransitionFade>
                    <loader-circle
                        v-if="!canSubmit"
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
import yup from "@/validation/index.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {ArrowLeftCircleIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {UnitAPI} from "@/service/APIProduct.js";
import {getRouteParamByKey} from "@/composables/helper.js";
import {useToast} from "vue-toastification";

const toast = useToast()
const idParam = getRouteParamByKey('id')

const loading = ref(true)

const unit = ref(null)
const publishStatus = ref(true)

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    name: yup.string().required('عنوان واحد محصول را وارد نمایید.'),
    is_published: yup.boolean().required('وضعیت انتشار را مشخص کنید.'),
  }),
}, (values, actions) => {
  if (!canSubmit.value) return

  canSubmit.value = false

  UnitAPI.updateById(idParam.value, {
    name: values.name,
    is_published: publishStatus.value,
  }, {
    success(response) {
      setFormFields(response.data)
      toast.success('ویرایش اطلاعات با موفقیت انجام شد.')
    },
    finally() {
      canSubmit.value = true
    },
  })
})

onMounted(() => {
  UnitAPI.fetchById(idParam.value, {
    success(response) {
      setFormFields(response.data)
      loading.value = false
    },
  })
})

function setFormFields(item) {
  unit.value = item
  publishStatus.value = item.is_published
}
</script>
