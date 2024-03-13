<template>
  <Disclosure v-slot="{ open }" :default-open="open">
    <DisclosureButton
        :class="[
            open ? openBtnClass : '',
            btnClass,
        ]"
        class="flex w-full items-start justify-between rounded-lg gap-4 px-4 py-2 text-right text-sm focus:outline-none focus-visible:ring focus-visible:ring-opacity-75 transition"
    >
      <slot :isOpen="open" name="button"></slot>

      <loader-circle
          v-if="isLoading"
          main-container-klass="relative !size-7 mx-auto"
          container-bg-color=""
          big-circle-color="border-transparent"
          spinner-klass="!size-5"
      />
      <ChevronUpIcon
          v-else
          :class="[
              open ? 'rotate-0' : 'rotate-180',
              open ? openBtnIconClass : '',
              btnIconClass,
          ]"
          class="h-5 w-5 text-opacity-70 transition transform shrink-0 mt-1"
      />
    </DisclosureButton>


    <VTransitionSlideFadeDownY mode="out-in">
      <loader-circle
          v-if="isLoading && open"
          main-container-klass="relative !size-16 mx-auto"
          container-bg-color=""
      />
      <template v-else>
        <div v-if="!open && slots['panelClosed']">
          <slot :isOpen="open" name="panelClosed"></slot>
        </div>

        <DisclosurePanel
            v-else
            :class="panelClass"
            class="px-4 pt-4 pb-2 text-sm text-gray-600"
        >
          <slot :is-open="open" name="panel"></slot>
        </DisclosurePanel>
      </template>
    </VTransitionSlideFadeDownY>
  </Disclosure>
</template>

<script setup>
import {Disclosure, DisclosureButton, DisclosurePanel} from "@headlessui/vue"
import {ChevronUpIcon} from '@heroicons/vue/24/outline'
import VTransitionSlideFadeDownY from "@/transitions/VTransitionSlideFadeDownY.vue"
import {useSlots} from "vue";
import LoaderCircle from "@/components/base/loader/LoaderCircle.vue";
import VTransitionFade from "@/transitions/VTransitionFade.vue";

defineProps({
  open: {
    type: Boolean,
    default: false,
  },
  btnClass: {
    type: String,
    default: 'text-violet-900 bg-violet-100 hover:bg-violet-200 focus-visible:ring-violet-500',
  },
  openBtnClass: String,
  btnIconClass: {
    type: String,
    default: 'text-black',
  },
  openBtnIconClass: String,
  panelClass: String,
  isLoading: Boolean,
})
const slots = useSlots()
</script>
