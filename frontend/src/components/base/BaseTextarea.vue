<template>
  <partial-input-label
    v-if="labelTitle && labelTitle.length"
    :title="labelTitle"
    :id="labelId"
    :is-optional="isOptional"
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
      <div v-if="hasIconSlot"
           class="absolute h-full w-10 flex justify-center items-start select-none pointer-events-none"
      >
        <slot name="icon"/>
      </div>
      <textarea
        ref="inp"
        :id="labelId"
        :name="name"
        :placeholder="placeholder"
        :class="[
                        'block w-full rounded-md border-0 py-3 px-3 text-gray-900 ring-1 ring-inset',
                        'ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600',
                        'sm:text-sm sm:leading-6 min-h-[8rem]',
                        klass,
                        hasIconSlot ? 'pr-10' : '',
                        ]"
        @change="handleChange($event, false)"
        @input="checkInput($event)"
        @blur="checkInput($event)"
        @keydown="checkInput($event)"
        @keyup="checkInput($event)"
      >{{ value }}</textarea>
    </div>
  </template>
  <div
    v-else
    class="flex items-center"
  >
    <input
      type="hidden"
      :value="value"
      :name="name"
      @change="handleChange($event, false)"
    >
    <span class="grow text-gray-500 text-sm">{{ value || '-' }}</span>
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
  <partial-input-error-message :error-message="errorMessage"/>
</template>

<script setup>
import {onMounted, ref, useSlots, watch} from "vue";
import uniqueId from "lodash.uniqueid";
import {useField} from "vee-validate";
import PartialInputLabel from "../partials/PartialInputLabel.vue";
import PartialInputErrorMessage from "../partials/PartialInputErrorMessage.vue";
import {PencilSquareIcon} from "@heroicons/vue/24/outline/index.js";

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
})

const emit = defineEmits([
  'input', 'blur', 'keydown', 'keyup', 'mount',
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

onMounted(() => {
  labelId.value = uniqueId(props.name)
  emit('mount', {input: inp.value})
});

defineExpose({
  input: inp,
})
</script>
