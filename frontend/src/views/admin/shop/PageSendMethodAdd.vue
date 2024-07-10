<template>
  <partial-card>
    <template #header>
      ایجاد روش ارسال جدید
    </template>
    <template #body>
      <div class="p-3">
        <form @submit.prevent="onSubmit">
          <div class="flex flex-wrap items-end justify-between">
            <div class="p-2">
              <partial-input-label title="انتخاب تصویر"/>
              <base-media-placeholder
                v-model:selected="methodImage"
                type="image"
              />
              <partial-input-error-message :error-message="errors.image"/>
            </div>

            <div class="p-2">
              <base-switch
                :enabled="publishStatus"
                label="عدم نمایش روش ارسال"
                name="is_published"
                on-label="نمایش روش ارسال"
                sr-text="نمایش/عدم نمایش روش ارسال"
                @change="(status) => {publishStatus=status}"
              />
            </div>
          </div>

          <div class="flex flex-wrap">
            <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
              <base-input
                label-title="عنوان روش ارسال"
                name="title"
                placeholder="عنوان را وارد نمایید"
              >
                <template #icon>
                  <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                </template>
              </base-input>
            </div>
            <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
              <base-input
                :is-optional="true"
                label-title="توضیحات روش ارسال"
                name="description"
                placeholder="توضیحات را وارد نمایید"
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
                name="price"
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
            <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
              <base-input
                :min="0"
                :money-mask="true"
                label-title="اولویت"
                name="priority"
                placeholder="وارد نمایید"
                type="text"
              >
                <template #icon>
                  <HashtagIcon class="h-6 w-6 text-gray-400"/>
                </template>
              </base-input>
            </div>
          </div>

          <div class="sm:flex sm:flex-wrap">
            <div class="p-2 md:w-1/2 xl:w-1/3">
              <base-switch
                :enabled="priceDetermineByLocStatus"
                label="در نظرگیری مکان فروشگاه برای قیمت ارسال"
                name="determine_price_by_shop_location"
                sr-text="در نظرگیری/عدم در نظرگیری مکان فروشگاه برای قیمت ارسال"
                @change="(status) => {priceDetermineByLocStatus=status}"
              />
            </div>
            <div class="p-2 md:w-1/2 xl:w-1/3">
              <base-switch
                :enabled="onlyForShopLocStatus"
                label="اعمال فقط برای محدوده مکان فروشگاه"
                name="only_for_shop_location"
                sr-text="اعمال/عدم اعمال نوع روش برای محدوده مکان فروشگاه"
                @change="(status) => {onlyForShopLocStatus=status}"
              />
            </div>
            <div class="p-2 md:w-1/2 xl:w-1/3">
              <base-switch
                :enabled="applyPriceForEachShipmentStatus"
                label="اعمال هزینه ارسال به ازای هر مرسوله"
                name="apply_number_of_shipments_on_price"
                sr-text="اعمال/عدم اعمال هزینه ارسال به ازای هر مرسوله"
                @change="(status) => {applyPriceForEachShipmentStatus=status}"
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

              <span class="ml-auto">ایجاد روش ارسال</span>
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
import BaseMediaPlaceholder from "@/components/base/BaseMediaPlaceholder.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import {ArrowLeftCircleIcon, CheckIcon, CurrencyDollarIcon, HashtagIcon,} from "@heroicons/vue/24/outline";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import yup from "@/validation/index.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {useRouter} from "vue-router";
import {SendMethodAPI} from "@/service/APIShop.js";

const router = useRouter()

const methodImage = ref(null)
const priceDetermineByLocStatus = ref(true)
const onlyForShopLocStatus = ref(false)
const applyPriceForEachShipmentStatus = ref(true)
const publishStatus = ref(true)

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    title: yup.string().required('عنوان را وارد نمایید.'),
    description: yup.string().optional(),
    price: yup.number()
      .min(0, 'هزینه ارسال باید بزرگتر از صفر باشد.')
      .required('هزینه ارسال را وارد نمایید.'),
    priority: yup.number()
      .min(0, 'مقدار اولویت باید بزرگتر از صفر باشد.')
      .required('اولویت را وارد نمایید.'),
  }),
}, (values, actions) => {
  if (!methodImage.value) {
    actions.setFieldError('image', 'تصویر را انتخاب نمایید.')
    return
  }

  canSubmit.value = false

  SendMethodAPI.create({
    title: values.title,
    description: values.description || '',
    image: methodImage.value.full_path,
    price: values.price,
    priority: values.priority,
    determine_price_by_shop_location: priceDetermineByLocStatus.value,
    only_for_shop_location: onlyForShopLocStatus.value,
    apply_number_of_shipments_on_price: applyPriceForEachShipmentStatus.value,
    is_published: publishStatus.value,
  }, {
    success() {
      actions.resetForm()
      methodImage.value = null
      priceDetermineByLocStatus.value = true
      onlyForShopLocStatus.value = false
      applyPriceForEachShipmentStatus.value = true
      publishStatus.value = true

      router.push({name: 'admin.send_methods'})
    },
    error(error) {
      if (error?.errors && Object.keys(error.errors).length >= 1) {
        actions.setErrors(error.errors)
      }
    },
    finally() {
      canSubmit.value = true
    }
  })
})
</script>
