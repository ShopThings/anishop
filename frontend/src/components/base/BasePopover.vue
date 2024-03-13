<template>
  <Popover v-slot="{close}">
    <PopoverButton as="template">
      <slot name="button"></slot>
    </PopoverButton>

    <TransitionChild
        as="template"
        enter="duration-300 ease-out"
        enter-from="opacity-0 translate-y-3"
        enter-to="opacity-100 translate-y-0"
        leave="duration-200 ease-in"
        leave-from="opacity-100 translate-y-0"
        leave-to="opacity-0 translate-y-3"
    >
      <PopoverPanel
          :class="[
                    'absolute z-[10] origin-top-right rounded-md overflow-y-auto max-h-96',
                    'bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none',
                    panelKlass,
                ]">
        <slot :close="close" name="panel"></slot>
      </PopoverPanel>
    </TransitionChild>
  </Popover>
</template>

<script setup>
import {Popover, PopoverButton, PopoverPanel, TransitionChild} from "@headlessui/vue";

defineProps({
  panelKlass: {
    type: String,
    default: 'right-0 top-full mt-3 w-80'
  },
})
</script>
