<template>
  <partial-filter-card
      v-model:selected-items="selectedColors"
      :displaying-selected-items="{selectedItems: selectedColors, length: Object.keys(selectedColors).length}"
      :items="colors"
      :show-selected-items="true"
      :is-loading="colorNSizeLoading"
      item-key="id"
      item-text-key="name"
      item-unique-key-text="color"
      panel-container-class="flex flex-wrap items-center gap-2"
  >
    <template #default>
      <div class="flex items-center justify-between gap-3 w-full pl-3 min-h-7">
        <span class="font-iranyekan-bold">رنگ</span>
        <div
            v-if="!colorNSizeLoading"
            class="rounded-full py-0.5 px-2.5 border-2 border-rose-400 flex items-center gap-1.5"
        >
          <span>{{ Object.keys(selectedColors).length }}</span>
          <span class="text-xs">انتخاب</span>
        </div>
      </div>
    </template>

    <template #item="{item}">
      <div
          :class="{
              'text-indigo-600': selectedColors[item.id],
          }"
          class="flex flex-col items-center cursor-pointer"
          @click="toggleColorSelection(item)"
      >
        <div
            :class="{
                'border-indigo-500': selectedColors[item.id],
            }"
            class="p-1 flex items-center justify-center border-2 rounded-lg"
        >
          <div
              :style="'background-color:' + item.hex + ';'"
              class="w-8 h-8 rounded-md shadow-md"
          ></div>
        </div>
        <span class="text-xs">{{ item.name }}</span>
      </div>
    </template>
  </partial-filter-card>

  <partial-filter-card
      v-model:selected-items="selectedSizes"
      :displaying-selected-items="{selectedItems: selectedSizes, length: Object.keys(selectedSizes).length}"
      :items="sizes"
      :show-selected-items="true"
      :is-loading="colorNSizeLoading"
      item-key="id"
      item-text-key="size"
      item-unique-key-text="size"
      panel-container-class="flex flex-wrap items-center gap-2"
  >
    <template #default>
      <div class="flex items-center justify-between gap-3 w-full pl-3 min-h-7">
        <span class="font-iranyekan-bold">اندازه</span>
        <div
            v-if="!colorNSizeLoading"
            class="rounded-full py-0.5 px-2.5 border-2 border-rose-400 flex items-center gap-1.5"
        >
          <span>{{ Object.keys(selectedSizes).length }}</span>
          <span class="text-xs">انتخاب</span>
        </div>
      </div>
    </template>

    <template #item="{item}">
      <div
          :class="{
              'border-indigo-500 bg-indigo-50': selectedSizes[item.id]
          }"
          class="flex items-center justify-center border-2 rounded-lg cursor-pointer"
          @click="toggleSizeSelection(item)"
      >
        <div class="rounded-md py-1.5 px-3">
          <span class="text-black">{{ item.size }}</span>
        </div>
      </div>
    </template>
  </partial-filter-card>

  <partial-filter-card
      v-model:selected-items="selectedBrands"
      :displaying-selected-items="{selectedItems: selectedBrands, length: Object.keys(selectedBrands).length}"
      :items="brands"
      :show-selected-items="true"
      type="multi"
      :is-loading="brandsLoading"
      item-key="id"
      item-text-key="name"
      item-unique-key-text="brand"
      panel-container-class="flex flex-wrap items-center gap-2"
  >
    <template #default>
      <div class="flex items-center justify-between gap-3 w-full pl-3 min-h-7">
        <span class="font-iranyekan-bold">برند</span>
        <div
            v-if="!brandsLoading"
            class="rounded-full py-0.5 px-2.5 border-2 border-rose-400 flex items-center gap-1.5"
        >
          <span>{{ Object.keys(selectedBrands).length }}</span>
          <span class="text-xs">انتخاب</span>
        </div>
      </div>
    </template>
  </partial-filter-card>

  <partial-filter-card
      v-if="minmaxPriceLoading || (minmaxPrice[0] !== 0 && minmaxPrice[1] !== 0)"
      :is-loading="minmaxPriceLoading"
      item-unique-key-text="price"
      panel-container-class="flex flex-wrap items-center gap-2"
  >
    <template #default>
      <div class="flex items-center gap-3 w-full pl-3">
        <span class="font-iranyekan-bold">محدوده قیمت</span>
        <span
            v-if="searchParams?.price_range"
            class="rounded-full w-2 h-2 bg-sky-400"></span>
      </div>
    </template>

    <template #panelClosed>
      <div
          v-if="selectedMinmaxPrice.length"
          class="px-3 pb-2 mt-[-2px] bg-white border-b border-slate-100"
      >
        <div class="flex flex-wrap items-center gap-3 justify-between text-xs text-slate-400">
          <div class="flex gap-2 items-center">
            <span>از</span>
            <div>
              <span class="font-iranyekan-bold text-sm">{{
                  numberFormat(selectedMinmaxPrice[0])
                }}</span>
              <span class="mr-1">تومان</span>
            </div>
          </div>

          <div class="flex gap-2 items-center">
            <span>تا</span>
            <div>
              <span class="font-iranyekan-bold text-sm">{{
                  numberFormat(selectedMinmaxPrice[1])
                }}</span>
              <span class="mr-1">تومان</span>
            </div>
          </div>
        </div>
      </div>
    </template>

    <template #panel>
      <div class="flex flex-col p-2 gap-3 mb-5">
        <div class="flex items-center gap-2">
          <span class="text-slate-500">از</span>
          <div class="grow">
            <base-input
              :value="numberFormat(selectedMinmaxPrice[0] ?? minmaxPrice[0])"
                klass="no-spin-arrow !py-1.5 !text-xl font-iranyekan-bold"
                name="min_price"
                @input="formatPriceNumberInput"
                @keydown="formatPriceNumberInput"
                @keyup="formatPriceNumberInput"
            />
          </div>
          <span class="text-xs text-slate-400">تومان</span>
        </div>
        <div class="flex items-center gap-2">
          <span class="text-slate-500">تا</span>
          <div class="grow">
            <base-input
              :value="numberFormat(selectedMinmaxPrice[1] ?? minmaxPrice[1])"
                klass="no-spin-arrow !py-1.5 !text-xl font-iranyekan-bold"
                name="max_price"
                @input="formatPriceNumberInput"
                @keydown="formatPriceNumberInput"
                @keyup="formatPriceNumberInput"
            />
          </div>
          <span class="text-xs text-slate-400">تومان</span>
        </div>
      </div>

      <div class="px-3">
        <base-range-slider
            :model-value="minmaxPrice"
            :max="minmaxPrice[1]"
            :min="minmaxPrice[0]"
            :tooltips="false"
            direction="rtl"
            @change="(val) => {selectedMinmaxPrice = val}"
        />
      </div>
      <div class="mt-2 flex items-center justify-between">
        <span class="text-xs text-slate-600">ارزانترین</span>
        <span class="text-xs text-slate-600">گرانترین</span>
      </div>
    </template>
  </partial-filter-card>

  <div class="border-b border-slate-100 px-4 py-2">
    <base-switch
        disabled-bullet-color="!bg-slate-300"
        disabled-color="bg-transparent border-2 border-slate-300"
        enabled-color="bg-indigo-600 border-2 border-indigo-600"
        label="فقط کالاهای موجود"
        label-class="!grow cursor-pointer py-2 font-iranyekan-bold !text-black"
        name="available_products"
        sr-text="فقط کالاهای موجود"
        @change="(status) => {availableProductsStatus = status}"
    />
  </div>

  <div class="border-b border-slate-100 px-4 py-2">
    <base-switch
        disabled-bullet-color="!bg-slate-300"
        disabled-color="bg-transparent border-2 border-slate-300"
        enabled-color="bg-indigo-600 border-2 border-indigo-600"
        label="محصولات ویژه"
        label-class="!grow cursor-pointer py-2 font-iranyekan-bold !text-black"
        name="special_products"
        sr-text="محصولات ویژه"
        @change="(status) => {specialProductsStatus = status}"
    />
  </div>

  <div
      v-if="attributesLoading"
      class="flex items-center justify-center my-3"
  >
    <loader3-dot/>
  </div>
  <template
      v-else-if="attributes?.length"
      v-for="attribute in attributes"
      :key="attribute.id"
  >
    <partial-filter-card
        v-model:selected-items="selectedAttributes[attribute.id]"
        :displaying-selected-items="getAttributeActualSelectedItems(selectedAttributes[attribute.id], attribute.id)"
        :item-unique-key-text="`attr${attribute.id}`"
        :items="attribute.values"
        :show-selected-items="true"
        :type="attribute.type === PRODUCT_ATTRIBUTE_TYPES.MULTI_SELECT.value ? 'multi' : 'single'"
        item-key="id"
        item-text-key="attribute_value"
        panel-container-class="divide-y divide-slate-100"
    >
      <template #default>
        <div
            :class="{
                'justify-between': attribute.type === PRODUCT_ATTRIBUTE_TYPES.MULTI_SELECT.value
            }"
            class="flex items-center gap-3 w-full pl-3"
        >
          <span class="font-iranyekan-bold">{{ attribute.title }}</span>

          <div
              v-if="attribute.type === PRODUCT_ATTRIBUTE_TYPES.MULTI_SELECT.value"
              class="rounded-full py-0.5 px-2.5 border-2 border-rose-400 flex items-center gap-1.5"
          >
            <span>{{
                getSelectedAttributeObject(selectedAttributes[attribute.id], attribute.id).length
              }}</span>
            <span class="text-xs">انتخاب</span>
          </div>
          <span
              v-else-if="selectedAttributes[attribute.id]"
              class="rounded-full w-2 h-2 bg-sky-400"></span>
        </div>
      </template>
    </partial-filter-card>
  </template>
