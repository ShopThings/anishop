<template>
  <router-view v-slot="{ Component, route }">
    <PageTransition v-bind='transitionProps'>
      <div :key="route.path" class="max-w-7xl mx-auto w-full">
        <component :is="Component" :key="route.path"/>
      </div>
    </PageTransition>
  </router-view>
</template>

<script setup>
import {defineTransitionProps, PageTransition, TransitionPresets} from "vue3-page-transition";
import {useHomeSettingsStore} from "@/store/StoreSettings.js";
import {provide} from "vue";

const transitionProps = defineTransitionProps({
  mode: 'out-in',
  name: TransitionPresets.fadeInUp,
  appear: true,
  overlay: true,
  overlayBgClassName: 'bg-violet-500',
  overlayZIndex: 999,
  transformDistance: '2rem',
  transitionDuration: 300,
})

//--------------------------------------
const homeSettingStore = useHomeSettingsStore()
provide('homeSettingStore', homeSettingStore);
</script>
