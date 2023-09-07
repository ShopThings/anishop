<template>
    <Slider
        v-model="value"
        :format="settings.format"
        :min="settings.min"
        :max="settings.max"
        :showTooltip="settings.showTooltip"
        :orientation="settings.orientation"
        :class="settings.class"
        @change="onChange"
        @update="onUpdate"
    />
</template>

<script setup>
import {computed, reactive} from "vue";
import Slider from "@vueform/slider";

const props = defineProps({
    modelValue: [String, Array, Object],
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
    orientation: props.orientation,
    showTooltip: 'drag',
    class: 'slider-blue',
})

function onChange(value) {
    emit('change', value)
}

function onUpdate(value) {
    emit('update', value)
}
</script>

<style scoped>
.slider-blue {
    --slider-connect-bg: #3B82F6;
    --slider-tooltip-bg: #3B82F6;
    --slider-handle-ring-color: #3B82F630;
}
</style>
