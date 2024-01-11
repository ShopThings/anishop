<template>
  <Menu v-slot="{open}">
    <div class="flex items-center" :class="{'flex-col': mini}">
      <slot name="link" :open="open"></slot>

      <MenuButton
        class="shrink-0 rounded-lg border border-white border-opacity-25 bg-white"
        :class="[
                    open ? 'bg-opacity-20' : 'bg-opacity-5',
                    mini ? 'w-full flex justify-center p-0.5' : 'p-3 mr-2 '
                ]">
        <ChevronLeftIcon class="h-5 w-5 transition"
                         :class="{'-rotate-90' : open}"/>
      </MenuButton>
    </div>
    <VTransitionSlideFadeDownY>
      <MenuItems>
        <ul
          class="flex flex-col py-2"
          :class="{'pr-3': !mini}"
        >
          <slot name="children"></slot>
        </ul>
      </MenuItems>
    </VTransitionSlideFadeDownY>
  </Menu>
</template>

<script setup>
import VTransitionSlideFadeDownY from "../../transitions/VTransitionSlideFadeDownY.vue"
import {ChevronLeftIcon} from "@heroicons/vue/24/outline/index.js"
import {Menu, MenuButton, MenuItems} from "@headlessui/vue"

defineProps({
  mini: {
    type: Boolean,
    default: false,
  },
})
</script>
