<template>
  <partial-card>
    <template #header>
      ایجاد برچسب سفارش جدید
    </template>
    <template #body>
      <div class="p-3">
        <form @submit.prevent="onSubmit">
          <div class="flex flex-wrap">
            <div class="p-2 w-full lg:w-1/2">
              <base-input label-title="عنوان"
                          placeholder="وارد نمایید"
                          name="question">
                <template #icon>
                  <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                </template>
              </base-input>
            </div>
          </div>

          <div class="sm:flex sm:flex-wrap sm:justify-between">
            <div class="p-2 flex md:w-1/3">
              <partial-input-label
                title="انتخاب رنگ"
                class="grow sm:grow-0"
              />
              <color-picker
                v-model:pureColor="pureColor"
                :disable-alpha="true"
                format="hex6"
                lang="En"
              />
            </div>
            <div class="p-2 md:w-1/3">
              <base-switch
                label="نمایش برچسب"
                name="is_published"
                :enabled="true"
                sr-text="نمایش/عدم نمایش برچسب"
                @change="(status) => {publishStatus=status}"
              />
            </div>
            <div class="p-2 md:w-1/3">
              <base-switch
                label="بازگشت محصول به انبار"
                name="should_return_order_product"
                :enabled="true"
                sr-text="بازگشت محصول به انبار/عدم بازگشت محصول به انبار"
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

              <span class="ml-auto">افزودن برچسب</span>
            </base-animated-button>
          </div>
        </form>
      </div>
    </template>
  </partial-card>
</template>

<script setup>
import {ref} from "vue";
import {useForm} from "vee-validate";
import yup from "../../../validation/index.js";
import PartialCard from "../../../components/partials/PartialCard.vue";
import LoaderCircle from "../../../components/base/loader/LoaderCircle.vue";
import VTransitionFade from "../../../transitions/VTransitionFade.vue";
import {ArrowLeftCircleIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "../../../components/base/BaseAnimatedButton.vue";
import BaseSwitch from "../../../components/base/BaseSwitch.vue";
import BaseInput from "../../../components/base/BaseInput.vue";
import PartialInputLabel from "../../../components/partials/PartialInputLabel.vue";

const canSubmit = ref(true)

const publishStatus = ref(true)
const pureColor = ref('')

const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return
})
</script>
