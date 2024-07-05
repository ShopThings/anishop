<template>
  <div
    :class="containerClass"
    class="text-sm flex items-center gap-2"
  >
    <label
      v-if="labelTitle && showLabel"
      :class="labelClass"
      :for="id ? id : labelId"
      class="cursor-pointer grow sm:grow-0"
    >
      {{ labelTitle }}
    </label>
    <input
      :id="id ? id : labelId"
      v-model="value"
      :disabled="disabled"
      :name="name"
      class="checkInput"
      type="checkbox"
      @change="emit('change', value)"
    >
    <div
      :class="[
          sizeClass,
          disabled
          ? `${disabledClass} ${disabledHoverClass}`
          : (
              value
              ? `${checkedClass} ${checkedHoverClass}`
              : `${uncheckedClass} ${uncheckedHoverClass}`
          ),
      ]"
      class="rounded border-2 cursor-pointer transition flex items-center justify-center shadow"
      @click="clickHandler"
    >
      <CheckIcon
        v-if="value"
        :class="[
            sizeClass,
            disabled ? disabledIconClass : iconClass,
        ]"
      />
    </div>
  </div>
</template>

<script setup>
import {computed, nextTick, onMounted, ref} from "vue";
import uniqueId from "lodash.uniqueid";
import {CheckIcon} from "@heroicons/vue/24/solid/index.js"

const props = defineProps({
  name: {
    type: String,
    required: true,
  },
  modelValue: {
    type: Boolean,
    default: false,
  },
  labelTitle: String,
  showLabel: {
    type: Boolean,
    default: true,
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  id: String,
  containerClass: String,
  labelClass: String,
  useDynamicLabelId: {
    type: Boolean,
    default: true,
  },

  sizeClass: {
    type: String,
    default: 'w-6 h-6',
  },
  disabledClass: {
    type: String,
    default: 'border-slate-300 bg-slate-200',
  },
  disabledHoverClass: {
    type: String,
    default: 'hover:border-slate-300 hover:bg-slate-200',
  },
  checkedClass: {
    type: String,
    default: 'border-indigo-600 bg-indigo-600',
  },
  checkedHoverClass: {
    type: String,
    default: 'hover:border-indigo-500 hover:bg-indigo-500',
  },
  uncheckedClass: {
    type: String,
    default: 'border-slate-400 bg-slate-100',
  },
  uncheckedHoverClass: {
    type: String,
    default: 'hover:border-slate-500 hover:bg-slate-200',
  },
  disabledIconClass: {
    type: String,
    default: 'text-slate-400',
  },
  iconClass: {
    type: String,
    default: 'text-white',
  },
})
const emit = defineEmits(['update:modelValue', 'change'])

const labelId = ref(null)
const value = computed({
  get() {
    return props.modelValue
  },
  set(value) {
    emit('update:modelValue', value)
  }
})

function clickHandler(e) {
  if (!props.disabled) {
    e.preventDefault()

    value.value = !value.value
    nextTick(() => {
      emit('change', value.value)
    })
  }
}

onMounted(() => {
  if (props.useDynamicLabelId) {
    labelId.value = uniqueId(props.name)
  } else {
    labelId.value = props.name
  }
});
</script>

<style scoped>
.checkInput {
  position: fixed;
  height: 0;
  padding: 0;
  overflow: hidden;
  clip: rect(0px, 0px, 0px, 0px);
  white-space: nowrap;
  border-width: 0;
  display: none;
}
</style>
