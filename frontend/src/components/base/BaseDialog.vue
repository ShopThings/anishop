<template>
  <slot name="button" :open="openModal"></slot>

  <partial-dialog
    @open="openModal"
    @close="closeModal"
    v-model:open="isOpen"
    :container-klass="containerKlass"
  >
    <template v-if="slots['closeButton']" #closeButton="{close}">
      <slot name="closeButton" :close="close"></slot>
    </template>

    <template v-if="slots['title']" #title>
      <slot name="title"></slot>
    </template>

    <template v-if="slots['body']" #body="{close}">
      <slot name="body" :close="closeModal"></slot>
    </template>
  </partial-dialog>
</template>

<script setup>
import PartialDialog from "../partials/PartialDialog.vue";
import {ref, useSlots, watch} from "vue";

const props = defineProps({
  open: Boolean,
  containerKlass: String,
})

const emit = defineEmits(['open', 'close', 'update:open'])
const slots = useSlots()

const isOpen = ref(props.open)

watch(() => props.open, function () {
  if (props.open)
    openModal()
  else
    closeModal()
})

function closeModal() {
  isOpen.value = false
  emit('update:open', isOpen.value)

  emit('close')
}

function openModal() {
  isOpen.value = true
  emit('update:open', isOpen.value)

  emit('open')
}
</script>
