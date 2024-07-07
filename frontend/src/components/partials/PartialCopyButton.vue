<template>
  <button
    v-tooltip.top="'کپی کردن'"
    :class="btnClass"
    type="button"
    @click="copyHandler"
  >
    <ClipboardDocumentIcon class="size-6"/>
  </button>
</template>

<script setup>
import {ClipboardDocumentIcon} from "@heroicons/vue/24/outline/index.js";
import {useClipboard} from "@vueuse/core";
import {POSITION, TYPE, useToast} from "vue-toastification";

const props = defineProps({
  text: {
    type: String,
    required: true,
  },
  btnClass: {
    type: String,
    default: 'rounded p-0.5 mx-1 shadow border text-rose-500 hover:border-rose-300 transition',
  },
})

const toast = useToast()
const {copy} = useClipboard()

function copyHandler() {
  copy(props.text).then(() => {
    toast('کپی انجام شد.', {
      timeout: 2000,
      type: TYPE.default,
      position: POSITION.BOTTOM_CENTER,
    })
  })
}
</script>

<style scoped>

</style>
