<template>
    <base-popover class="sm:relative" panel-klass="right-0 w-full sm:mt-3 sm:w-80">
        <template #button>
            <button type="button"
                    class="relative h-[40px] rounded-lg border-0 py-2 px-2 bg-transparent text-black hover:bg-slate-200 active:bg-slate-300 focus:bg-sky-200 transition-all flex justify-between items-center">
                <BellAlertIcon class="h-6 w-6 "/>
                <ChevronDownIcon class="h-3 w-3 mr-1"/>

                <span
                    class="absolute rounded-full bg-orange-400 w-2 h-2 z-[1] -top-1 -right-1"></span>
            </button>
        </template>

        <template #panel>
            <ul class="divide-y divide-gray-100 py-2">
                <li class="py-2 px-4 flex flex-col">
                    <router-link to="#"
                                 class="flex justify-between items-center group px-4 py-3 hover:bg-indigo-50 transition rounded-lg">
                        <span>دیدگاه شما</span>
                        <span class="w-6 h-6 rounded bg-teal-500 text-white px-1 text-center mr-2">5</span>
                    </router-link>
                </li>
                <li class="py-2 px-4 flex flex-col">
                    <router-link to="#"
                                 class="flex justify-between items-center group px-4 py-3 hover:bg-indigo-50 transition rounded-lg">
                        <span>درخواست مرجوعی کالا</span>
                        <span class="w-6 h-6 rounded bg-pink-500 text-white px-1 text-center mr-2">2</span>
                    </router-link>
                </li>
                <li class="py-2 px-4 flex flex-col">
                    <router-link to="#"
                                 class="flex justify-between items-center group px-4 py-3 hover:bg-indigo-50 transition rounded-lg">
                        <span>تماس با ما</span>
                        <span class="w-6 h-6 rounded bg-cyan-500 text-white px-1 text-center mr-2">9</span>
                    </router-link>
                </li>
                <li class="py-2 px-4 flex flex-col">
                    <router-link to="#"
                                 class="flex justify-between items-center group px-4 py-3 hover:bg-indigo-50 transition rounded-lg">
                        <span>شکایات</span>
                        <span class="w-6 h-6 rounded bg-rose-500 text-white px-1 text-center mr-2">0</span>
                    </router-link>
                </li>
            </ul>
        </template>
    </base-popover>
</template>

<script setup>
import {ChevronDownIcon} from "@heroicons/vue/24/solid/index.js";
import BasePopover from "../base/BasePopover.vue";
import {BellAlertIcon} from "@heroicons/vue/24/outline/index.js";
</script>

<style scoped>

</style>
