<template>
    <Menu v-slot="{open}">
        <div class="flex items-center">
            <slot name="link"></slot>

            <MenuButton
                class="p-3 mr-2 shrink-0 rounded-lg border border-white border-opacity-25 bg-white"
                :class="[open ? 'bg-opacity-20' : 'bg-opacity-5']">
                <ChevronLeftIcon class="h-5 w-5 transition"
                                 :class="{'-rotate-90' : open}"/>
            </MenuButton>
        </div>
        <VTransitionSlideFadeDownY>
            <MenuItems>
                <ul class="flex flex-col py-2 pr-3">
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
</script>

<style scoped>

</style>
