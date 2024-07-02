<template>
  <template v-if="editMode">
    <Combobox
      v-model="selectedItems"
      :by="optionsKey"
      :multiple="multiple"
      :name="name"
    >
      <div class="relative">
        <ComboboxInput
          :displayValue="setDisplayValue"
          :placeholder="placeholder || 'انتخاب کنید یا جستجو نمایید'"
          class="block w-full rounded-md border-0 py-3 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
          @change="handleChangeValue"
        />
        <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
          <span class="absolute inset-y-0 right-0 flex items-center px-1 bg-indigo-600 rounded-r">
              <ChevronUpDownIcon aria-hidden="true" class="h-5 w-5 text-white"/></span>
        </ComboboxButton>

        <VTransitionSlideFadeUpY>
          <ComboboxOptions
            ref="optionsContainerRef"
            :class="optionsClass"
            class="absolute z-[5] h-auto w-full rounded-md bg-white py-1 text-base shadow-md ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
          >
            <loader-progress v-if="isLoading"/>

            <div class="flex border-b">
              <button
                class="w-full p-2 bg-slate-100 hover:bg-slate-200 transition"
                type="button"
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
                class="bg-rose-50 px-3 shrink-0 border-r  hover:bg-rose-100 transition group"
                type="button"
                @click="removeAllSelectedItems"
              >
                <BackspaceIcon class="w-6 h-6 text-rose-600 rotate-180 group-hover:scale-105 transition"/>
              </button>
            </div>
            <div
              v-if="showSelectedItemsPanel"
              class="absolute top-0 left-0 w-full h-full z-[2] bg-white rounded-lg"
            >
              <div
                class="flex items-center gap-3 justify-between p-2 mb-2 shadow sticky -top-1 z-[1] bg-white rounded-t-lg">
                <span>موارد انتخاب شده</span>
                <base-button-close @click="toggleSelectedItemsShowing"/>
              </div>

              <ul
                v-if="Array.isArray(selectedItems) ? selectedItems.length : selectedItems"
                class="bg-white"
              >
                <li
                  v-for="item in selectedItems"
                  v-if="Array.isArray(selectedItems)"
                  :key="item[optionsKey]"
                  class="flex gap-3 items-center divide-y p-2"
                >
                  <div class="grow">
                    <slot :item="item" :selected="true" name="item">
                      {{ nestedArray.get(item, optionsText) }}
                    </slot>
                  </div>
                  <button
                    v-tooltip.right="'حذف از انتخاب‌ها'"
                    class="shrink-0 bg-rose-50 hover:bg-rose-100 transition py-1 px-1.5 rounded-lg"
                    type="button"
                    @click="removeSelectedItem(item)"
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
                    <slot :item="selectedItems" :selected="true" name="item">
                      {{ nestedArray.get(selectedItems, optionsText) }}
                    </slot>
                  </div>
                  <button
                    v-tooltip.right="'حذف از موارد انتخاب شده'"
                    class="shrink-0 bg-rose-50 hover:bg-rose-100 transition py-1 px-1.5 rounded-lg"
                    type="button"
                    @click="removeSelectedItem(selectedItems)"
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
                v-tooltip.top="'صفحه بعد'"
                :class="{'hidden': !currentPage || !lastPage || currentPage >= lastPage}"
                class="shrink-0 p-1 rounded-lg border hover:bg-gray-100 transition"
                type="button"
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
                v-tooltip.top="'صفحه قبل'"
                :class="{'hidden': !currentPage || currentPage <= 1}"
                class="shrink-0 p-1 rounded-lg border hover:bg-gray-100 transition"
                type="button"
                @click="emit('click-prev-page', query)"
              >
                <ChevronLeftIcon class="w-6 h-6"/>
              </button>
            </div>

            <div
              v-if="(!isLoading || query !== '' || !isLocalSearch) && (filteredOptions && Object.keys(filteredOptions).length === 0)"
              class="relative cursor-default select-none py-2 px-4 text-gray-700"
            >
              هیچ موردی پیدا نشد.
            </div>

            <div class="max-h-56 my-custom-scrollbar">
              <ComboboxOption
                v-for="item in filteredOptions"
                :key="item[optionsKey]"
                v-slot="{ active, selected }"
                :value="item"
                as="template"
              >
                <div
                  :class="[
                      active ? 'bg-violet-100 text-primary' : 'text-gray-900',
                      'relative cursor-default select-none py-2 pl-10 pr-4',
                    ]"
                >
                  <div
                    :class="[
                      selected ? 'font-medium' : 'font-normal',
                      'line-clamp-2',
                    ]"
                  >
                    <slot :item="item" :selected="selected" name="item">
                      {{ nestedArray.get(item, optionsText) }}
                    </slot>
                  </div>
                  <span
                    v-if="selected"
                    class="absolute inset-y-0 left-0 flex items-center pl-3 text-primary"
                  >
                    <CheckIcon aria-hidden="true" class="h-5 w-5"/>
                  </span>
                </div>
              </ComboboxOption>
            </div>
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
      class="shrink-0 mr-2"
      type="button"
    >
      <PencilSquareIcon
        class="h-6 w-6 text-gray-400 hover:text-gray-600 transition"
        @click="toggleEditMode"
      />
    </button>
  </div>
