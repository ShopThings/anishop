<template>
  <slot :open="openModal" name="button"></slot>

  <partial-dialog
    v-model:open="isOpen"
    :container-klass="containerKlass"
    :dialog-container-class="dialogContainerClass"
    @close="closeModal"
    @open="openModal"
  >
    <template v-if="slots['closeButton']" #closeButton="{close}">
      <slot :close="close" name="closeButton"></slot>
    </template>

    <template v-if="slots['title']" #title>
      <slot name="title"></slot>
    </template>

    <template v-if="slots['body']" #body>
      <slot :close="closeModal" name="body"></slot>
    </template>
  </partial-dialog>
</template>

<script setup>
import {computed, useSlots} from "vue";
import PartialDialog from "@/components/partials/PartialDialog.vue";

const props = defineProps({
  open: Boolean,
  dialogContainerClass: [String, Object],
  containerKlass: [String, Object],
})

const emit = defineEmits(['update:open', 'open', 'close'])
const slots = useSlots()

const isOpen = computed({
  get() {
    return props.open
  },
  set(value) {
    emit('update:open', value)
  },
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
