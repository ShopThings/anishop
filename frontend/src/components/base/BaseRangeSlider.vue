<template>
  <Slider
      v-model="value"
      :class="settings.class"
      :direction="settings.direction"
      :format="settings.format"
      :max="settings.max"
      :min="settings.min"
      :options="settings.options"
      :orientation="settings.orientation"
      :showTooltip="settings.showTooltip"
      :tooltipPosition="settings.tooltipPosition"
      :tooltips="settings.tooltips"
      @change="onChange"
      @update="onUpdate"
      @slide="onSlide"
  />
</template>

<script setup>
import {computed, reactive} from "vue";
import Slider from "@vueform/slider";

const props = defineProps({
  modelValue: [String, Array, Object, Number],
  format: Function,
  min: Number,
  max: Number,
  orientation: {
    type: String,
    validator: (value) => {
      return ['horizontal', 'vertical'].indexOf(value) !== -1
    },
    default: 'horizontal',
  },
  direction: {
    type: String,
    validator: (value) => {
      return ['ltr', 'rtl'].indexOf(value) !== -1
    },
    default: 'ltr',
  },
  tooltips: {
    type: Boolean,
    default: true,
  },
  showTooltip: {
    type: String,
    default: 'drag',
    validator: (value) => {
      return ['always', 'focus', 'drag'].indexOf(value) !== -1
    },
  },
  tooltipPosition: {
    type: String,
    default: 'top',
    validator: (value) => {
      return ['top', 'bottom', 'left', 'right'].indexOf(value) !== -1
    },
  },
  options: Object,
})
const emit = defineEmits(['update:modelValue', 'change', 'update', 'slide'])

const value = computed({
  get() {
    return props.modelValue
  },
  set(value) {
    emit('update:modelValue', value)
  },
})
const settings = reactive({
  min: props.min,
  max: props.max,
  format: props.format,
  tooltips: props.tooltips,
  orientation: props.orientation,
  direction: props.direction,
  showTooltip: props.showTooltip, // ['always', 'focus', 'drag']
  tooltipPosition: props.tooltipPosition, // ['top', 'bottom', 'left', 'right']
  class: 'slider-indigo',
  options: props.options,
})

function onChange(value) {
  emit('change', value)
}

function onUpdate(value) {
  emit('update', value)
}

function onSlide(value) {
  emit('slide', value)
}
</script>

<style scoped>
.slider-indigo {
  --slider-connect-bg: #4F46E5;
  --slider-tooltip-bg: #4F46E5;
  --slider-handle-ring-color: #4F46E530;
}
</style>
