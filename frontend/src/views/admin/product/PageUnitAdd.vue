<template>
  <partial-card>
    <template #header>
      ایجاد واحد محصول جدید
    </template>
    <template #body>
      <div class="p-3">
        <form @submit.prevent="onSubmit">
          <div class="flex flex-wrap items-end justify-between">
            <div class="w-full p-2 sm:w-1/2">
              <base-input
                  label-title="عنوان واحد"
                  name="name"
                  placeholder="عنوان واحد را وارد نمایید"
              >
                <template #icon>
                  <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                </template>
              </base-input>
            </div>
            <div class="p-2">
              <base-switch
                  :enabled="true"
                  label="عدم نمایش واحد"
                  name="is_published"
                  on-label="نمایش واحد"
                  sr-text="نمایش/عدم نمایش واحد"
                  @change="(status) => {publishStatus=status}"
              />
            </div>
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

              <span class="ml-auto">افزودن واحد</span>
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
      </div>
    </template>
  </partial-card>
</template>

<script setup>
import {ref} from "vue";
import yup from "@/validation/index.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {ArrowLeftCircleIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {UnitAPI} from "@/service/APIProduct.js";
import {useRouter} from "vue-router";

const router = useRouter()

const publishStatus = ref(true)

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    name: yup.string().required('نام رنگ را وارد نمایید.'),
    is_published: yup.boolean().required('وضعیت انتشار را مشخص کنید.'),
  }),
}, (values, actions) => {
  canSubmit.value = false

  UnitAPI.create({
    name: values.name,
    is_published: publishStatus.value,
  }, {
    success() {
      actions.resetForm()
      router.push({name: 'admin.units'})
    },
    finally() {
      canSubmit.value = true
    },
  })
})
</script>
