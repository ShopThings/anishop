<template>
  <form>
    <base-loading-panel
        :loading="loading"
        type="form"
    >
      <template #content>
        <partial-card class="mb-3 p-3 relative">
          <template #body>
            <loader-dot-orbit
                v-if="isSubmitting"
                main-container-klass="absolute w-full h-full top-0 left-0 z-[2]"
                container-bg-color="bg-blue-50 opacity-40"
            />

            <TransitionGroup name="fade-group">
              <div
                  v-for="(product, idx) in products"
                  :key="idx"
                  class="p-2 border-2 border-dashed rounded-lg border-indigo-200 mb-3 relative"
              >
                <partial-builder-remove-btn
                    v-if="products.length > 1"
                    @click="handleRemoveProduct(idx)"
                />

                <div class="flex flex-wrap">
                  <div class="w-full p-2 sm:w-1/2 xl:w-1/6">
                    <base-input
                        type="number"
                        :min="0"
                        label-title="تعداد موجود"
                        placeholder="وارد نمایید"
                        :name="'stock_count[' + idx + ']'"
                        :value="product?.stock_count"
                        @input="(v) => {product.stock_count = v}"
                    >
                      <template #icon>
                        <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                      </template>
                    </base-input>
                  </div>
                  <div class="w-full p-2 sm:w-1/2 xl:w-1/6">
                    <base-input
                        type="number"
                        :min="0"
                        label-title="بیشترین تعداد در سبد خرید"
                        placeholder="وارد نمایید"
                        :name="'cart_max_count[' + idx + ']'"
                        :value="product?.cart_max_count"
                        @input="(v) => {product.cart_max_count = v}"
                    >
                      <template #icon>
                        <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                      </template>
                    </base-input>
                  </div>
                  <div class="w-full p-2 sm:w-1/2 xl:w-2/6">
                    <partial-input-label title="رنگ"/>
                    <base-select-searchable
                        :options="colors"
                        options-key="id"
                        options-text="name"
                        :name="'color[' + idx + ']'"
                        :value="product?.color"
                        @change="(c) => {product.color = c}"
                    />
                  </div>
                  <div class="w-full p-2 sm:w-1/2 xl:w-2/6">
                    <base-input
                        label-title="سایز"
                        :is-optional="true"
                        placeholder="وارد نمایید"
                        :name="'size[' + idx + ']'"
                        :value="product?.size"
                        @input="(v) => {product.size = v}"
                    >
                      <template #icon>
                        <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                      </template>
                    </base-input>
                  </div>
                </div>

                <div class="flex flex-wrap">
                  <div class="w-full p-2 sm:w-1/2 xl:w-4/12">
                    <base-input
                        label-title="گارانتی"
                        :is-optional="true"
                        placeholder="وارد نمایید"
                        :name="'guarantee[' + idx + ']'"
                        :value="product?.guarantee"
                        @input="(v) => {product.guarantee = v}"
                    >
                      <template #icon>
                        <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                      </template>
                    </base-input>
                  </div>
                  <div class="w-full p-2 sm:w-1/2 xl:w-2/12">
                    <base-input
                        type="number"
                        :min="0"
                        label-title="وزن با بسته‌بندی(گرم)"
                        placeholder="وارد نمایید"
                        :name="'weight[' + idx + ']'"
                        :value="product?.weight"
                        @input="(v) => {product.weight = v}"
                    >
                      <template #icon>
                        <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                      </template>
                    </base-input>
                  </div>
                  <div class="w-full p-2 sm:w-1/2 xl:w-3/12">
                    <base-input
                        type="number"
                        :min="0"
                        label-title="قیمت"
                        placeholder="وارد نمایید"
                        :name="'price[' + idx + ']'"
                        :value="product?.price"
                        @input="(v) => {product.price = v}"
                    >
                      <template #icon>
                        <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                      </template>
                    </base-input>
                  </div>
                  <div class="w-full p-2 sm:w-1/2 xl:w-3/12">
                    <base-input
                        type="number"
                        :min="0"
                        label-title="قیمت با تخفیف"
                        placeholder="وارد نمایید"
                        :name="'discounted_price[' + idx + ']'"
                        :value="product?.discounted_price"
                        @input="(v) => {product.discounted_price = v}"
                    >
                      <template #icon>
                        <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                      </template>
                    </base-input>
                  </div>
                </div>

                <div class="flex flex-wrap items-end">
                  <div class="w-full p-2 sm:w-1/2 xl:w-3/12">
                    <partial-input-label
                        title="تخفیف تا تاریخ"
                        :is-optional="true"
                    />
                    <date-picker
                        v-model="product.discount_date"
                        placeholder="تخفیف تا تاریخ"
                    />
                  </div>

                  <div class="w-full p-2 sm:w-1/2 xl:w-3/12">
                    <base-input
                        type="number"
                        :min="0"
                        label-title="مالیات بر ارزش افزوده"
                        placeholder="وارد نمایید"
                        :name="'tax_rate[' + idx + ']'"
                        :value="product?.tax_rate"
                        @input="(v) => {product.tax_rate = v}"
                    >
                      <template #icon>
                        <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                      </template>
                    </base-input>
                  </div>

                  <div class="w-full p-2 sm:w-1/2 xl:w-3/12">
                    <base-switch
                        label="موجود"
                        :name="'is_available[' + idx + ']'"
                        :enabled="product?.is_available"
                        sr-text="موجود/ناموجود"
                        @change="(status) => {product.is_available = status}"
                    />
                  </div>

                  <div class="w-full p-2 sm:w-1/2 xl:w-3/12">
                    <base-switch
                        label="محصول ویژه"
                        :name="'is_special[' + idx + ']'"
                        :enabled="product?.is_special"
                        sr-text="ویژه نمودن محصول"
                        @change="(status) => {product.is_special = status}"
                    />
                  </div>
                </div>

                <hr class="my-3">

                <div class="flex flex-wrap">
                  <div class="p-2 w-full sm:w-auto sm:grow">
                    <base-switch
                        label="نمایش بزودی"
                        :name="'show_coming_soon[' + idx + ']'"
                        :enabled="product?.show_coming_soon"
                        sr-text="نمایش/عدم نمایش بزودی"
                        @change="(status) => {product.show_coming_soon = status}"
                    />
                  </div>
                  <div class="p-2 w-full sm:w-auto sm:grow">
                    <base-switch
                        label="نمایش تماس برای اطلاعات بیشتر"
                        :name="'show_call_for_more[' + idx + ']'"
                        :enabled="product?.show_call_for_more"
                        sr-text="نمایش/عدم نمایش تماس برای اطلاعات بیشتر"
                        @change="(status) => {product.show_call_for_more = status}"
                    />
                  </div>
                  <div class="p-2 w-full sm:w-auto sm:grow">
                    <base-switch
                        label="نمایش محصول"
                        :name="'is_published[' + idx + ']'"
                        :enabled="product?.is_published"
                        sr-text="نمایش/عدم نمایش محصول"
                        @change="(status) => {product.is_published = status}"
                    />
                  </div>
                </div>
              </div>
            </TransitionGroup>
            <div class="mt-3 mb-1">
              <base-button
                  class="!text-orange-600 border-orange-400 w-full sm:w-auto flex items-center hover:bg-orange-50 mr-auto"
                  @click="handleNewProductClick"
              >
                <span class="mr-auto text-sm">محصول جدید</span>
                <PlusIcon class="h-6 w-6 mr-auto sm:mr-2"/>
              </base-button>
            </div>
          </template>
        </partial-card>

        <partial-card>
          <template #body>
            <partial-stepy-next-prev-buttons
                :current-step="options.currentStep"
                :current-step-index="options.currentStepIndex"
                :last-step="options.lastStep"
                :allow-next-step="!isSubmitting"
                :allow-prev-step="false"
                :show-prev-step-button="false"
                :loading="isSubmitting"
                @next="handleNextClick(options.next)"
            />
          </template>
        </partial-card>
      </template>
    </base-loading-panel>
  </form>
