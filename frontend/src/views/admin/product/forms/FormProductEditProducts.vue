<template>
  <base-loading-panel
    :loading="loading"
    type="form"
  >
    <template #content>
      <form>
        <partial-card class="mb-3 p-3 relative">
          <template #body>
            <loader-dot-orbit
              v-if="!canSubmit"
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

                <base-message type="default" :has-close="false">
                  <div class="leading-loose">
                    وارد نمودن یکی از
                    <span class="rounded-lg py-0.5 px-2.5 bg-white text-black mx-1">رنگ</span>
                    <span class="rounded-lg py-0.5 px-2.5 bg-white text-black mx-1">سایز</span>
                    یا
                    <span class="rounded-lg py-0.5 px-2.5 bg-white text-black mx-1">گارانتی</span>
                    و همچنین
                    <span class="rounded-lg py-0.5 px-2.5 bg-white text-black mx-1">قیمت محصول</span>
                    و
                    <span class="text-lime-200 text-shadow-lg underline underline-offset-8">مقدار بیشتر از صفر</span>
                    برای
                    <span class="rounded-lg py-0.5 px-2.5 bg-white text-black mx-1">بیشترین تعداد در سبد خرید</span>
                    برای در نظر گرفته شدن محصول، ضروری می‌باشد. در غیر اینصورت محصول در نظر گرفته
                    <span class="text-lime-200 text-shadow-lg underline underline-offset-8">نمی‌شود</span>
                    .
                  </div>
                </base-message>

                <div class="flex flex-wrap">
                  <div class="w-full p-2 sm:w-1/2 xl:w-1/6">
                    <base-input
                      type="text"
                      :min="0"
                      :money-mask="true"
                      label-title="تعداد موجود"
                      placeholder="وارد نمایید"
                      :name="'stock_count[' + idx + ']'"
                      :value="product?.stock_count?.toString()"
                      @input="(v) => {product.stock_count = v}"
                    >
                      <template #icon>
                        <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                      </template>
                    </base-input>
                  </div>
                  <div class="w-full p-2 sm:w-1/2 xl:w-1/6">
                    <base-input
                      type="text"
                      :min="0"
                      :money-mask="true"
                      label-title="بیشترین تعداد در سبد خرید"
                      placeholder="وارد نمایید"
                      :name="'max_cart_count[' + idx + ']'"
                      :value="product?.max_cart_count?.toString()"
                      @input="(v) => {product.max_cart_count = v}"
                    >
                      <template #icon>
                        <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                      </template>
                    </base-input>
                  </div>
                  <div class="w-full p-2 sm:w-1/2 xl:w-2/6">
                    <partial-input-label title="رنگ" :is-optional="true"/>
                    <base-select-searchable
                      :options="colors"
                      options-key="id"
                      options-text="name"
                      :name="'color[' + idx + ']'"
                      :value="product?.color"
                      :is-loading="loadingGetColors"
                      :is-local-search="false"
                      @change="(c) => {product.color = c}"
                      @query="searchColor"
                      @click-next-page="searchColorNextPage"
                      @click-prev-page="searchColorPrevPage"
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
                      type="text"
                      :min="0"
                      :money-mask="true"
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
                      type="text"
                      :min="0"
                      :money-mask="true"
                      label-title="قیمت"
                      placeholder="وارد نمایید"
                      :name="'price[' + idx + ']'"
                      :value="product?.price?.toString()"
                      @input="(v) => {product.price = v}"
                    >
                      <template #icon>
                        <ArrowLeftCircleIcon class="h-6 w-6 text-gray-400"/>
                      </template>
                    </base-input>
                  </div>
                  <div class="w-full p-2 sm:w-1/2 xl:w-3/12">
                    <base-input
                      type="text"
                      :min="0"
                      :money-mask="true"
                      label-title="قیمت با تخفیف"
                      placeholder="وارد نمایید"
                      :name="'discounted_price[' + idx + ']'"
                      :value="product?.discounted_price?.toString()"
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
                      title="تخفیف از تاریخ"
                      :is-optional="true"
                    />
                    <date-picker
                      v-model="product.discounted_from"
                      placeholder="تخفیف از تاریخ"
                    />
                    <partial-input-error-message
                      :error-message="customErrors[idx] ? customErrors[idx]?.discounted_from : null"
                    />
                  </div>
                  <div class="w-full p-2 sm:w-1/2 xl:w-3/12">
                    <partial-input-label
                      title="تخفیف تا تاریخ"
                      :is-optional="true"
                    />
                    <date-picker
                      v-model="product.discounted_until"
                      placeholder="تخفیف تا تاریخ"
                    />
                    <partial-input-error-message
                      :error-message="customErrors[idx] ? customErrors[idx]?.discounted_until : null"
                    />
                  </div>

                  <div class="w-full p-2 sm:w-1/2 xl:w-3/12">
                    <base-input
                      type="text"
                      :min="0"
                      :money-mask="true"
                      placeholder="وارد نمایید"
                      :name="'tax_rate[' + idx + ']'"
                      :value="product?.tax_rate?.toString()"
                      @input="(v) => {product.tax_rate = v}"
                    >
                      <template #label>
                        <div class="flex items-center gap-1.5 text-sm">
                          <span>مالیات بر ارزش افزوده</span>
                          <span class="text-xs text-pink-600">(بر حسب درصد)</span>
                        </div>
                      </template>
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

                <hr class="my-3">

                <div class="p-2 w-full sm:w-auto sm:grow flex flex-col sm:items-center gap-3 sm:flex-row-reverse">
                  <div class="shrink-0">
                    <base-switch
                      label="مرسوله مجزا"
                      :name="'has_separate_shipment[' + idx + ']'"
                      :enabled="product?.has_separate_shipment"
                      sr-text="مرسوله مجزا"
                      @change="(status) => {product.has_separate_shipment = status}"
                    />
                  </div>

                  <div
                    class="text-lime-600 text-sm leading-relaxed grow border-2 border-lime-300 bg-lime-50 p-2 rounded-lg">
                    در صورت فعال نمودن این گزینه، محصول به صورت جداگانه در نظر گرفته می‌شود و هزینه ارسال در نظر گرفته
                    شده
                    دوباره برای این محصول نیز در نظر گرفته خواهد شد.
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
              :allow-next-step="canSubmit"
              :allow-prev-step="canSubmit"
              :show-prev-step-button="canSubmit"
              :loading="!canSubmit"
              @next="handleNextClick(options.next)"
            />
          </template>
        </partial-card>
      </form>
    </template>
  </base-loading-panel>
