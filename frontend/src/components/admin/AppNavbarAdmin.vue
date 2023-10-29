<template>
    <div class="h-[64px] relative" ref="navbar">
        <nav class="bg-white w-full shadow-md">
            <div class="h-[64px] py-2 px-6 flex">
                <div class="h-full grow flex justify-between">
                    <ul class="flex mt-[4px] space-x-reverse">
                        <li class="px-1 xl:hidden">
                            <base-popover-side panel-class="">
                                <template #button>
                                    <button type="button"
                                            class="relative w-[45] h-[45] rounded-lg border-0 py-2 px-2 bg-transparent text-black hover:bg-slate-200 active:bg-slate-300 focus:bg-sky-200 transition-all flex justify-between items-center z-[1]">
                                        <Bars3BottomRightIcon class="w-6 h-6"/>
                                    </button>
                                </template>

                                <template #panel="{close}">
                                    <nav class="h-full" :class="sidebarBgColor" ref="sidebar">
                                        <button
                                            type="button"
                                            class="w-10 h-10 absolute left-0 top-2 -translate-x-12 rounded-lg p-2 bg-white text-black group transition bg-opacity-60 hover:bg-opacity-100"
                                            @click="close"
                                        >
                                            <XMarkIcon class="w-6 h-6 group-hover:scale-110 transition"/>
                                        </button>

                                        <OverlayScrollbarsComponent defer :options="scrollOptions">
                                            <div ref="scrollableSection">
                                                <div class="flex flex-col pb-4 bg-gradient-to-b from-[#ffffff4f]">
                                                    <div class="py-4 bg-gradient-to-b from-[#ffffff4f] mb-0 lg:mb-4">
                                                        <router-link :to="{name: 'home'}" target="_blank">
                                                            <img class="h-[28px] mx-auto lg:h-[36px]"
                                                                 src="/logo-with-type-light.png"
                                                                 alt="لوگو">
                                                        </router-link>
                                                    </div>

                                                    <div class="flex flex-col px-3">
                                                        <div class="flex flex-col">
                                                            <div
                                                                class="text-center w-21 h-21 lg:w-24 lg:h-24 rounded-full bg-white mx-auto p-6 bg-opacity-90 shadow-lg">
                                                                <UserIcon class="h-10 w-10 lg:h-12 lg:w-12 mx-auto text-slate-700"/>
                                                            </div>
                                                            <span class="text-center mt-3">
                                                                <template v-if="user.first_name || user.last_name">
                                                                    {{ (user.first_name + ' ' + user.last_name).trim() }}
                                                                </template>
                                                                <template v-else>
                                                                    {{ user.username }}
                                                                </template>
                                                            </span>
                                                            <span class="text-center mt-1 text-xs opacity-80">
                                                                <template v-if="user.roles">
                                                                    <span v-for="(role, key, idx) in user.roles">
                                                                        {{ role }}
                                                                        <span
                                                                            v-if="idx !== Object.keys(user.roles).length - 1">, </span>
                                                                    </span>
                                                                </template>
                                                                <template v-else>
                                                                    <span class="px-2 py-1 bg-white bg-opacity-60 text-black rounded inline-block">فاقد نقش</span>
                                                                </template>
                                                            </span>
                                                        </div>

                                                        <div class="flex items-center mt-4">
                                                            <router-link :to="{name: 'home'}" target="_blank"
                                                                         class="flex justify-center px-2 py-2.5 bg-white text-black rounded-lg grow hover:bg-opacity-90 transition">
                                                                <ComputerDesktopIcon class="h-6 w-6 ml-2"/>
                                                                <span>نمایش سایت</span>
                                                            </router-link>

                                                            <div class="mr-3 shrink-0">
                                                                <router-link :to="{name: 'admin.settings'}" v-tooltip.left="'تنظیمات'"
                                                                             class="ring-1 ring-white text-center rounded-lg px-2.5 py-2.5 hover:bg-white hover:bg-opacity-10 transition block">
                                                                    <Cog6ToothIcon class="h-6 w-6"/>
                                                                </router-link>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="px-3 pb-3 text-sm">
                                                    <sidebar-links-admin/>
                                                </div>
                                            </div>
                                        </OverlayScrollbarsComponent>

                                        <div class="flex flex-col px-3 py-3 bg-gradient-to-b from-[#ffffff0f]" ref="bottomSection">
                                            <ul class="flex flex-col">
                                                <li>
                                                    <router-link :to="{name: 'admin.logout'}"
                                                                 class="rounded-lg py-3 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                                                        <PowerIcon class="h-6 w-6 ml-2 shrink-0"/>
                                                        <span
                                                            class="grow">خروج</span>
                                                    </router-link>
                                                </li>
                                            </ul>
                                        </div>
                                    </nav>
                                </template>
                            </base-popover-side>
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
        </nav>
    </div>
</template>

<script setup>
import {ref, watchEffect} from "vue"
import {Bars3BottomRightIcon} from '@heroicons/vue/24/outline'
import NavbarUserActionAdmin from "./NavbarUserActionAdmin.vue"
import NavbarAlertsAdmin from "./NavbarAlertsAdmin.vue"
import NavbarShoppingStatuses from "./NavbarShoppingStatuses.vue"
import {XMarkIcon, ComputerDesktopIcon, PowerIcon} from "@heroicons/vue/24/outline/index.js";
import SidebarLinksAdmin from "./SidebarLinksAdmin.vue";
import {OverlayScrollbarsComponent} from "overlayscrollbars-vue";
import BasePopoverSide from "../base/BasePopoverSide.vue";
import {Cog6ToothIcon, UserIcon} from "@heroicons/vue/24/solid/index.js";
import {useAdminStore} from "../../store/StoreUserAuth.js";
import {useResizeObserver} from "@vueuse/core";

const props = defineProps({
    sidebarBgColor: {
        type: String,
        default: 'bg-indigo-600 text-white',
    },
})

const navbar = ref(null)

const scrollOptions = {scrollbars: {theme: 'os-theme-light'}}

const scrollableSection = ref(null)
const bottomSection = ref(null)
const sidebar = ref(null)

const store = useAdminStore()
const user = store.getUser

watchEffect(() => {
    if (sidebar.value) {
        setHeight()
    }
})

useResizeObserver(sidebar, (entries) => {
    sidebar.value.target = entries[0].target
    setHeight()
})

function setHeight() {
    if (bottomSection.value && scrollableSection.value) {
        const bh = bottomSection.value.offsetHeight
        scrollableSection.value.style.height = `calc(100vh - (${bh}px))`
    }
}

defineExpose({
    navbar,
})
</script>

<style>

</style>
