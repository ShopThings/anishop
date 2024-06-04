<template>
  <partial-filter-card
    v-model:selected-items="selectedColors"
    :displaying-selected-items="{selectedItems: selectedColors, length: Object.keys(selectedColors).length}"
    :is-loading="filterStore.colorAndSizeLoading"
    :items="filterStore.getColors"
    :show-selected-items="true"
    item-key="id"
    item-text-key="name"
    item-unique-key-text="color"
    panel-container-class="flex flex-wrap items-center gap-2"
  >
    <template #default>
      <div class="flex items-center justify-between gap-3 w-full pl-3 min-h-7">
        <span class="font-iranyekan-bold">رنگ</span>
        <div
          v-if="!filterStore.colorAndSizeLoading"
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
    :is-loading="filterStore.colorAndSizeLoading"
    :items="filterStore.getSizes"
    :show-selected-items="true"
    item-key="id"
    item-text-key="size"
    item-unique-key-text="size"
    panel-container-class="flex flex-wrap items-center gap-2"
  >
    <template #default>
      <div class="flex items-center justify-between gap-3 w-full pl-3 min-h-7">
        <span class="font-iranyekan-bold">اندازه</span>
        <div
          v-if="!filterStore.colorAndSizeLoading"
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
    :displaying-selected-items="{selectedItems: getSelectedBrands, length: Object.keys(getSelectedBrands).length}"
    :is-loading="filterStore.brandsLoading"
    :items="filterStore.getBrands"
    :show-selected-items="true"
    item-key="id"
    item-text-key="name"
    item-unique-key-text="brand"
    panel-container-class="divide-y divide-slate-100"
    type="multi"
  >
    <template #default>
      <div class="flex items-center justify-between gap-3 w-full pl-3 min-h-7">
        <span class="font-iranyekan-bold">برند</span>
        <div
          v-if="!filterStore.brandsLoading"
          class="rounded-full py-0.5 px-2.5 border-2 border-rose-400 flex items-center gap-1.5"
        >
          <span>{{ Object.keys(getSelectedBrands).length }}</span>
          <span class="text-xs">انتخاب</span>
        </div>
      </div>
    </template>
  </partial-filter-card>

  <partial-filter-card
    v-if="filterStore.priceRangeLoading || (filterStore.getPriceRange[0] !== 0 && filterStore.getPriceRange[1] !== 0)"
    :is-loading="filterStore.priceRangeLoading"
    item-unique-key-text="price"
    panel-container-class="flex flex-wrap items-center gap-2"
  >
    <template #default>
      <div class="flex items-center gap-3 w-full pl-3">
        <span class="font-iranyekan-bold">محدوده قیمت</span>
        <span
          v-if="filterParamStore.getPriceRange"
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
              :value="numberFormat(selectedMinmaxPrice[0] ?? filterStore.getPriceRange[0])"
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
              :value="numberFormat(selectedMinmaxPrice[1] ?? filterStore.getPriceRange[1])"
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
          :max="filterStore.getPriceRange[1]"
          :min="filterStore.getPriceRange[0]"
          :model-value="filterStore.getPriceRange"
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
      :enabled="availableProductsStatus"
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
      :enabled="specialProductsStatus"
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
    v-if="filterStore.attributesLoading"
    class="flex items-center justify-center my-3"
  >
    <loader3-dot/>
  </div>
  <template
    v-for="attribute in filterStore.getDynamicFilters"
    v-else-if="filterStore.getDynamicFilters?.length"
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
import {computed, ref, shallowRef, watch} from "vue";
import PartialFilterCard from "@/components/partials/pages/PartialFilterCard.vue";
import BaseRangeSlider from "@/components/base/BaseRangeSlider.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import {numberFormat} from "@/composables/helper.js";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import {PRODUCT_ATTRIBUTE_TYPES} from "@/composables/constants.js";
import isObject from "lodash.isobject";
import Loader3Dot from "@/components/base/loader/Loader3Dot.vue";
import {useRoute} from "vue-router";
import {watchImmediate} from "@vueuse/core";
import {useProductFilterParamStore, useProductFilterStore} from "@/store/StoreProductFilter.js";

const route = useRoute()

const filterStore = useProductFilterStore()
const filterParamStore = useProductFilterParamStore()

