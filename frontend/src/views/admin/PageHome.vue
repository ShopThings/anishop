<template>
  <div class="flex flex-col sm:flex-row items-center mt-3">
    <div
      class="flex items-center justify-center gap-1.5 w-full sm:w-auto min-h-10 py-1 px-2 rounded-t-md sm:rounded-tl-none sm:rounded-r-md bg-blue-950 text-white border-2 border-indigo-400">
      <span>{{ date }}</span>
      <span>-</span>
      <span class="font-sans" dir="ltr">{{ enDate }}</span>
    </div>
    <div
      class="flex items-center justify-center w-full sm:w-auto min-h-10 py-1 px-2 rounded-b-md sm:rounded-br-none sm:rounded-l-md bg-blue-950 text-white border-2 border-indigo-400 min-w-28 text-center"
      dir="ltr"
    >
      {{ time }}
    </div>
  </div>

  <div
    v-if="userStore.hasAnyRole([ROLES.DEVELOPER, ROLES.SUPER_ADMIN]) ||
    userStore.hasPermission(PERMISSION_PLACES.ORDER, PERMISSIONS.READ)"
    class="mt-3"
  >
    <sale-info/>
  </div>

  <div
    v-if="userStore.hasPermission(PERMISSION_PLACES.USER, PERMISSIONS.READ) ||
          userStore.hasPermission(PERMISSION_PLACES.COLOR, PERMISSIONS.READ) ||
          userStore.hasPermission(PERMISSION_PLACES.BRAND, PERMISSIONS.READ) ||
          userStore.hasPermission(PERMISSION_PLACES.FESTIVAL, PERMISSIONS.READ) ||
          userStore.hasPermission(PERMISSION_PLACES.CATEGORY, PERMISSIONS.READ) ||
          userStore.hasPermission(PERMISSION_PLACES.PRODUCT, PERMISSIONS.READ) ||
          userStore.hasPermission(PERMISSION_PLACES.COUPON, PERMISSIONS.READ) ||
          userStore.hasPermission(PERMISSION_PLACES.PRODUCT_COMMENT, PERMISSIONS.READ) ||
          userStore.hasPermission(PERMISSION_PLACES.ORDER, PERMISSIONS.READ) ||
          userStore.hasPermission(PERMISSION_PLACES.ORDER_BADGE, PERMISSIONS.READ) ||
          userStore.hasPermission(PERMISSION_PLACES.BLOG, PERMISSIONS.READ) ||
          userStore.hasPermission(PERMISSION_PLACES.BLOG_CATEGORY, PERMISSIONS.READ) ||
          userStore.hasPermission(PERMISSION_PLACES.BLOG_COMMENT, PERMISSIONS.READ) ||
          userStore.hasPermission(PERMISSION_PLACES.STATIC_PAGE, PERMISSIONS.READ) ||
          userStore.hasPermission(PERMISSION_PLACES.SLIDER, PERMISSIONS.READ) ||
          userStore.hasPermission(PERMISSION_PLACES.FILE_MANAGER, PERMISSIONS.READ)"
    class="mt-3"
  >
    <hr class="my-6">
    <quick-access-links/>
  </div>

  <div
    v-if="userStore.hasPermission(PERMISSION_PLACES.USER, PERMISSIONS.READ) ||
          userStore.hasPermission(PERMISSION_PLACES.ORDER, PERMISSIONS.READ) ||
          userStore.hasPermission(PERMISSION_PLACES.RETURN_ORDER_REQUEST, PERMISSIONS.READ) ||
          userStore.hasPermission(PERMISSION_PLACES.PRODUCT, PERMISSIONS.READ)"
    class="mt-3"
  >
    <needed-charts/>
  </div>

  <div class="mt-3">
    <hr class="my-6">
    <counting-badges/>
  </div>
</template>

<script setup>
import {onBeforeMount, onBeforeUnmount, ref} from "vue";
import QuickAccessLinks from "@/components/admin/dashboard/QuickAccessLinks.vue";
import NeededCharts from "@/components/admin/dashboard/NeededCharts.vue";
import CountingBadges from "@/components/admin/dashboard/CountingBadges.vue";
import SaleInfo from "@/components/admin/dashboard/SaleInfo.vue";
import {PERMISSION_PLACES, PERMISSIONS, ROLES, useAdminAuthStore} from "@/store/StoreUserAuth.js";

const userStore = useAdminAuthStore()

const date = ref(null)
const enDate = ref(null)
const time = ref('00:00:00 AM')
let dateInterval = null
let timeInterval = null

onBeforeUnmount(() => {
  if (dateInterval) {
    clearInterval(dateInterval)
    dateInterval = null
  }

  if (timeInterval) {
    clearInterval(timeInterval)
    timeInterval = null
  }
})

onBeforeMount(() => {
  timeInterval = setInterval(() => {
    time.value = (new Date()).toLocaleTimeString()
  }, 1000)

  dateInterval = setInterval(() => {
    const today = new Date()
    const weekday = today.toLocaleDateString('fa-IR', {weekday: 'long'})
    const day = today.toLocaleDateString('fa-IR', {day: 'numeric'})
    const month = today.toLocaleDateString('fa-IR', {month: 'long'})
    const enMonth = today.toLocaleDateString('en', {month: 'short'})
    const year = today.toLocaleDateString('fa-IR', {year: 'numeric'})

    date.value = `${weekday} , ${day} ${month} ${year}`
    enDate.value = `${today.getDate().toString().padStart(2, '0')} ${enMonth}`
  }, 600)
})
</script>