</template>

<script setup>
import {computed, onMounted, ref, watch} from "vue";
import PartialFilterCard from "@/components/partials/pages/PartialFilterCard.vue";
import BaseRangeSlider from "@/components/base/BaseRangeSlider.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import {numberFormat} from "@/composables/helper.js";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import {PRODUCT_ATTRIBUTE_TYPES} from "@/composables/constants.js";
import isObject from "lodash.isobject";
import {HomeProductAPI} from "@/service/APIHomePages.js";
import Loader3Dot from "@/components/base/loader/Loader3Dot.vue";
import {useRoute} from "vue-router";
import {watchImmediate} from "@vueuse/core";

const props = defineProps({
  searchParams: {
    type: Object,
    default: () => {
      return {}
    },
  },
})
const emit = defineEmits(['update:searchParams'])

const route = useRoute()
const searchParams = computed({
  get() {
    return props.searchParams
  },
  set(value) {
    emit('update:searchParams', value)
  }
})

//-----------------------------------------
// Brand Filter
//-----------------------------------------
const selectedBrands = ref({})
const brands = ref([])
const brandsLoading = ref(true)

//-----------------------------------------
// Color & Size Filter
//-----------------------------------------
const selectedColors = ref({})
const colors = ref([])

const selectedSizes = ref({})
const sizes = ref([])

