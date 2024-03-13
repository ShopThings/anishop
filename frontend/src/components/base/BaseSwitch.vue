<template>
  <SwitchGroup>
    <div class="flex items-center">
      <SwitchLabel
          v-if="label"
          :class="[
            labelClass,
            !onLabel ? 'grow sm:grow-0' : '',
        ]"
          class="ml-3 text-sm text-gray-500"
      >
        <template v-if="slots['label']">
          <slot name="label"></slot>
        </template>
        <template v-else>{{ label }}</template>
      </SwitchLabel>

      <input
          :name="name"
          :value="value"
          class="checkInput"
          hidden="hidden"
          readonly="readonly"
          type="checkbox"
          @change="localHandleChange($event)"
      />

      <Switch
          v-model="value"
          :class="value ? (enabledColor || 'bg-indigo-600') : (disabledColor || 'bg-slate-300')"
          :disabled="loading"
          class="relative flex h-6 w-11 items-center rounded-full shrink-0 transition"
      >
        <VTransitionFade>
          <loader-circle
              v-if="loading"
              big-circle-color="border-transparent"
              container-klass="rounded-full"
              main-container-klass="absolute w-[calc(100%+.5rem)] h-[calc(100%+.5rem)] -top-1 -left-1"
              spinner-klass="!w-5 !h-5"
          />
        </VTransitionFade>

        <span v-if="srText" class="sr-only">{{ srText }}</span>
        <span
            :class="[
              bulletClass,
              value ? 'rtl:-translate-x-6 translate-x-6' : 'rtl:-translate-x-1 translate-x-1',
              value ? enabledBulletColor : disabledBulletColor
          ]"
            class="inline-block h-4 w-4 transform rounded-full bg-white transition"
        />
      </Switch>
      <SwitchLabel
          v-if="onLabel"
          :class="{'grow sm:grow-0': !label}"
          class="mr-3 text-sm text-gray-500"
      >{{ onLabel }}
      </SwitchLabel>
    </div>

    <partial-input-error-message :error-message="errorMessage"/>
  </SwitchGroup>
</template>

<script setup>
import {nextTick, ref, useSlots, watch} from "vue";
import {Switch, SwitchGroup, SwitchLabel} from "@headlessui/vue";
import {useField} from "vee-validate";
import yup from "@/validation/index.js";
import PartialInputErrorMessage from "@/components/partials/PartialInputErrorMessage.vue";
import isFunction from "lodash.isfunction";
import VTransitionFade from "@/transitions/VTransitionFade.vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";

const props = defineProps({
  name: String,
  enabled: Boolean,
  label: String,
  onLabel: String,
  srText: String,
  labelClass: String,
  enabledColor: {
    type: String,
    default: 'bg-indigo-600',
  },
  disabledColor: {
    type: String,
    default: 'bg-slate-300',
  },
  enabledBulletColor: String,
  disabledBulletColor: String,
  bulletClass: String,
  beforeChangeFn: Function,
  loading: {
    type: Boolean,
    default: false,
  },
})
const emit = defineEmits(['update:loading', 'change', 'before-change'])
const slots = useSlots()

const {value, errorMessage} = useField(() => props.name, yup.boolean())

value.value = props.enabled

watch(() => props.enabled, () => {
  value.value = props.enabled
})

const isHandlingChange = ref(false)

watch(value, () => {
  if (!isHandlingChange.value) {
    isHandlingChange.value = true;
    localHandleChange()
  }
})

async function localHandleChange(event) {
  if (props.loading) return false

  emit('change', value.value)

  let res = await handleBeforeChange()

  if (res === false) {
    value.value = !value.value
  }

  nextTick(() => {
    isHandlingChange.value = false;
  })

  return res
}

async function handleBeforeChange() {
  emit('before-change', value.value)

  let res = true
  if (isFunction(props.beforeChangeFn)) {
    try {
      res = await props.beforeChangeFn(value.value)
    } catch (error) {
      res = error
    }
  }

  return res
}
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
