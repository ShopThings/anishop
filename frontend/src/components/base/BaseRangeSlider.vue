<template>
  <Slider
      v-model="value"
      :format="settings.format"
      :min="settings.min"
      :max="settings.max"
      :showTooltip="settings.showTooltip"
      :tooltipPosition="settings.tooltipPosition"
      :tooltips="settings.tooltips"
      :orientation="settings.orientation"
      :direction="settings.direction"
      :class="settings.class"
      :options="settings.options"
      @change="onChange"
      @update="onUpdate"
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
const emit = defineEmits(['update:modelValue', 'change', 'update'])

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
</script>

<style scoped>
.slider-indigo {
  --slider-connect-bg: #4F46E5;
  --slider-tooltip-bg: #4F46E5;
  --slider-handle-ring-color: #4F46E530;
}
</style>
