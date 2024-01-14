<template>
  <Disclosure v-slot="{ open }" :default-open="open">
    <DisclosureButton
      :class="btnClass"
      class="flex w-full items-start justify-between rounded-lg gap-4 px-4 py-2 text-right text-sm font-medium focus:outline-none focus-visible:ring focus-visible:ring-opacity-75 transition"
    >
      <slot name="button" :isOpen="open"></slot>
      <ChevronUpIcon
        :class="[
                    open ? 'rotate-0' : 'rotate-180',
                    btnIconClass,
                ]"
        class="h-5 w-5 text-opacity-70 transition transform shrink-0 mt-1"
      />
    </DisclosureButton>


    <VTransitionSlideFadeDownY mode="out-in">
      <div v-if="!open && slots['panelClosed']">
        <slot name="panelClosed" :isOpen="open"></slot>
      </div>

      <DisclosurePanel
        v-else
        class="px-4 pt-4 pb-2 text-sm text-gray-600"
        :class="panelClass"
      >
        <slot name="panel" :is-open="open"></slot>
      </DisclosurePanel>
    </VTransitionSlideFadeDownY>
  </Disclosure>
</template>

<script setup>
import {Disclosure, DisclosureButton, DisclosurePanel} from "@headlessui/vue"
import {ChevronUpIcon} from '@heroicons/vue/24/outline'
import VTransitionSlideFadeDownY from "@/transitions/VTransitionSlideFadeDownY.vue"
import {useSlots} from "vue";

defineProps({
  open: {
    type: Boolean,
    default: false,
  },
  btnClass: {
    type: String,
    default: 'text-violet-900 bg-violet-100 hover:bg-violet-200 focus-visible:ring-violet-500',
  },
  btnIconClass: {
    type: String,
    default: 'text-black',
  },
  panelClass: String,
})
const slots = useSlots()
</script>
