<template>
  <Menu v-slot="{open}">
    <div :class="{'flex-col': mini}" class="flex items-center">
      <slot :open="open" name="link"></slot>

      <MenuButton
          :class="[
                    open ? 'bg-opacity-20' : 'bg-opacity-5',
                    mini ? 'w-full flex justify-center p-0.5' : 'p-3 mr-2 '
                ]"
          class="shrink-0 rounded-lg border border-white border-opacity-25 bg-white">
        <ChevronLeftIcon :class="{'-rotate-90' : open}"
                         class="h-5 w-5 transition"/>
      </MenuButton>
    </div>
    <VTransitionSlideFadeDownY>
      <MenuItems>
        <ul
            :class="{'pr-3': !mini}"
            class="flex flex-col py-2"
        >
          <slot name="children"></slot>
        </ul>
      </MenuItems>
    </VTransitionSlideFadeDownY>
  </Menu>
</template>

<script setup>
import VTransitionSlideFadeDownY from "@/transitions/VTransitionSlideFadeDownY.vue"
import {ChevronLeftIcon} from "@heroicons/vue/24/outline/index.js"
import {Menu, MenuButton, MenuItems} from "@headlessui/vue"

defineProps({
  mini: {
    type: Boolean,
    default: false,
  },
})
</script>