</template>

<script setup>
import {computed, onMounted, reactive, ref} from "vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import PartialStepyNextPrevButtons from "@/components/partials/PartialStepyNextPrevButtons.vue";
import yup from "@/validation/index.js";
import {ArrowLeftCircleIcon, PlusIcon} from "@heroicons/vue/24/outline/index.js";
import BaseInput from "@/components/base/BaseInput.vue";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import BaseSelectSearchable from "@/components/base/BaseSelectSearchable.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import PartialBuilderRemoveBtn from "@/components/partials/PartialBuilderRemoveBtn.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import LoaderDotOrbit from "@/components/base/loader/LoaderDotOrbit.vue";
import {useToast} from "vue-toastification";
import {useFormSubmit} from "@/composables/form-submit.js";
import {escapeMoneyCharacter, getRouteParamByKey} from "@/composables/helper.js";
import BaseMessage from "@/components/base/BaseMessage.vue";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import {ColorAPI, ProductAPI} from "@/service/APIProduct.js";

defineProps({
  options: {
    type: Object,
    required: true,
  },
})
const emit = defineEmits(['add', 'remove'])

const toast = useToast()
const slugParam = getRouteParamByKey('slug', null, false)

const loading = ref(true)

//------------------------
// Color operation
//------------------------

const loadingGetColors = ref(true)
const colors = ref([])
const colorSelectConfig = reactive({
  limit: 15,
  currentPage: 1,
  lastPage: null,
  offset: () => {
    return (colorSelectConfig.currentPage - 1) * colorSelectConfig.limit;
  },
})

function searchColor(query) {
  loadingGetColors.value = true
  ColorAPI.fetchAll({
    limit: colorSelectConfig.limit,
    offset: colorSelectConfig.offset(),
    text: query
  }, {
    success(response) {
      colors.value = response.data
      if (response.meta) {
        colorSelectConfig.lastPage = response.meta?.last_page
      }
    },
    finally() {
      loadingGetColors.value = false
    }
  })
}

function searchColorNextPage(query) {
  if (colorSelectConfig.currentPage < colorSelectConfig.lastPage) {
    colorSelectConfig.currentPage++
    searchColor(query)
  }
}

function searchColorPrevPage(query) {
  if (colorSelectConfig.currentPage > 1) {
    colorSelectConfig.currentPage--
    searchColor(query)
  }
}

//------------------------

//------------------------
// Product new instance
//------------------------
const hasAnyProduct = computed(() => {
  return !!getDefinedProducts().length
})

const products = ref([
  {
    id: null,
    stock_count: null,
    max_cart_count: null,
    color: null,
    size: null,
    guarantee: null,
    weight: null,
    price: null,
    discounted_price: null,
    discounted_from: null,
    discounted_until: null,
    tax_rate: null,
    is_available: true,
    is_special: false,
    show_coming_soon: false,
    show_call_for_more: false,
    is_published: true,
    has_separate_shipment: false,
  }
])

