<template>
  <div class="flex">
    <base-button
        v-if="!readonly"
        v-tooltip="'افزایش تعداد'"
        :class="[btnClass, plusBtnClass]"
        class="bg-white !text-gray-500 rounded-l-none hover:border-indigo-500 hover:!text-black shrink-0 !py-1"
        @click="handleIncrease"
        @mousedown="handleIncreaseMouseDown"
        @mouseleave="resetInterval"
        @mouseup="resetInterval"
        @touchend="resetInterval"
        @touchstart="handleIncreaseMouseDown"
    >
      <PlusIcon class="h-6 w-6"/>
    </base-button>

    <input
        v-if="hasEditableInput"
        v-model="value"
        :class="[
          'py-1 px-4 text-gray-900 border-0 ring-1 ring-inset ring-gray-300 grow text-center text-lg font-iranyekan-bold w-20 focus:ring-inset focus:ring-indigo-500',
          valueContainerClass,
          readonly ? 'rounded-md' : '!rounded-none'
        ]"
        name="quantity_spinner"
        type="text"
    >
    <template v-else>
      <div class="flex items-center">
        <div
          :class="[
              valueContainerClass,
              readonly ? 'rounded-md' : ''
            ]"
          class="py-1.5 px-3 text-gray-900 border-y border-gray-200 grow text-center text-lg font-iranyekan-bold w-20"
        >
          {{ value }}
        </div>

        <div
          v-if="slots['afterValue']"
          class="border-y border-gray-200 h-full flex items-center justify-center pl-2"
        >
          <slot name="afterValue"></slot>
        </div>
      </div>
    </template>

    <base-button
        v-if="!readonly"
        v-tooltip="'کاهش تعداد'"
        :class="[btnClass, minusBtnClass]"
        class="bg-white !text-gray-500 rounded-r-none hover:border-indigo-500 hover:!text-black shrink-0 !py-1"
        @click="handleDecrease"
        @mousedown="handleDecreaseMouseDown"
        @mouseleave="resetInterval"
        @mouseup="resetInterval"
        @touchend="resetInterval"
        @touchstart="handleDecreaseMouseDown"
    >
      <MinusIcon class="h-6 w-6"/>
    </base-button>
  </div>
</template>

<script setup>
import {computed, ref, useSlots} from "vue";
import {MinusIcon, PlusIcon} from "@heroicons/vue/24/outline/index.js";
import BaseButton from "@/components/base/BaseButton.vue";
import isFunction from "lodash.isfunction";

const props = defineProps({
  modelValue: [Number, String],
  max: Number,
  min: {
    type: Number,
    default: 0,
    validator(value) {
      return value >= 0;
    },
  },
  hasEditableInput: Boolean,
  readonly: Boolean,
  //
  valueContainerClass: String,
  btnClass: String,
  minusBtnClass: String,
  plusBtnClass: String,
})
const emit = defineEmits(['update:modelValue', 'increase', 'decrease'])
const slots = useSlots()

const value = computed({
  get() {
    let v = props.modelValue
    v = parseInt(v, 10)
    return !isNaN(v) ? v : 0
  },
  set(value) {
    emit('update:modelValue', value)
  },
})

function handleDecrease() {
  if (value.value <= props.min) {
    resetInterval()
    return
  }

  value.value--
  emit('decrease', value.value)
}

function handleIncrease() {
  if (value.value >= props.max) {
    resetInterval()
    return
  }

  value.value++
  emit('increase', value.value)
}

//------------------------------------------

function handleIncreaseMouseDown() {
  resetInterval()
  startInterval(() => {
    handleIncrease()
  })
}

function handleDecreaseMouseDown() {
  resetInterval()
  startInterval(() => {
    handleDecrease()
  })
}

const increaseDecreaseInterval = ref(null)
const increaseDecreaseTimeout = ref(null)
const intervalTime = 35

function startInterval(callback) {
  increaseDecreaseTimeout.value = setTimeout(() => {
    increaseDecreaseInterval.value = setInterval(() => {
      if (isFunction(callback)) {
        callback()
      }
    }, intervalTime)
  }, 250)
}

function resetInterval() {
  clearInterval(increaseDecreaseInterval.value)
  clearTimeout(increaseDecreaseTimeout.value)
  increaseDecreaseInterval.value = null
  increaseDecreaseTimeout.value = null
}
</script>
