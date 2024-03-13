<template>
  <div class="h-[94px]">
    <div class="flex flex-col fixed bg-white w-full shadow-md z-10">
      <div class="h-[64px]">
        <nav>
          <div class="h-[64px] py-2 px-6 flex">
            <div class="h-full shrink-0">
              <router-link :to="{name: 'home'}">
                <img
                    alt="لوگو"
                    class="h-[30px] sm:h-[36px] mt-[7px]"
                    src="/logo-with-type.png"
                >
              </router-link>
            </div>

            <div class="h-full grow">
              <div class="px-6 max-w-lg xl:max-w-2xl hidden md:block">
                <base-input
                    klass="bg-slate-100 focus:bg-white !ring-0 focus:!ring-2 rounded-xl"
                    name="search"
                    placeholder="جستجو..."
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
                  <partial-navbar-guest-mobile
                      :is-loading="menuLoading"
                      :menu="localMenu"
                  />
                </li>
              </ul>
            </div>
          </div>
        </nav>
      </div>

      <div class="h-[30px]">
        <div class="flex gap-3 px-6 relative h-[24px]">
          <div class="ml-6">
            <navbar-categories/>
          </div>

          <partial-navbar-guest
              :is-loading="menuLoading"
              :menu="localMenu"
              class="hidden space-x-10 space-x-reverse lg:flex"
          />

          <div class="mr-auto flex items-center gap-2 bg-indigo-50 px-3 rounded-full">
            <div class="flex items-center gap-2">
              <span class="text-slate-400 text-xs hidden sm:inline-block">قیمت</span>
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
import {onMounted, ref} from "vue";
import {MagnifyingGlassIcon} from '@heroicons/vue/24/outline'
import NavbarCart from "./NavbarCart.vue"
import NavbarUserAction from "./NavbarUserAction.vue"
import PartialNavbarGuest from "./partials/PartialNavbarGuest.vue"
import DialogSearch from "./DialogSearch.vue";
import BaseInput from "./base/BaseInput.vue";
import NavbarCategories from "./NavbarCategories.vue";
import PartialNavbarGuestMobile from "@/components/partials/PartialNavbarGuestMobile.vue";
import {HomeMainPageAPI} from "@/service/APIHomePages.js";
import {MENU_PLACES} from "@/composables/constants.js";

const menuLoading = ref(true)
const localMenu = ref(null)

onMounted(() => {
  HomeMainPageAPI.fetchMenu(MENU_PLACES.TOP_MENU.value, {
    success(response) {
      localMenu.value = response.data[0] || {}
      return false
    },
    error() {
      return false
    },
    finally() {
      menuLoading.value = false
    },
  })
})
</script>