const colorNSizeLoading = ref(true)

function toggleColorSelection(item) {
  if (!selectedColors.value[item.id])
    selectedColors.value[item.id] = item
  else
    delete selectedColors.value[item.id]
}

function toggleSizeSelection(item) {
  if (!selectedSizes.value[item.id]) {
    selectedSizes.value[item.id] = item
  } else {
    delete selectedSizes.value[item.id]
  }
}

//-----------------------------------------
// Price Filter
//-----------------------------------------
const selectedMinmaxPrice = ref([])
const minmaxPrice = ref([0, 0])
const minmaxPriceLoading = ref(true)

function formatPriceNumberInput(value, evt) {
  value = numberFormat(value)
  evt.target.value = value

  let tmpValue = value.replace(/\D/g, '')
  tmpValue = +tmpValue
  if (evt.target.name === 'min_price') {
    selectedMinmaxPrice.value[0] = tmpValue
  } else if (evt.target.name === 'max_price') {
    selectedMinmaxPrice.value[1] = tmpValue
  }
}

//-----------------------------------------
// Status Filters
//-----------------------------------------
const availableProductsStatus = ref(false)
const specialProductsStatus = ref(false)

//-----------------------------------------
// Attributes Filter
//-----------------------------------------
const selectedAttributes = ref({})
const attributes = ref([])
const attributesLoading = ref(true)

//-----------------------------------------
// initialize empty objects for attributes
//-----------------------------------------
function initializeAttributesObject() {
  for (let i of attributes.value) {
    if (i.type === PRODUCT_ATTRIBUTE_TYPES.MULTI_SELECT.value)
      selectedAttributes.value[i.id] = {}
    else
      selectedAttributes.value[i.id] = ''
  }
}

initializeAttributesObject()

