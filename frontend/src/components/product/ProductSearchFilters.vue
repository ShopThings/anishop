<template>
  <partial-filter-card
    v-model:selected-items="filterParamStore.colors"
    :displaying-selected-items="{selectedItems: filterParamStore.colors, length: Object.keys(filterParamStore.colors).length}"
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
          <span>{{ Object.keys(filterParamStore.colors).length }}</span>
          <span class="text-xs">انتخاب</span>
        </div>
      </div>
    </template>

    <template #item="{item}">
      <div
        :class="{
              'text-indigo-600': filterParamStore.colors[item.id],
          }"
        class="flex flex-col items-center cursor-pointer"
        @click="toggleColorSelection(item)"
      >
        <div
          :class="filterParamStore.colors[item.id] ? 'bg-indigo-200/15 border-indigo-500 p-1.5' : 'p-1'"
          class="size-12 flex items-center justify-center border-2 rounded transition-all"
        >
          <div
            :style="'background-color:' + item.hex + ';'"
            :class="filterParamStore.colors[item.id] ? 'shadow' : 'shadow-md'"
            class="w-full h-full rounded-sm"
          ></div>
        </div>
        <span class="text-xs whitespace-nowrap line-clamp">{{ item.name }}</span>
      </div>
    </template>
  </partial-filter-card>

  <partial-filter-card
    v-model:selected-items="filterParamStore.sizes"
    :displaying-selected-items="{selectedItems: filterParamStore.sizes, length: Object.keys(filterParamStore.sizes).length}"
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
          <span>{{ Object.keys(filterParamStore.sizes).length }}</span>
          <span class="text-xs">انتخاب</span>
        </div>
      </div>
    </template>

    <template #item="{item}">
      <div
        :class="{
              'border-indigo-500 bg-indigo-50': filterParamStore.sizes[item.id]
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
    v-model:selected-items="filterParamStore.brands"
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
      v-model:selected-items="filterParamStore.dynamicFilters[attribute.id]"
      :displaying-selected-items="getAttributeActualSelectedItems(filterParamStore.dynamicFilters[attribute.id], attribute.id)"
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
                getSelectedAttributeObject(filterParamStore.dynamicFilters[attribute.id], attribute.id).length
              }}</span>
            <span class="text-xs">انتخاب</span>
          </div>
          <span
            v-else-if="filterParamStore.dynamicFilters[attribute.id]"
            class="rounded-full w-2 h-2 bg-sky-400"></span>
        </div>
      </template>
    </partial-filter-card>
  </template>

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
        v-if="filterParamStore.priceRange.length"
        class="px-3 pb-2 mt-[-2px] bg-white border-b border-slate-100"
      >
        <div class="flex flex-wrap items-center gap-3 justify-between text-xs text-slate-400">
          <div class="flex gap-2 items-center">
            <span>از</span>
            <div>
              <span class="font-iranyekan-bold text-sm">{{
                  numberFormat(filterParamStore.priceRange[0])
                }}</span>
              <span class="mr-1">تومان</span>
            </div>
          </div>

          <div class="flex gap-2 items-center">
            <span>تا</span>
            <div>
              <span class="font-iranyekan-bold text-sm">{{
                  numberFormat(filterParamStore.priceRange[1])
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
              :value="numberFormat(filterParamStore.priceRange[0] ?? filterStore.getPriceRange[0])"
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
              :value="numberFormat(filterParamStore.priceRange[1] ?? filterStore.getPriceRange[1])"
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
          :model-value="filterStore.priceRange"
          :tooltips="false"
          direction="rtl"
          @slide="(val) => {filterParamStore.priceRange = val}"
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
      :enabled="filterParamStore.onlyAvailable"
      disabled-bullet-color="!bg-slate-300"
      disabled-color="bg-transparent border-2 border-slate-300"
      enabled-color="bg-indigo-600 border-2 border-indigo-600"
      label="فقط کالاهای موجود"
      label-class="!grow cursor-pointer py-2 font-iranyekan-bold !text-black"
      name="available_products"
      sr-text="فقط کالاهای موجود"
      @change="(status) => {filterParamStore.onlyAvailable = status}"
    />
  </div>

  <div class="border-b border-slate-100 px-4 py-2">
    <base-switch
      :enabled="filterParamStore.isSpecial"
      disabled-bullet-color="!bg-slate-300"
      disabled-color="bg-transparent border-2 border-slate-300"
      enabled-color="bg-indigo-600 border-2 border-indigo-600"
      label="محصولات ویژه"
      label-class="!grow cursor-pointer py-2 font-iranyekan-bold !text-black"
      name="special_products"
      sr-text="محصولات ویژه"
      @change="(status) => {filterParamStore.isSpecial = status}"
    />
  </div>
</template>

<script setup>
import {computed, onMounted} from "vue";
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

const emit = defineEmits(['filters-loaded'])

const route = useRoute()

const filterStore = useProductFilterStore()
const filterParamStore = useProductFilterParamStore()

//-----------------------------------------
// Brand Filter
//-----------------------------------------
const getSelectedBrands = computed(() => {
  let brandsObj = {}

  for (let b in filterParamStore.brands) {
    if (filterParamStore.brands.hasOwnProperty(b) && !!filterParamStore.brands[b]) {
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
function toggleColorSelection(item) {
  if (!filterParamStore.colors[item.id]) {
    filterParamStore.colors[item.id] = item
  } else {
    delete filterParamStore.colors[item.id]
  }
}

function toggleSizeSelection(item) {
  if (!filterParamStore.sizes[item.id]) {
    filterParamStore.sizes[item.id] = item
  } else {
    delete filterParamStore.sizes[item.id]
  }
}

//-----------------------------------------
// Price Filter
//-----------------------------------------
function formatPriceNumberInput(value, evt) {
  value = numberFormat(value)
  evt.target.value = value

  let tmpValue = value.replace(/\D/g, '')
  tmpValue = +tmpValue
  if (evt.target.name === 'min_price') {
    filterParamStore.priceRange[0] = tmpValue
  } else if (evt.target.name === 'max_price') {
    filterParamStore.priceRange[1] = tmpValue
  }
}

//-----------------------------------------
// initialize empty objects for attributes
//-----------------------------------------
function initializeAttributesObject() {
  for (let i of filterStore.getDynamicFilters) {
    if (i.type === PRODUCT_ATTRIBUTE_TYPES.MULTI_SELECT.value) {
      if (!isObject(filterParamStore.dynamicFilters[i.id])) {
        filterParamStore.dynamicFilters[i.id] = {}
      }
    } else {
      if (!filterParamStore.dynamicFilters[i.id]) {
        filterParamStore.dynamicFilters[i.id] = ''
      }
    }
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
function fetchFilters() {
  filterStore.fetchBrands(() => filterParamStore.readFiltersFromRoute())
  filterStore.fetchColorsAndSizes(() => filterParamStore.readFiltersFromRoute())
  filterStore.fetchPriceRange(data => filterParamStore.priceRange = data)
  filterStore.fetchDynamicFilters(() => initializeAttributesObject())
}

watchImmediate([
  () => route.query?.category,
  () => route.query?.festival
], () => {
  fetchFilters()
})

//----------------------------
onMounted(() => {
  filterStore.fetchAllFiltersAtOnce({
    finally() {
      emit('filters-loaded')
    },
  }, true)
})
</script>
