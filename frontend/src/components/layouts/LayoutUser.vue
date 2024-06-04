<template>
  <div class="flex items-stretch relative">
    <div class="shrink-0 flex">
      <app-sidebar-user/>
    </div>

    <div ref="pageContainer" class="grow flex flex-col overflow-auto">
      <app-navbar-user ref="navbarCom"/>

      <div v-if="title" ref="extra" class="p-3 max-w-7xl mx-auto w-full">
        <h1 class="flex items-center text-indigo-700">
          {{ title }}
        </h1>
      </div>

      <div ref="page" class="px-3 pb-3 max-w-7xl mx-auto w-full">
        <router-view v-slot="{ Component, route }">
          <PageTransition v-bind='transitionProps'>
            <div :key="route.path">
              <component :is="Component" :key="route.path"/>
            </div>
          </PageTransition>
        </router-view>
      </div>

      <div ref="footer" class="max-w-7xl mx-auto w-full">
        <app-footer-user/>
      </div>
    </div>
  </div>
</template>

<script setup>
import {provide, ref, watch, watchEffect} from "vue";
import {useRoute} from "vue-router";
import AppFooterUser from "@/components/user/AppFooterUser.vue";
import {useResizeObserver} from "@vueuse/core";
import AppNavbarUser from "@/components/user/AppNavbarUser.vue";
import AppSidebarUser from "@/components/user/AppSidebarUser.vue";
import {PageTransition} from "vue3-page-transition";
import {useCountingStuffsStore, useNotificationStore} from "@/store/StoreUserPanel.js";
import {useHomeSettingsStore} from "@/store/StoreSettings.js";
import {usePageTransition} from "@/composables/page-transition.js";
import {useHead, useSeoMeta} from "@unhead/vue";
import {titleOperations} from "@/composables/helper.js";

const route = useRoute()
const transitionProps = usePageTransition()

//--------------------------------------
const countingStore = useCountingStuffsStore();
provide('countingStore', countingStore);

const notificationStore = useNotificationStore();
provide('notificationStore', notificationStore);

const homeSettingStore = useHomeSettingsStore()
provide('homeSettingStore', homeSettingStore);

//--------------------------------------
const headTitle = ref(null)

useHead({
  meta: [
    {name: 'robots', content: 'noindex'},
  ],
})
useSeoMeta({
  title: headTitle,
})

//--------------------------------------
let title = ref(null)
let breadcrumb = ref(null)

const pageContainer = ref(null)
const page = ref(null)
const footer = ref(null)
const navbarCom = ref(null)
const navbar = ref(null)
const extra = ref(null)

useResizeObserver(pageContainer, () => {
  const nh = navbar.value?.offsetHeight || 0
  const fh = footer.value?.offsetHeight || 0
  const eh = extra.value?.offsetHeight || 0

  page.value.style.minHeight = `calc(100vh - ${nh}px - ${fh}px - ${eh}px - 1px)`
})

watchEffect(() => {
  navbar.value = navbarCom.value?.navbar
})

watch(route, (to) => {
  title.value = to.meta?.title || null
  breadcrumb.value = to.meta?.breadcrumb || []

  // Create a title from breadcrumb to set as title of page
  let assembledTitle = [];
  breadcrumb.value.forEach((item) => {
    if (item.name) {
      assembledTitle.push(item.name)
    }
  })

  if (assembledTitle.length) {
    headTitle.value = titleOperations.join(assembledTitle)
  } else {
    headTitle.value = to.meta?.title
  }
}, {flush: 'pre', immediate: true, deep: true})
</script>
