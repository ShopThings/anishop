<template>
    <div class="h-[64px]">
        <nav class="fixed bg-white w-full shadow-md z-10">
            <div class="md:container md:mx-auto">
                <div class="h-[64px] py-2 px-6 flex">
                    <div class="h-full">
                        <router-link :to="{name: 'home'}">
                            <img class="h-[28px] mt-[10px]"
                                 src="/logo-with-type.png"
                                 alt="لوگو">
                        </router-link>
                    </div>

                    <div class="h-full grow">
                        <partial-navbar-guest
                            class="hidden justify-center space-x-10 space-x-reverse mt-[10px] lg:flex"/>
                    </div>

                    <div class="h-full">
                        <ul class="flex justify-center mt-[4px] space-x-reverse">
                            <li class="px-2">
                                <dialog-search/>
                            </li>
                            <li class="sm:relative px-2">
                                <navbar-cart/>
                            </li>
                            <li class="hidden relative px-2 lg:inline-block">
                                <navbar-user-action/>
                            </li>
                            <li class="px-2 block lg:hidden">
                                <base-popover-side>
                                    <template #button>
                                        <button type="button"
                                                class="relative w-[45] h-[45] border-0 py-2 px-2 bg-transparent text-black hover:bg-gray-100 rounded-lg active:bg-gray-200 focus:bg-sky-50 transition-all flex justify-between items-center">
                                            <Bars3Icon class="h-6 w-6"/>
                                        </button>
                                    </template>
                                    <template #panel="{close}">
                                        <div class="mb-3 flex items-center">
                                    <span class="ml-auto text-sm text-gray-400">
                                        منو
                                    </span>
                                            <button @click="close" type="button"
                                                    class="w-[45] h-[45] border-0 py-2 px-2 bg-transparent text-black hover:bg-gray-100 rounded-lg active:bg-gray-200 focus:bg-sky-50 transition-all">
                                                <XMarkIcon class="h-6 w-6"/>
                                            </button>
                                        </div>

                                        <div class="flex mb-3 border-y py-3">
                                            <router-link :to="{name: 'login'}"
                                                         class="flex w-full items-center rounded-md px-2 py-2 text-sm text-gray-900 hover:bg-primary hover:text-white transition">
                                                <ArrowLeftOnRectangleIcon class="h-6 w-6 text-sky-400 ml-2"/>
                                                ورود
                                            </router-link>
                                            <router-link :to="{name: 'signup'}"
                                                         class="flex w-full items-center rounded-md px-2 py-2 text-sm text-gray-900 hover:bg-primary hover:text-white mr-2 transition">
                                                <UserPlusIcon class="h-6 w-6 text-sky-400 ml-2"/>
                                                ثبت نام
                                            </router-link>
                                        </div>

                                        <partial-navbar-guest class="justify-center space-y-5 w-full space-x-reverse"/>
                                    </template>
                                </base-popover-side>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</template>

<script setup>
import {XMarkIcon, Bars3Icon} from '@heroicons/vue/24/solid'
import {ArrowLeftOnRectangleIcon, UserPlusIcon} from '@heroicons/vue/24/outline'
import NavbarCart from "./NavbarCart.vue"
import NavbarUserAction from "./NavbarUserAction.vue"
import DialogSearch from "./DialogSearch.vue"
import PartialNavbarGuest from "./partials/PartialNavbarGuest.vue"
import BasePopoverSide from "./base/BasePopoverSide.vue"
</script>

<style scoped>

</style>
