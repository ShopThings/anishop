<template>
  <base-floating-drop-down
      placement="right-start"
      :shift="false"
      :container="container"
      :items="items"
  >
    <template #button>
      <button type="button"
              class="text-black bg-white p-1 rounded-md ring-1 ring-cyan-300 transition hover:ring-cyan-500">
        <outline.Bars3Icon class="h-5 w-5"/>
      </button>
    </template>

    <template #item="{item, hide}">
      <template v-if="!item.id || (item.id && removals.indexOf(item.id) === -1)">
        <base-button
            :type="item?.link?.href ? 'link' : 'button'"
            :to="item?.link?.href"
            @click="itemClick(item, hide)"
            class="flex items-center w-full !px-2 !py-1 text-sm transition hover:bg-gray-100"
            :class="item.link.class"
            default-class="rounded-md"
        >
          <component v-if="item?.link?.icon" :is="outline[item.link.icon]" class="h-5 w-5"/>
          <span :class="item.link.icon ? 'mr-2' : ''">{{ item.link.text }}</span>
        </base-button>
      </template>
    </template>
  </base-floating-drop-down>
</template>

<script setup>
import * as outline from '@heroicons/vue/24/outline';
import {isProxy, toRaw} from "vue";
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

function itemClick(item, hide) {
  if (item?.event?.click) {
    if (item.link?.closeOnClick !== false)
      hide()
    item.event.click(isProxy(props.data) ? toRaw(props.data) : props.data)
  }
}
</script>
