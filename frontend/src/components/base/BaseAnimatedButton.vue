<template>
  <base-button
      :type="type"
      class="group flex justify-center items-center"
      @click="checkButton"
  >
    <slot klass="group-hover:scale-110 group-hover:scale-110 transition-all" name="icon"></slot>
    <slot></slot>
  </base-button>
</template>

<script setup>
import BaseButton from "./BaseButton.vue";

defineProps({
  type: {
    type: String,
    default: 'button',
    validator(value) {
      return ['submit', 'button', 'reset'].indexOf(value) !== -1;
    },
  },
})

const emit = defineEmits(['click'])

function checkButton() {
  emit('click')
}
</script>
