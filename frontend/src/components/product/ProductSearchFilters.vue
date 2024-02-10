<template>
  <partial-filter-card
    item-key="id"
    item-text-key="name"
    item-unique-key-text="color"
    :items="colors"
    v-model:selected-items="selectedColors"
    :show-selected-items="true"
    :displaying-selected-items="{selectedItems: selectedColors, length: Object.keys(selectedColors).length}"
    panel-container-class="flex flex-wrap items-center gap-2"
  >
    <template #default>
      <div class="flex items-center justify-between gap-3 w-full pl-3">
        <span class="font-iranyekan-bold">رنگ</span>
        <div class="rounded-full py-0.5 px-2.5 border-2 border-rose-400 flex items-center gap-1.5">
          <span>{{ Object.keys(selectedColors).length }}</span>
          <span class="text-xs">انتخاب</span>
        </div>
      </div>
    </template>

    <template #item="{item}">
      <div
        class="flex flex-col items-center cursor-pointer"
        :class="{
                    'text-indigo-600': selectedColors[item.id],
                }"
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
    item-key="id"
    item-text-key="size"
    item-unique-key-text="size"
    :items="sizes"
    v-model:selected-items="selectedSizes"
    :show-selected-items="true"
    :displaying-selected-items="{selectedItems: selectedSizes, length: Object.keys(selectedSizes).length}"
    panel-container-class="flex flex-wrap items-center gap-2"
  >
    <template #default>
      <div class="flex items-center justify-between gap-3 w-full pl-3">
        <span class="font-iranyekan-bold">اندازه</span>
        <div class="rounded-full py-0.5 px-2.5 border-2 border-rose-400 flex items-center gap-1.5">
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
        <div
          class="rounded-md py-1.5 px-3"
        >
          <span class="text-black">{{ item.size }}</span>
        </div>
      </div>
    </template>
  </partial-filter-card>

  <partial-filter-card
    item-key="id"
    item-text-key="size"
    item-unique-key-text="price"
    panel-container-class="flex flex-wrap items-center gap-2"
  >
    <template #default>
      <div class="flex items-center gap-3 w-full pl-3">
        <span class="font-iranyekan-bold">محدوده قیمت</span>
        <span
          v-if="selectedMinmaxPrice.length"
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
                  formatPriceLikeNumber(selectedMinmaxPrice[0])
                }}</span>
              <span class="mr-1">تومان</span>
            </div>
          </div>

          <div class="flex gap-2 items-center">
            <span>تا</span>
            <div>
              <span class="font-iranyekan-bold text-sm">{{
                  formatPriceLikeNumber(selectedMinmaxPrice[1])
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
              name="min_price"
              klass="no-spin-arrow !py-1.5 !text-xl font-iranyekan-bold"
              :value="formatPriceLikeNumber(selectedMinmaxPrice[0])"
              @keydown="formatPriceNumberInput"
              @keyup="formatPriceNumberInput"
              @input="formatPriceNumberInput"
            />
          </div>
          <span class="text-xs text-slate-400">تومان</span>
        </div>
        <div class="flex items-center gap-2">
          <span class="text-slate-500">تا</span>
          <div class="grow">
            <base-input
              name="max_price"
              klass="no-spin-arrow !py-1.5 !text-xl font-iranyekan-bold"
              :value="formatPriceLikeNumber(selectedMinmaxPrice[1])"
              @keydown="formatPriceNumberInput"
              @keyup="formatPriceNumberInput"
              @input="formatPriceNumberInput"
            />
          </div>
          <span class="text-xs text-slate-400">تومان</span>
        </div>
      </div>

      <div class="px-3">
        <base-range-slider
          :tooltips="false"
          :min="minmaxPrice[0]"
          :max="minmaxPrice[1]"
          v-model="selectedMinmaxPrice"
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
      name="available_products"
      label="فقط کالاهای موجود"
      label-class="!grow cursor-pointer py-2 font-iranyekan-bold !text-black"
      enabled-color="bg-indigo-600 border-2 border-indigo-600"
      disabled-color="bg-transparent border-2 border-slate-300"
      disabled-bullet-color="!bg-slate-300"
      sr-text="فقط کالاهای موجود"
      @change="(status) => {availableProductsStatus = status}"
    />
  </div>

  <div class="border-b border-slate-100 px-4 py-2">
    <base-switch
      name="special_products"
      label="محصولات ویژه"
      label-class="!grow cursor-pointer py-2 font-iranyekan-bold !text-black"
      enabled-color="bg-indigo-600 border-2 border-indigo-600"
      disabled-color="bg-transparent border-2 border-slate-300"
      disabled-bullet-color="!bg-slate-300"
      sr-text="محصولات ویژه"
      @change="(status) => {specialProductsStatus = status}"
    />
  </div>

  <template
    v-for="attribute in attributes"
    :key="attribute.id"
  >
    <partial-filter-card
      panel-container-class="divide-y divide-slate-100"
      item-key="id"
      item-text-key="attribute_value"
      :item-unique-key-text="`attr${attribute.id}`"
      :items="attribute.values"
      :show-selected-items="true"
      :displaying-selected-items="getAttributeActualSelectedItems(selectedAttributes[attribute.id], attribute.id)"
      :type="attribute.type === PRODUCT_ATTRIBUTE_TYPES.MULTI_SELECT.value ? 'multi' : 'single'"
      v-model:selected-items="selectedAttributes[attribute.id]"
    >
      <template #default>
        <div
          class="flex items-center gap-3 w-full pl-3"
          :class="{
                        'justify-between': attribute.type === PRODUCT_ATTRIBUTE_TYPES.MULTI_SELECT.value
                    }"
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
import {ref} from "vue";
import PartialFilterCard from "@/components/partials/pages/PartialFilterCard.vue";
import BaseRangeSlider from "@/components/base/BaseRangeSlider.vue";
import BaseInput from "@/components/base/BaseInput.vue";
import {formatPriceLikeNumber} from "@/composables/helper.js";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import {PRODUCT_ATTRIBUTE_TYPES} from "@/composables/constants.js";
import isObject from "lodash.isobject";

//----------------------------
// Color Filter
//----------------------------
const selectedColors = ref({})
const colors = ref([
  {
    id: 1,
    name: 'طوسی',
    hex: '#d2d6e0',
  },
  {
    id: 2,
    name: 'مشکی',
    hex: '#000',
  },
  {
    id: 3,
    name: 'نقره‌ای',
    hex: '#efefef',
  },
  {
    id: 4,
    name: 'آبی',
    hex: '#2f62e2',
  },
  {
    id: 5,
    name: 'سبز',
    hex: '#36db5d',
  },
  {
    id: 6,
    name: 'بنفش',
    hex: '#9a23ea',
  },
  {
    id: 7,
    name: 'قرمز',
    hex: '#e44444',
  },
])

function toggleColorSelection(item) {
  if (!selectedColors.value[item.id])
    selectedColors.value[item.id] = item
  else
    delete selectedColors.value[item.id]
}

//----------------------------

//----------------------------
// Size Filter
//----------------------------
const selectedSizes = ref({})
const sizes = ref([
  {
    id: 1,
    size: 'M',
  },
  {
    id: 2,
    size: 'L',
  },
  {
    id: 3,
    size: 'XL',
  },
  {
    id: 4,
    size: 'XXL',
  },
])

function toggleSizeSelection(item) {
  if (!selectedSizes.value[item.id])
    selectedSizes.value[item.id] = item
  else
    delete selectedSizes.value[item.id]
}

//----------------------------

//----------------------------
// Price Filter
//----------------------------
const selectedMinmaxPrice = ref([0, 120000000])
const minmaxPrice = ref([0, 120000000])

function formatPriceNumberInput(value, evt) {
  value = formatPriceLikeNumber(value)
  evt.target.value = value

  let tmpValue = value.replace(/[^0-9]/g, '')
  tmpValue = +tmpValue
  if (evt.target.name === 'min_price') {
    selectedMinmaxPrice.value[0] = tmpValue
  } else if (evt.target.name === 'max_price') {
    selectedMinmaxPrice.value[1] = tmpValue
  }
}

//----------------------------

//----------------------------
// Status Filters
//----------------------------
const availableProductsStatus = ref(false)
const specialProductsStatus = ref(false)

//----------------------------

//----------------------------
// Attributes Filter
//----------------------------
const selectedAttributes = ref({})
const attributes = ref([
  {
    id: 1,
    title: 'نوع',
    type: 'multi_select',
    values: [
      {
        id: 1,
        attribute_value: 'نوت بوک (لپ تاپ)',
      },
      {
        id: 2,
        attribute_value: 'آلترابوک',
      },
      {
        id: 3,
        attribute_value: 'نت بوک',
      },
      {
        id: 4,
        attribute_value: 'کروم بوک',
      },
    ],
  },
  {
    id: 2,
    title: 'طریقه ارسال',
    type: 'single_select',
    values: [
      {
        id: 1,
        attribute_value: 'پست پیشتاز',
      },
      {
        id: 2,
        attribute_value: 'باربری',
      },
      {
        id: 3,
        attribute_value: 'حضوری',
      },
    ],
  },
])

// initialize empty objects for attributes
for (let i of attributes.value) {
  if (i.type === PRODUCT_ATTRIBUTE_TYPES.MULTI_SELECT.value)
    selectedAttributes.value[i.id] = {}
  else
    selectedAttributes.value[i.id] = ''
}

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
</script>
