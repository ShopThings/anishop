<template>
  <base-switch
    v-model:loading="operationLoading"
    :before-change-fn="handleSwitchChange"
    :enabled="value"
    :label="label || offLabel"
    :name="generateUniqueName"
    :on-label="onLabel"
    :sr-text="label"
  />
</template>

<script setup>
import {computed, ref} from "vue";
import BaseSwitch from "@/components/base/BaseSwitch.vue";
import {useConfirmToast} from "@/composables/toast-helper.js";
import isObject from "lodash.isobject";
import {useToast} from "vue-toastification";
import uniqueId from "lodash.uniqueid";

const props = defineProps({
  id: Number,
  // parameters to pass api method in order
  parameters: Array,
  data: Object,
  modelValue: {
    type: Boolean,
    default: false,
  },
  //
  label: String,
  onLabel: String,
  offLabel: String,
  //
  api: Object,
  apiMethod: {
    type: String,
    default: 'updateById',
  },
  updateKey: String,
  //
  successMessage: {
    type: String,
    default: 'وضعیت با موفقیت تغییر یافت.',
  },
})
const emit = defineEmits(['update:modelValue', 'success', 'fail'])

const toast = useToast()
const value = computed({
  get() {
    return props.modelValue
  },
  set(value) {
    emit('update:modelValue', value)
  },
})
const operationLoading = ref(false)

function handleSwitchChange(status) {
  return new Promise((resolve, reject) => {
    if (operationLoading.value) {
      reject(false)
    }

    useConfirmToast({
      accept() {
        if (isObject(props.api) && (props.id || (props.parameters?.length))) {
          operationLoading.value = true

          const parameters = props.id
            ? [props.id]
            : (
              props.parameters?.length
                ? props.parameters
                : []
            )

          props.api[props.apiMethod](...parameters, Object.assign(props.data, {
            [props.updateKey]: status,
          }), {
            success() {
              toast.success(props.successMessage)
              emit('success')
              resolve(true)
            },
            error() {
              emit('fail')
              reject(false)
            },
            finally() {
              operationLoading.value = false
            },
          })
        } else {
          emit('success')
          resolve(true)
        }
      },
      decline() {
        reject(false)
      },
    })
  })
}

const generateUniqueName = computed(() => {
  return uniqueId('inp-remote-switch-')
})
</script>
