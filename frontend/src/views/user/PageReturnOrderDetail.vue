<template>
  <template v-if="!loading">
    <base-message
      :has-close="false"
      class="rounded-lg"
      type="warning"
    >
      <div class="leading-relaxed">
        در صورتی می‌توانید اطلاعات سفارش مرجوع شده را تغییر دهید که توسط سایت تغییر وضعیت داده نشده باشد.
      </div>
    </base-message>

    <partial-card class="border-2 border-teal-500 mb-3">
      <template #body>
        <div class="py-3 px-4">
          <div class="text-sm flex flex-col sm:flex-row flex-wrap items-center gap-2">
            <div class="flex flex-wrap items-center gap-2">
              <span class="text-gray-400 text-xs">بازگشت سفارش به کد:</span>
              <span class="tracking-widest">{{ returnOrder?.order_code }}</span>
            </div>
            <router-link
              :to="{name: 'user.order.detail', params: {code: returnOrder?.order_code}}"
              class="flex gap-2 items-center text-blue-600 group ml-auto mr-auto sm:ml-0"
            >
              <span>مشاهده جزئیات</span>
              <ArrowLongLeftIcon class="w-5 h-5 group-hover:-translate-x-1.5 transition"/>
            </router-link>
          </div>
        </div>
      </template>
    </partial-card>

    <div class="mb-3">
      <partial-badge-status-return-order
        :color-hex="returnOrder?.status.color_hex"
        :text="returnOrder?.status.text"
        class="w-full py-2 !text-sm"
      />
    </div>
  </template>
  <template v-else>
    <div
      class="flex gap-2 items-center justify-between bg-white animate-pulse w-full h-8 rounded my-3 border-2 border-teal-500 mb-3 px-3">
      <div class="w-40 h-3 rounded bg-slate-300"></div>
      <div class="w-16 h-3 rounded bg-blue-300"></div>
    </div>

    <div class="flex gap-2 items-center justify-center bg-slate-300 animate-pulse w-full h-8 rounded my-3 px-3">
      <div class="w-36 h-3 rounded bg-orange-300"></div>
      <div class="w-8 h-3 rounded bg-orange-300"></div>
    </div>
  </template>

  <form @submit.prevent="onSubmit">
    <div>
      <h2 class="text-slate-400 mb-1">
        توضیحات
      </h2>

      <base-loading-panel
        :loading="loading"
        type="form"
      >
        <template #content>
          <partial-card class="border-0">
            <template #body>
              <div class="p-3">
                <div class="flex flex-wrap items-center gap-2 5">
                  <div
                    v-if="!returnOrder?.has_status_changed"
                    class="mb-4 text-left"
                  >
                    <base-button
                      :disabled="cancelRequestLoading"
                      class="!border-2 border-rose-500 bg-rose-500 px-4 text-sm !py-1"
                      type="button"
                      @click="handleCancelReturnOrder"
                    >
                      <VTransitionFade>
                        <loader-circle
                          v-if="cancelRequestLoading"
                          big-circle-color="border-transparent"
                          main-container-klass="absolute w-full h-full top-0 left-0"
                          spinner-klass="!w-6 !h-6"
                        />
                      </VTransitionFade>

                      <span>لغو درخواست</span>
                    </base-button>
                  </div>

                  <div
                    v-if="returnOrder?.next_status?.value"
                    class="mb-4 text-left"
                  >
                    <base-button
                      :disabled="nextRequestStatusLoading"
                      class="!border-2 border-black !text-black px-4 text-sm !py-1"
                      type="button"
                      @click="handleNextStatus"
                    >
                      <VTransitionFade>
                        <loader-circle
                          v-if="nextRequestStatusLoading"
                          big-circle-color="border-transparent"
                          container-bg-color="bg-black"
                          main-container-klass="absolute w-full h-full top-0 left-0"
                          small-circle-color="border-t-white"
                          spinner-klass="!w-6 !h-6"
                        />
                      </VTransitionFade>

                      <span>{{ returnOrder.next_status.text }}</span>
                    </base-button>
                  </div>
                </div>

                <div class="flex flex-wrap items-center gap-2 mb-3">
                  <span class="text-gray-400 text-xs">کد مرجوع:</span>
                  <span class="tracking-widest rounded bg-emerald-200 py-1 px-2.5">{{ returnOrder?.code }}</span>
                </div>
                <div class="mb-3">
                  <base-textarea
                    :in-edit-mode="!returnOrder?.description || returnOrder?.description?.trim() === ''"
                    :is-editable="!returnOrder?.has_status_changed"
                    :value="returnOrder?.description"
                    label-title="علت مرجوع نمودن محصول"
                    name="description"
                    placeholder="توضیحات خود را وارد نمایید..."
                  />
                </div>

                <template
                  v-if="returnOrder?.not_accepted_description && returnOrder?.not_accepted_description?.trim() !== ''"
                >
                  <partial-input-label title="پاسخ به درخواست"/>
                  <div class="flex flex-col gap-3 shadow-md bg-indigo-50 p-6 mt-3 border border-slate-50 leading-loose">
                    <div>
                      {{ returnOrder?.not_accepted_description ?? '-' }}
                    </div>
                  </div>
                </template>
              </div>
            </template>
          </partial-card>
        </template>
      </base-loading-panel>
    </div>

    <div class="mt-6">
      <h2 class="text-slate-400 mb-1">
        آیتم‌های سفارش
      </h2>

      <base-message
        v-if="!loading"
        :has-close="false"
        class="rounded-lg"
        type="info"
      >
        <div class="leading-relaxed">
          برای عدم انتخاب کالا برای مرجوع، تعداد آن را
          <span class="bg-white text-black rounded inline-block py-1 px-2 mx-1">صفر</span>
          قرار دهید.
        </div>
      </base-message>

      <base-loading-panel
        :loading="loading"
        type="list"
      >
        <template #content>
          <partial-card class="border-0">
            <template #body>
              <ul
                v-if="items?.length"
                class="divide-y divide-gray-100"
              >
                <li
                  v-for="product in items"
                  :key="product.id"
                  class="relative flex flex-col md:flex-row mb-2 py-3 pr-3 pl-10"
                >
                  <div class="shrink-0">
                    <router-link
                      v-if="product?.product"
                      :to="{name: 'product.detail', params: {slug: product.product.slug}}"
                      class="inline-block"
                    >
                      <base-lazy-image
                        :alt="product.product.title"
                        :lazy-src="product.image.path"
                        :size="FileSizes.SMALL"
                        class="!w-36 md:!w-24 h-auto hover:scale-95 transition"
                      />
                    </router-link>
                    <div
                      v-else
                      class="inline-block text-center size-36 border rounded md:size-24 hover:scale-95 transition"
                    >
                      <div class="flex flex-col justify-center gap-2 h-full">
                        <PhotoIcon class="size-8 text-rose-300 mx-auto"/>
                        <span class="text-xs text-slate-400">فاقد تصویر</span>
                      </div>
                    </div>
                  </div>

                  <div class="grow mr-3 mb-2 md:mb-0">
                    <router-link
                      v-if="product?.product"
                      :to="{name: 'product.detail', params: {slug: product.product.slug}}"
                      class="inline-block mb-2 text-blue-600 hover:text-opacity-90 leading-relaxed"
                    >
                      {{ product.product_title }}
                    </router-link>
                    <div
                      v-else
                      class="inline-block mb-2 text-blue-600 hover:text-opacity-90 leading-relaxed"
                    >
                      {{ product.product_title }}
                    </div>

                    <div class="flex flex-wrap mb-2 items-center">
                      <div
                        v-if="product.discounted_price && getPercentageOfPortion(product.discounted_price, product.price) > 0"
                        class="rounded-lg bg-rose-500 text-white py-1 px-2 my-1 ml-3 flex items-center justify-center"
                      >
                        <span class="text-xs">%</span>
                        <div class="mr-1 inline-block text-sm">
                          {{ getPercentageOfPortion(product.discounted_price, product.price) }}
                        </div>
                      </div>

                      <div class="flex flex-wrap items-center gap-3">
                        <div class="text-xl">
                          <template v-if="product.discounted_price">
                            {{ numberFormat(product.discounted_price) }}
                          </template>
                          <template v-else>
                            {{ numberFormat(product.price) }}
                          </template>
                          <span class="text-xs text-gray-400">تومان</span>
                        </div>

                        <div
                          v-if="product.discounted_price"
                          class="relative text-center"
                        >
                        <span
                          class="absolute top-1/2 -translate-y-1/2 left-0 h-[1px] w-full bg-slate-500 -rotate-3"></span>
                          <div class="text-slate-500 text-sm">
                            {{ numberFormat(product.price) }}
                            <span class="text-xs text-gray-400">تومان</span>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="flex flex-wrap gap-2 items-end mb-2">
                      <div class="flex flex-col gap-2 items-center justify-center">
                        <span class="text-pink-500 text-xs">تعداد محصول بازگشتی</span>
                        <base-spinner
                          v-model="product.return_quantity"
                          :max="product.quantity"
                          :min="0"
                        />
                      </div>
                      <span v-if="product.is_accepted"
                            class="py-1 px-2 text-xs border-2 border-green-500 rounded-full">تایید مرجوع</span>
                    </div>
                  </div>

                  <div class="shrink-0 mr-3 text-sm md:w-72">
                    <div
                      v-if="product.color_name"
                      class="mb-2 flex items-center"
                    >
                      <span class="text-gray-600 ml-2 text-xs">رنگ:</span>
                      {{ product.color_name }}
                      <span
                        v-tooltip.top="'' + product.color_name + ''"
                        :style="'background-color:' + product.color_hex"
                        class="inline-block w-5 h-5 rounded-full border mr-2"
                      ></span>
                    </div>
                    <div
                      v-if="product.size"
                      class="mb-2"
                    >
                      <span class="text-gray-600 ml-2 text-xs">سایز:</span>
                      {{ product.size }}
                    </div>
                    <div v-if="product.guarantee">
                      <span class="text-gray-600 ml-2 text-xs">گارانتی:</span>
                      {{ product.guarantee }}
                    </div>
                  </div>
                </li>
              </ul>

              <div
                v-else
                class="text-orange-300 text-center p-2"
              >
                هیچ محصولی خریداری نشده است!
              </div>
            </template>
          </partial-card>
        </template>
      </base-loading-panel>
    </div>

    <div
      v-if="!loading"
      class="mt-3"
    >
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

        <span class="ml-auto">ثبت درخواست</span>
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

