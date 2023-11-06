<template>
    <div class="sm:h-[64px] relative" ref="navbar">
        <nav class="w-full">
            <div class="h-auto sm:h-[64px] py-2 px-6 flex">
                <div class="h-full grow flex flex-col items-start gap-3 sm:gap-0 sm:flex-row sm:justify-between">
                    <ul class="flex mt-[4px] space-x-reverse justify-between w-full sm:justify-start">
                        <li class="px-1 lg:hidden">
                            <base-popover-side panel-class="">
                                <template #button>
                                    <button type="button"
                                            class="relative w-[45] h-[45] rounded-lg border-0 py-2 px-2 bg-transparent text-black hover:bg-slate-200 active:bg-slate-300 focus:bg-sky-200 transition-all flex justify-between items-center z-[1]">
                                        <Bars3BottomRightIcon class="w-6 h-6"/>
                                    </button>
                                </template>

                                <template #panel="{close}">
                                    <nav class="bg-awesome text-black h-full" ref="sidebar">
                                        <button
                                            type="button"
                                            class="w-10 h-10 absolute left-0 top-2 -translate-x-12 rounded-lg p-2 bg-white text-black group transition bg-opacity-60 hover:bg-opacity-100"
                                            @click="close"
                                        >
                                            <XMarkIcon class="w-6 h-6 group-hover:scale-110 transition"/>
                                        </button>

                                        <div
                                            ref="topSection"
                                            class="flex flex-col"
                                        >
                                            <div
                                                class="flex flex-row items-center gap-3 border-b-2 border-slate-200 py-3 px-2">
                                                <div
                                                    class="text-center w-12 h-12 rounded-full bg-indigo-400 p-1 bg-opacity-90">
                                                    <div
                                                        v-if="user.first_name || user.last_name"
                                                        class="w-full h-full text-center text-2xl text-indigo-900"
                                                    >
                                                        {{ (user.first_name + ' ' + user.last_name).trim().at(0) }}
                                                    </div>
                                                    <UserIcon v-else class="h-8 w-8 m-1 text-white"/>
                                                </div>
                                                <div class="text-sm">
                                                    <div>
                                                        <template v-if="user.first_name || user.last_name">
                                                            {{
                                                                (user.first_name + ' ' + user.last_name).trim()
                                                            }}
                                                        </template>
                                                        <template v-else>
                                                            {{ user.username }}
                                                        </template>
                                                    </div>
                                                    <div class="mt-1 text-xs opacity-80">
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
                                                    </div>
                                                </div>
                                                <div class="flex items-center justify-center mr-auto">
                                                    <base-button
                                                        v-tooltip.bottom="'اعلانات'"
                                                        type="link"
                                                        :to="{name: 'user.notifications'}"
                                                        class="relative w-[40] h-[40] border-0 !px-2 rounded-lg bg-transparent !text-black hover:bg-slate-200 active:bg-slate-300 focus:bg-sky-200 transition-all"
                                                    >
                                                        <BellIcon class="h-5 w-5"/>
                                                    </base-button>
                                                </div>
                                            </div>
                                        </div>

                                        <div ref="scrollableSection"
                                             class="my-custom-scrollbar p-3 text-sm">
                                            <sidebar-links-user/>
                                        </div>

                                        <div class="flex flex-col py-2 px-3 bg-gradient-to-b from-[#ffffff0f]"
                                             ref="bottomSection">
                                            <ul class="flex flex-col">
                                                <li>
                                                    <router-link :to="{name: 'user.logout'}"
                                                                 class="rounded-lg border-2 border-transparent py-2.5 px-3 flex hover:border-indigo-400 hover:bg-indigo-100 transition">
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
                        <li class="px-0.5">
                            <dialog-search/>
                        </li>
                        <li class="sm:relative px-0.5">
                            <navbar-cart position="right"/>
                        </li>
                    </ul>
                    <ul class="flex mt-[4px] space-x-reverse shrink-0 w-full sm:w-auto">
                        <li class="px-1 grow sm:grow-0">
                            <base-button
                                type="link"
                                :to="{name: 'home'}"
                                class="text-sm !text-black border-2 hover:bg-primary hover:!bg-opacity-[15%] w-full border-primary !py-1"
                            >
                                مشاهده سایت
                            </base-button>
                        </li>
                        <li class="relative px-1 grow sm:grow-0">
                            <base-button
                                type="link"
                                :to="{name: 'user.profile'}"
                                class="bg-primary border-primary w-full border-2 !py-1"
                            >
                                <span class="grow text-sm">اطلاعات حساب</span>
                            </base-button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</template>

<script setup>
import {ref, watchEffect} from "vue"
import {XMarkIcon, PowerIcon, BellIcon, Bars3BottomRightIcon} from "@heroicons/vue/24/outline/index.js";
import BasePopoverSide from "../base/BasePopoverSide.vue";
import {UserIcon} from "@heroicons/vue/24/solid/index.js";
import {useAdminStore, useUserStore} from "../../store/StoreUserAuth.js";
import {useResizeObserver} from "@vueuse/core";
import SidebarLinksUser from "./SidebarLinksUser.vue";
import BaseButton from "../base/BaseButton.vue";
import DialogSearch from "../DialogSearch.vue";
import NavbarCart from "../NavbarCart.vue";

const navbar = ref(null)

const scrollableSection = ref(null)
const topSection = ref(null)
const bottomSection = ref(null)
const sidebar = ref(null)

// const store = useUserStore()
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
        const th = topSection.value.offsetHeight
        const bh = bottomSection.value.offsetHeight
        scrollableSection.value.style.height = `calc(100vh - (${th}px) - (${bh}px))`
    }
}

defineExpose({
    navbar,
})
</script>

<style>

</style>
