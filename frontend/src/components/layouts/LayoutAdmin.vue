<template>
    <div class="flex">
        <app-sidebar-admin class="shrink-0" ref="sidebarCom"/>

        <div class="grow flex flex-col overflow-auto" ref="pageContainer">
            <app-navbar-admin :sidebar="sidebarCom" ref="navbarCom"/>

            <div class="p-3" ref="extra" v-if="title">
                <div class="rounded-t-lg bg-white px-3 py-2 border border-b border-b-emerald-300">
                    <h1 class="flex items-center">
                        <ArrowLeftCircleIcon class="h-5 w-5 ml-2 text-emerald-500"/>
                        {{ title }}
                    </h1>
                </div>
                <div class="rounded-b-lg bg-gray-50 px-3 py-1 border-x border-b">
                    <ul class="flex flex-wrap text-amber-500 text-xs">
                        <li>
                            <router-link :to="{name: 'admin.home'}" class="text-black">
                                <HomeIcon class="w-4 h-4"/>
                            </router-link>
                        </li>
                        <li v-for="(crumb, idx) in breadcrumb" :key="idx">
                            <ul class="flex">
                                <li class="p-1 text-gray-300">
                                    <ChevronLeftIcon class="h-3 w-3"/>
                                </li>
                                <li v-if="crumb.link">
                                    <router-link :to="calcCrumbLink(crumb)">
                                        {{ crumb.name }}
                                    </router-link>
                                </li>
                                <li v-else class="text-slate-400">
                                    {{ crumb.name }}
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
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

            <div class="bg-gradient-to-t from-indigo-200">
                <div ref="footer">
                    <app-footer-admin/>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref, watch, watchEffect} from "vue"
import {useResizeObserver} from "@vueuse/core"
import {useRoute} from "vue-router"
import {ChevronLeftIcon, ArrowLeftCircleIcon, HomeIcon} from '@heroicons/vue/24/outline'
import VTransitionSlideFadeUpY from "../../transitions/VTransitionSlideFadeUpY.vue"
import AppNavbarAdmin from "../admin/AppNavbarAdmin.vue"
import AppFooterAdmin from "../admin/AppFooterAdmin.vue"
import AppSidebarAdmin from "../admin/AppSidebarAdmin.vue"

const route = useRoute()

let title = ref(null)
let breadcrumb = ref(null)

const sidebarCom = ref(null)
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

function calcCrumbLink(crumb) {
    const obj = {name: crumb.link}

    const params = {}
    if (crumb.params) {
        for (const i of crumb.params) {
            if (route.params[i]) {
                params[i] = route.params[i]
            }
        }

        obj.params = params
    }

    return obj;
}
</script>

<style scoped>

</style>
