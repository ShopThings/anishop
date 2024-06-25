<template>
  <BaseMenu
    :open="open"
    btnClass="relative h-[40px] rounded-lg border-0 py-2 px-2 bg-transparent text-black hover:bg-slate-200 active:bg-slate-300 focus:bg-sky-200 transition-all flex justify-between items-center"
    @open="() => emit('open')"
  >
    <template #button>
      <UserIcon class="h-6 w-6"/>
      <ChevronDownIcon class="h-3 w-3 mr-1"/>
    </template>
    <template #items>
      <MenuItems
        class="absolute z-[10] left-0 mt-3 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
      >
        <template v-if="userStore.getUser">
          <div class="px-1 py-1">
            <MenuItem v-slot="{ active }">
              <router-link
                :class="[
                   active ? 'bg-primary text-white' : 'text-gray-900',
                   'flex w-full items-center rounded-md px-2 py-2 text-sm transition',
                ]"
                :to="{name: 'user.home'}"
              >
                <WindowIcon
                  :active="active"
                  aria-hidden="true"
                  class="ml-2 h-5 w-5 text-sky-400"
                />
                پیشخوان
              </router-link>
            </MenuItem>
          </div>

          <div class="px-1 py-1">
            <MenuItem v-slot="{ active, close }">
              <button
                :class="[
                   active ? 'bg-primary text-white' : 'text-gray-900',
                   'flex w-full items-center rounded-md px-2 py-2 text-sm transition',
                ]"
                type="button"
                @click="() => {close(); router.push({name: 'user.logout'});}"
              >
                <PowerIcon
                  :active="active"
                  aria-hidden="true"
                  class="ml-2 h-5 w-5 text-sky-400"
                />
                خروج
              </button>
            </MenuItem>
          </div>
        </template>
        <template v-else>
          <div class="px-1 py-1">
            <MenuItem v-slot="{ active }">
              <router-link
                :class="[
                   active ? 'bg-primary text-white' : 'text-gray-900',
                   'flex w-full items-center rounded-md px-2 py-2 text-sm transition',
                ]"
                :to="{name: 'login'}"
              >
                <ArrowLeftEndOnRectangleIcon
                  :active="active"
                  aria-hidden="true"
                  class="ml-2 h-5 w-5 text-sky-400"
                />
                ورود
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
                :to="{name: 'signup'}"
              >
                <UserPlusIcon
                  :active="active"
                  aria-hidden="true"
                  class="ml-2 h-5 w-5 text-sky-400"
                />
                ثبت نام
              </router-link>
            </MenuItem>
          </div>
        </template>
      </MenuItems>
    </template>
  </BaseMenu>
</template>

<script setup>
import {MenuItem, MenuItems} from '@headlessui/vue'
import {ChevronDownIcon} from '@heroicons/vue/24/solid'
import {ArrowLeftEndOnRectangleIcon, PowerIcon, UserIcon, UserPlusIcon, WindowIcon,} from '@heroicons/vue/24/outline'
import BaseMenu from "./base/BaseMenu.vue"
import {useUserAuthStore} from "@/store/StoreUserAuth.js";
import {useRouter} from "vue-router";

defineProps({
  open: {
    type: Boolean,
    default: false,
  },
})
const emit = defineEmits(['open'])

const router = useRouter()
const userStore = useUserAuthStore()
</script>
