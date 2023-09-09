<template>
    <Disclosure v-slot="{ open }" :default-open="open">
        <DisclosureButton
            class="flex w-full justify-between rounded-lg px-4 py-2 text-left text-sm font-medium focus:outline-none focus-visible:ring focus-visible:ring-opacity-75 transition"
            :class="btnClass"
        >
            <slot name="button" :is-open="open"></slot>
            <ChevronUpIcon
                :class="[
                    open ? 'rotate-0' : 'rotate-180',
                    btnIconClass,
                ]"
                class="h-5 w-5 text-opacity-70 transition transform"
            />
        </DisclosureButton>

        <VTransitionSlideFadeDownY>
            <DisclosurePanel
                class="px-4 pt-4 pb-2 text-sm text-gray-500"
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
import VTransitionSlideFadeDownY from "../../transitions/VTransitionSlideFadeDownY.vue"

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
</script>

<style scoped>

</style>
