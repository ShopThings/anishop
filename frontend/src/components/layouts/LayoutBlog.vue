<template>
  <app-navbar-blog/>

  <router-view v-slot="{ Component, route }">
    <VTransitionSlideFadeUpY>
      <div>
        <component :is="Component" :key="route.path"/>
      </div>
    </VTransitionSlideFadeUpY>
  </router-view>

  <app-footer-user/>
</template>

<script setup>
import {provide} from "vue";
import VTransitionSlideFadeUpY from "@/transitions/VTransitionSlideFadeUpY.vue"
import AppNavbarBlog from "@/components/AppNavbarBlog.vue";
import AppFooterUser from "@/components/user/AppFooterUser.vue";
import {useHomeSettingsStore} from "@/store/StoreSettings.js";

//--------------------------------------
const homeSettingStore = useHomeSettingsStore()
provide('homeSettingStore', homeSettingStore);
</script>
