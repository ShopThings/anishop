<template>
  <VDropdown :container="container"
             :disabled="itemsLength <= 0"
             :distance="6"
             :placement="placement"
             :shift="shift"
  >
    <slot name="button"></slot>

    <template v-if="itemsLength > 0" #popper="{hide}">
      <ul class="p-2 min-w-[9rem] text-gray-700">
        <li v-for="(item, idx) in items" :key="idx" class="w-full">
          <slot :hide="hide" :index="idx" :item="item" name="item"></slot>
        </li>
      </ul>
    </template>
  </VDropdown>
</template>

<script setup>
import {computed} from "vue";
import isObject from "lodash.isobject";

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
  if (isObject(props.items)) {
    return Object.keys(props.items).length
  } else if (Array.isArray(props.items)) {
    return props.items.length
  }

  return 0
})
</script>
