<template>
    <div class="h-[64px]">
        <nav class="fixed bg-white w-full shadow-md z-10">
            <div class="md:container mx-auto">
                <div class="h-[64px] py-2 px-6 flex">
                    <div class="h-full shrink-0">
                        <router-link :to="{name: 'home'}">
                            <img class="h-[32px] mt-[10px]"
                                 src="/logo-with-type.png"
                                 alt="لوگو">
                        </router-link>
                    </div>

                    <div class="h-full grow">
                        <partial-navbar-blog
                            class="hidden justify-center space-x-10 space-x-reverse mt-[10px] lg:flex"/>
                    </div>

                    <div class="h-full">
                        <ul class="flex justify-center mt-[4px] space-x-reverse">
                            <li class="px-1 sm:px-2">
                                <dialog-search-blog/>
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

                                        <div class="mb-3 py-3">
                                            <base-button
                                                type="link"
                                                :to="{name: 'home'}"
                                                class="w-full border-2 border-indigo-500 text-sm !text-black hover:bg-indigo-100 transition flex items-center gap-3 justify-center"
                                            >
                                                <span class="mx-auto">مشاهده فروشگاه</span>
                                                <ShoppingCartIcon class="w-6 h-6 text-indigo-400"/>
                                            </base-button>
                                        </div>

                                        <partial-navbar-blog class="justify-center space-y-5 w-full space-x-reverse"/>
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
import PartialNavbarBlog from "./partials/PartialNavbarBlog.vue";
import DialogSearchBlog from "./DialogSearchBlog.vue";
import {Bars3Icon, XMarkIcon} from "@heroicons/vue/24/solid/index.js";
import {ShoppingCartIcon} from "@heroicons/vue/24/outline/index.js";
import BasePopoverSide from "./base/BasePopoverSide.vue";
import BaseButton from "./base/BaseButton.vue";
</script>

<style scoped>

</style>