function handleNewProductClick() {
  products.value.push({
    id: null,
    stock_count: null,
    max_cart_count: null,
    color: null,
    size: null,
    guarantee: null,
    weight: null,
    price: null,
    discounted_price: null,
    discounted_from: null,
    discounted_until: null,
    tax_rate: null,
    is_available: true,
    is_special: false,
    show_coming_soon: false,
    show_call_for_more: false,
    is_published: true,
    has_separate_shipment: false,
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

const customErrors = ref([])
const {canSubmit, errors, onSubmit} = useFormSubmit({
  validationSchema: yup.object().shape({
    stock_count: yup.array().of(
      yup.string()
        .greaterThanNumber(0, 'تعداد محصول در انبار باید عددی بزرگتر یا برابر صفر باشد.', {equal: true})
    ),
    max_cart_count: yup.array().of(
      yup.string()
        .greaterThanNumber(0, 'تعداد محصول در هر خرید باید عددی بزرگتر از صفر باشد.')
    ),
    weight: yup.array().of(yup.string().greaterThanNumber(0, 'وزن محصول باید عددی بزرگتر از صفر باشد.')),
    price: yup.array().of(
      yup.string()
        .greaterThanNumber(1000, 'قیمت باید عددی مثبت و بزرگتر از ۱۰۰۰ تومان باشد.', {equal: true}).required('')
    ),
    discounted_price: yup.array().of(
      yup.string()
        .greaterThanNumber(0, 'قیمت با تخفیف باید عددی مثبت و بزرگتر از ۱۰۰۰ تومان باشد.', {equal: true})
    ),
    tax_rate: yup.array().of(
      yup.string()
        .percentage('درصد مالیات وارد شده نامعتبر می‌باشد.')
        .required('درصد مالیات را وارد نمایید.')
    ),
    is_available: yup.array().of(yup.boolean().required('وضعیت موجودی را مشخص کنید.')),
    is_special: yup.array().of(yup.boolean().required('آیا محصول ویژه می‌باشد.')),
    show_coming_soon: yup.array().of(yup.boolean().required('وضعیت بزودی را مشخص کنید.')),
    show_call_for_more: yup.array().of(yup.boolean().required('وضعیت تماس برای اطلاعات بیشتر را مشخص کنید.')),
    is_published: yup.array().of(yup.boolean().required('وضعیت انتشار را مشخص کنید.')),
    has_separate_shipment: yup.array().of(yup.boolean().required('وضعیت مرسوله مجزا را مشخص کنید.')),
  }),
}, (values, actions) => {
  if (!canSubmit.value) return

  if (!hasAnyProduct.value) {
    toast.error('وارد نمودن حداقل یک محصول الزامی می‌باشد.')
    return
  }

  customErrors.value = []

  let haveError = false
  const definedProducts = getDefinedProducts()
  for (let i = 0; i < definedProducts.length; i++) {
    if (definedProducts[i].discounted_from && definedProducts[i].discounted_until) {
      const d1 = new Date(definedProducts[i].discounted_from)
      const d2 = new Date(definedProducts[i].discounted_until)

      if (d1 > d2) {
        customErrors.value[i] = {
          'discounted_from': 'تاریخ شروع تخفیف باید کوچکتر از تاریخ پایان تخفیف باشد.',
        }
        haveError = true
      }
    }
  }

  if (haveError) return

  canSubmit.value = false

  ProductAPI.modifyProducts(productStore.getProductSlug, {
    products: definedProducts,
  }, {
    success() {
      toast.success('محصولات بروزرسانی شد.');
      if (nextFn) nextFn()
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
  ProductAPI.fetchVariants(slugParam.value, {
    success: (response) => {
      if (response.data) {
        products.value = response.data.map((item) => {
          item['discounted_from'] = item['normal_discounted_from']
          item['discounted_until'] = item['normal_discounted_until']

          delete item['normal_discounted_from']
          delete item['normal_discounted_until']
        })
      }
      loading.value = false
    },
  })

  searchColor()
})

function getDefinedProducts() {
  const p = []

  let tmpProducts = JSON.parse(JSON.stringify(products.value))
  tmpProducts = escapeMoneyCharacter(tmpProducts, [
    'stock_count', 'max_cart_count', 'price', 'discounted_price', 'tax_rate',
  ])

  for (let i of tmpProducts) {
    if ((i.color || i.size || i.guarantee) && i.price && i.max_cart_count > 0) {
      if (i.discounted_price === 0) {
        i.discounted_price = null
      }
      p.push(i)
    }
  }

  return p
}
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
