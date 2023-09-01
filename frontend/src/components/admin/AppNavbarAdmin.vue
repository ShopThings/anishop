<template>
    <div class="h-[64px] relative" ref="navbar">
        <nav class="bg-white w-full shadow-md">
            <div class="md:container md:mx-auto">
                <div class="h-[64px] py-2 px-6 flex">
                    <div class="h-full grow flex justify-between">
                        <ul class="flex mt-[4px] space-x-reverse">
                            <li class="px-1">
                                <button type="button" @click="toggleSidebar"
                                        class="relative w-[45] h-[45] rounded-lg border-0 py-2 px-2 bg-transparent text-black hover:bg-gray-100 active:bg-gray-200 focus:bg-sky-50 transition-all flex justify-between items-center">
                                    <Bars3BottomRightIcon class="w-6 h-6"/>
                                </button>
                            </li>
                            <li class="px-1">
                                <navbar-alerts-admin/>
                            </li>
                            <li class="px-1">
                                <navbar-shopping-statuses/>
                            </li>
                        </ul>
                        <ul class="flex mt-[4px] space-x-reverse">
                            <li class="relative px-1">
                                <navbar-user-action-admin/>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</template>

<script setup>
import {ref} from "vue"
import {Bars3BottomRightIcon} from '@heroicons/vue/24/outline'
import NavbarUserActionAdmin from "./NavbarUserActionAdmin.vue"
import NavbarAlertsAdmin from "./NavbarAlertsAdmin.vue"
import NavbarShoppingStatuses from "./NavbarShoppingStatuses.vue"

const props = defineProps({
    sidebar: Object,
})

const navbar = ref(null)
const closeClassName = '__sidebar-admin-is-close'
const openClassName = '__sidebar-admin-is-open'
const triggeredClassName = '__sidebar-admin-btn-triggered'

function toggleSidebar() {
    if (props.sidebar.sidebar) {
        if (!props.sidebar.sidebar.classList.contains(closeClassName)) {
            props.sidebar.sidebar.classList.add(closeClassName)
            props.sidebar.sidebar.classList.remove(openClassName)
            props.sidebar.sidebar.classList.remove(triggeredClassName)
        } else {
            props.sidebar.sidebar.classList.add(openClassName)
            props.sidebar.sidebar.classList.remove(closeClassName)
            props.sidebar.sidebar.classList.add(triggeredClassName)
        }

        props.sidebar.setHeight()
    }
}

defineExpose({
    navbar,
})
</script>

<style>
.__sidebar-admin-is-open {
    visibility: visible;
    display: block;
    right: 0;
    transition: right ease-in-out .26s;
}

.__sidebar-admin-is-close {
    position: fixed;
    right: -100% !important;
    transition: all ease-in-out .26s;
}
</style>
