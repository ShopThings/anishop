<template>
  <partial-card>
    <template #header>
      ویرایش کد تخفیف -
      <span
          v-if="coupon?.id"
          class="text-slate-400 text-base"
      >{{ coupon?.title }}</span>
    </template>
    <template #body>
      <div class="p-3">
        <base-loading-panel
            :loading="loading"
            type="form"
        >
          <template #content>
            <form @submit.prevent="onSubmit">
              <div class="p-2">
                <base-switch
                    :enabled="coupon?.is_published"
                    label="قابل استفاده نمودن کد تخفیف"
                    name="is_published"
                    sr-text="قابل استفاده نمودن کد تخفیف"
                    @change="(status) => {publishStatus=status}"
                />
              </div>

              <div class="w-full p-2">
                <base-input
                  :in-edit-mode="false"
                    :is-editable="false"
                    :value="coupon?.code"
                    label-title="کد"
                    name="code"
                    placeholder="وارد نمایید"
                >
                  <template #editModeLabel="{value}">
                    <span class="px-2 py-1 bg-teal-200 rounded text-black text-sm tracking-widest">{{
                        value || '-'
                      }}</span>
                  </template>
                  <template #icon>
                    <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                  </template>
                </base-input>
              </div>

              <div class="flex flex-wrap">
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input
                      :value="coupon?.title"
                      label-title="عنوان"
                      name="title"
                      placeholder="وارد نمایید"
                  >
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input
                      :min="0"
                      :money-mask="true"
                      :value="coupon?.price?.toString()"
                      name="price"
                      placeholder="وارد نمایید"
                      type="text"
                  >
                    <template #label>
                      <div class="flex items-center gap-1.5 text-sm">
                        <span>قیمت تخفیف</span>
                        <span class="text-xs text-pink-600">(بر حسب تومان)</span>
                      </div>
                    </template>
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
              </div>

              <hr class="my-3">

              <div class="flex flex-wrap">
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input
                      :is-optional="true"
                      :min="0"
                      :money-mask="true"
                      :value="coupon?.apply_min_price?.toString()"
                      name="apply_min_price"
                      placeholder="وارد نمایید"
                      type="text"
                  >
                    <template #label>
                      <div class="flex items-center gap-1.5 text-sm">
                        <span>حداقل قیمت اعمال</span>
                        <span class="text-xs text-pink-600">(بر حسب تومان)</span>
                      </div>
                    </template>
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input
                      :is-optional="true"
                      :min="0"
                      :money-mask="true"
                      :value="coupon?.apply_max_price?.toString()"
                      name="apply_max_price"
                      placeholder="وارد نمایید"
                      type="text"
                  >
                    <template #label>
                      <div class="flex items-center gap-1.5 text-sm">
                        <span>حداکثر قیمت اعمال</span>
                        <span class="text-xs text-pink-600">(بر حسب تومان)</span>
                      </div>
                    </template>
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input
                      :min="0"
                      :money-mask="true"
                      :value="coupon?.use_count?.toString()"
                      label-title="تعداد قابل استفاده"
                      name="use_count"
                      placeholder="وارد نمایید"
                      type="text"
                  >
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input
                      :is-optional="true"
                      :min="0"
                      :money-mask="true"
                      :value="coupon?.reusable_after?.toString()"
                      name="reusable_after"
                      placeholder="وارد نمایید"
                      type="text"
                  >
                    <template #label>
                      <div class="flex items-center gap-1.5 text-sm">
                        <span>قابل استفاده مجدد پس از</span>
                        <span class="text-xs text-pink-600">(بر حسب روز)</span>
                      </div>
                    </template>
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                  <partial-input-lead text="مقدار وارد شده برای استفاده هر کاربر می‌باشد"/>
                </div>
              </div>

              <hr class="my-3">

              <div class="flex flex-wrap">
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <partial-input-label
                      :is-optional="true"
                      title="تاریخ شروع"
                  />
                  <date-picker
                      v-model="startDate"
                      placeholder="انتخاب تاریخ شروع"
                  />
                  <partial-input-error-message :error-message="errors.start_at"/>
                </div>
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <partial-input-label
                      :is-optional="true"
                      title="تاریخ پایان"
                  />
                  <date-picker
                      v-model="endDate"
                      placeholder="انتخاب تاریخ پایان"
                  />
                  <partial-input-error-message :error-message="errors.end_at"/>
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

                  <span class="ml-auto">ویرایش کوپن تخفیف</span>
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
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import {ArrowLeftCircleIcon, CheckIcon} from "@heroicons/vue/24/outline/index.js";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import PartialInputLead from "@/components/partials/PartialInputLead.vue";
import {useToast} from "vue-toastification";
import {useFormSubmit} from "@/composables/form-submit.js";
import {CouponAPI} from "@/service/APIShop.js";
import {getRouteParamByKey} from "@/composables/helper.js";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";

