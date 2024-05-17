<template>
  <slot :open="openModal" name="button"></slot>

  <partial-dialog
      v-model:open="isOpen"
      :dialog-container-class="dialogContainerClass"
      :container-klass="containerKlass"
      @close="closeModal"
      @open="openModal"
  >
    <template v-if="slots['closeButton']" #closeButton="{close}">
      <slot :close="close" name="closeButton"></slot>
    </template>

    <template v-if="slots['title']" #title>
      <slot name="title"></slot>
    </template>

    <template v-if="slots['body']" #body="{close}">
      <slot :close="closeModal" name="body"></slot>
    </template>
  </partial-dialog>
</template>

<script setup>
import PartialDialog from "@/components/partials/PartialDialog.vue";
import {ref, useSlots, watch} from "vue";

const props = defineProps({
  open: Boolean,
  dialogContainerClass: [String, Object],
  containerKlass: [String, Object],
})

const emit = defineEmits(['open', 'close'])
const slots = useSlots()

const isOpen = ref(props.open)

// sorry but it should be with watching open property otherwise it doesn't work!
watch(() => props.open, () => {
  isOpen.value = props.open
})

function closeModal() {
  isOpen.value = false
  emit('close')
}

function openModal() {
  isOpen.value = true
  emit('open')
}
</script>
