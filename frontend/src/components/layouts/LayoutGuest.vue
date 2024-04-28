<template>
  <app-navbar/>

  <router-view v-slot="{ Component, route }">
    <PageTransition v-bind='transitionProps'>
      <div :key="route.path" class="max-w-7xl mx-auto w-full">
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
import {useCartStore} from "@/store/StoreUserCart.js";
import {usePageTransition} from "@/composables/page-transition.js";

const transitionProps = usePageTransition()

//--------------------------------------
const homeSettingStore = useHomeSettingsStore()
provide('homeSettingStore', homeSettingStore);

const cartStore = useCartStore()
provide('cartStore', cartStore)
</script>
