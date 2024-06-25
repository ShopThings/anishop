<template>
  <base-page-progress-bar/>

  <template v-if="!loading">
    <template v-if="errorObject.has_error">
      <div class="flex items-center justify-center h-svh-full text-rose-500">
        <div class="flex flex-col gap-3 items-center">
          <ExclamationCircleIcon class="size-10"/>
          <span>{{ errorObject.message || 'خطای غیر منتظره' }}</span>

          <div class="flex gap-2 items-center">
            <button
              class="rounded-full border-2 border-emerald-400 bg-white py-1 px-3 text-sm text-emerald-500"
              type="button"
              @click="reloadHandler"
            >
              بارگذاری مجدد
            </button>
            <router-link
              :to="{name: 'home'}"
              class="rounded-full border-2 border-black bg-black py-1 px-3 text-sm text-white"
            >
              بازگشت به خانه
            </router-link>
          </div>
        </div>
      </div>
    </template>

    <template v-else>
      <component :is="layout">
        <router-view/>
      </component>
    </template>
  </template>

  <div
    v-else
    class="flex items-center justify-center h-svh-full"
  >
    <loader3-dot/>
  </div>
</template>

<script setup>
import {computed, onMounted, ref, toValue, watch} from "vue";
import {useRoute} from "vue-router";
import {ExclamationCircleIcon} from "@heroicons/vue/24/outline/index.js"
import BasePageProgressBar from "./components/base/BasePageProgressBar.vue";
import {useHomeSettingNoTimerStore} from "@/store/StoreSettings.js";
import {useHead, useSeoMeta} from "@unhead/vue";
import {titleOperations} from "@/composables/helper.js";
import Loader3Dot from "@/components/base/loader/Loader3Dot.vue";

const route = useRoute()
const loading = ref(true)
const errorObject = ref({
  has_error: false,
  message: null,
})

const layout = computed(() => {
  const layoutMeta = route?.meta?.layout
  return layoutMeta ? layoutMeta : "layout-empty"
})

function reloadHandler() {
  window.location.reload()
}

// Retrieve settings from the store
const settingStore = useHomeSettingNoTimerStore()

// Fetch settings from the store
async function fetchSettings() {
  // If settings are already fetched, return
  if (settingStore.settings?.length > 0) {
    return
  }

  // Otherwise, fetch settings from the API
  await settingStore.fetchSettings({
    error(err, msg) {
      errorObject.value = {
        has_error: true,
        message: msg
      }
    },
  })
}

const routeTitle = ref(route?.meta?.title)
const localTitle = ref(settingStore.getTitle)
const localDescription = ref(settingStore.getDescription)
const localKeywords = ref(settingStore.getKeywords)

const routePath = ref(route.path)

useHead({
  titleTemplate: (title) => !title ? toValue(localTitle) : titleOperations.join([toValue(localTitle), title]),

  link: [
    {rel: 'canonical', href: (window ? window.location.origin : import.meta.env.VITE_BASE_URL) + routePath}
  ],

  meta: [
    {name: 'apple-mobile-web-app-title', content: toValue(localTitle)},

    // OpenGraph data (Most widely used)
    {property: 'og:title', content: toValue(routeTitle)},
    {property: 'og:site_name', content: toValue(localTitle)},

    // The list of types is available here: http://ogp.me/#types
    {property: 'og:type', content: 'website'},

    // Should be the same as your canonical link, see below.
    {property: 'og:url', content: (window ? window.location.origin : import.meta.env.VITE_BASE_URL) + routePath},

    // DON'T HAVE IMAGE RIGHT NOW!
    // {property: 'og:image', content: ...},

    // Often the same as your meta description, but not always.
    {property: 'og:description', content: toValue(localDescription)},

    // Twitter card
    {name: 'twitter:card', content: '...'},
    {name: 'twitter:site', content: (window ? window.location.origin : import.meta.env.VITE_BASE_URL) + routePath},
    {name: 'twitter:title', content: toValue(localTitle)},
    {name: 'twitter:description', content: toValue(localDescription)},

    // DON'T HAVE IMAGE RIGHT NOW!
    // {name: 'twitter:image:src', content: ...},

    // Google / Schema.org markup:
    {itemprop: 'name', content: toValue(localTitle)},
    {itemprop: 'description', content: toValue(localDescription)},

    // DON'T HAVE IMAGE RIGHT NOW!
    // {itemprop: 'image', content: ...}
  ],
})

useSeoMeta({
  title: routeTitle,
  description: localDescription,
  keywords: Array.isArray(localKeywords.value) ? localKeywords.value.join(', ') : localKeywords.value
})

//----------------------------------------------------------------
watch(route, () => {
  routePath.value = route.path
  routeTitle.value = route?.meta?.title
})

onMounted(async () => {
  await fetchSettings()

  loading.value = false

  localTitle.value = settingStore.getTitle
  localDescription.value = settingStore.getDescription
  localKeywords.value = settingStore.getKeywords
})
</script>
