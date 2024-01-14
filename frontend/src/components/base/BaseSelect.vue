<template>
  <template v-if="editMode">
    <Listbox
      v-model="selectedItems"
      :name="name"
      :multiple="multiple"
      :by="optionsKey"
    >
      <div class="relative">
        <ListboxButton class="relative" :class="btnClass">
          <slot name="button">
            <span class="block truncate text-right pr-6">{{ selectText || 'انتخاب کنید' }}</span>

            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true"/></span>
          </slot>
        </ListboxButton>

        <VTransitionSlideFadeUpY>
          <ListboxOptions :class="optionsClass"
                          class="absolute min-w-[12rem] z-[5] max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-md ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
          >
            <loader-progress v-if="isLoading"/>

            <div
              v-if="!isLoading && options && Object.keys(options).length === 0"
              class="relative cursor-default select-none py-2 px-4 text-gray-700"
            >
              هیچ موردی پیدا نشد.
            </div>

            <ListboxOption
              v-slot="{ active, selected }"
              v-for="item in options"
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
            </ListboxOption>
          </ListboxOptions>
        </VTransitionSlideFadeUpY>
      </div>
    </Listbox>
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
import {Listbox, ListboxButton, ListboxOption, ListboxOptions} from "@headlessui/vue"
import {ChevronUpDownIcon, CheckIcon} from '@heroicons/vue/24/outline'
import VTransitionSlideFadeUpY from "@/transitions/VTransitionSlideFadeUpY.vue";
import {PencilSquareIcon} from "@heroicons/vue/24/outline/index.js";
import isObject from "lodash.isobject";
import LoaderProgress from "./loader/LoaderProgress.vue";

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
  optionsClass: {
    type: String,
    default: 'mt-1 left-0'
  },
  btnClass: {
    type: String,
    default: 'block w-full rounded-md border-0 py-3 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
  },
  hasEditMode: {
    type: Boolean,
    default: true,
  },
  isEditable: {
    type: Boolean,
    default: true,
  },
  isLoading: {
    type: Boolean,
    default: false,
  },
})
const emit = defineEmits(['change'])

const editMode = ref(props.hasEditMode)

watch(() => props.hasEditMode, () => {
  editMode.value = props.hasEditMode
})

function toggleEditMode() {
  editMode.value = true
}

const selectText = ref('')
const selectedItems = ref(null)

function resetSelectedItems() {
  if (props.multiple) {
    selectedItems.value = []
  }
  setToSelectedItems(props.selected)
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
setSelectedItemsText()

watch(() => props.selected, () => {
  resetSelectedItems()
  setSelectedItemsText()
})

function setSelectedItemsText() {
  selectText.value = ''

  if (!selectedItems.value) return

  if (props.multiple) {
    if (selectedItems.value.length > 2) {
      selectText.value = '(' + selectedItems.value.length + ' مورد' + ')'
    } else {
      selectText.value = selectedItems.value.map((item) => item[props.optionsText]).join(', ')
    }
  } else {
    if (selectedItems.value[props.optionsKey])
      selectText.value = selectedItems.value[props.optionsText]
  }
}

const fullTextOfSelectedItems = computed(() => {
  if (!selectedItems.value) return ''

  if (props.multiple) {
    return selectedItems.value.map((item) => item[props.optionsText]).join(', ') || '-'
  } else {
    if (selectedItems.value[props.optionsKey])
      return selectedItems.value[props.optionsText]
  }
})

watch(selectedItems, () => {
  setSelectedItemsText()
  emit('change', selectedItems.value)
})
</script>