//----------------------------
function getSelectedAttributeObject(attributeObj, attributeId) {
  let newObj = {}
  let idx = -1, idx2 = -1

  if (isObject(attributeObj)) {
    for (let o in attributeObj) {
      if (attributeObj.hasOwnProperty(o)) {
        if (attributeObj[o] && attributeObj[o] === true) {
          idx = attributes.value.findIndex((item) => (+item.id === +attributeId))
          if (idx !== -1) {
            idx2 = attributes.value[idx].values.findIndex((item) => (+item.id === +o))
            if (idx2 !== -1) {
              newObj[o] = attributes.value[idx].values[idx2]
            }
          }
        }
      }
    }
  } else if (attributeObj) {
    attributeObj = +attributeObj
    idx = attributes.value.findIndex((item) => (+item.id === attributeId))
    if (idx !== -1) {
      idx2 = attributes.value[idx].values.findIndex((item) => (+item.id === attributeObj))
      if (idx2 !== -1) {
        newObj[attributeObj] = attributes.value[idx].values[idx2]
      }
    }
  }

  return {
    attributes: newObj,
    length: Object.keys(newObj).length,
  }
}

function getAttributeActualSelectedItems(attributeObj, attributeId) {
  const obj = getSelectedAttributeObject(attributeObj, attributeId)

  return {
    selectedItems: obj.attributes,
    length: obj.length,
  }
}

//----------------------------
function fetchFilters() {
  HomeProductAPI.fetchBrandsFilter({
    category: route.query?.category,
    festival: route.query?.festival,
  }, {
    success(response) {
      brands.value = response.data
    },
    error() {
      return false
    },
    finally() {
      brandsLoading.value = false
    },
  })

  HomeProductAPI.fetchColorsAndSizesFilter({
    category: route.query?.category,
    festival: route.query?.festival,
  }, {
    success(response) {
      let data = response.data

      colors.value = []
      sizes.value = []

      let counter = 1;
      for (let i of data) {
        colors.value.push({
          id: counter,
          name: i.name,
          hex: i.hex,
        })

        sizes.value.push({
          id: counter,
          size: i.size,
        })

        counter++
      }
    },
    error() {
      return false
    },
    finally() {
      colorNSizeLoading.value = false
    },
  })

  HomeProductAPI.fetchPriceRangeFilter({
    category: route.query?.category,
    festival: route.query?.festival,
  }, {
    success(response) {
      let data = response.data

      selectedMinmaxPrice.value = []
      minmaxPrice.value = [0, 0]

      if (data?.min && data?.max) {
        selectedMinmaxPrice.value = [data.min, data.max]
        minmaxPrice.value = [data.min, data.max]
      }
    },
    error() {
      return false
    },
    finally() {
      minmaxPriceLoading.value = false
    },
  })

  HomeProductAPI.fetchDynamicFilters({
    category: route.query?.category,
    festival: route.query?.festival,
  }, {
    success(response) {
      attributes.value = response.data
      initializeAttributesObject()
    },
    error() {
      return false
    },
    finally() {
      attributesLoading.value = false
    },
  })
}

watch([
  selectedBrands,
  selectedColors,
  selectedSizes,
  selectedMinmaxPrice,
  availableProductsStatus,
  specialProductsStatus,
  selectedAttributes,
], () => {
  let spObj = {}

  if (Object.keys(selectedBrands.value).length) {
    spObj.brands = Object.keys(selectedBrands.value)
  } else {
    delete searchParams.value.brands
  }

  if (
      selectedMinmaxPrice.value[0] && selectedMinmaxPrice.value[1] &&
      +selectedMinmaxPrice.value[0] < +selectedMinmaxPrice.value[1]
  ) {
    spObj.price_range = selectedMinmaxPrice.value
  } else {
    delete searchParams.value.price_range
  }

  if (availableProductsStatus.value) {
    spObj.only_available = true
  } else {
    delete searchParams.value.only_available
  }

  if (specialProductsStatus.value) {
    spObj.is_special = true
  } else {
    delete searchParams.value.is_special
  }

  if (Object.keys(selectedAttributes.value).length) {
    spObj.dynamic_filters = selectedAttributes.value
  } else {
    delete searchParams.value.dynamic_filters
  }

  Object.assign(searchParams.value, spObj)
})

watchImmediate([
  () => route.query?.category,
  () => route.query?.festival
], () => {
  fetchFilters()
})

onMounted(() => {
  fetchFilters()
})
</script>