//-----------------------------------------
// Brand Filter
//-----------------------------------------
const selectedBrands = ref({})

const getSelectedBrands = computed(() => {
  let brandsObj = {}

  for (let b in selectedBrands.value) {
    if (selectedBrands.value.hasOwnProperty(b) && !!selectedBrands.value[b]) {
      let idx = filterStore.getBrands.findIndex((item) => (+item.id === +b));
      if (idx !== -1) {
        brandsObj[b] = filterStore.getBrands[idx]
      }
    }
  }

  return brandsObj
})

//-----------------------------------------
// Color & Size Filter
//-----------------------------------------
const selectedColors = ref({})
const selectedSizes = ref({})

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

//-----------------------------------------
// initialize empty objects for attributes
//-----------------------------------------
function initializeAttributesObject() {
  for (let i of filterStore.getDynamicFilters) {
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
          idx = filterStore.getDynamicFilters.findIndex((item) => (+item.id === +attributeId))
          if (idx !== -1) {
            idx2 = filterStore.getDynamicFilters[idx].values.findIndex((item) => (+item.id === +o))
            if (idx2 !== -1) {
              newObj[o] = filterStore.getDynamicFilters[idx].values[idx2]
            }
          }
        }
      }
    }
  } else if (attributeObj) {
    attributeObj = +attributeObj
    idx = filterStore.getDynamicFilters.findIndex((item) => (+item.id === attributeId))
    if (idx !== -1) {
      idx2 = filterStore.getDynamicFilters[idx].values.findIndex((item) => (+item.id === attributeObj))
      if (idx2 !== -1) {
        newObj[attributeObj] = filterStore.getDynamicFilters[idx].values[idx2]
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
const isLocallyChange = shallowRef(false)

function fetchFilters() {
  filterStore.fetchBrands()
  filterStore.fetchColorsAndSizes()
  filterStore.fetchPriceRange((data) => {
    selectedMinmaxPrice.value = data
  })
  filterStore.fetchDynamicFilters(() => {
    initializeAttributesObject()
  })
}

function checkSelectedFilters() {
  if (Object.keys(selectedBrands.value).length) {
    filterParamStore.setBrands(Object.keys(selectedBrands.value))
  } else {
    filterParamStore.removeBrands()
  }

  if (
    selectedMinmaxPrice.value[0] && selectedMinmaxPrice.value[1] &&
    +selectedMinmaxPrice.value[0] < +selectedMinmaxPrice.value[1]
  ) {
    filterParamStore.setPriceRange(selectedMinmaxPrice.value[0], selectedMinmaxPrice.value[1])
  } else {
    filterParamStore.removePriceRange()
  }

  if (availableProductsStatus.value) {
    filterParamStore.setOnlyAvailable(true)
  } else {
    filterParamStore.removeOnlyAvailable()
  }

  if (specialProductsStatus.value) {
    filterParamStore.setIsSpecial(true)
  } else {
    filterParamStore.removeIsSpecial()
  }

  if (Object.keys(selectedAttributes.value).length) {
    filterParamStore.setDynamicFilters(selectedAttributes.value)
  } else {
    filterParamStore.removeDynamicFilters()
  }
}

function checkSearchParams() {
  isLocallyChange.value = true

  selectedBrands.value = filterParamStore.getBrands || {}

  let priceRange = filterParamStore.getPriceRange
  selectedMinmaxPrice.value = priceRange && priceRange[0] && priceRange[1] ? filterParamStore.getPriceRange : []

  availableProductsStatus.value = !!filterParamStore.getOnlyAvailable
  specialProductsStatus.value = !!filterParamStore.getIsSpecial

  selectedAttributes.value = filterParamStore.getDynamicFilters || {}
}

checkSearchParams()

watch([
  selectedBrands,
  selectedColors,
  selectedSizes,
  selectedMinmaxPrice,
  availableProductsStatus,
  specialProductsStatus,
  selectedAttributes,
], () => {
  if (!isLocallyChange.value) {
    checkSelectedFilters()
  } else {
    isLocallyChange.value = false
  }
})

watchImmediate([
  () => route.query?.category,
  () => route.query?.festival
], () => {
  fetchFilters()
})

watch([
  filterParamStore.searchParams,
  () => route.query
], () => {
  checkSearchParams()
})
</script>
