<template>
  <partial-card>
    <template #header>
      ایجاد روش پرداخت جدید
    </template>
    <template #body>
      <div class="p-3">
        <form @submit.prevent="onSubmit">
          <div class="flex flex-wrap items-end justify-between">
            <div class="p-2">
              <partial-input-label title="انتخاب تصویر"/>
              <base-media-placeholder
                type="image"
                v-model:selected="methodImage"
              />
              <partial-input-error-message :error-message="errors.image"/>
            </div>

            <div class="p-2">
              <base-switch
                label="عدم نمایش روش پرداخت"
                on-label="نمایش روش پرداخت"
                name="is_published"
                :enabled="true"
                sr-text="نمایش/عدم نمایش روش پرداخت"
                @change="(status) => {publishStatus=status}"
              />
            </div>
          </div>

          <div class="flex flex-wrap">
            <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
              <base-input
                label-title="عنوان روش پرداخت"
                placeholder="عنوان را وارد نمایید"
                name="title"
              >
                <template #icon>
                  <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                </template>
              </base-input>
            </div>
            <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
              <partial-input-label title="نوع روش پرداخت"/>
              <base-select
                :options="paymentTypes"
                options-key="value"
                options-text="name"
                name="type"
                @change="paymentTypeChange"
              >
                <template #item="{item, selected}">
                  <div class="flex items-center">
                    <base-lazy-image
                      :lazy-src="item.image"
                      :alt="item.name"
                      class="!w-14 h-auto ml-3"
                    />
                    <span :class="{'text-primary': selected}">{{ item.name }}</span>
                  </div>
                </template>
              </base-select>
              <partial-input-error-message :error-message="errors.type"/>
            </div>
          </div>

          <template
            v-if="selectedPaymentType"
          >
            <hr class="my-3">

            <VTransitionSlideFadeDownY mode="out-in">
              <div
                v-if="selectedPaymentType.value === 'behpardakht'"
                class="flex flex-wrap"
              >
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input label-title="شماره ترمینال"
                              placeholder="وارد نمایید"
                              name="terminal_id">
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input label-title="نام کاربری"
                              placeholder="وارد نمایید"
                              name="username">
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input label-title="کلمه عبور"
                              placeholder="وارد نمایید"
                              name="password"
                              type="password">
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
              </div>
              <div
                v-else-if="selectedPaymentType.value === 'idpay'"
                class="flex flex-wrap"
              >
                <div class="w-full p-2 sm:w-1/2">
                  <base-input label-title="کلید API"
                              placeholder="وارد نمایید"
                              name="api_key">
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
              </div>
              <div
                v-else-if="selectedPaymentType.value === 'irankish'"
                class="flex flex-wrap"
              >
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input label-title="شماره ترمینال"
                              placeholder="وارد نمایید"
                              name="terminal_id">
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input label-title="کلمه عبور"
                              placeholder="وارد نمایید"
                              name="password"
                              type="password">
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input label-title="شناسه پذیرنده"
                              placeholder="وارد نمایید"
                              name="acceptor_id">
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="w-full p-2">
                  <base-textarea
                    name="public_key"
                    placeholder="وارد نمایید"
                    label-title="کلید عمومی"
                  >
                    <template #icon>
                      <InformationCircleIcon class="h-6 w-6 mt-3 text-gray-400"/>
                    </template>
                  </base-textarea>
                </div>
              </div>
              <div
                v-else-if="selectedPaymentType.value === 'parsian'"
                class="flex flex-wrap"
              >
                <div class="w-full p-2 sm:w-1/2">
                  <base-input label-title="رمز پذیرنده"
                              placeholder="وارد نمایید"
                              name="password"
                              type="password">
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
              </div>
              <div
                v-else-if="selectedPaymentType.value === 'sadad'"
                class="flex flex-wrap"
              >
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input label-title="کلید"
                              placeholder="وارد نمایید"
                              name="password"
                              type="password">
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input label-title="شماره ترمینال"
                              placeholder="وارد نمایید"
                              name="terminal_id">
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
                <div class="w-full p-2 sm:w-1/2 xl:w-1/3">
                  <base-input label-title="شماره مرچنت"
                              placeholder="وارد نمایید"
                              name="merchant_id">
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
              </div>
              <div
                v-else-if="selectedPaymentType.value === 'sepehr'"
                class="flex flex-wrap"
              >
                <div class="w-full p-2 sm:w-1/2">
                  <base-input label-title="شماره ترمینال"
                              placeholder="وارد نمایید"
                              name="terminal_id">
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
              </div>
              <div
                v-else-if="selectedPaymentType.value === 'zarinpal'"
                class="flex flex-wrap"
              >
                <div class="w-full p-2 sm:w-1/2">
                  <base-input label-title="شماره مرچنت"
                              placeholder="وارد نمایید"
                              name="merchant_id">
                    <template #icon>
                      <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                    </template>
                  </base-input>
                </div>
              </div>
            </VTransitionSlideFadeDownY>
          </template>

          <div class="px-2 py-3">
            <base-animated-button
              type="submit"
              class="bg-emerald-500 text-white mr-auto px-6 w-full sm:w-auto"
              :disabled="!canSubmit"
            >
              <VTransitionFade>
                <loader-circle
                  v-if="!canSubmit"
                  main-container-klass="absolute w-full h-full top-0 left-0"
                  big-circle-color="border-transparent"
                />
              </VTransitionFade>

              <template #icon="{klass}">
                <CheckIcon :class="klass" class="h-6 w-6 ml-auto sm:ml-2"/>
              </template>

              <span class="ml-auto">ایجاد روش پرداخت</span>
            </base-animated-button>
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
import {ArrowLeftCircleIcon, CheckIcon, InformationCircleIcon} from "@heroicons/vue/24/outline";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import yup from "@/validation/index.js";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import VTransitionSlideFadeDownY from "@/transitions/VTransitionSlideFadeDownY.vue";
import BaseTextarea from "@/components/base/BaseTextarea.vue";
import BaseSelect from "@/components/base/BaseSelect.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {PaymentMethodAPI} from "@/service/APIPayment.js";
import {PAYMENT_METHOD_TYPES} from "@/composables/constants.js";
import {useRouter} from "vue-router";

