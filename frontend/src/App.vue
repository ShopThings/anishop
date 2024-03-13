<template>
  <component :is="layout">
    <router-view/>
  </component>
</template>

<script setup>
import {computed} from "vue"
import {useRoute} from "vue-router"

const route = useRoute()
let layoutMeta;

const layout = computed(() => {
  layoutMeta = route.meta.layout
  if (layoutMeta) {
    return layoutMeta
  }
  return 'layout-empty'
})
</script>
