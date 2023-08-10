<template>
    <app-navbar/>

    <router-view v-slot="{ Component, route }">
        <VTransitionSlideFadeUpY>
            <div>
                <component :is="Component" :key="route.path"/>
            </div>
        </VTransitionSlideFadeUpY>
    </router-view>

    <app-footer/>
</template>

<script setup>
import AppNavbar from "../AppNavbar.vue"
import AppFooter from "../AppFooter.vue"
import VTransitionSlideFadeUpY from "../../transitions/VTransitionSlideFadeUpY.vue"
</script>

<style scoped>

</style>