const toast = useToast()
const idParam = getRouteParamByKey('id')

const loading = ref(true)

const coupon = ref(null)

const publishStatus = ref(true)
const startDate = ref(null)
const endDate = ref(null)

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    is_published: yup.boolean().required('وضعیت انتشار را مشخص کنید.'),
    code: yup.string()
      .transform(transformNumbersToEnglish)
      .matches(/[a-zA-Z\-_]+/g, 'تنها از حروف و اعداد انگلیسی استفاده نمایید.')
      .required('کد تخفیف برای استفاده کاربر را وارد نمایید.'),
    title: yup.string().required('عنوان را وارد نمایید.'),
    price: yup.string()
        .transform(transformNumbersToEnglish)
        .positiveNumber('مبلغ باید عددی مثبت و بیشتر از ۱۰۰۰ تومان باشد.', {gt: 1000})
      .required('مبلغ کوپن تخفیف را وارد نمایید.'),
    apply_min_price: yup.string()
        .optional()
        .transform(transformNumbersToEnglish)
        .positiveNumber('حداقل قیمت اعمال باید عددی مثبت و بیشتر از ۱۰۰۰ تومان باشد.', {gt: 1000, optional: true})
        .lessThanNumber('apply_max_price', 'حداقل قیمت اعمال باید کوچکتر از حداکثر قیمت اعمال باشد.', {equal: true}),
    apply_max_price: yup.string()
        .optional()
        .transform(transformNumbersToEnglish)
        .positiveNumber('حداکثر مبلغ اعمال باید عددی مثبت و بیشتر از ۱۰۰۰ تومان باشد.', {gt: 1000, optional: true})
        .greaterThanNumber('apply_min_price', 'حداکثر قیمت اعمال باید بزرگتر از حداقل قیمت اعمال باشد.', {equal: true}),
    use_count: yup.string()
        .transform(transformNumbersToEnglish)
        .positiveNumber('تعداد استفاده باید عددی مثبت و بیشتر از ۱ باشد.', {gt: 1})
        .required('تعداد قابل استفاده را وارد نمایید.'),
    reusable_after: yup.string()
        .transform(transformNumbersToEnglish)
        .positiveNumber('باید عددی مثبت و بیشتر از ۱ باشد.', {gt: 1})
        .required('تعداد روز را وارد نمایید.'),
  }),
}, (values, actions) => {
  if (startDate.value && endDate.value) {
    const d1 = new Date(startDate.value)
    const d2 = new Date(endDate.value)
    if (d1 > d2) {
      actions.setFieldError('start_at', 'تاریخ شروع باید از تاریخ پایان کوچکتر باشد.')
      return
    }
  }

  canSubmit.value = false

  CouponAPI.updateById(idParam.value, {
    title: values.title,
    price: values.price,
    apply_min_price: values.apply_min_price,
    apply_max_price: values.apply_max_price,
    start_at: startDate.value,
    end_at: endDate.value,
    use_count: values.use_count,
    reusable_after: values.reusable_after,
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
  CouponAPI.fetchById(idParam.value, {
    success(response) {
      setFormFields(response.data)
      loading.value = false
    },
  })
})

function setFormFields(item) {
  coupon.value = item
  startDate.value = item.actual_start_at
  endDate.value = item.actual_end_at
  publishStatus.value = item.is_published
}
</script>
