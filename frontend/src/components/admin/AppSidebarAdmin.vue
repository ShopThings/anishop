<template>
    <base-sidebar ref="sidebar">
        <OverlayScrollbarsComponent defer :options="scrollOptions">
            <div ref="scrollableSection">
                <div class="flex flex-col pb-4 bg-gradient-to-b from-[#ffffff4f]">
                    <div class="py-4 bg-gradient-to-b from-[#ffffff4f] mb-0 lg:mb-4">
                        <router-link :to="{name: 'home'}" target="_blank">
                            <img class="h-[28px] mx-auto lg:h-[36px]"
                                 src="/logo-light.png"
                                 alt="لوگو">
                        </router-link>
                    </div>

                    <div class="flex flex-col px-3">
                        <div class="flex flex-col">
                            <div
                                v-tooltip.left="'' + (user.first_name || user.last_name ? (user.first_name + ' ' + user.last_name).trim() : user.username) + ''"
                                class="text-center w-12 h-12 rounded-full bg-white mx-auto p-3 bg-opacity-90 shadow-lg">
                                <UserIcon class="h-6 w-6 mx-auto text-slate-700"/>
                            </div>
                        </div>

                        <div class="flex flex-col items-center mt-4">
                            <router-link :to="{name: 'home'}" target="_blank" v-tooltip.left="'نمایش سایت'"
                                         class="flex justify-center px-2.5 py-2.5 bg-white text-black rounded-lg grow hover:bg-opacity-90 transition">
                                <ComputerDesktopIcon class="h-6 w-6"/>
                            </router-link>

                            <div class="mt-3">
                                <router-link :to="{name: 'admin.settings'}" v-tooltip.left="'تنظیمات'"
                                             class="ring-1 ring-white text-center rounded-lg px-2.5 py-2.5 hover:bg-white hover:bg-opacity-10 transition block">
                                    <Cog6ToothIcon class="h-6 w-6"/>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-3 pb-3 text-sm">
                    <sidebar-links-admin :mini="true"/>
                </div>
            </div>
        </OverlayScrollbarsComponent>

        <div class="flex flex-col px-3 py-2 bg-gradient-to-b from-[#ffffff0f]" ref="bottomSection">
            <ul class="flex flex-col">
                <li v-tooltip.left="'خروج'">
                    <router-link :to="{name: 'admin.logout'}"
                                 class="rounded-lg py-3 px-2 flex justify-center hover:bg-white hover:bg-opacity-10 transition">
                        <PowerIcon class="h-6 w-6 shrink-0"/>
                    </router-link>
                </li>
            </ul>
        </div>
    </base-sidebar>
</template>

<script setup>
import {ref, watchEffect} from "vue"
import {useResizeObserver} from "@vueuse/core"
import {OverlayScrollbarsComponent} from "overlayscrollbars-vue"
import {UserIcon, Cog6ToothIcon} from '@heroicons/vue/24/solid'
import {ComputerDesktopIcon, PowerIcon} from '@heroicons/vue/24/outline'
import BaseSidebar from "../base/BaseSidebar.vue"
import SidebarLinksAdmin from "./SidebarLinksAdmin.vue"
import {useAdminStore} from "../../store/StoreUserAuth.js";

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
        scrollableSection.value.style.height = `calc(100vh - (${bh}px) - 1px)`
    }
}

defineExpose({
    sidebar,
    setHeight,
})
</script>

<style>

</style>
