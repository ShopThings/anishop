<template>
    <div class="h-[94px]">
        <div class="flex flex-col fixed bg-white w-full shadow-md z-10">
            <div class="h-[64px]">
                <nav>
                    <div class="h-[64px] py-2 px-6 flex">
                        <div class="h-full shrink-0">
                            <router-link :to="{name: 'home'}">
                                <img class="h-[30px] sm:h-[36px] mt-[7px]"
                                     src="/logo-with-type.png"
                                     alt="لوگو">
                            </router-link>
                        </div>

                        <div class="h-full grow">
                            <div class="px-6 max-w-lg xl:max-w-2xl hidden md:block">
                                <base-input
                                    name="search"
                                    placeholder="جستجو..."
                                    klass="bg-slate-100 focus:bg-white !ring-0 focus:!ring-2 rounded-xl"
                                >
                                    <template #icon>
                                        <MagnifyingGlassIcon class="w-6 h-6 text-slate-400"/>
                                    </template>
                                </base-input>
                            </div>
                        </div>

                        <div class="h-full">
                            <ul class="flex justify-center mt-[4px] space-x-reverse">
                                <li class="px-1 md:hidden">
                                    <dialog-search/>
                                </li>
                                <li class="sm:relative px-1">
                                    <navbar-cart/>
                                </li>
                                <li class="hidden relative px-1 lg:inline-block">
                                    <navbar-user-action/>
                                </li>
                                <li class="px-1 sm:px-2 block lg:hidden">
                                    <base-popover-side>
                                        <template #button>
                                            <button type="button"
                                                    class="relative w-[40px] h-[40px] border-0 py-2 px-2 bg-transparent text-black rounded-lg hover:bg-slate-200 active:bg-slate-300 focus:bg-sky-200 transition-all flex justify-between items-center">
                                                <Bars3Icon class="h-6 w-6"/>
                                            </button>
                                        </template>
                                        <template #panel="{close}">
                                            <div class="mb-3 flex items-center">
                                            <span class="ml-auto text-sm text-gray-400">
                                                منو
                                            </span>
                                                <button @click="close" type="button"
                                                        class="w-[40px] h-[40px] border-0 py-2 px-2 bg-transparent text-black rounded-lg hover:bg-slate-200 active:bg-slate-300 focus:bg-sky-200 transition-all">
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

                                            <partial-navbar-guest
                                                class="justify-center space-y-5 w-full space-x-reverse"/>
                                        </template>
                                    </base-popover-side>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>

            <div class="h-[30px]">
                <div class="flex gap-3 px-6 relative h-[24px]">
                    <div class="ml-6">
                        <navbar-categories />
                    </div>

                    <partial-navbar-guest
                        class="hidden space-x-10 space-x-reverse lg:flex"
                    />

                    <div class="mr-auto flex items-center gap-2 bg-indigo-50 px-3 rounded-full">
                        <div class="flex items-center gap-2">
                            <span class="text-slate-400 text-xs hidden sm:inline-block">قیمت لحظه‌ای</span>
                            <span class="iranyekan-bold text-sm">دلار</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-iranyekan-bold">52,000</span>
                            <span class="text-xs text-slate-400">تومان</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {XMarkIcon, Bars3Icon} from '@heroicons/vue/24/solid'
import {ArrowLeftOnRectangleIcon, UserPlusIcon, MagnifyingGlassIcon} from '@heroicons/vue/24/outline'
import NavbarCart from "./NavbarCart.vue"
import NavbarUserAction from "./NavbarUserAction.vue"
import PartialNavbarGuest from "./partials/PartialNavbarGuest.vue"
import BasePopoverSide from "./base/BasePopoverSide.vue"
import DialogSearch from "./DialogSearch.vue";
import BaseInput from "./base/BaseInput.vue";
import NavbarCategories from "./NavbarCategories.vue";
</script>

<style scoped>

</style>
