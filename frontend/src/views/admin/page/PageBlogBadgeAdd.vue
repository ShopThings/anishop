<template>
  <partial-card>
    <template #header>
      ایجاد برچسب دیدگاه جدید
    </template>
    <template #body>
      <div class="p-3">
        <form @submit.prevent="onSubmit">
          <div class="p-2">
            <base-switch
              label="عدم نمایش برچسب"
              on-label="نمایش برچسب"
              name="is_published"
              :enabled="true"
              sr-text="نمایش/عدم نمایش برچسب دیدگاه"
              @change="(status) => {publishStatus=status}"
            />
          </div>

          <div class="flex flex-wrap items-end">
            <div class="w-full p-2 sm:w-1/2">
              <base-input label-title="نام"
                          placeholder="وارد نمایید"
                          name="title">
                <template #icon>
                  <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                </template>
              </base-input>
            </div>
            <div class="flex p-2">
              <partial-input-label title="انتخاب رنگ"/>
              <color-picker
                v-model:pureColor="pureColor"
                :disable-alpha="true"
                format="hex6"
                lang="En"
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

              <span class="ml-auto">ایجاد برچسب</span>
            </base-animated-button>
          </div>
        </form>
      </div>
    </template>
  </partial-card>
</template>

<script setup>
import {ref} from "vue";
import PartialCard from "../../../components/partials/PartialCard.vue";
import BaseSwitch from "../../../components/base/BaseSwitch.vue";
import BaseInput from "../../../components/base/BaseInput.vue";
import {useForm} from "vee-validate";
import yup from "../../../validation/index.js";
import LoaderCircle from "../../../components/base/loader/LoaderCircle.vue";
import VTransitionFade from "../../../transitions/VTransitionFade.vue";
import BaseAnimatedButton from "../../../components/base/BaseAnimatedButton.vue";
import {ArrowLeftCircleIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
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
