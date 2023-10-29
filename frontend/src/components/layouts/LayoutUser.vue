<template>
    <div class="flex items-stretch relative">
        <div class="shrink-0 flex">
            <app-sidebar-user/>
        </div>

        <div class="grow flex flex-col overflow-auto" ref="pageContainer">
            <app-navbar-user ref="navbarCom"/>

            <div class="p-3" ref="extra" v-if="title">
                <h1 class="flex items-center text-indigo-700">
                    {{ title }}
                </h1>
            </div>

            <div class="px-3 pb-3" ref="page">
                <router-view v-slot="{ Component, route }">
                    <VTransitionSlideFadeUpY>
                        <div>
                            <component :is="Component" :key="route.path"/>
                        </div>
                    </VTransitionSlideFadeUpY>
                </router-view>
            </div>

            <div ref="footer">
                <app-footer-user/>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref, watch, watchEffect} from "vue";
import {useRoute} from "vue-router";
import VTransitionSlideFadeUpY from "../../transitions/VTransitionSlideFadeUpY.vue"
import AppFooterUser from "../user/AppFooterUser.vue";
import {useResizeObserver} from "@vueuse/core";
import AppNavbarUser from "../user/AppNavbarUser.vue";
import AppSidebarUser from "../user/AppSidebarUser.vue";

const route = useRoute()

let title = ref(null)
let breadcrumb = ref(null)

const pageContainer = ref(null)
const page = ref(null)
const footer = ref(null)
const navbarCom = ref(null)
const navbar = ref(null)
const extra = ref(null)

useResizeObserver(pageContainer, () => {
    const nh = navbar.value?.offsetHeight || 0
    const fh = footer.value?.offsetHeight || 0
    const eh = extra.value?.offsetHeight || 0

    page.value.style.minHeight = `calc(100vh - ${nh}px - ${fh}px - ${eh}px - 1px)`
})

watchEffect(() => {
    navbar.value = navbarCom.value?.navbar
})

watch(route, (to) => {
    title.value = to.meta?.title || null
    breadcrumb.value = to.meta?.breadcrumb || []
}, {flush: 'pre', immediate: true, deep: true})
</script>

<style scoped>

</style>
