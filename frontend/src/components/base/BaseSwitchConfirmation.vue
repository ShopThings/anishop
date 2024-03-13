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
  id: {
    type: Number,
    required: true,
  },
  modelValue: {
    type: Boolean,
    default: false,
  },
  label: String,
  onLabel: String,
  offLabel: String,
  api: Object,
  updateKey: String,
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
        if (isObject(props.api) && props.id) {
          operationLoading.value = true

          props.api.updateById(props.id, {
            [props.updateKey]: status,
          }, {
            success() {
              toast.success('وضعیت با موفقیت تغییر یافت.')
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