const router = useRouter()

const methodImage = ref(null)
const publishStatus = ref(true)
const paymentTypes = [
  {
    value: PAYMENT_METHOD_TYPES.BEHPARDAKHT.value,
    name: PAYMENT_METHOD_TYPES.BEHPARDAKHT.text,
    image: '/gateways/beh-pardakht.png',
    type: PAYMENT_METHOD_TYPES.BEHPARDAKHT.type,
  },
  {
    value: PAYMENT_METHOD_TYPES.IDPAY.value,
    name: PAYMENT_METHOD_TYPES.IDPAY.text,
    image: '/gateways/idpay.png',
    type: PAYMENT_METHOD_TYPES.IDPAY.type,
  },
  {
    value: PAYMENT_METHOD_TYPES.IRANKISH.value,
    name: PAYMENT_METHOD_TYPES.IRANKISH.text,
    image: '/gateways/irankish.jpg',
    type: PAYMENT_METHOD_TYPES.IRANKISH.type,
  },
  {
    value: PAYMENT_METHOD_TYPES.PARSIAN.value,
    name: PAYMENT_METHOD_TYPES.PARSIAN.text,
    image: '/gateways/tap.jpg',
    type: PAYMENT_METHOD_TYPES.PARSIAN.type,
  },
  {
    value: PAYMENT_METHOD_TYPES.SADAD.value,
    name: PAYMENT_METHOD_TYPES.SADAD.text,
    image: '/gateways/sadad.jpg',
    type: PAYMENT_METHOD_TYPES.SADAD.type,
  },
  {
    value: PAYMENT_METHOD_TYPES.SEPEHR.value,
    name: PAYMENT_METHOD_TYPES.SEPEHR.text,
    image: '/gateways/mabna.png',
    type: PAYMENT_METHOD_TYPES.SEPEHR.type,
  },
  {
    value: PAYMENT_METHOD_TYPES.ZARINPAL.value,
    name: PAYMENT_METHOD_TYPES.ZARINPAL.text,
    image: '/gateways/zarinpal.png',
    type: PAYMENT_METHOD_TYPES.ZARINPAL.type,
  },
]
const selectedPaymentType = ref(null)

