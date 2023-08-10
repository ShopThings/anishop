<template>
    <Popover class="relative" v-slot="{close}">
        <PopoverButton as="template">
            <slot name="button" :close="close"></slot>
        </PopoverButton>
        <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0"
            enter-to="opacity-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100"
            leave-to="opacity-0"
        >
            <PopoverOverlay class="fixed z-20 inset-0 bg-black bg-opacity-25"/>
        </TransitionChild>

        <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 translate-x-20"
            enter-to="opacity-100 translate-x-0"
            leave="duration-200 ease-in"
            leave-from="opacity-100 translate-x-0"
            leave-to="opacity-0 translate-x-20"
        >
            <PopoverPanel
                class="fixed z-20 bg-white w-4/5 h-screen top-0 right-0 py-3 pr-6 pl-3 sm:w-80">
                <slot name="panel" :close="close"></slot>
            </PopoverPanel>
        </TransitionChild>
    </Popover>
</template>

<script setup>
import {Popover, PopoverButton, PopoverOverlay, PopoverPanel, TransitionChild} from "@headlessui/vue";
</script>

<style scoped>

</style>
