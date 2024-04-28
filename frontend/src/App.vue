<template>
  <base-page-progress-bar/>
  <component :is="layout">
    <router-view/>
  </component>
</template>

<script setup>
import {computed} from "vue"
import {useRoute} from "vue-router"
import BasePageProgressBar from "./components/base/BasePageProgressBar.vue";

const route = useRoute()
const layout = computed(() => {
  const layoutMeta = route?.meta?.layout
  return layoutMeta ? layoutMeta : 'layout-empty'
})
</script>
