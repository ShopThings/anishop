<template>
  <BaseMenu
    :open="open"
    btnClass="relative h-[40px] rounded-lg border-0 py-2 px-2 bg-transparent text-black hover:bg-slate-200 active:bg-slate-300 focus:bg-sky-200 transition-all flex justify-between items-center"
    @open="() => emit('open')"
  >
    <template #button>
      <UserIcon class="h-6 w-6"/>

      <span class="mr-1 text-xs text-gray-400 hidden lg:inline-block">سلام، </span>
      <span class="mr-1 text-sm hidden lg:inline-block">
        <template v-if="user?.first_name">
            {{ user?.first_name }}
        </template>
        <template v-else>
            کاربر گرامی
        </template>
      </span>
      <ChevronDownIcon class="h-3 w-3 mr-1"/>
    </template>
    <template #items>
      <MenuItems
          class="absolute z-[10] left-0 mt-3 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
        <div class="px-1 py-1">
          <MenuItem v-slot="{ active }">
            <router-link
              :class="[
                    active ? 'bg-primary text-white' : 'text-gray-900',
                   'flex w-full items-center rounded-md px-2 py-2 text-sm transition',
                ]"
              :to="{name: 'admin.user.profile', params: {id: user?.id}}"
            >
              <IdentificationIcon
                  :active="active"
                  aria-hidden="true"
                  class="ml-2 h-5 w-5 text-sky-400"
              />
              تغییر مشخصات
            </router-link>
          </MenuItem>
        </div>

        <div
          v-if="store.hasPermission(PERMISSION_PLACES.SETTING, PERMISSIONS.UPDATE)"
          class="px-1 py-1"
        >
          <MenuItem v-slot="{ active }">
            <router-link
              :class="[
                    active ? 'bg-primary text-white' : 'text-gray-900',
                   'flex w-full items-center rounded-md px-2 py-2 text-sm transition',
                ]"
              :to="{name: 'admin.settings'}"
            >
              <Cog6ToothIcon
                  :active="active"
                  aria-hidden="true"
                  class="ml-2 h-5 w-5 text-sky-400"
              />
              تنظیمات سایت
            </router-link>
          </MenuItem>
        </div>

        <div class="px-1 py-1">
          <MenuItem v-slot="{ active }">
            <router-link
              :class="[
                    active ? 'bg-primary text-white' : 'text-gray-900',
                   'flex w-full items-center rounded-md px-2 py-2 text-sm transition',
                ]"
              :to="{name: 'admin.logout'}"
            >
              <PowerIcon
                  :active="active"
                  aria-hidden="true"
                  class="ml-2 h-5 w-5 text-sky-400"
              />
              خروج
            </router-link>
          </MenuItem>
        </div>
      </MenuItems>
    </template>
  </BaseMenu>
</template>

<script setup>
import {MenuItem, MenuItems} from '@headlessui/vue'
import {ChevronDownIcon} from '@heroicons/vue/24/solid'
import {Cog6ToothIcon, IdentificationIcon, PowerIcon, UserIcon} from '@heroicons/vue/24/outline'
import BaseMenu from "@/components/base/BaseMenu.vue"
import {PERMISSION_PLACES, PERMISSIONS, useAdminAuthStore} from "@/store/StoreUserAuth.js";

defineProps({
  open: {
    type: Boolean,
    default: false,
  },
})
const emit = defineEmits(['open'])

const store = useAdminAuthStore()
const user = store.getUser
</script>
