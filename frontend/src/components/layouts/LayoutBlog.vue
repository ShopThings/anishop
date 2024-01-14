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
import VTransitionSlideFadeUpY from "@/transitions/VTransitionSlideFadeUpY.vue"
import AppNavbarBlog from "@/components/AppNavbarBlog.vue";
import AppFooterUser from "@/components/user/AppFooterUser.vue";
</script>
