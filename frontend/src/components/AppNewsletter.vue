<template>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-3 my-6">
    <div
      class="p-10 h-56 rounded-lg bg-indigo-600 text-center text-white flex flex-col items-center justify-center relative">
      <img
        src="/images/newsletter-bg.jpg"
        alt=""
        class="w-full h-full object-cover absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 rounded-lg z-[1] opacity-10"
      >

      <div class="relative z-[2]">
        <h2 class="font-iranyekan-bold mb-4 text-2xl">
          عضویت در خبرنامه
        </h2>
        <p class="font-iranyekan-light leading-relaxed">
          با عضویت در خبرنامه ما از آخرین نوشته‌ها، محصولات و تخفیفات با خبر شوید.
        </p>
      </div>
    </div>

    <div class="p-6 h-56 rounded-lg bg-slate-200 flex flex-col items-center justify-center">
      <partial-general-title
        title="ثبت شماره موبایل"
        type="side"
        container-class="mb-2 p-2"
      />

      <p class="text-sm mb-4 text-slate-500 leading-relaxed text-center">
        شماره موبایل خود را جهت دریافت پیام‌های هفتگی، وارد نمایید.
      </p>

      <form @submit.prevent="onSubmit" class="w-full">
        <div class="flex items-center gap-3">
          <div class="grow">
            <base-input
              placeholder="09xxxxxxxxx"
              name="mobile"
              klass="tracking-widest"
            />
          </div>

          <base-button
            type="submit"
            class="bg-primary px-4 shrink-0"
            :disabled="isSubmitting"
          >
            <VTransitionFade>
              <loader-circle
                v-if="isSubmitting"
                main-container-klass="absolute w-full h-full top-0 left-0"
                big-circle-color="border-transparent"
              />
            </VTransitionFade>

            <span>ثبت موبایل</span>
          </base-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import {ref} from "vue";
import PartialGeneralTitle from "./partials/PartialGeneralTitle.vue";
import BaseInput from "./base/BaseInput.vue";
import {useForm} from "vee-validate";
import yup from "../validation/index.js";
import BaseButton from "./base/BaseButton.vue";
import VTransitionFade from "../transitions/VTransitionFade.vue";
import LoaderCircle from "./base/loader/LoaderCircle.vue";

const canSubmit = ref(true)

const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return
})
</script>
