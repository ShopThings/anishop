<template>
    <base-sidebar
        :mini="isMini"
        ref="sidebar"
    >
        <button
            type="button"
            :class="[
                'w-8 h-8 absolute left-0 top-12 -translate-x-1/2 rounded-xl p-1 bg-white text-black group transition',
                'border hover:bg-opacity-80 z-[1] shadow-lg hidden xl:inline-block'
            ]"
            @click="toggleMiniMenu"
        >
            <ChevronLeftIcon
                class="w-6 h-6 group-hover:scale-110 transition"
                :class="{'rotate-180': !isMini}"
            />
        </button>

        <OverlayScrollbarsComponent defer :options="scrollOptions">
            <div ref="scrollableSection">
                <VTransitionFade mode="out-in">
                    <div
                        v-if="!isMini"
                        class="flex flex-col pb-4 bg-gradient-to-b from-[#ffffff4f]"
                    >
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
                                    {{
                                        (user.first_name + ' ' + user.last_name).trim()
                                    }}
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
                                    <span
                                        class="px-2 py-1 bg-white bg-opacity-60 text-black rounded inline-block">فاقد نقش</span>
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

                    <div
                        v-else
                        class="flex flex-col pb-4 bg-gradient-to-b from-[#ffffff4f]"
                    >
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
                </VTransitionFade>

                <VTransitionFade mode="out-in">
                    <div
                        v-if="!isMini"
                        class="px-3 pb-3 text-sm"
                    >
                        <sidebar-links-admin/>
                    </div>
                    <div
                        v-else
                        class="px-3 pb-3 text-sm"
                    >
                        <sidebar-links-admin :mini="isMini"/>
                    </div>
                </VTransitionFade>
            </div>
        </OverlayScrollbarsComponent>

        <div class="flex flex-col px-3 py-3 bg-gradient-to-b from-[#ffffff0f]" ref="bottomSection">
            <VTransitionFade mode="out-in">
                <ul
                    v-if="!isMini"
                    class="flex flex-col"
                >
                    <li>
                        <router-link :to="{name: 'admin.logout'}"
                                     class="rounded-lg py-3 px-3 flex hover:bg-white hover:bg-opacity-10 transition">
                            <PowerIcon class="h-6 w-6 ml-2 shrink-0"/>
                            <span
                                class="grow">خروج</span>
                        </router-link>
                    </li>
                </ul>

                <ul
                    v-else
                    class="flex flex-col"
                >
                    <li v-tooltip.left="'خروج'">
                        <router-link :to="{name: 'admin.logout'}"
                                     class="rounded-lg py-3 px-2 flex justify-center hover:bg-white hover:bg-opacity-10 transition">
                            <PowerIcon class="h-6 w-6 shrink-0"/>
                        </router-link>
                    </li>
                </ul>
            </VTransitionFade>
        </div>
    </base-sidebar>
</template>

<script setup>
import {ref, watch, watchEffect} from "vue"
import {useResizeObserver, useWindowSize} from "@vueuse/core"
import {OverlayScrollbarsComponent} from "overlayscrollbars-vue"
import {UserIcon, Cog6ToothIcon} from '@heroicons/vue/24/solid'
import {ComputerDesktopIcon, PowerIcon} from '@heroicons/vue/24/outline'
import BaseSidebar from "../base/BaseSidebar.vue"
import SidebarLinksAdmin from "./SidebarLinksAdmin.vue"
import {useAdminStore} from "../../store/StoreUserAuth.js";
import {ChevronLeftIcon} from "@heroicons/vue/24/outline/index.js";
import VTransitionFade from "../../transitions/VTransitionFade.vue";

const scrollOptions = {scrollbars: {theme: 'os-theme-light'}}

const scrollableSection = ref(null)
const bottomSection = ref(null)
const sidebar = ref(null)

const store = useAdminStore()
const user = store.getUser

const isMini = ref(false)

const {width} = useWindowSize()

function checkWindowSize() {
    if (width.value <= 1280) {
        isMini.value = true
    }
}

checkWindowSize()

watch(width, () => {
    checkWindowSize()
})

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

function toggleMiniMenu() {
    isMini.value = !isMini.value
}

defineExpose({
    sidebar,
    setHeight,
})
</script>

<style>

</style>