</template>

<script setup>
import {onMounted, ref} from "vue";
import PartialCard from "../../../../components/partials/PartialCard.vue";
import PartialStepyNextPrevButtons from "../../../../components/partials/PartialStepyNextPrevButtons.vue";
import {useForm} from "vee-validate";
import yup from "../../../../validation/index.js";
import {ArrowLeftCircleIcon, PlusIcon} from "@heroicons/vue/24/outline/index.js";
import BaseInput from "../../../../components/base/BaseInput.vue";
import PartialInputLabel from "../../../../components/partials/PartialInputLabel.vue";
import BaseSelectSearchable from "../../../../components/base/BaseSelectSearchable.vue";
import BaseLoadingPanel from "../../../../components/base/BaseLoadingPanel.vue";
import {useRequest} from "../../../../composables/api-request.js";
import {apiRoutes} from "../../../../router/api-routes.js";
import BaseSwitch from "../../../../components/base/BaseSwitch.vue";
import PartialBuilderRemoveBtn from "../../../../components/partials/PartialBuilderRemoveBtn.vue";
import BaseButton from "../../../../components/base/BaseButton.vue";
import LoaderDotOrbit from "../../../../components/base/loader/LoaderDotOrbit.vue";

defineProps({
  options: {
    type: Object,
    required: true,
  },
})
const emit = defineEmits(['add', 'remove'])

const loading = ref(false)
const canSubmit = ref(true)

const colors = ref([])

//------------------------
// Product new instance
//------------------------
const products = ref([
  {
    stock_count: null,
    max_cart_count: null,
    color: null,
    size: null,
    guarantee: null,
    weight: null,
    price: null,
    discounted_price: null,
    discounted_until: null,
    tax_rate: null,
    is_available: true,
    is_special: false,
    show_coming_soon: false,
    show_call_for_more: false,
    is_published: true,
  }
])

function handleNewProductClick() {
  products.value.push({
    stock_count: null,
    max_cart_count: null,
    color: null,
    size: null,
    guarantee: null,
    weight: null,
    price: null,
    discounted_price: null,
    discounted_until: null,
    tax_rate: null,
    is_available: true,
    is_special: false,
    show_coming_soon: false,
    show_call_for_more: false,
    is_published: true,
  })

  emit('add', products.value)
}

function handleRemoveProduct(idx) {
  products.value.splice(idx, 1)

  emit('remove', products.value)
}

//------------------------
let nextFn = null

function handleNextClick(next) {
  onSubmit()
  nextFn = next
}

const {handleSubmit, errors, isSubmitting} = useForm({
  validationSchema: yup.object().shape({}),
})

const onSubmit = handleSubmit((values, actions) => {
  if (!canSubmit.value) return

  return new Promise((resolve) => {
    setTimeout(() => {
      resolve()
      if (nextFn)
        nextFn()
    }, 2000)
  })
})

onMounted(() => {
  // useRequest(apiRoutes.admin.colors.index, null, {
  //     success: (response) => {
  //         colors.value = response.data
  //
  //         loading.value = false
  //     }
  // })
})
</script>

<style scoped>
.fade-group-enter-active,
.fade-group-leave-active {
  transition: opacity 0.3s ease;
  transition-delay: .05s;
}

.fade-group-enter-from,
.fade-group-leave-to {
  opacity: 0;
}
</style>
