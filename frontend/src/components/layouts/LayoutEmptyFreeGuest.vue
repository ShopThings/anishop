<template>
  <router-view v-slot="{ Component, route }">
    <PageTransition v-bind='transitionProps'>
      <component :is="Component" :key="route.path"/>
    </PageTransition>
  </router-view>
</template>

<script setup>
import {PageTransition} from "vue3-page-transition";
import {useHomeSettingsStore} from "@/store/StoreSettings.js";
import {provide} from "vue";
import {usePageTransition} from "@/composables/page-transition.js";

const transitionProps = usePageTransition()

//--------------------------------------
const homeSettingStore = useHomeSettingsStore()
provide('homeSettingStore', homeSettingStore);
</script>
