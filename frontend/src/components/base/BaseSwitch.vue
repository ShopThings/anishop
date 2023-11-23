<template>
    <SwitchGroup>
        <div class="flex items-center">
            <SwitchLabel
                v-if="label"
                class="ml-3 text-sm text-gray-500"
                :class="[
                    labelClass,
                    !onLabel ? 'grow sm:grow-0' : '',
                ]"
            >
                <template v-if="slots['label']">
                    <slot name="label"></slot>
                </template>
                <template v-else>{{ label }}</template>
            </SwitchLabel>

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
                class="relative flex h-6 w-11 items-center rounded-full shrink-0 transition"
            >
                <span v-if="srText" class="sr-only">{{ srText }}</span>
                <span
                    :class="[
                        bulletClass,
                        value ? 'rtl:-translate-x-6 translate-x-6' : 'rtl:-translate-x-1 translate-x-1',
                        value ? enabledBulletColor : disabledBulletColor
                    ]"
                    class="inline-block h-4 w-4 transform rounded-full bg-white transition"
                />
            </Switch>
            <SwitchLabel
                v-if="onLabel"
                class="mr-3 text-sm text-gray-500"
                :class="{'grow sm:grow-0': !label}"
            >{{ onLabel }}
            </SwitchLabel>
        </div>
    </SwitchGroup>
</template>

<script setup>
import {useSlots, watch} from "vue";
import {Switch, SwitchGroup, SwitchLabel} from "@headlessui/vue";
import {useField} from "vee-validate";
import yup from "../../validation/index.js";

const props = defineProps({
    name: String,
    enabled: Boolean,
    label: String,
    onLabel: String,
    srText: String,
    labelClass: String,
    enabledColor: {
        type: String,
        default: 'bg-indigo-600',
    },
    disabledColor: {
        type: String,
        default: 'bg-slate-300',
    },
    enabledBulletColor: String,
    disabledBulletColor: String,
    bulletClass: String,
})
const emit = defineEmits(['change'])
const slots = useSlots()

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
    height: 0;
    padding: 0;
    overflow: hidden;
    clip: rect(0px, 0px, 0px, 0px);
    white-space: nowrap;
    border-width: 0;
    display: none;
}
</style>
