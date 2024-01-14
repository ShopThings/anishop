<template>
  <base-popover class="sm:relative" panel-klass="right-0 w-full sm:mt-3 sm:w-[25rem]">
    <template #button>
      <button type="button"
              class="relative h-[40px] rounded-lg border-0 py-2 px-2 bg-transparent text-black hover:bg-slate-200 active:bg-slate-300 focus:bg-sky-200 transition-all flex justify-between items-center">
        <ShoppingBagIcon class="h-6 w-6 "/>
        <ChevronDownIcon class="h-3 w-3 mr-1"/>

        <span
          class="absolute rounded-full bg-sky-400 w-2 h-2 z-[1] -top-1 -right-1"></span>
      </button>
    </template>

    <template #panel>
      <div class="py-2 flex flex-col">
        <div class="flex justify-center px-3">
          <div class="py-1 px-1 flex grow">
            <router-link to="#"
                         class="flex flex-col w-full justify-center text-center group px-3 py-1 shadow transition rounded-lg hover:opacity-90"
                         style="background-color: #16b3ff; color: #ffffff">
                        <span
                          class="rounded text-xl group-hover:scale-90 transition">5</span>
              <span class="text-xs group-hover:scale-90 transition">در صف بررسی</span>
            </router-link>
          </div>
          <div class="py-1 px-1 flex grow">
            <router-link to="#"
                         class="flex flex-col w-full justify-center text-center group px-3 py-1 shadow transition rounded-lg hover:opacity-90"
                         style="background-color: #f7a56d; color: #000000">
                        <span
                          class="rounded text-xl group-hover:scale-90 transition">2</span>
              <span class="text-xs group-hover:scale-90 transition">مرجوع شده</span>
            </router-link>
          </div>
          <div class="py-1 px-1 flex grow">
            <router-link to="#"
                         class="flex flex-col w-full justify-center text-center group px-3 py-1 shadow transition rounded-lg hover:opacity-90"
                         style="background-color: #237b00; color: #ffffff">
                        <span
                          class="rounded text-xl group-hover:scale-90 transition">45</span>
              <span class="text-xs group-hover:scale-90 transition">تحویل به مشتری</span>
            </router-link>
          </div>
        </div>

        <div class="flex justify-center px-3">
          <div class="py-1 px-1 flex grow">
            <router-link to="#"
                         class="flex flex-col w-full justify-center text-center group px-3 py-1 shadow transition rounded-lg hover:opacity-90"
                         style="background-color: #f3f436; color: #000000">
                        <span
                          class="rounded text-xl group-hover:scale-90 transition">2</span>
              <span class="text-xs group-hover:scale-90 transition">تحویل به پست</span>
            </router-link>
          </div>
          <div class="py-1 px-1 flex grow">
            <router-link to="#"
                         class="flex flex-col w-full justify-center text-center group px-3 py-1 shadow transition rounded-lg hover:opacity-90"
                         style="background-color: #ffffff; color: #000000">
                        <span
                          class="rounded text-xl group-hover:scale-90 transition">5</span>
              <span class="text-xs group-hover:scale-90 transition">خروج از انبار</span>
            </router-link>
          </div>
          <div class="py-1 px-1 flex grow">
            <router-link to="#"
                         class="flex flex-col w-full justify-center text-center group px-3 py-1 shadow transition rounded-lg hover:opacity-90"
                         style="background-color: #004b86; color: #ffffff">
                        <span
                          class="rounded text-xl group-hover:scale-90 transition">6</span>
              <span class="text-xs group-hover:scale-90 transition">آماده‌سازی سفارش</span>
            </router-link>
          </div>
        </div>

        <div class="flex justify-center px-3">
          <div class="py-1 px-1 flex grow">
            <router-link to="#"
                         class="flex flex-col w-full justify-center text-center group px-3 py-1 shadow transition rounded-lg hover:opacity-90"
                         style="background-color: #F44336; color: #ffffff">
                        <span
                          class="rounded text-xl group-hover:scale-90 transition">8</span>
              <span class="text-xs group-hover:scale-90 transition">لغو شده</span>
            </router-link>
          </div>
          <div class="py-1 px-1 flex grow">
            <router-link to="#"
                         class="flex flex-col w-full justify-center text-center group px-3 py-1 shadow transition rounded-lg hover:opacity-90"
                         style="background-color: #000000; color: #ffffff">
                        <span
                          class="rounded text-xl group-hover:scale-90 transition">12</span>
              <span class="text-xs group-hover:scale-90 transition">تایید نشده</span>
            </router-link>
          </div>
          <div class="py-1 px-1 flex grow">
            <router-link to="#"
                         class="flex flex-col w-full justify-center text-center group px-3 py-1 shadow transition rounded-lg hover:opacity-90"
                         style="background-color: #f3cd21; color: #ffffff">
                        <span
                          class="rounded text-xl group-hover:scale-90 transition">0</span>
              <span class="text-xs group-hover:scale-90 transition">تحویل به باربری خصوصی</span>
            </router-link>
          </div>
        </div>
      </div>
    </template>
  </base-popover>
</template>

<script setup>
import {ChevronDownIcon} from "@heroicons/vue/24/solid/index.js";
import {ShoppingBagIcon} from "@heroicons/vue/24/outline/index.js";
import BasePopover from "@/components/base/BasePopover.vue";
</script>
