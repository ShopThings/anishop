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
import {PageTransition} from "vue3-page-transition";
import {usePageTransition} from "@/composables/page-transition.js";

const transitionProps = usePageTransition()
</script>
