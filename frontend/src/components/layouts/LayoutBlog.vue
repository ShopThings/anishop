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
import VTransitionSlideFadeUpY from "../../transitions/VTransitionSlideFadeUpY.vue"
import AppNavbarBlog from "../AppNavbarBlog.vue";
import AppFooterUser from "../user/AppFooterUser.vue";
</script>

<style scoped>

</style>
