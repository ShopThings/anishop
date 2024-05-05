<template>
  <partial-card>
    <template #header>
      افزودن هزینه ارسال برحسب وزن
    </template>
    <template #body>
      <div class="p-3">
        <form @submit.prevent="onSubmit">
          <div class="flex flex-wrap">
            <div class="p-2 w-full sm:w-1/2 lg:w-1/3">
              <base-input
                :min="0"
                :money-mask="true"
                name="min_weight"
                placeholder="وارد نمایید"
                type="text"
              >
                <template #label>
                  <div class="flex items-center gap-1.5 text-sm">
                    <span>حداقل وزن مرسوله</span>
                    <span class="text-xs text-pink-600">(بر حسب گرم)</span>
                  </div>
                </template>
                <template #icon>
                  <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                </template>
              </base-input>
            </div>
            <div class="p-2 w-full sm:w-1/2 lg:w-1/3">
              <base-input
                :min="0"
                :money-mask="true"
                name="max_weight"
                placeholder="وارد نمایید"
                type="text"
              >
                <template #label>
                  <div class="flex items-center gap-1.5 text-sm">
                    <span>حداکثر وزن مرسوله</span>
                    <span class="text-xs text-pink-600">(بر حسب گرم)</span>
                  </div>
                </template>
                <template #icon>
                  <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                </template>
              </base-input>
            </div>
            <div class="p-2 w-full sm:w-1/2 lg:w-1/3">
              <base-input
                :min="0"
                :money-mask="true"
                name="post_price"
                placeholder="وارد نمایید"
                type="text"
              >
                <template #label>
                  <div class="flex items-center gap-1.5 text-sm">
                    <span>هزینه ارسال</span>
                    <span class="text-xs text-pink-600">(بر حسب تومان)</span>
                  </div>
                </template>
                <template #icon>
                  <CurrencyDollarIcon class="h-6 w-6 text-gray-400"/>
                </template>
              </base-input>
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

              <span class="ml-auto">افزودن هزینه ارسال</span>
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
import yup, {transformNumbersToEnglish} from "@/validation/index.js";
import PartialCard from "@/components/partials/PartialCard.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {ArrowLeftCircleIcon, CheckIcon, CurrencyDollarIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {WeightPostPriceAPI} from "@/service/APIShop.js";
import {useRouter} from "vue-router";

const router = useRouter()

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    min_weight: yup.string()
      .transform(transformNumbersToEnglish)
      .positiveNumber('حداقل وزن مرسوله باید عددی مثبت و بیشتر از صفر باشد.', {gt: 0})
      .max(yup.ref('max_weight'), 'حداقل وزن باید از حداکثر وزن مرسوله کمتر باشد.')
      .required('حداقل وزن مرسوله را وارد نمایید.'),
    max_weight: yup.string()
      .transform(transformNumbersToEnglish)
      .positiveNumber('حداکثر وزن مرسوله باید عددی مثبت و بیشتر از صفر باشد.', {gt: 0})
      .min(yup.ref('min_weight'), 'حداکثر وزن باید از حداقل وزن مرسوله بیشتر باشد.')
      .required('حداکثر وزن مرسوله را وارد نمایید.'),
    post_price: yup.string()
      .transform(transformNumbersToEnglish)
      .positiveNumber('هزیته ارسال باید عددی مثبت و بیشتر از صفر باشد.', {gt: 0})
      .required('هزینه ارسال را وارد نمایید.'),
  }),
}, (values, actions) => {
  canSubmit.value = false

  WeightPostPriceAPI.create({
    min_weight: values.min_weight,
    max_weight: values.max_weight,
    post_price: values.post_price,
  }, {
    success() {
      actions.resetForm()
      router.push({name: 'admin.post_prices.weights'})
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
</script>
