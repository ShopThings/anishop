<template>
  <div>
    <partial-input-label
      v-if="labelTitle && labelTitle.length"
      :title="labelTitle"
      :id="id || labelId"
      :is-optional="isOptional"
    />
    <partial-input-label
      v-else-if="hasLabelSlot"
      :id="id || labelId"
      :is-optional="isOptional"
    >
      <template #label>
        <slot name="label"></slot>
      </template>
    </partial-input-label>

    <template v-if="editMode">
      <div :class="isTypePassword ? 'flex' : ''">
        <div class="flex grow relative">
          <div
            v-if="hasIconSlot"
            class="absolute h-full w-10 flex justify-center items-center select-none pointer-events-none"
          >
            <slot name="icon"/>
          </div>
          <input
            v-if="canUseLocalMask"
            ref="inp"
            :id="id || labelId"
            :value="value"
            :name="name"
            :type="type"
            :placeholder="placeholder"
            :class="[
              'block w-full rounded-md border-0 py-3 px-3 text-gray-900 ring-1 ring-inset ring-gray-300',
              'placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
              klass,
              isVisiblePassword ? '!ring-amber-500' : '',
              hasIconSlot ? 'rtl:pr-10 ltr:pl-10' : '',
              hasClearButton ? 'rtl:pl-10 ltr:pr-10' : '',
            ]"
            :min="min"
            :max="max"
            :minlength="minLength"
            :maxlength="maxLength"
            v-mask="mask"
            v-money="localMoneyMask"
            @change="inputChangeHandler"
            @input="checkInput($event)"
            @blur="checkInput($event)"
            @keydown="checkInput($event)"
            @keyup="checkInput($event)"
          />
          <input
            v-else
            ref="inp"
            :id="id || labelId"
            :value="value"
            :name="name"
            :type="type"
            :placeholder="placeholder"
            :class="[
              'block w-full rounded-md border-0 py-3 px-3 text-gray-900 ring-1 ring-inset ring-gray-300',
              'placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
              klass,
              isVisiblePassword ? '!ring-amber-500' : '',
              hasIconSlot ? 'rtl:pr-10 ltr:pl-10' : '',
              hasClearButton ? 'rtl:pl-10 ltr:pr-10' : '',
            ]"
            :min="min"
            :max="max"
            :minlength="minLength"
            :maxlength="maxLength"
            v-money="localMoneyMask"
            @change="inputChangeHandler"
            @input="checkInput($event)"
            @blur="checkInput($event)"
            @keydown="checkInput($event)"
            @keyup="checkInput($event)"
          />
          <button
            v-if="hasClearButton"
            v-tooltip.right="'پاک کردن'"
            type="button"
            class="absolute h-full w-10 flex justify-center items-center rtl:left-0 ltr:right-0 group"
            :class="{'hidden': (!value || value.toString().trim() === '')}"
            @click="clearInputHandler"
          >
            <XMarkIcon class="w-6 h-6 text-gray-400 group-hover:text-rose-500 group-hover:rotate-90 transition"/>
          </button>
        </div>
        <button
          v-if="isTypePassword"
          type="button"
          :class="[
              'mr-2 rounded border-0 ring-1 ring-gray-300 text-rose-600 bg-white',
              'min-w-[48px] group transition-all',
              isVisiblePassword ? '!ring-amber-500' : '',
          ]"
          @click="togglePasswordVisibility"
        >
          <EyeSlashIcon
            v-if="isVisiblePassword"
            class="w-6 h-6 mx-auto group-active:w-5 group-active:h-5 transition-all"
          />
          <EyeIcon
            v-if="!isVisiblePassword"
            class="w-6 h-6 mx-auto group-active:w-5 group-active:h-5 transition-all"
          />
        </button>
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
        @change="inputChangeHandler"
      >
      <slot name="editModeLabel" :value="value">
        <span class="grow text-gray-500 text-sm">{{ value || '-' }}</span>
      </slot>
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
  </div>
</template>

<script setup>
import {computed, onMounted, ref, useSlots, watch} from "vue"
import {EyeIcon, EyeSlashIcon, PencilSquareIcon, XMarkIcon} from "@heroicons/vue/24/outline"
import uniqueId from 'lodash.uniqueid'
import {useField} from "vee-validate";
import PartialInputLabel from "@/components/partials/PartialInputLabel.vue";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import isObject from "lodash.isobject";
import {escapeMoneyCharacter} from "@/composables/helper.js";

const props = defineProps({
  id: String,
  name: {
    type: String,
    required: true,
  },
  value: String,
  type: {
    type: String,
    default: 'text',
  },
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
  min: Number,
  max: Number,
  minLength: Number,
  maxLength: Number,

  hasClearButton: {
    type: Boolean,
    default: true,
  },

  // mask options
  moneyMask: {
    // {
    //   decimal: '.',
    //   thousands: ',',
    //   prefix: '',
    //   suffix: '',
    //   precision: 0,
    //   masked: false
    // }
    type: [Boolean, Object],
    default: false,
  },
  mask: {
    // '#': {pattern: /\d/},
    // 'X': {pattern: /[0-9a-zA-Z]/},
    // 'S': {pattern: /[a-zA-Z]/},
    // 'A': {pattern: /[a-zA-Z]/, transform: v => v.toLocaleUpperCase()},
    // 'a': {pattern: /[a-zA-Z]/, transform: v => v.toLocaleLowerCase()},
    // '!': {escape: true}
    type: [String, Array, Object],
    default: '',
  },
})

const emit = defineEmits([
  'input', 'blur', 'keydown', 'keyup', 'mount', 'cleared'
])

const slots = useSlots()

const localMoneyMask = computed(() => {
  if (!isObject(props.moneyMask) && props.moneyMask === true) {
    return {}
  }
  return false
})

const canUseLocalMask = computed(() => {
  if (isObject(props.mask) && 'mask' in props.mask) {
    return true
  }
  if (Array.isArray(props.mask) && props.mask.filter((item) => item.trim() !== '').length) {
    return true
  }
  return props.mask.trim() !== ''
})

const hasIconSlot = !!slots['icon']
const hasLabelSlot = !!slots['label']
const isVisiblePassword = ref(false)
const inp = ref()
const labelId = ref(null)

const editMode = ref(props.hasEditMode)

watch(() => props.hasEditMode, () => {
  editMode.value = props.hasEditMode
})

const {value, errorMessage, handleChange} = useField(() => props.name)

if (props.value && props.value.length) {
  value.value = props.value
}

watch(() => props.value, () => {
  value.value = props.value
})

function inputChangeHandler(event) {
  const rawValue = getEscapedMoneyValue(event.target.value)
  handleChange(rawValue, false)
}

const isTypePassword = computed(() => {
  return props.type === 'password'
})

function togglePasswordVisibility() {
  inp.value.type = !isVisiblePassword.value ? 'text' : props.type
  inp.value.focus()
  isVisiblePassword.value = !isVisiblePassword.value
}

function checkInput(event) {
  emit(event.type, event.target.value || '', event)
}

function toggleEditMode() {
  editMode.value = true
}

function clearInputHandler() {
  value.value = ''
  inp.value.focus()
  emit('cleared', inp.value)
}

function getEscapedMoneyValue(value) {
  if (localMoneyMask.value === false) return value
  return escapeMoneyCharacter(value)
}

onMounted(() => {
  labelId.value = uniqueId(props.name)
  emit('mount', inp.value)
});

defineExpose({
  input: inp,
})
</script>