function paymentTypeChange(selected) {
  selectedPaymentType.value = selected
}

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    title: yup.string().required('عنوان را وارد نمایید.'),
    is_published: yup.boolean().required('وضعیت انتشار را مشخص کنید.'),
  }),
}, (values, actions) => {
  if (!canSubmit.value) return

  if (!methodImage.value) {
    actions.setFieldError('image', 'تصویر را انتخاب نمایید.')
    return
  }

  // validate payment method type
  if (!selectedPaymentType.value || ![
    PAYMENT_METHOD_TYPES.BEHPARDAKHT.value,
    PAYMENT_METHOD_TYPES.IDPAY.value,
    PAYMENT_METHOD_TYPES.IRANKISH.value,
    PAYMENT_METHOD_TYPES.SADAD.value,
    PAYMENT_METHOD_TYPES.SEPEHR.value,
    PAYMENT_METHOD_TYPES.PARSIAN.value,
    PAYMENT_METHOD_TYPES.ZARINPAL.value,
  ].includes(selectedPaymentType.value.value)) {
    actions.setFieldError('type', 'انتخاب نوع روش پرداخت اجباری می‌باشد.')
    return
  }

  if ([
    PAYMENT_METHOD_TYPES.BEHPARDAKHT.value,
    PAYMENT_METHOD_TYPES.IRANKISH.value,
    PAYMENT_METHOD_TYPES.SADAD.value,
    PAYMENT_METHOD_TYPES.SEPEHR.value,
  ].includes(selectedPaymentType.value.value) && (!values.terminal_id || values.terminal_id.trim() === '')) {
    actions.setFieldError('terminal_id', 'شماره ترمینال را وارد نمایید.')
    return
  }

  if (PAYMENT_METHOD_TYPES.BEHPARDAKHT.value === selectedPaymentType.value.value &&
    (!values.username || values.username.trim() === '')) {
    actions.setFieldError('username', 'نام کاربری را وارد نمایید.')
    return
  }

  if ([
    PAYMENT_METHOD_TYPES.BEHPARDAKHT.value,
    PAYMENT_METHOD_TYPES.IRANKISH.value,
    PAYMENT_METHOD_TYPES.PARSIAN.value,
    PAYMENT_METHOD_TYPES.SEPEHR.value,
  ].includes(selectedPaymentType.value.value) && (!values.password || values.password.trim() === '')) {
    actions.setFieldError('password', 'کلمه عبور را وارد نمایید.')
    return
  }

  if (PAYMENT_METHOD_TYPES.IDPAY.value === selectedPaymentType.value.value &&
    (!values.api_key || values.api_key.trim() === '')) {
    actions.setFieldError('api_key', 'کلید API را وارد نمایید.')
    return
  }

  if (PAYMENT_METHOD_TYPES.IRANKISH.value === selectedPaymentType.value.value) {
    if (!values.acceptor_id || values.acceptor_id.trim() === '') {
      actions.setFieldError('acceptor_id', 'شناسه پذیرنده را وارد نمایید.')
      return
    }

    if (!values.public_key || values.public_key.trim() === '') {
      actions.setFieldError('public_key', 'کلید عمومی را وارد نمایید.')
      return
    }
  }

  if ([
    PAYMENT_METHOD_TYPES.SADAD.value,
    PAYMENT_METHOD_TYPES.ZARINPAL.value,
  ].includes(selectedPaymentType.value.value) && (!values.merchant_id || values.merchant_id.trim() === '')) {
    actions.setFieldError('merchant_id', 'شماره مرچنت را وارد نمایید.')
    return
  }
  //

  // assemble payment options
  const options = {}
  switch (selectedPaymentType.value.value) {
    case PAYMENT_METHOD_TYPES.BEHPARDAKHT.value:
      options.terminal_id = values.terminal_id
      options.username = values.username
      options.password = values.password
      break;
    case PAYMENT_METHOD_TYPES.IDPAY.value:
      options.api_key = values.api_key
      break;
    case PAYMENT_METHOD_TYPES.IRANKISH.value:
      options.terminal_id = values.terminal_id
      options.password = values.password
      options.acceptor_id = values.acceptor_id
      options.public_key = values.public_key
      break;
    case PAYMENT_METHOD_TYPES.PARSIAN.value:
      options.password = values.password
      break;
    case PAYMENT_METHOD_TYPES.SADAD.value:
      options.password = values.password
      options.terminal_id = values.terminal_id
      options.merchant_id = values.merchant_id
      break;
    case PAYMENT_METHOD_TYPES.SEPEHR.value:
      options.terminal_id = values.terminal_id
      break;
    case PAYMENT_METHOD_TYPES.ZARINPAL.value:
      options.merchant_id = values.merchant_id
      break;
  }
  //

  canSubmit.value = false

  PaymentMethodAPI.create({
    title: values.title,
    image: methodImage.value.full_path,
    type: selectedPaymentType.value.type,
    bank_gateway_type: selectedPaymentType.value.value,
    options,
    is_published: publishStatus.value,
  }, {
    success() {
      actions.resetForm()
      methodImage.value = null
      selectedPaymentType.value = null
      publishStatus.value = true

      router.push({name: 'admin.payment_methods'})
    },
    finally() {
      canSubmit.value = true
    }
  })
})
</script>
