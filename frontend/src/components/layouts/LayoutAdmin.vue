<template>
  <div class="flex items-stretch relative">
    <div class="shrink-0 flex">
      <app-sidebar-admin/>
    </div>

    <div ref="pageContainer" class="grow flex flex-col overflow-auto">
      <app-navbar-admin ref="navbarCom"/>

      <div v-if="title" ref="extra" class="p-3 layout-max-w mx-auto w-full">
        <div class="rounded-t-lg bg-white px-3 py-2 border border-b border-b-emerald-300">
          <h1 class="flex items-center">
            <ArrowLeftCircleIcon class="h-5 w-5 ml-2 text-emerald-500"/>
            {{ title }}
          </h1>
        </div>
        <div class="rounded-b-lg bg-gray-50 px-3 py-1 border-x border-b">
          <ul class="flex flex-wrap text-amber-500 text-xs">
            <li>
              <router-link :to="{name: 'admin.home'}" class="text-black">
                <HomeIcon class="w-4 h-4"/>
              </router-link>
            </li>
            <li v-for="(crumb, idx) in breadcrumb" :key="idx">
              <ul class="flex">
                <li class="p-1 text-gray-300">
                  <ChevronLeftIcon class="h-3 w-3"/>
                </li>
                <li v-if="crumb.link">
                  <router-link :to="calcCrumbLink(crumb)">
                    {{ crumb.name }}
                  </router-link>
                </li>
                <li v-else class="text-slate-400">
                  {{ crumb.name }}
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>

      <div ref="page" class="px-3 pb-3 layout-max-w mx-auto w-full">
        <router-view v-slot="{ Component, route }">
          <PageTransition v-bind='transitionProps'>
            <div :key="route.path">
              <component :is="Component" :key="route.path"/>
            </div>
          </PageTransition>
        </router-view>
      </div>

      <div class="bg-gradient-to-t from-indigo-200">
        <div ref="footer" class="layout-max-w mx-auto w-full">
          <app-footer-admin/>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import {onBeforeMount, provide, ref, watch, watchEffect} from "vue"
import {useResizeObserver} from "@vueuse/core"
import {useRoute, useRouter} from "vue-router"
import {ArrowLeftCircleIcon, ChevronLeftIcon, HomeIcon} from '@heroicons/vue/24/outline'
import AppNavbarAdmin from "@/components/admin/AppNavbarAdmin.vue"
import AppFooterAdmin from "@/components/admin/AppFooterAdmin.vue"
import AppSidebarAdmin from "@/components/admin/AppSidebarAdmin.vue"
import {PageTransition} from "vue3-page-transition";
import {useCountingAlertsStore, useCountingOrdersStore, useNotificationStore} from "@/store/StoreAdminPanel.js";
import {useAdminAuthStore} from "@/store/StoreUserAuth.js";
import {usePageTransition} from "@/composables/page-transition.js";
import {useHead, useSeoMeta} from "@unhead/vue";
import {titleOperations} from "@/composables/helper.js";

const route = useRoute()
const router = useRouter()
const user = useAdminAuthStore()

const transitionProps = usePageTransition()

//--------------------------------------
const countingAlertStore = useCountingAlertsStore();
provide('countingAlertStore', countingAlertStore);

const countingOrderStore = useCountingOrdersStore();
provide('countingOrderStore', countingOrderStore);

const notificationStore = useNotificationStore();
provide('notificationStore', notificationStore);

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
  title.value = (to.meta?.titleAppearance !== false && to.meta?.title) ? to.meta?.title : null
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

function calcCrumbLink(crumb) {
  const obj = {name: crumb.link}

  const params = {}
  if (crumb.params) {
    for (const i of crumb.params) {
      if (route.params[i]) {
        params[i] = route.params[i]
      }
    }

    obj.params = params
  }

  return obj;
}

onBeforeMount(() => {
  if (!user.getUser) {
    user.$reset()
    router.push({name: 'admin.login'})
  }
})
</script>
