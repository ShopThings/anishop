<template>
  <form class="relative" @submit.prevent="onSubmit">
    <loader-dot-orbit
      v-if="isSubmitting"
      main-container-klass="absolute w-full h-full top-0 left-0 z-[2]"
      container-bg-color="bg-blue-50 opacity-40"
    />

    <div class="mb-3 mt-12">
      <base-input
        name="username"
        placeholder="شماره موبایل"
        label-title="شماره موبایل"
      >
        <template #icon>
          <DevicePhoneMobileIcon class="w-6 h-6 text-gray-400"/>
        </template>
      </base-input>
    </div>

    <div class="mb-2">
      <v-captcha/>
    </div>
    <div class="mb-6">
      <base-input name="captcha" placeholder="کد تصویر" label-title="کد تصویر">
        <template #icon>
          <QrCodeIcon class="w-6 h-6 text-gray-400"/>
        </template>
      </base-input>
    </div>

    <div class="mb-3">
      <base-button
        type="submit"
        class="w-full flex justify-center items-center group bg-primary border-primary text-white"
        :disabled="isSubmitting"
      >
        <span class="mx-auto">ارسال کد</span>
        <ArrowLeftIcon
          class="h-6 w-6 text-white opacity-60 group-hover:-translate-x-1.5 transition-all"/>
      </base-button>
    </div>
  </form>
</template>

<script setup>
import {ref} from "vue";
import {DevicePhoneMobileIcon, QrCodeIcon} from "@heroicons/vue/24/outline/index.js";
import BaseInput from "../../components/base/BaseInput.vue";
import {useForm} from "vee-validate";
import yup from "../../validation/index.js";
import LoaderDotOrbit from "../../components/base/loader/LoaderDotOrbit.vue";
import BaseButton from "../../components/base/BaseButton.vue";
import VCaptcha from "../../components/base/VCaptcha.vue";
import {ArrowLeftIcon} from "@heroicons/vue/24/solid/index.js";
import isFunction from "lodash.isfunction";

const props = defineProps({
  options: {
    type: Object,
    required: true,
  },
})

const canSubmit = ref(true)

const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return

  return new Promise((resolve) => {
    setTimeout(() => {
      resolve()
      if (isFunction(props.options?.next))
        props.options.next()
    }, 2000)
  })
})
</script>
