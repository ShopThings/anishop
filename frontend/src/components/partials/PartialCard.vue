<template>
  <div class="bg-white rounded-lg border" ref="card">
    <h1 v-if="slots['header']"
        class="border-b border-b-amber-400 p-2 text-lg">
      <slot name="header"></slot>
    </h1>
    <slot name="body"></slot>
    <slot name="footer"></slot>
  </div>
</template>

<script setup>
import {ref, useSlots} from "vue";

const card = ref(null)

const slots = useSlots()

defineExpose({
  card,
})
</script>
