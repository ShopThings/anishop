<template>
    <router-view v-slot="{ Component, route }">
        <VTransitionSlideFadeUpY>
            <div>
                <component :is="Component" :key="route.path"/>
            </div>
        </VTransitionSlideFadeUpY>
    </router-view>
</template>

<script setup>
import VTransitionSlideFadeUpY from "../../transitions/VTransitionSlideFadeUpY.vue"
</script>

<style scoped>

</style>
