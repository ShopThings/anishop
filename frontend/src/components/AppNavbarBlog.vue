<template>
  <div class="h-[64px]">
    <nav class="fixed right-0 left-0 top-0 bg-white w-full shadow-md z-10">
      <div class="layout-max-w mx-auto w-full">
        <div class="h-[64px] py-2 px-6 flex">
          <div class="h-full shrink-0">
            <router-link :to="{name: 'home'}">
              <img
                  alt="لوگو"
                  class="h-[32px] mt-[10px]"
                  src="/logo-with-type.png"
              >
            </router-link>
          </div>

          <div class="h-full grow">
            <partial-navbar-blog
                :is-loading="menuLoading"
                :menu="localMenu"
                class="hidden justify-center space-x-10 space-x-reverse mt-[10px] lg:flex"
            />
          </div>

          <div class="h-full">
            <ul class="flex justify-center mt-[4px] space-x-reverse">
              <li class="px-1 sm:px-2">
                <dialog-search-blog/>
              </li>
              <li class="hidden relative px-1 lg:inline-block">
                <navbar-user-action/>
              </li>

              <li class="px-1 sm:px-2 block lg:hidden">
                <partial-navbar-blog-mobile
                    :is-loading="menuLoading"
                    :menu="localMenu"
                />
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
  </div>
</template>

<script setup>
import {onMounted, ref} from "vue";
import PartialNavbarBlog from "./partials/PartialNavbarBlog.vue";
import DialogSearchBlog from "./blog/global-search/DialogSearchBlog.vue";
import PartialNavbarBlogMobile from "@/components/partials/PartialNavbarBlogMobile.vue";
import {HomeMainPageAPI} from "@/service/APIHomePages.js";
import {MENU_PLACES} from "@/composables/constants.js";
import NavbarUserAction from "@/components/NavbarUserAction.vue";

const menuLoading = ref(true)
const localMenu = ref(null)

onMounted(() => {
  HomeMainPageAPI.fetchMenu(MENU_PLACES.TOP_MENU_BLOG.value, {
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
