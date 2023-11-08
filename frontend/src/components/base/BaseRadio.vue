<template>
    <div
        :class="containerClass"
        class="text-sm flex items-center gap-3"
    >
        <label
            v-if="labelTitle && showLabel"
            :for="id ? id : labelId"
            :class="labelClass"
            class="cursor-pointer grow sm:grow-0"
        >
            <slot name="text" :title="labelTitle">
                {{ labelTitle }}
            </slot>
        </label>
        <input
            :id="id ? id : labelId"
            type="radio"
            :name="name"
            :value="value"
            v-model="localValue"
            class="radioInput"
            :disabled="disabled"
            @change="emit('change', localValue)"
        >
        <div
            class="rounded-full w-6 h-6 cursor-pointer transition flex items-center justify-center shadow border-8 shrink-0"
            :class="[
                disabled
                ? (
                    modelValue === value
                    ? disabledCheckedClass + ' ' + disabledCheckedHoverClass
                    : disabledClass + ' ' + disabledHoverClass
                )
                : (
                    modelValue === value
                    ? checkedClass + ' ' + checkedHoverClass
                    : uncheckedClass + ' ' + uncheckedHoverClass
                ),
            ]"
            @click="() => {
                if(!disabled) {
                    localValue = value
                    nextTick(() => {
                        emit('change', localValue)
                    })
                }
            }"
        >
        </div>
    </div>
</template>

<script setup>
import {computed, nextTick, onMounted, ref} from "vue";
import uniqueId from "lodash.uniqueid";

const props = defineProps({
    name: {
        type: String,
        required: true,
    },
    value: {
        type: [String, Number],
        required: true,
    },
    modelValue: {
        type: [String, Number],
        required: true,
    },
    labelTitle: String,
    showLabel: {
        type: Boolean,
        default: true,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    id: String,
    containerClass: String,
    labelClass: String,
    disabledClass: {
        type: String,
        default: 'border-slate-300 bg-slate-200',
    },
    disabledHoverClass: {
        type: String,
        default: 'hover:border-slate-300',
    },
    disabledCheckedClass: {
        type: String,
        default: 'border-slate-300 bg-indigo-600',
    },
    disabledCheckedHoverClass: {
        type: String,
        default: 'hover:border-slate-300',
    },
    checkedClass: {
        type: String,
        default: 'border-indigo-600',
    },
    checkedHoverClass: {
        type: String,
        default: 'hover:border-indigo-500',
    },
    uncheckedClass: {
        type: String,
        default: 'border-slate-400',
    },
    uncheckedHoverClass: {
        type: String,
        default: 'hover:border-slate-500',
    },
})
const emit = defineEmits(['update:modelValue', 'change'])

const labelId = ref(null)
const localValue = computed({
    get() {
        return props.modelValue
    },
    set(value) {
        emit('update:modelValue', value)
    },
})

onMounted(() => {
    labelId.value = uniqueId(props.name)
});
</script>

<style scoped>
.radioInput {
    position: fixed;
    height: 0;
    padding: 0;
    overflow: hidden;
    clip: rect(0px, 0px, 0px, 0px);
    white-space: nowrap;
    border-width: 0;
    display: none;
}
</style>
