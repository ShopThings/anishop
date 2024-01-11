<template>
  <div
    class="text-center mb-3 border-2 border-dashed border-violet-400 rounded-lg p-4 bg-white bg-opacity-60 flex flex-col sm:flex-row items-center gap-3">
    <div
      v-if="slots['text']"
      class="text-sm text-secondary grow mb-2 sm:mb-0 text-right"
    >
      <slot name="text"></slot>
    </div>
    <router-link
      v-if="slots['buttonText']"
      :to="{name: routeName}"
      class="w-full sm:w-auto shrink-0 rounded bg-emerald-500 text-white p-2 px-3 mx-auto sm:mx-0 flex items-center justify-center hover:bg-opacity-90 transition group text-sm"
    >
      <slot name="buttonText"></slot>
    </router-link>
  </div>
</template>

<script setup>
import {useSlots} from "vue";

defineProps({
  routeName: String
})

const slots = useSlots()
</script>
