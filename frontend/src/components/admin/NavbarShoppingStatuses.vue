<template>
  <base-popover class="sm:relative" panel-klass="right-0 w-full sm:mt-3 sm:w-[25rem]">
    <template #button>
      <button
          class="relative h-[40px] rounded-lg border-0 py-2 px-2 bg-transparent text-black hover:bg-slate-200 active:bg-slate-300 focus:bg-sky-200 transition-all flex justify-between items-center"
          type="button"
      >
        <ShoppingBagIcon class="h-6 w-6 "/>
        <ChevronDownIcon class="h-3 w-3 mr-1"/>

        <span v-if="countingOrderStore.hasNewChange"
              class="absolute rounded-full bg-sky-400 w-2 h-2 z-[1] -top-1 -right-1"></span>
      </button>
    </template>

    <template #panel>
      <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 p-2.5">
        <div class="h-full">
          <router-link
            v-for="badge in countingOrderStore.getCounts"
            :key="badge.code"
            :style="[
                  'background-color:' + badge.color_hex,
                   'color:' + getTextColor(badge.color_hex),
              ]"
            :to="{name: 'admin.orders', query: {badge_code: badge.code}}"
            class="flex flex-col w-full h-full justify-center text-center group px-3 py-1 shadow transition rounded-lg hover:opacity-90"
          >
            <span class="rounded text-xl group-hover:scale-90 transition">{{ numberFormat(badge.count) }}</span>
            <span class="text-xs group-hover:scale-90 transition">{{ badge.title }}</span>
          </router-link>
        </div>
      </div>
    </template>
  </base-popover>
</template>

<script setup>
import {inject} from "vue";
import {ChevronDownIcon} from "@heroicons/vue/24/solid/index.js";
import {ShoppingBagIcon} from "@heroicons/vue/24/outline/index.js";
import BasePopover from "@/components/base/BasePopover.vue";
import {getTextColor, numberFormat} from "@/composables/helper.js";

const countingOrderStore = inject('countingOrderStore')
</script>
