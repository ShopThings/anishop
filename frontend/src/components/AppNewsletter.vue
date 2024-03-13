<template>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-3 my-6">
    <div
        class="p-10 h-56 rounded-lg bg-indigo-600 text-center text-white flex flex-col items-center justify-center relative">
      <img
          alt=""
          class="w-full h-full object-cover absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 rounded-lg z-[1] opacity-10"
          src="/images/newsletter-bg.jpg"
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
          container-class="mb-2 p-2"
          title="ثبت شماره موبایل"
          type="side"
      />

      <p class="text-sm mb-4 text-slate-500 leading-relaxed text-center">
        شماره موبایل خود را جهت دریافت پیام‌های هفتگی، وارد نمایید.
      </p>

      <form class="w-full" @submit.prevent="onSubmit">
        <div class="flex items-center gap-3">
          <div class="grow">
            <base-input
                klass="tracking-widest"
                name="mobile"
                placeholder="09xxxxxxxxx"
            />
          </div>

          <base-button
              :disabled="!canSubmit"
              class="bg-primary px-4 shrink-0"
              type="submit"
          >
            <VTransitionFade>
              <loader-circle
                  v-if="!canSubmit"
                  big-circle-color="border-transparent"
                  main-container-klass="absolute w-full h-full top-0 left-0"
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
import PartialGeneralTitle from "./partials/PartialGeneralTitle.vue";
import BaseInput from "./base/BaseInput.vue";
import yup, {transformNumbersToEnglish} from "@/validation/index.js";
import BaseButton from "./base/BaseButton.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import LoaderCircle from "./base/loader/LoaderCircle.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {HomeMainPageAPI} from "@/service/APIHomePages.js";

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    mobile: yup.string()
        .transform(transformNumbersToEnglish)
        .persianMobile('شماره موبایل نامعتبر است.')
        .required('موبایل را وارد نمایید.'),
  }),
}, (values, actions) => {
  canSubmit.value = false

  HomeMainPageAPI.addToNewsletter(values.mobile, {
    success() {
      actions.resetForm()
    },
  })
})
</script>
