<template>
    <base-sidebar class="text-white" ref="sidebar">
        <button type="button" @click="closeSidebar"
                class="w-10 h-10 absolute left-0 top-2.5 -translate-x-full rounded-l-lg p-2 bg-indigo-600 group transition hover:bg-opacity-90 lg:hidden">
            <Bars3CenterLeftIcon class="w-6 h-6 group-hover:scale-110 transition"/>
        </button>

        <OverlayScrollbarsComponent defer :options="scrollOptions">
            <div ref="scrollableSection">
                <div class="flex flex-col pb-4 bg-gradient-to-b from-[#ffffff4f]">
                    <div class="py-4 bg-gradient-to-b from-[#ffffff4f] mb-0 lg:mb-4">
                        <router-link :to="{name: 'home'}">
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
                                        <span v-if="idx != Object.keys(user.roles).length - 1">, </span>
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

                <div class="px-3 pb-3">
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
    </base-sidebar>
</template>

<script setup>
import {ref, watch, watchEffect} from "vue"
import {useResizeObserver, useWindowScroll, useWindowSize} from "@vueuse/core"
import {OverlayScrollbarsComponent} from "overlayscrollbars-vue"
import {UserIcon, Cog6ToothIcon} from '@heroicons/vue/24/solid'
import {ComputerDesktopIcon, PowerIcon, Bars3CenterLeftIcon} from '@heroicons/vue/24/outline'
import BaseSidebar from "../base/BaseSidebar.vue"
import SidebarLinksAdmin from "./SidebarLinksAdmin.vue"
import {useAdminStore} from "../../store/StoreUserAuth.js";

const scrollOptions = {scrollbars: {theme: 'os-theme-light'}}
//
const scrollableSection = ref(null)
const bottomSection = ref(null)
const sidebar = ref(null)
const {x, y} = useWindowScroll()
const {width} = useWindowSize()

const closeClassName = '__sidebar-admin-is-close'
const openClassName = '__sidebar-admin-is-open'
const triggeredClassName = '__sidebar-admin-btn-triggered'

const store = useAdminStore()
const user = store.getUser

function closeSidebar() {
    if (sidebar.value) {
        sidebar.value.classList.add(closeClassName)
        sidebar.value.classList.remove(openClassName)
        sidebar.value.classList.remove(triggeredClassName)
    }
}

function setSidebarOpenClass() {
    if (!sidebar.value?.classList?.contains(triggeredClassName)) {
        sidebar.value?.classList?.add(openClassName)
        sidebar.value?.classList?.remove(closeClassName)
    }
}

watch(sidebar, () => {
    if (sidebar.value) setSidebarOpenClass()
})

watchEffect(() => {
    if (sidebar.value) {
        if (width.value < 1024) {
            if (sidebar.value.style)
                sidebar.value.style.top = 0

            if (!sidebar.value?.classList?.contains(triggeredClassName)) {
                sidebar.value?.classList?.add(closeClassName)
                sidebar.value?.classList?.remove(openClassName)
            }
            //
            if (y.value > 0) {
                if (!sidebar.value?.classList?.contains('__sidebar-admin-top')) {
                    sidebar.value?.classList?.add('__sidebar-admin-top')
                }
            } else {
                sidebar.value?.classList?.remove('__sidebar-admin-top')
            }
        } else {
            if (sidebar.value.style)
                sidebar.value.style.top = y.value + 'px'
        }

        if (sidebar.value.style) {
            if ((x.value > 5 || x.value < -5))
                sidebar.value.style.right = 0
        }

        setHeight()
    }
})

useResizeObserver(sidebar, (entries) => {
    sidebar.value = entries[0].target
    setHeight()
})

function setHeight() {
    const sb = sidebar.value
    const bh = bottomSection.value.offsetHeight
    const st = sb && sb.getBoundingClientRect ? sb.getBoundingClientRect().top : 0

    scrollableSection.value.style.height = `calc(100vh - (${bh}px) - (${st}px))`
}

defineExpose({
    sidebar,
    setHeight,
})
</script>

<style>
.__sidebar-admin-top {
    top: 64px;
}
</style>
