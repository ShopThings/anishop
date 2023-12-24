<template>
  <VDropdown :distance="6"
             :placement="placement"
             :shift="shift"
             :container="container"
             :disabled="itemsLength <= 0"
  >
    <slot name="button"></slot>

    <template v-if="itemsLength > 0" #popper="{hide}">
      <ul class="p-2 min-w-[9rem] text-gray-700">
        <li v-for="(item, idx) in items" :key="idx">
          <slot name="item" :item="item" :index="idx" :hide="hide"></slot>
        </li>
      </ul>
    </template>
  </VDropdown>
</template>

<script setup>
import {computed} from "vue";
import isObject from "lodash.isobject";
import isArray from "lodash.isarray";

const props = defineProps({
  placement: {
    type: String,
    default: 'top',
  },
  shift: {
    type: Boolean,
    default: true,
  },
  container: {
    type: [Object, String],
    default: () => 'body',
  },
  items: {
    type: Object,
    default: () => {
      return {}
    },
  },
})

const itemsLength = computed(() => {
  if (isObject(props.items))
    return Object.keys(props.items).length
  else if (isArray(props.items))
    return props.items.length
  return 0
})
</script>

<style scoped>

</style>
