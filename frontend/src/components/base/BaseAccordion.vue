<template>
  <Disclosure v-slot="{ open }" :default-open="open">
    <DisclosureButton
      v-if="isLoading"
      :class="[
            open ? openBtnClass : '',
            btnClass,
        ]"
      class="flex w-full items-start justify-between rounded-lg gap-4 px-4 py-2 text-right text-sm focus:outline-none focus-visible:ring focus-visible:ring-opacity-75 transition"
    >
      <div>
        <slot :isOpen="open" name="button"></slot>
      </div>

      <loader-circle
        v-if="isLoading"
        big-circle-color="border-transparent"
        container-bg-color=""
        main-container-klass="relative !size-7"
        spinner-klass="!size-5"
      />
    </DisclosureButton>
    <DisclosureButton
      v-else
      :class="[
            open ? openBtnClass : '',
            btnClass,
        ]"
      class="flex w-full items-start justify-between rounded-lg gap-4 px-4 py-2 text-right text-sm focus:outline-none focus-visible:ring focus-visible:ring-opacity-75 transition"
    >
      <div>
        <slot :isOpen="open" name="button"></slot>
      </div>

      <ChevronUpIcon
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
        container-bg-color=""
        main-container-klass="relative !size-16 mx-auto"
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

/**
 * 📍[NOTE]
 *  As you notice there are two disclosure buttons and only difference is that, one have loading in it
 *  and the other one doesn't. I know I can write it better but something about disclosure button that
 *  don't update isLoading check and always show the loader, so I came up with duplicating idea.
 *
 *  [If somehow this get working in ALL CASES(because previous non duplicated one was working on most cases but NOT ALL CASES),
 *   fell free to make it as a normal component again!]
 */

const props = defineProps({
  open: {
    type: Boolean,
    default: false,
  },
  btnClass: {
    type: String,
    default: 'text-slate-900 bg-white hover:bg-violet-100 focus-visible:ring-violet-500 border border-violet-300',
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