</template>

<script setup>
import {computed, nextTick, ref, watch} from "vue"
import {Combobox, ComboboxButton, ComboboxInput, ComboboxOption, ComboboxOptions} from "@headlessui/vue"
import {
  BackspaceIcon,
  CheckIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
  ChevronUpDownIcon,
} from '@heroicons/vue/24/outline'
import VTransitionSlideFadeUpY from "@/transitions/VTransitionSlideFadeUpY.vue";
import isObject from "lodash.isobject";
import LoaderProgress from "./loader/LoaderProgress.vue";
import {PencilSquareIcon} from "@heroicons/vue/24/outline/index.js";
import BaseButtonClose from "@/components/base/BaseButtonClose.vue";
import {nestedArray} from "@/composables/helper.js";
import isFunction from "lodash.isfunction";
import {useDebounceFn, useElementBounding} from "@vueuse/core";

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
  inEditMode: {
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
  //
  beforeChangeFn: Function,
})
const emit = defineEmits(['change', 'before-change', 'query', 'click-next-page', 'click-prev-page'])

const editMode = ref(props.inEditMode)

watch(() => props.inEditMode, () => {
  editMode.value = props.inEditMode
})

function toggleEditMode() {
  editMode.value = true
}

//--------------------------------------
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

const isHandlingChange = ref(false)

watch(selectedItems, async (newValue, oldValue) => {
  if (!isHandlingChange.value) {
    if (
      newValue && oldValue &&
      newValue[props.optionsKey] === oldValue[props.optionsKey]
    ) return

    isHandlingChange.value = true;

    emit('change', newValue)

    let res = await handleBeforeChange()
    if (res === false) {
      selectedItems.value = oldValue
    }

    await nextTick(() => {
      isHandlingChange.value = false;
    })
  }
})

async function handleBeforeChange() {
  emit('before-change', selectedItems.value)

  let res = true
  if (isFunction(props.beforeChangeFn)) {
    try {
      res = await props.beforeChangeFn(selectedItems.value)
    } catch (error) {
      res = error
    }
  }

  return res
}

//--------------------------------
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
    selectedItems.value = selectedItems.value.filter(i => i[props.optionsKey] !== item[props.optionsKey])
  } else {
    selectedItems.value = null
  }
}

//--------------------------------
// Filter items
//--------------------------------
const query = ref('')
const callQueryEdit = useDebounceFn(() => {
  emit('query', query.value)
}, 300)

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

  callQueryEdit()
}

//--------------------------------------------
// Make options container be in page
//--------------------------------------------
const optionsContainerRef = ref(null)

watch(() => optionsContainerRef.value?.$el?.classList, () => {
  if (optionsContainerRef.value.$el) {
    const {top, right, bottom, left} = useElementBounding(optionsContainerRef.value.$el)

    const isVisible = (
      top.value >= 0 &&
      left.value >= 0 &&
      bottom.value <= window.innerHeight &&
      right.value <= window.innerWidth
    );

    if (!isVisible) {
      optionsContainerRef.value.$el.classList.add('bottom-[calc(100%+0.25rem)]')
    } else {
      optionsContainerRef.value.$el.classList.remove('bottom-[calc(100%+0.25rem)]')
    }
  }
})

//--------------------------------
defineExpose({
  resetSelectedItems,
  removeSelectedItem,
  removeSelectedItems: removeAllSelectedItems,
})
</script>
