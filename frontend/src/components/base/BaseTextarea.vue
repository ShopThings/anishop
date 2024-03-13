<template>
  <partial-input-label
      v-if="labelTitle && labelTitle.length"
      :id="labelId"
      :is-optional="isOptional"
      :title="labelTitle"
  />
  <partial-input-label
      v-else-if="hasLabelSlot"
      :id="labelId"
      :is-optional="isOptional"
  >
    <template #label>
      <slot name="label"></slot>
    </template>
  </partial-input-label>

  <template v-if="editMode">
    <div class="flex grow relative">
      <div
          v-if="hasIconSlot"
          class="absolute h-full w-10 flex justify-center items-start select-none pointer-events-none"
      >
        <slot name="icon"/>
      </div>
      <textarea
          :id="labelId"
          ref="inp"
          v-model="value"
          :class="[
            'block w-full rounded-md border-0 py-3 px-3 text-gray-900 ring-1 ring-inset',
            'ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600',
            'sm:text-sm sm:leading-6 min-h-[8rem]',
            klass,
            hasIconSlot ? 'pr-10' : '',
            hasClearButton ? 'rtl:pl-10 ltr:pr-10' : '',
          ]"
          :name="name"
          :placeholder="placeholder"
          @blur="checkInput($event)"
          @change="handleChange($event, false)"
          @input="checkInput($event)"
          @keydown="checkInput($event)"
          @keyup="checkInput($event)"
      ></textarea>
      <button
          v-if="hasClearButton"
          v-tooltip.right="'پاک کردن'"
          :class="{'hidden': (!value || value.toString().trim() === '')}"
          class="absolute h-12 w-10 flex justify-center items-center rtl:left-0 ltr:right-0 group"
          type="button"
          @click="clearInputHandler"
      >
        <XMarkIcon class="w-6 h-6 text-gray-400 group-hover:text-rose-500 group-hover:rotate-90 transition"/>
      </button>
    </div>
  </template>
  <div
      v-else
      class="flex items-center"
  >
    <input
        :name="name"
        :value="value"
        type="hidden"
        @change="handleChange($event, false)"
    >
    <slot :value="value || '-'" name="editModeLabel">
      <span class="grow text-gray-500 text-sm">{{ value || '-' }}</span>
    </slot>
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
  <partial-input-error-message :error-message="errorMessage"/>
</template>

<script setup>
import {onMounted, ref, useSlots, watch} from "vue";
import uniqueId from "lodash.uniqueid";
import {useField} from "vee-validate";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import {PencilSquareIcon, XMarkIcon} from "@heroicons/vue/24/outline/index.js";

const props = defineProps({
  name: {
    type: String,
    required: true,
  },
  value: String,
  placeholder: String,
  klass: String,
  labelTitle: String,
  isOptional: {
    type: Boolean,
    default: false,
  },
  hasEditMode: {
    type: Boolean,
    default: true,
  },
  isEditable: {
    type: Boolean,
    default: true,
  },

  hasClearButton: {
    type: Boolean,
    default: true,
  },
})

const emit = defineEmits([
  'input', 'blur', 'keydown', 'keyup', 'mount', 'cleared'
])

const slots = useSlots()

const hasIconSlot = !!slots['icon']
const hasLabelSlot = !!slots['label']
const inp = ref()
const labelId = ref(null)

const editMode = ref(props.hasEditMode)

watch(() => props.hasEditMode, () => {
  editMode.value = props.hasEditMode
})

const {value, errorMessage, handleChange} = useField(() => props.name, undefined)

if (props.value && props.value.length)
  value.value = props.value

watch(() => props.value, () => {
  value.value = props.value
})

function checkInput(event) {
  emit(event.type, event.target.value || '')
}

function toggleEditMode() {
  editMode.value = true
}

function clearInputHandler() {
  value.value = ''

  if (inp.value) {
    inp.value.focus()
  }

  emit('cleared', inp?.value || '')
}

onMounted(() => {
  labelId.value = uniqueId(props.name)
  emit('mount', {input: inp.value})
});

defineExpose({
  input: inp,
})
</script>
