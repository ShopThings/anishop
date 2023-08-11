<template>
    <Combobox v-model="selected" :name="name" :multiple="multiple">
        <div class="relative mt-1">
            <ComboboxInput
                class="block w-full rounded-md border-0 py-3 pl-3 pr-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                :displayValue="setDisplayValue"
                placeholder="انتخاب کنید یا جستجو نمایید"
                @change="handleChangeValue"
            />
            <ComboboxButton
                class="absolute inset-y-0 right-0 flex items-center pr-2">
                <span class="absolute inset-y-0 right-0 flex items-center px-1 bg-blue-600 rounded-r">
                    <ChevronUpDownIcon class="h-5 w-5 text-white" aria-hidden="true"/></span>
            </ComboboxButton>

            <VTransitionSlideFadeUpY>
                <ComboboxOptions :class="optionsClass"
                                 class="absolute z-[5] max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-md ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                >
                    <loader-progress v-if="isLoading"/>

                    <div
                        v-if="isObject(filteredOptions) && Object.keys(filteredOptions).length === 0 && query !== ''"
                        class="relative cursor-default select-none py-2 px-4 text-gray-700"
                    >
                        هیچ موردی پیدا نشد.
                    </div>

                    <ComboboxOption
                        v-slot="{ active, selected }"
                        v-for="item in filteredOptions"
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
                    </ComboboxOption>
                </ComboboxOptions>
            </VTransitionSlideFadeUpY>
        </div>
    </Combobox>
</template>

<script setup>
import {ref, watch} from "vue"
import {Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions} from "@headlessui/vue"
import {ChevronUpDownIcon, CheckIcon} from '@heroicons/vue/24/outline'
import VTransitionSlideFadeUpY from "../../transitions/VTransitionSlideFadeUpY.vue";
import isObject from "lodash.isobject";
import isArray from "lodash.isarray";
import LoaderProgress from "./loader/LoaderProgress.vue";

const props = defineProps({
    selected: [String, Number, Array],
    name: {
        type: String,
        default: 'select',
    },
    multiple: Boolean,
    options: {
        type: [Object, Number],
        required: true,
        default: {},
    },
    isLoading: {
        type: Boolean,
        default: false,
    },
    isLocalSearch: {
        type: Boolean,
        default: true,
    },
    optionsKey: String,
    optionsClass: {
        type: String,
        default: 'mt-1'
    },
})
const emit = defineEmits(['change', 'query'])

const filteredOptions = ref(props.options)

const selectText = ref('')
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
    if (isObject(filteredOptions.value)) {
        for (const p in filteredOptions.value) {
            if (filteredOptions.value.hasOwnProperty(p)) {
                if (props.multiple && isArray(props.selected)) {
                    for (const a of props.selected) {
                        if (filteredOptions.value[p][props.optionsKey] === a) {
                            setToSelected(filteredOptions.value[p])
                        }
                    }
                } else {
                    const tmpSelectedProp = isArray(props.selected) ? props.selected.shift() : props.selected
                    if (filteredOptions.value[p][props.optionsKey] === tmpSelectedProp) {
                        setToSelected(filteredOptions.value[p])
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
    emit('change', selected.value)
})

function setDisplayValue(item) {
    if (props.multiple) {
        if (item.length > 2) {
            return '(' + item.length + ' مورد' + ')' + ', '
        } else {
            return item.length ? item.map((i) => i[props.optionsKey]).join(', ') + ', ' : ''
        }
    } else {
        if (item[props.optionsKey])
            return item[props.optionsKey]
    }

    return ''
}

//--------------------------------
// Filter items
//--------------------------------

const query = ref('')

function handleChangeValue($event) {
    query.value = $event.target.value.split(', ').pop()
    if (props.isLocalSearch) {
        if (isObject(filteredOptions)) {
            filteredOptions.value = query.value === ''
                ? props.options
                : props.options.filter((item) =>
                    item[props.optionsKey]
                        .toLowerCase()
                        .replace(/\s+/g, '')
                        .includes(query.value.toLowerCase().replace(/\s+/g, ''))
                )
        }
    }
    emit('query', query.value)
}

</script>

<style scoped>

</style>
