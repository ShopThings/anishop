<template>
  <template v-if="editMode">
    <Combobox
        v-model="selectedItems"
        :name="name"
        :multiple="multiple"
        :by="optionsKey"
    >
      <div class="relative">
        <ComboboxInput
            class="block w-full rounded-md border-0 py-3 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
            :displayValue="setDisplayValue"
            :placeholder="placeholder || 'انتخاب کنید یا جستجو نمایید'"
            @change="handleChangeValue"
        />
        <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
                <span class="absolute inset-y-0 right-0 flex items-center px-1 bg-indigo-600 rounded-r">
                    <ChevronUpDownIcon class="h-5 w-5 text-white" aria-hidden="true"/></span>
        </ComboboxButton>

        <VTransitionSlideFadeUpY>
          <ComboboxOptions
              :class="optionsClass"
              class="absolute z-[5] max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-md ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
          >
            <loader-progress v-if="isLoading"/>

            <div class="flex border-b">
              <button
                  type="button"
                  class="w-full p-2 bg-slate-100 hover:bg-slate-200 transition"
                  @click="toggleSelectedItemsShowing"
              >
                <span>نمایش موارد انتخاب شده</span>
                <span class="mr-2">
                  <span class="text-slate-500 text-sm">(</span>
                  <span class="text-base mx-0.5">{{ selectedItemsCount }}</span>
                  <span class="text-slate-500 text-sm">)</span>
                </span>
              </button>
              <button
                  v-tooltip.right="'حذف تمام موارد انتخاب شده'"
                  type="button"
                  class="bg-rose-50 px-3 shrink-0 border-r  hover:bg-rose-100 transition group"
                  @click="removeAllSelectedItems"
              >
                <BackspaceIcon class="w-6 h-6 text-rose-600 rotate-180 group-hover:scale-105 transition"/>
              </button>
            </div>
            <div
                v-if="showSelectedItemsPanel"
                class="absolute top-0 left-0 w-full h-full z-[2] bg-white"
            >
              <div class="text-left p-2 mb-2 shadow sticky -top-1 z-[1] bg-white">
                <base-button-close @click="toggleSelectedItemsShowing"/>
              </div>

              <ul
                  v-if="Array.isArray(selectedItems) ? selectedItems.length : selectedItems"
                  class="bg-white"
              >
                <li
                    v-if="Array.isArray(selectedItems)"
                    v-for="item in selectedItems"
                    :key="item[optionsKey]"
                    class="flex gap-3 items-center divide-y p-2"
                >
                  <div class="grow">
                    <slot name="item" :item="item" :selected="true">
                      {{ nestedArray.get(item, optionsText) }}
                    </slot>
                  </div>
                  <button
                      type="button"
                      v-tooltip.right="'حذف از انتخاب‌ها'"
                      class="shrink-0 bg-rose-50 hover:bg-rose-100 transition py-1 px-1.5 rounded-lg"
                      @click="() => {removeSelectedItem(item)}"
                  >
                    <BackspaceIcon class="w-6 h-6 rotate-180 text-rose-600"/>
                  </button>
                </li>
                <li
                    v-else
                    :key="selectedItems[optionsKey]"
                    class="flex gap-3 items-center divide-y p-2"
                >
                  <div class="grow">
                    <slot name="item" :item="selectedItems" :selected="true">
                      {{ nestedArray.get(selectedItems, optionsText) }}
                    </slot>
                  </div>
                  <button
                      type="button"
                      v-tooltip.right="'حذف از موارد انتخاب شده'"
                      class="shrink-0 bg-rose-50 hover:bg-rose-100 transition py-1 px-1.5 rounded-lg"
                      @click="() => {removeSelectedItem(selectedItems)}"
                  >
                    <BackspaceIcon class="w-6 h-6 rotate-180 text-rose-600"/>
                  </button>
                </li>
              </ul>
              <div
                  v-else
                  class="text-slate-400 text-center"
              >
                هیچ موردی انتخاب نشده است
              </div>
            </div>

            <div
                v-if="!isLoading && hasPagination"
                class="flex gap-3 items-center px-3 py-2 shadow sticky -top-1 z-[1] bg-white"
            >
              <button
                  type="button"
                  v-tooltip.top="'صفحه بعد'"
                  class="shrink-0 p-1 rounded-lg border hover:bg-gray-100 transition"
                  :class="{'hidden': !currentPage || !lastPage || currentPage >= lastPage}"
                  @click="emit('click-next-page', query)"
              >
                <ChevronRightIcon class="w-6 h-6"/>
              </button>
              <div class="grow flex gap-2 items-center justify-center">
                <span class="text-xs text-slate-400">صفحه</span>
                <span class="text-base bg-violet-100 py-0.5 px-2 rounded-lg">{{ currentPage }}</span>
                <span class="text-xs text-slate-400">از</span>
                <span class="text-base bg-violet-100 py-0.5 px-2 rounded-lg">{{ lastPage }}</span>
              </div>
              <button
                  type="button"
                  v-tooltip.top="'صفحه قبل'"
                  class="shrink-0 p-1 rounded-lg border hover:bg-gray-100 transition"
                  :class="{'hidden': !currentPage || currentPage <= 1}"
                  @click="emit('click-prev-page', query)"
              >
                <ChevronLeftIcon class="w-6 h-6"/>
              </button>
            </div>

            <div
                v-if="!isLoading && filteredOptions && Object.keys(filteredOptions).length === 0 && (query !== '' || (!isLocalSearch))"
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
                        {{ nestedArray.get(item, optionsText) }}
                    </slot>
                </span>
                <span
                    v-if="selected"
                    class="absolute inset-y-0 left-0 flex items-center pl-3 text-primary"
                >
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
import {
  ChevronUpDownIcon,
  CheckIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
  BackspaceIcon,
} from '@heroicons/vue/24/outline'
import VTransitionSlideFadeUpY from "@/transitions/VTransitionSlideFadeUpY.vue";
import isObject from "lodash.isobject";
import LoaderProgress from "./loader/LoaderProgress.vue";
import {PencilSquareIcon} from "@heroicons/vue/24/outline/index.js";
import BaseButtonClose from "@/components/base/BaseButtonClose.vue";
import {nestedArray} from "@/composables/helper.js";

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
  // pagination props
  hasPagination: Boolean,
  currentPage: Number,
  lastPage: Number,
})
const emit = defineEmits(['change', 'query', 'click-next-page', 'click-prev-page'])

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
const selectedItemsCount = computed(() => {
  if (props.multiple) {
    return selectedItems.value.length
  } else {
    return selectedItems.value ? 1 : 0
  }
})

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
      (Array.isArray(value) && value.length === 0) ||
      (isObject(value) && Object.keys(value).length === 0)
  ) return

  if (props.multiple) {
    if (value) {
      if (Array.isArray(value)) {
        for (const a of value) {
          if (selectedItems.value.indexOf(a) === -1)
            selectedItems.value.push(a)
        }
      } else {
        if (selectedItems.value.indexOf(value) === -1)
          selectedItems.value.push(value)
      }
    }
  } else {
    selectedItems.value = Array.isArray(value) ? value.shift() : value;
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
  if (!item) return ''

  if (props.multiple) {
    if (item.length > 2) {
      return '(' + item.length + ' مورد' + ')' + ', '
    } else {
      return item.length ? item.map((i) => nestedArray.get(i, props.optionsText)).join(', ') + ', ' : ''
    }
  } else {
    if (nestedArray.get(item, props.optionsText))
      return nestedArray.get(item, props.optionsText)
  }

  return ''
}

