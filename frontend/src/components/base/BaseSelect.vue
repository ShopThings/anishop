<template>
    <Listbox v-model="selected" :name="name" :multiple="multiple">
        <div class="relative mt-1">
            <ListboxButton class="relative" :class="btnClass">
                <slot name="button">
                    <span class="block truncate text-right pr-6">{{ selectText || 'انتخاب کنید' }}</span>

                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true"/></span>
                </slot>
            </ListboxButton>

            <VTransitionSlideFadeUpY>
                <ListboxOptions :class="optionsClass"
                                class="absolute z-[5] max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-md ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                >
                    <ListboxOption
                        v-slot="{ active, selected }"
                        v-for="item in options"
                        :key="item[optionsKey] ?? item"
                        :value="item"
                        as="template"
                    >
                        <li :class="[
                              active ? 'bg-violet-100 text-primary' : 'text-gray-900',
                              'relative cursor-default select-none py-2 pl-10 pr-4',
                            ]"
                        >
                            <span
                                :class="[
                                selected ? 'font-medium' : 'font-normal',
                                'block truncate',
                              ]"
                            >
                                <slot name="item" :item="item">
                                    {{ item[optionsKey] ?? item }}
                                </slot>
                            </span>
                            <span
                                v-if="selected"
                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-primary">
                                <CheckIcon class="h-5 w-5" aria-hidden="true"/>
                            </span>
                        </li>
                    </ListboxOption>
                </ListboxOptions>
            </VTransitionSlideFadeUpY>
        </div>
    </Listbox>
</template>

<script setup>
import {ref, watch} from "vue"
import {Listbox, ListboxButton, ListboxOption, ListboxOptions} from "@headlessui/vue"
import {ChevronUpDownIcon, CheckIcon} from '@heroicons/vue/24/outline'
import VTransitionSlideFadeUpY from "../../transitions/VTransitionSlideFadeUpY.vue";
import isObject from "lodash.isobject";
import isArray from "lodash.isarray";

const props = defineProps({
    selected: [String, Number, Array],
    name: {
        type: String,
        default: 'select',
    },
    multiple: Boolean,
    text: {
        type: [String, Number],
        default: '',
    },
    options: {
        type: [Object, Number],
        required: true,
        default: {},
    },
    optionsKey: String,
    optionsClass: {
        type: String,
        default: 'mt-1'
    },
    btnClass: {
        type: String,
        default: 'block w-full rounded-md border-0 py-3 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
    },
})
const emit = defineEmits(['change'])

const selectText = ref(props.text)
const selected = ref(null)
if (props.multiple) {
    if (props.selected) {
        setToSelected(props.selected)
    } else {
        selected.value = []
    }
} else {
    setToSelected(props.selected)
}

watch(() => props.text, () => {
    if (props.multiple)
        selectText.value = [props.text]
    else
        selectText.value = props.text
})

function setToSelected(value) {
    if (props.multiple) {
        if (isArray(value)) {
            for (const a of value) {
                if (selected.value.indexOf(a) === -1)
                    selected.value.push(a)
            }
        } else {
            if (selected.value.indexOf(value) === -1)
                selected.value.push(value)
        }
    } else {
        selected.value = isArray(value) ? value.shift() : value;
    }
}

function changeSelected() {
    if (isObject(props.options)) {
        for (const p in props.options) {
            if (props.options.hasOwnProperty(p)) {
                if (props.multiple && isArray(props.selected)) {
                    for (const a of props.selected) {
                        if (props.options[p][props.optionsKey] === a) {
                            setToSelected(props.options[p])
                        }
                    }
                } else {
                    const tmpSelectedProp = isArray(props.selected) ? props.selected.shift() : props.selected
                    if (props.options[p][props.optionsKey] === tmpSelectedProp) {
                        setToSelected(props.options[p])
                    }
                }
            }
        }
    } else {
        setToSelected(props.selected)
    }
}

changeSelected()

watch(() => props.selected, () => {
    changeSelected()
})

watch(selected, () => {
    if (props.multiple) {
        if (selected.value.length > 2) {
            selectText.value =  '(' + selected.value.length + ' مورد' + ')'
        } else {
            selectText.value =  selected.value.map((item) => item[props.optionsKey]).join(', ')
        }
    } else {
        if (selected.value[props.optionsKey])
            selectText.value = selected.value[props.optionsKey]
    }
    emit('change', selected.value)
})
</script>

<style scoped>

</style>
