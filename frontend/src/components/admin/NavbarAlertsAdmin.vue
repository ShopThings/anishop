<template>
  <base-popover class="sm:relative" panel-klass="right-0 w-full mt-2 sm:mt-3 sm:w-72 sm:mt-0">
    <template #button>
      <button
          class="relative h-[40px] rounded-lg border-0 py-2 px-2 bg-transparent text-black hover:bg-slate-200 active:bg-slate-300 focus:bg-sky-200 transition-all flex justify-between items-center"
          type="button"
      >
        <BellAlertIcon v-if="countingAlertStore.hasNewChange" class="h-6 w-6 animate-wiggle"/>
        <BellIcon v-else class="h-6 w-6"/>
        <ChevronDownIcon class="h-3 w-3 mr-1"/>

        <span v-if="countingAlertStore.hasNewChange"
              class="absolute rounded-full bg-orange-400 w-2 h-2 z-[1] -top-1 -right-1"></span>
      </button>
    </template>

    <template #panel>
      <ul class="divide-y divide-gray-100 py-1.5">
        <li
          v-if="userStore.hasPermission(PERMISSION_PLACES.PRODUCT_COMMENT, PERMISSIONS.READ) &&
            countingAlertStore.getProductCommentCount !== null"
          class="py-1 px-2 flex flex-col"
        >
          <router-link
            :to="{name: 'admin.products.comments'}"
            class="flex justify-between items-center group px-3 py-2 hover:bg-indigo-50 transition rounded-lg"
          >
            <span>دیدگاه محصول</span>
            <span class="min-w-6 h-6 rounded bg-blue-500 text-white px-1 text-center mr-2">{{
                numberFormat(countingAlertStore.getProductCommentCount)
              }}</span>
          </router-link>
        </li>
        <li
          v-if="userStore.hasPermission(PERMISSION_PLACES.BLOG_COMMENT, PERMISSIONS.READ) &&
            countingAlertStore.getBlogCommentCount !== null"
          class="py-1 px-2 flex flex-col"
        >
          <router-link
            :to="{name: 'admin.blogs.comments'}"
            class="flex justify-between items-center group px-3 py-2 hover:bg-indigo-50 transition rounded-lg"
          >
            <span>دیدگاه بلاگ</span>
            <span class="min-w-6 h-6 rounded bg-blue-500 text-white px-1 text-center mr-2">{{
                numberFormat(countingAlertStore.getBlogCommentCount)
              }}</span>
          </router-link>
        </li>
        <li
          v-if="userStore.hasPermission(PERMISSION_PLACES.RETURN_ORDER_REQUEST, PERMISSIONS.READ) &&
            countingAlertStore.getReturnOrderCount !== null"
          class="py-1 px-2 flex flex-col"
        >
          <router-link
            :to="{name: 'admin.return_orders'}"
            class="flex justify-between items-center group px-3 py-2 hover:bg-indigo-50 transition rounded-lg"
          >
            <span>درخواست مرجوعی کالا</span>
            <span class="min-w-6 h-6 rounded bg-blue-500 text-white px-1 text-center mr-2">{{
                numberFormat(countingAlertStore.getReturnOrderCount)
              }}</span>
          </router-link>
        </li>
        <li
          v-if="userStore.hasPermission(PERMISSION_PLACES.CONTACT_US, PERMISSIONS.READ) &&
            countingAlertStore.getContactCount !== null"
          class="py-1 px-2 flex flex-col"
        >
          <router-link
            :to="{name: 'admin.contacts'}"
            class="flex justify-between items-center group px-3 py-2 hover:bg-indigo-50 transition rounded-lg"
          >
            <span>تماس با ما</span>
            <span class="min-w-6 h-6 rounded bg-blue-500 text-white px-1 text-center mr-2">{{
                numberFormat(countingAlertStore.getContactCount)
              }}</span>
          </router-link>
        </li>
        <li
          v-if="userStore.hasPermission(PERMISSION_PLACES.COMPLAINT, PERMISSIONS.READ) &&
             countingAlertStore.getComplaintCount !== null"
          class="py-1 px-2 flex flex-col"
        >
          <router-link
            :to="{name: 'admin.complaints'}"
            class="flex justify-between items-center group px-3 py-2 hover:bg-indigo-50 transition rounded-lg"
          >
            <span>شکایات</span>
            <span class="min-w-6 h-6 rounded bg-blue-500 text-white px-1 text-center mr-2">{{
                numberFormat(countingAlertStore.getComplaintCount)
              }}</span>
          </router-link>
        </li>
      </ul>
    </template>
  </base-popover>
</template>

<script setup>
import {inject} from "vue";
import {ChevronDownIcon} from "@heroicons/vue/24/solid/index.js";
import BasePopover from "@/components/base/BasePopover.vue";
import {BellAlertIcon, BellIcon} from "@heroicons/vue/24/outline/index.js";
import {numberFormat} from "@/composables/helper.js";
import {PERMISSION_PLACES, PERMISSIONS, useAdminAuthStore} from "@/store/StoreUserAuth.js";

const userStore = useAdminAuthStore()
const countingAlertStore = inject('countingAlertStore')
</script>
