<template>
  <div class="h-[94px]">
    <div class="flex flex-col fixed right-0 left-0 top-0 bg-white w-full shadow-md z-10">
      <div class="h-[64px] layout-max-w mx-auto w-full">
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
                <navbar-search/>
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

      <div class="h-[30px] layout-max-w mx-auto w-full">
        <div class="flex gap-3 px-6 relative h-[24px]">
          <div class="ml-6">
            <navbar-categories/>
          </div>

          <partial-navbar-guest
            :is-loading="menuLoading"
            :menu="localMenu"
            class="hidden space-x-10 space-x-reverse lg:flex"
          />

          <div
            v-if="getSiteFirstPhone"
            class="mr-auto bg-teal-100 px-2 rounded-full"
          >
            <div class="flex items-center gap-2">
              <DevicePhoneMobileIcon class="size-6 text-emerald-500 animate-bounce"/>

              <div class="flex justify-center items-center gap-3">
                <a
                  :href="'tel:' + (getSiteFirstPhone?.phone)"
                  class="tracking-widest font-iranyekan-light"
                  v-html="obfuscateNumber(getSiteFirstPhone?.phone)"
                ></a>
                <span v-if="getSiteFirstPhone?.name"
                      class="text-emerald-500 text-xs whitespace-nowrap">{{ getSiteFirstPhone?.name }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import {computed, inject, onMounted, ref} from "vue";
import {DevicePhoneMobileIcon} from '@heroicons/vue/24/outline'
import NavbarCart from "./NavbarCart.vue"
import NavbarUserAction from "./NavbarUserAction.vue"
import PartialNavbarGuest from "./partials/PartialNavbarGuest.vue"
import DialogSearch from "./product/global-search/DialogSearch.vue";
import NavbarCategories from "./NavbarCategories.vue";
import PartialNavbarGuestMobile from "@/components/partials/PartialNavbarGuestMobile.vue";
import {HomeMainPageAPI} from "@/service/APIHomePages.js";
import {MENU_PLACES} from "@/composables/constants.js";
import {obfuscateNumber} from "@/composables/helper.js";
import NavbarSearch from "@/components/product/global-search/NavbarSearch.vue";

const homeSettingStore = inject('homeSettingStore')

const menuLoading = ref(true)
const localMenu = ref(null)

const getSiteFirstPhone = computed(() => {
  let phones = homeSettingStore.getPhones

  if (!Array.isArray(phones) || !phones.length) return null

  let first = phones[0]
  let splatted = first.split(' ')

  if (splatted?.length < 2) {
    return {
      phone: first
    }
  }

  return {
    phone: splatted[0],
    name: splatted.slice(1).join(' '),
  }
})

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
