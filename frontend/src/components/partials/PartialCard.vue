<template>
  <div ref="card" class="bg-white rounded-lg border">
    <h1 v-if="slots['header']"
        class="border-b-2 border-b-indigo-200 py-2 px-3 text-lg text-indigo-500">
      <slot name="header"></slot>
    </h1>
    <slot name="body"></slot>
    <slot name="footer"></slot>
  </div>
</template>

<script setup>
import {ref, useSlots} from "vue";

const slots = useSlots()

const card = ref(null)

defineExpose({
  card,
})
</script>
