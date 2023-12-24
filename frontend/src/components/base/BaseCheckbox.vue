<template>
  <div
      :class="containerClass"
      class="text-sm flex items-center gap-2"
  >
    <label
        v-if="labelTitle && showLabel"
        :for="id ? id : labelId"
        :class="labelClass"
        class="cursor-pointer grow sm:grow-0"
    >
      {{ labelTitle }}
    </label>
    <input
        :id="id ? id : labelId"
        type="checkbox"
        :name="name"
        v-model="value"
        class="checkInput"
        :disabled="disabled"
        @change="emit('change', value)"
    >
    <div
        class="rounded w-6 h-6 border-2 cursor-pointer transition flex items-center justify-center shadow"
        :class="[
                disabled
                ? disabledClass + ' ' + disabledHoverClass
                : (
                    value
                    ? checkedClass + ' ' + checkedHoverClass
                    : uncheckedClass + ' ' + uncheckedHoverClass
                ),
            ]"
        @click="() => {
                if(!disabled) {
                    value = !value
                    nextTick(() => {
                        emit('change', value)
                    })
                }
            }"
    >
      <CheckIcon
          v-if="value"
          :class="[
                    'h-6 w-6',
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

onMounted(() => {
  labelId.value = uniqueId(props.name)
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
