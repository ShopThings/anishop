<template>
    <Disclosure v-slot="{ open }" :default-open="open">
        <DisclosureButton
            class="flex w-full justify-between rounded-lg bg-violet-100 px-4 py-2 text-left text-sm font-medium text-violet-900 hover:bg-violet-200 focus:outline-none focus-visible:ring focus-visible:ring-violet-500 focus-visible:ring-opacity-75 transition"
        >
            <slot name="button"></slot>
            <ChevronUpIcon
                :class="open ? 'rotate-0' : '-rotate-0'"
                class="h-5 w-5 text-violet-500 transition rotate-180 transform"
            />
        </DisclosureButton>

        <VTransitionSlideFadeDownY>
            <DisclosurePanel class="px-4 pt-4 pb-2 text-sm text-gray-500">
                <slot name="panel"></slot>
            </DisclosurePanel>
        </VTransitionSlideFadeDownY>
    </Disclosure>
</template>

<script setup>
import {Disclosure, DisclosureButton, DisclosurePanel} from "@headlessui/vue"
import {ChevronUpIcon} from '@heroicons/vue/24/outline'
import VTransitionSlideFadeDownY from "../../transitions/VTransitionSlideFadeDownY.vue"

defineProps({
    open: {
        type: Boolean,
        default: false,
    },
})
</script>

<style scoped>

</style>
