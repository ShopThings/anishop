<template>
  <app-navbar/>

  <router-view v-slot="{ Component, route }">
    <PageTransition v-bind='transitionProps'>
      <div :key="route.path">
        <component :is="Component" :key="route.path"/>
      </div>
    </PageTransition>
  </router-view>

  <app-footer/>
</template>

<script setup>
import {PageTransition, TransitionPresets, defineTransitionProps} from 'vue3-page-transition'
import AppNavbar from "../AppNavbar.vue"
import AppFooter from "../AppFooter.vue"

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
</script>

<style scoped>

</style>
