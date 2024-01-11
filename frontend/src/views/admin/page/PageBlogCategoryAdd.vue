<template>
  <partial-card>
    <template #header>
      ایجاد دسته‌بندی بلاگ جدید
    </template>
    <template #body>
      <div class="p-3">
        <form @submit.prevent="onSubmit">
          <div class="p-2">
            <base-switch
              label="عدم نمایش بلاگ"
              on-label="نمایش بلاگ"
              name="is_published"
              :enabled="true"
              sr-text="نمایش/عدم نمایش بلاگ"
              @change="(status) => {publishStatus=status}"
            />
          </div>

          <div class="flex flex-wrap">
            <div class="w-full p-2 sm:w-1/2">
              <base-input label-title="نام دسته‌بندی"
                          placeholder="وارد نمایید"
                          name="title">
                <template #icon>
                  <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                </template>
              </base-input>
            </div>
            <div class="w-full p-2 sm:w-1/2">
              <base-input
                type="number"
                label-title="اولویت"
                placeholder="وارد نمایید"
                name="priority"
                :min="0"
              >
                <template #icon>
                  <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                </template>
              </base-input>
            </div>
          </div>

          <div class="flex flex-wrap">
            <div class="w-full p-2 sm:w-1/2">
              <base-switch
                label="نمایش در منوی اصلی"
                name="show_in_menu"
                :enabled="true"
                sr-text="نمایش/عدم نمایش در منوی اصلی"
                @change="(status) => {showInMenuStatus=status}"
              />
            </div>
            <div class="w-full p-2 sm:w-1/2">
              <base-switch
                label="نمایش در منوی کناری"
                name="show_in_side_menu"
                :enabled="true"
                sr-text="نمایش/عدم نمایش در منوی کناری"
                @change="(status) => {showInSideMenuStatus=status}"
              />
            </div>
          </div>

          <div class="p-2">
            <partial-input-label title="کلمات کلیدی"/>
            <vue3-tags-input
              :tags="tags"
              placeholder="کلمات کلیدی خود را وارد نمایید"
              @on-tags-changed="(t) => {tags = t}"
            />
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

              <span class="ml-auto">ایجاد دسته‌بندی بلاگ</span>
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
import Vue3TagsInput from "vue3-tags-input";
import PartialInputLabel from "../../../components/partials/PartialInputLabel.vue";

const canSubmit = ref(true)

const tags = ref([])
const publishStatus = ref(true)
const showInMenuStatus = ref(true)
const showInSideMenuStatus = ref(true)

const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return
})
</script>