const fullTextOfSelectedItems = computed(() => {
  if (!selectedItems.value) return ''

  if (props.multiple) {
    return (selectedItems.value && selectedItems.value.length)
        ? selectedItems.value.map((i) => nestedArray.get(i, props.optionsText)).join(', ')
        : '-'
  } else {
    if (selectedItems.value && nestedArray.get(selectedItems.value, props.optionsText))
      return nestedArray.get(selectedItems.value, props.optionsText)
  }
})

//--------------------------------
// Selected items showing
//--------------------------------

const showSelectedItemsPanel = ref(false)

function toggleSelectedItemsShowing() {
  showSelectedItemsPanel.value = !showSelectedItemsPanel.value
}

function removeAllSelectedItems() {
  if (props.multiple) {
    selectedItems.value = []
  } else {
    selectedItems.value = null
  }
}

function removeSelectedItem(item) {
  if (props.multiple) {
    selectedItems.value = selectedItems.value.filter(i => i[props.optionsKey] !== item[optionsKey])
  } else {
    selectedItems.value = null
  }
}

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
            nestedArray.get(item, props.optionsText)
                ?.toLowerCase()
                ?.replace(/\s+/g, '')
                ?.includes(query.value.toLowerCase().replace(/\s+/g, ''))
        )
  }
  emit('query', query.value)
}

</script>
