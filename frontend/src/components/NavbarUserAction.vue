<template>
  <BaseMenu :open="open" @open="() => emit('open')"
            btnClass="relative h-[40px] rounded-lg border-0 py-2 px-2 bg-transparent text-black hover:bg-slate-200 active:bg-slate-300 focus:bg-sky-200 transition-all flex justify-between items-center">
    <template #button>
      <UserIcon class="h-6 w-6"/>
      <ChevronDownIcon class="h-3 w-3 mr-1"/>
    </template>
    <template #items>
      <MenuItems
        class="absolute z-[10] left-0 mt-3 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
        <div class="px-1 py-1">
          <MenuItem v-slot="{ active }">
            <router-link :to="{name: 'login'}"
                         :class="[
                                         active ? 'bg-primary text-white' : 'text-gray-900',
                                        'flex w-full items-center rounded-md px-2 py-2 text-sm transition',
                                     ]"
            >
              <ArrowLeftOnRectangleIcon
                :active="active"
                class="ml-2 h-5 w-5 text-sky-400"
                aria-hidden="true"
              />
              ورود
            </router-link>
          </MenuItem>
        </div>

        <div class="px-1 py-1">
          <MenuItem v-slot="{ active }">
            <router-link :to="{name: 'signup'}"
                         :class="[
                                         active ? 'bg-primary text-white' : 'text-gray-900',
                                        'flex w-full items-center rounded-md px-2 py-2 text-sm transition',
                                     ]"
            >
              <UserPlusIcon
                :active="active"
                class="ml-2 h-5 w-5 text-sky-400"
                aria-hidden="true"
              />
              ثبت نام
            </router-link>
          </MenuItem>
        </div>
      </MenuItems>
    </template>
  </BaseMenu>
</template>

<script setup>
import {MenuItems, MenuItem} from '@headlessui/vue'
import {ChevronDownIcon} from '@heroicons/vue/24/solid'
import {UserIcon, UserPlusIcon, ArrowLeftOnRectangleIcon} from '@heroicons/vue/24/outline'
import BaseMenu from "./base/BaseMenu.vue"

defineProps({
  open: {
    type: Boolean,
    default: false,
  },
})
const emit = defineEmits(['open'])
</script>
