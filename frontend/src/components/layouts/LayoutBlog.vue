<template>
  <app-navbar-blog/>

  <router-view v-slot="{ Component, route }">
    <PageTransition v-bind='transitionProps'>
      <div :key="route.path" class="max-w-7xl mx-auto w-full">
        <component :is="Component" :key="route.path"/>
      </div>
    </PageTransition>
  </router-view>

  <app-footer-user/>
</template>

<script setup>
import {provide} from "vue";
import AppNavbarBlog from "@/components/AppNavbarBlog.vue";
import AppFooterUser from "@/components/user/AppFooterUser.vue";
import {useHomeSettingsStore} from "@/store/StoreSettings.js";
import {PageTransition} from "vue3-page-transition";
import {usePageTransition} from "@/composables/page-transition.js";

const transitionProps = usePageTransition()

//--------------------------------------
const homeSettingStore = useHomeSettingsStore()
provide('homeSettingStore', homeSettingStore);
</script>
