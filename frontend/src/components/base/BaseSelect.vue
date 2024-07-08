<template>
  <template v-if="editMode">
    <Listbox
      v-model="selectedItems"
      :by="optionsKey"
      :multiple="multiple"
      :name="name"
    >
      <div class="relative">
        <ListboxButton :class="[btnClass, btnSpaceClass]" class="relative">
          <slot name="button">
            <span class="block truncate text-right pr-6">{{ selectText || 'انتخاب کنید' }}</span>

            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                    <ChevronUpDownIcon aria-hidden="true" class="h-5 w-5 text-gray-400"/></span>
          </slot>
        </ListboxButton>

        <VTransitionSlideFadeUpY>
          <ListboxOptions
            ref="optionsContainerRef"
            :class="optionsClass"
            class="absolute min-w-[12rem] z-[5] max-h-60 w-full my-custom-scrollbar rounded-md bg-white py-1 text-base shadow-md ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
          >
            <loader-progress v-if="isLoading"/>

            <div
              v-if="!isLoading && options && Object.keys(options).length === 0"
              class="relative cursor-default select-none py-2 px-4 text-gray-700"
            >
              هیچ موردی پیدا نشد.
            </div>

            <ListboxOption
              v-for="item in options"
              :key="item[optionsKey]"
              v-slot="{ active, selected }"
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
                    <slot :item="item" :selected="selected" name="item">
                        {{ nestedArray.get(item, optionsText) }}
                    </slot>
                </span>
                <span
                  v-if="selected"
                  class="absolute inset-y-0 left-0 flex items-center pl-3 text-primary">
                    <CheckIcon aria-hidden="true" class="h-5 w-5"/>
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
import {Listbox, ListboxButton, ListboxOption, ListboxOptions} from "@headlessui/vue"
import {CheckIcon, ChevronUpDownIcon} from '@heroicons/vue/24/outline'
import VTransitionSlideFadeUpY from "@/transitions/VTransitionSlideFadeUpY.vue";
import {PencilSquareIcon} from "@heroicons/vue/24/outline/index.js";
import isObject from "lodash.isobject";
import LoaderProgress from "./loader/LoaderProgress.vue";
import {nestedArray} from "@/composables/helper.js";
import isFunction from "lodash.isfunction";
import {useElementBounding} from "@vueuse/core";

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
    default: 'block w-full rounded-md bg-white border-0 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
  },
  btnSpaceClass: {
    type: String,
    default: 'p-3',
  },
  inEditMode: {
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
  beforeChangeFn: Function,
})
const emit = defineEmits(['change', 'before-change'])

const editMode = ref(props.inEditMode)

watch(() => props.inEditMode, () => {
  editMode.value = props.inEditMode
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
      selectText.value = selectedItems.value.map((item) => nestedArray.get(item, props.optionsText)).join(', ')
    }
  } else {
    if (selectedItems.value[props.optionsKey])
      selectText.value = nestedArray.get(selectedItems.value, props.optionsText)
  }
}

const fullTextOfSelectedItems = computed(() => {
  if (!selectedItems.value) return ''

  if (props.multiple) {
    return selectedItems.value.map((item) => nestedArray.get(item, props.optionsText)).join(', ') || '-'
  } else {
    if (selectedItems.value[props.optionsKey])
      return nestedArray.get(selectedItems.value, props.optionsText)
  }
})

const isHandlingChange = ref(false)

watch(selectedItems, async (newValue, oldValue) => {
  if (
    isHandlingChange.value ||
    (
      newValue && oldValue &&
      newValue[props.optionsKey] === oldValue[props.optionsKey]
    )
  ) return

  isHandlingChange.value = true;

  emit('change', newValue)

  let res = await handleBeforeChange()
  if (res === false) {
    selectedItems.value = oldValue
  }

  setSelectedItemsText()

  await nextTick(() => {
    isHandlingChange.value = false;
  })
})

async function handleBeforeChange() {
  emit('before-change', selectedItems.value)

  let res = true
  if (isFunction(props.beforeChangeFn)) {
    try {
      res = await props.beforeChangeFn(selectedItems.value)
    } catch (error) {
      res = false
    }
  }

  return res
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
</script>
