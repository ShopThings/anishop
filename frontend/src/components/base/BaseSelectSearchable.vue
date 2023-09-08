<template>
    <template v-if="editMode">
        <Combobox
            v-model="selectedItems"
            :name="name"
            :multiple="multiple"
            :by="optionsKey"
        >
            <div class="relative mt-1">
                <ComboboxInput
                    class="block w-full rounded-md border-0 py-3 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    :displayValue="setDisplayValue"
                    :placeholder="placeholder || 'انتخاب کنید یا جستجو نمایید'"
                    @change="handleChangeValue"
                />
                <ComboboxButton
                    class="absolute inset-y-0 right-0 flex items-center pr-2">
                <span class="absolute inset-y-0 right-0 flex items-center px-1 bg-indigo-600 rounded-r">
                    <ChevronUpDownIcon class="h-5 w-5 text-white" aria-hidden="true"/></span>
                </ComboboxButton>

                <VTransitionSlideFadeUpY>
                    <ComboboxOptions :class="optionsClass"
                                     class="absolute z-[5] max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-md ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                    >
                        <loader-progress v-if="isLoading"/>

                        <div
                            v-if="!isLoading && filteredOptions && Object.keys(filteredOptions).length === 0 && query !== ''"
                            class="relative cursor-default select-none py-2 px-4 text-gray-700"
                        >
                            هیچ موردی پیدا نشد.
                        </div>

                        <ComboboxOption
                            v-slot="{ active, selected }"
                            v-for="item in filteredOptions"
                            :key="item[optionsKey]"
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
                                <slot name="item" :item="item" :selected="selected">
                                    {{ item[optionsText] }}
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
    <div
        v-else
        class="flex items-center"
    >
        <span class="grow text-gray-500 text-sm">{{ fullTextOfSelectedItems || '-' }}</span>
        <button
            v-if="isEditable"
            type="button"
            class="shrink-0 mr-2"
        >
            <PencilSquareIcon
                @click="toggleEditMode"
                class="h-6 w-6 text-gray-400 hover:text-gray-600 transition"
            />
        </button>
    </div>
</template>

<script setup>
import {computed, ref, watch} from "vue"
import {Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions} from "@headlessui/vue"
import {ChevronUpDownIcon, CheckIcon} from '@heroicons/vue/24/outline'
import VTransitionSlideFadeUpY from "../../transitions/VTransitionSlideFadeUpY.vue";
import isObject from "lodash.isobject";
import isArray from "lodash.isarray";
import LoaderProgress from "./loader/LoaderProgress.vue";
import {PencilSquareIcon} from "@heroicons/vue/24/outline/index.js";

const props = defineProps({
    selected: {
        type: [Object, Array],
        default: () => {
            return {}
        },
    },
    name: {
        type: String,
        default: 'select',
    },
    multiple: Boolean,
    options: {
        type: Object,
        required: true,
    },
    optionsKey: {
        type: String,
        required: true,
    },
    optionsText: {
        type: String,
        required: true,
    },
    placeholder: {
        type: String,
        default: 'انتخاب کنید یا جستجو نمایید',
    },
    isLoading: {
        type: Boolean,
        default: false,
    },
    isLocalSearch: {
        type: Boolean,
        default: true,
    },
    optionsClass: {
        type: String,
        default: 'mt-1'
    },
    hasEditMode: {
        type: Boolean,
        default: true,
    },
    isEditable: {
        type: Boolean,
        default: true,
    },
})
const emit = defineEmits(['change', 'query'])

const editMode = ref(props.hasEditMode)

watch(() => props.hasEditMode, () => {
    editMode.value = props.hasEditMode
})

function toggleEditMode() {
    editMode.value = true
}

const filteredOptions = ref(props.options)

watch(() => props.options, () => {
    filteredOptions.value = props.options
})

const selectedItems = ref(null)

function resetSelectedItems() {
    if (props.multiple) {
        selectedItems.value = []
    }
    if (props.selected) {
        setToSelectedItems(props.selected)
    }
}

function setToSelectedItems(value) {
    if (
        !value ||
        (isArray(value) && value.length === 0) ||
        (isObject(value) && Object.keys(value).length === 0)
    ) return

    if (props.multiple) {
        if (isArray(value)) {
            for (const a of value) {
                if (selectedItems.value.indexOf(a) === -1)
                    selectedItems.value.push(a)
            }
        } else {
            if (selectedItems.value.indexOf(value) === -1)
                selectedItems.value.push(value)
        }
    } else {
        selectedItems.value = isArray(value) ? value.shift() : value;
    }
}

resetSelectedItems()

watch(() => props.selected, () => {
    resetSelectedItems()
})

watch(selectedItems, () => {
    emit('change', selectedItems.value)
})

function setDisplayValue(item) {
    if(!item) return ''

    if (props.multiple) {
        if (item.length > 2) {
            return '(' + item.length + ' مورد' + ')' + ', '
        } else {
            return item.length ? item.map((i) => i[props.optionsText]).join(', ') + ', ' : ''
        }
    } else {
        if (item[props.optionsText])
            return item[props.optionsText]
    }

    return ''
}

const fullTextOfSelectedItems = computed(() => {
    if (props.multiple) {
        return selectedItems.value.length ? selectedItems.value.map((i) => i[props.optionsText]).join(', ') : '-'
    } else {
        if (selectedItems.value[props.optionsText])
            return selectedItems.value[props.optionsText]
    }
})

//--------------------------------
// Filter items
//--------------------------------

const query = ref('')

function handleChangeValue($event) {
    query.value = $event.target.value.split(',').pop().trim()
    if (props.isLocalSearch) {
        filteredOptions.value = query.value === ''
            ? props.options
            : props.options.filter((item) =>
                item[props.optionsText]
                    .toLowerCase()
                    .replace(/\s+/g, '')
                    .includes(query.value.toLowerCase().replace(/\s+/g, ''))
            )
    }
    emit('query', query.value)
}

</script>

<style scoped>

</style>
