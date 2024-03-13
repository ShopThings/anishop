<template>
  <base-sidebar
      ref="sidebar"
      :mini="false"
      bg="bg-awesome text-black"
  >
    <div
        ref="topSection"
        class="flex flex-col"
    >
      <div class="flex flex-row items-center gap-3 border-b-2 border-slate-200 py-3 px-2">
        <div class="text-center w-12 h-12 rounded-full bg-indigo-400 p-1 bg-opacity-90">
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
            <partial-username-label v-if="user" :user="user"/>
          </div>
          <div class="mt-1 text-xs opacity-80">
            <template v-if="user.roles">
              <span v-for="(role, key, idx) in user.roles">
                  {{ role }}
                  <span v-if="idx !== Object.keys(user.roles).length - 1">, </span>
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
              v-tooltip.left="'اعلانات'"
              :to="{name: 'user.notifications'}"
              class="relative w-[40] h-[40] border-0 !px-2 rounded-lg bg-transparent !text-black hover:bg-slate-200 active:bg-slate-300 focus:bg-sky-200 transition-all"
              type="link"
          >
            <template v-if="notificationsStore.hasNewNotification">
              <BellAlertIcon class="h-5 w-5 animate-wiggle"/>
              <span
                  class="absolute rounded-full bg-sky-500 text-white w-1.5 h-1.5 z-[1] top-0.5 right-0.5 text-sm"></span>
            </template>
            <BellIcon v-else class="h-5 w-5"/>
          </base-button>
        </div>
      </div>
    </div>

    <div
        ref="scrollableSection"
        class="my-custom-scrollbar p-3 text-sm"
    >
      <sidebar-links-user/>
    </div>

    <div ref="bottomSection" class="flex flex-col py-2 px-3 bg-gradient-to-b from-[#ffffff0f]">
      <ul class="flex flex-col">
        <li>
          <router-link
              :to="{name: 'user.logout'}"
              class="rounded-lg border-2 border-transparent py-2.5 px-3 flex hover:border-indigo-400 hover:bg-indigo-100 transition"
          >
            <PowerIcon class="h-6 w-6 ml-2 shrink-0"/>
            <span class="grow">خروج</span>
          </router-link>
        </li>
      </ul>
    </div>
  </base-sidebar>
</template>

<script setup>
import {inject, ref, watchEffect} from "vue"
import {useResizeObserver} from "@vueuse/core"
import {UserIcon} from '@heroicons/vue/24/solid'
import {PowerIcon} from '@heroicons/vue/24/outline'
import BaseSidebar from "@/components/base/BaseSidebar.vue"
import {useUserAuthStore} from "@/store/StoreUserAuth.js";
import SidebarLinksUser from "./SidebarLinksUser.vue";
import BaseButton from "@/components/base/BaseButton.vue";
import {BellAlertIcon, BellIcon} from "@heroicons/vue/24/outline/index.js";
import PartialUsernameLabel from "@/components/partials/PartialUsernameLabel.vue";

const notificationsStore = inject('notificationStore')

const scrollableSection = ref(null)
const topSection = ref(null)
const bottomSection = ref(null)
const sidebar = ref(null)

const store = useUserAuthStore()
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
  if (topSection.value && bottomSection.value && scrollableSection.value) {
    const th = Math.floor(topSection.value.offsetHeight)
    const bh = Math.floor(bottomSection.value.offsetHeight)
    scrollableSection.value.style.height = `calc(100vh - (${th}px) - (${bh}px))`
  }
}

defineExpose({
  sidebar,
  setHeight,
})
</script>
