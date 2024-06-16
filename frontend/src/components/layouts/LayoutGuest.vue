<template>
  <app-navbar/>

  <router-view v-slot="{ Component, route }">
    <PageTransition v-bind='transitionProps'>
      <div :key="route.path" class="layout-max-w mx-auto w-full">
        <component :is="Component" :key="route.path"/>
      </div>
    </PageTransition>
  </router-view>

  <app-footer/>
</template>

<script setup>
import {provide} from "vue";
import {PageTransition} from 'vue3-page-transition'
import AppNavbar from "@/components/AppNavbar.vue"
import AppFooter from "@/components/AppFooter.vue"
import {useHomeSettingsStore} from "@/store/StoreSettings.js";
import {usePageTransition} from "@/composables/page-transition.js";

const transitionProps = usePageTransition()

//--------------------------------------
const homeSettingStore = useHomeSettingsStore()
provide('homeSettingStore', homeSettingStore);
</script>
