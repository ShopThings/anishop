<template>
  <base-button
      @click="checkButton"
      :type="type"
      class="group flex justify-center items-center"
  >
    <slot name="icon" klass="group-hover:scale-110 group-hover:scale-110 transition-all"></slot>
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

<style scoped>

</style>