<script setup>
import {onMounted, ref} from "vue";
import {ArrowLongLeftIcon, CheckIcon, PhotoIcon} from "@heroicons/vue/24/outline/index.js";
import BaseMessage from "@/components/base/BaseMessage.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import yup from "@/validation/index.js";
import BaseTextarea from "@/components/base/BaseTextarea.vue";
import PartialBadgeStatusReturnOrder from "@/components/partials/PartialBadgeStatusReturnOrder.vue";
import BaseLazyImage from "@/components/base/BaseLazyImage.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import BaseAnimatedButton from "@/components/base/BaseAnimatedButton.vue";
import BaseSpinner from "@/components/base/BaseSpinner.vue";
import {useFormSubmit} from "@/composables/form-submit.js";
import {getPercentageOfPortion, getRouteParamByKey, numberFormat} from "@/composables/helper.js";
import {UserPanelReturnOrderAPI} from "@/service/APIUserPanel.js";
import {useToast} from "vue-toastification";
import {useConfirmToast} from "@/composables/toast-helper.js";
import {useRouter} from "vue-router";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import {FileSizes} from "@/composables/file-list.js";

const router = useRouter()
const toast = useToast()
const codeParam = getRouteParamByKey('code', null, false)

const returnOrder = ref(null)
const items = ref([])
const loading = ref(true)

