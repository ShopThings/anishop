<template>
  <partial-card>
    <template #header>
      ویرایش هزینه ارسال برحسب وزن
    </template>
    <template #body>
      <div class="p-3">
        <base-loading-panel
            :loading="loading"
            type="form"
        >
          <template #content>
            <form @submit.prevent="onSubmit">
              <div class="flex flex-wrap">
                <div class="p-2 w-full sm:w-1/2 lg:w-1/3">
                  <base-input
                      :min="0"
                      :money-mask="true"
                      :value="postPrice?.min_weight"
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
                      :value="postPrice?.max_weight"
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
                      :value="postPrice?.post_price"
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

                  <span class="ml-auto">ویرایش هزینه ارسال</span>
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
          </template>
        </base-loading-panel>
      </div>
    </template>
  </partial-card>
</template>

<script setup>
import {onMounted, ref} from "vue";
import yup, {transformNumbersToEnglish} from "@/validation/index.js";
import PartialCard from "@/components/partials/PartialCard.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {CheckIcon, CurrencyDollarIcon, ArrowLeftCircleIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import {useToast} from "vue-toastification";
import {useFormSubmit} from "@/composables/form-submit.js";
import {WeightPostPriceAPI} from "@/service/APIShop.js";
import {getRouteParamByKey} from "@/composables/helper.js";

const toast = useToast()
const idParam = getRouteParamByKey('id')

const loading = ref(true)
const postPrice = ref(null)

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

  WeightPostPriceAPI.updateById(idParam.value, {
    min_weight: values.min_weight,
    max_weight: values.max_weight,
    post_price: values.post_price,
  }, {
    success(response) {
      toast.success('ویرایش اطلاعات با موفقیت انجام شد.')
      setFormFields(response.data)
    },
    error(error) {
      if (error.errors && Object.keys(error.errors).length >= 1)
        actions.setErrors(error.errors)
    },
    finally() {
      canSubmit.value = true
    },
  })
})

onMounted(() => {
  WeightPostPriceAPI.fetchById(idParam.value, {
    success: (response) => {
      setFormFields(response.data)
      loading.value = false
    },
  })
})

function setFormFields(item) {
  postPrice.value = item
}
</script>
