<template>
  <base-floating-drop-down
    v-if="hasAnyItem"
    :container="container"
    :items="items"
    :shift="false"
    placement="right-start"
  >
    <template #button>
      <slot name="button">
        <button
          class="text-black p-1 rounded-full ring-1 ring-slate-300 transition hover:ring-cyan-500"
          type="button"
        >
          <outline.EllipsisHorizontalIcon class="size-6"/>
        </button>
      </slot>
    </template>

    <template #item="{item, hide}">
      <template v-if="!item.id || (item.id && removals.indexOf(item.id) === -1)">
        <base-button
          :class="item.link.class"
          :to="item?.link?.href"
          :type="item?.link?.href ? 'link' : 'button'"
          class="flex items-center w-full !px-2 !py-1 text-sm transition hover:bg-gray-100"
          default-class="rounded-md"
          @click="itemClick(item, hide)"
        >
          <component :is="outline[item.link.icon]" v-if="item?.link?.icon" class="h-5 w-5"/>
          <span :class="item.link.icon ? 'mr-2' : ''">{{ item.link.text }}</span>
        </base-button>
      </template>
    </template>
  </base-floating-drop-down>
</template>

<script setup>
import * as outline from '@heroicons/vue/24/outline';
import {computed, isProxy, toRaw} from "vue";
import BaseFloatingDropDown from "../BaseFloatingDropDown.vue";
import BaseButton from "../BaseButton.vue";

const props = defineProps({
  container: {
    type: [Object, String],
    default: 'body',
  },
  items: {
    type: Array,
    required: true,
  },
  data: Object,
  removals: {
    type: Array,
    default: () => {
      return []
    }
  },
})

const hasAnyItem = computed(() => {
  let filteredLength = props.items.length

  if (props.removals.length) {
    filteredLength = props.items.filter(item => {
      if (!item?.id) return true
      return props.removals.indexOf(item.id) === -1
    }).length
  }

  return props.items.length && filteredLength !== 0
})

function itemClick(item, hide) {
  if (item?.event?.click) {
    if (item.link?.closeOnClick !== false)
      hide()
    item.event.click(isProxy(props.data) ? toRaw(props.data) : props.data)
  }
}
</script>
