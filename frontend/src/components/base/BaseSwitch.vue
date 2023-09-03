<template>
    <SwitchGroup>
        <div class="flex items-center">
            <SwitchLabel v-if="label" class="ml-3 text-sm text-gray-500">{{ label }}</SwitchLabel>

            <input
                type="checkbox"
                hidden="hidden"
                readonly="readonly"
                :name="name"
                :value="value"
                class="checkInput"
                @change="handleChange($event, false)"
            />

            <Switch
                v-model="value"
                :class="value ? (enabledColor || 'bg-indigo-600') : (disabledColor || 'bg-slate-300')"
                class="relative flex h-6 w-11 items-center rounded-full shrink-0"
            >
                <span v-if="srText" class="sr-only">{{ srText }}</span>
                <span
                    :class="value ? 'rtl:-translate-x-6 translate-x-6' : 'rtl:-translate-x-1 translate-x-1'"
                    class="inline-block h-4 w-4 transform rounded-full bg-white transition"
                />
            </Switch>
            <SwitchLabel v-if="onLabel" class="mr-3 text-sm text-gray-500">{{ onLabel }}</SwitchLabel>
        </div>
    </SwitchGroup>
</template>

<script setup>
import {watch} from "vue";
import {Switch, SwitchGroup, SwitchLabel} from "@headlessui/vue";
import {useField} from "vee-validate";
import yup from "../../validation/index.js";

const props = defineProps({
    name: String,
    enabled: Boolean,
    label: String,
    onLabel: String,
    srText: String,
    enabledColor: {
        type: String,
        default: 'bg-indigo-600',
    },
    disabledColor: {
        type: String,
        default: 'bg-slate-300',
    },
})
const emit = defineEmits(['change'])

const {value, handleChange} = useField(() => props.name, yup.boolean())

value.value = props.enabled

watch(() => props.enabled, () => {
    value.value = props.enabled
})

watch(value, () => {
    emit('change', value.value)
})
</script>

<style scoped>
.checkInput {
    position: fixed;
    height: 0px;
    padding: 0px;
    overflow: hidden;
    clip: rect(0px, 0px, 0px, 0px);
    white-space: nowrap;
    border-width: 0px;
    display: none;
}
</style>