const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    description: yup.string().required('علت مرجوع نمودن کالا را وارد نمایید.'),
  }),
}, (values, actions) => {
  let definedItems = getDefinedItems()
  if (!definedItems.length) {
    toast.error('لطفا آیتم‌های مرجوع و تعداد آن‌ها را به درستی وارد نمایید.')
    return
  }

  canSubmit.value = false

  UserPanelReturnOrderAPI.updateById(codeParam.value, {
    description: values.description,
    items: definedItems,
  }, {
    success(response) {
      setFormFields(response.data)
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

function fetchRequestedReturnOrder() {
  UserPanelReturnOrderAPI.fetchById(codeParam.value, {
    success(response) {
      setFormFields(response.data)
      loading.value = false
    },
  })
}

const cancelRequestLoading = ref(false)

function handleCancelReturnOrder() {
  if (cancelRequestLoading.value) return

  useConfirmToast(() => {
    cancelRequestLoading.value = true

    UserPanelReturnOrderAPI.deleteById(codeParam.value, {
      success() {
        router.push({name: 'user.return_orders'})
      },
      finally() {
        cancelRequestLoading.value = false
      },
    })
  }, 'انصراف از مرجوع کالا')
}

const nextRequestStatusLoading = ref(false)

function handleNextStatus() {
  if (nextRequestStatusLoading.value || !returnOrder?.next_status?.value) return

  useConfirmToast(() => {
    nextRequestStatusLoading.value = true

    UserPanelReturnOrderAPI.changeStatus(codeParam.value, {
      status: returnOrder.next_status.value
    }, {
      success() {
        toast.success('تغییر وضعیت با موفقیت انجام شد.')
        fetchRequestedReturnOrder()
      },
      finally() {
        nextRequestStatusLoading.value = false
      },
    })
  }, 'تغییر وضعیت مرجوع', 'لطفا دقت نمایید، وضعیت مرجوع در حال تغییر به ' + '[' + returnOrder.next_status.text + ']' + ' می‌باشد.')
}

onMounted(() => {
  fetchRequestedReturnOrder()
})

function setFormFields(item) {
  returnOrder.value = item
  items.value = item.items
}

function getDefinedItems() {
  let defined = []

  for (let i of items) {
    let retQnt = +i.return_quantity
    if (i.id && i.return_quantity && !isNaN(retQnt) && retQnt > 0 && retQnt <= +i.quantity) {
      defined.push({
        id: i.id,
        quantity: retQnt,
      })
    }
  }

  return defined
}
</script>
