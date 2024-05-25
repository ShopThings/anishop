<template>
  <base-loading-panel :loading="loading">
    <template #loader>
      <loader-page/>
    </template>

    <template #content>
      <template v-if="isPageExists">
        <app-navigation-header :title="page?.title"/>

        <div class="px-3">
          <partial-card class="border-0 p-6">
            <template #body>
              <div
                class="styled-description"
                v-html="page?.description"
              ></div>

            </template>
          </partial-card>
        </div>
      </template>
      <div v-else class="mt-8 px-3">
        <partial-empty-rows
          image="/images/empty-statuses/empty-page.svg"
          image-class="!w-72"
          message="صفحه مورد نظر شما وجود ندارد!"
        />
      </div>
    </template>
  </base-loading-panel>

  <app-newsletter/>
</template>

<script setup>
import AppNavigationHeader from "@/components/AppNavigationHeader.vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import AppNewsletter from "@/components/AppNewsletter.vue";
import {onMounted, ref} from "vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import {HomePageAPI} from "@/service/APIHomePages.js";
import {getRouteParamByKey} from "@/composables/helper.js";
import PartialEmptyRows from "@/components/partials/PartialEmptyRows.vue";
import LoaderPage from "@/components/base/loader/LoaderPage.vue";
import {useHomeSettingNoTimerStore} from "@/store/StoreSettings.js";
import {useSeoMeta} from "@unhead/vue";

const urlParam = getRouteParamByKey('url', null, false)

const loading = ref(true)
const page = ref(null)
const isPageExists = ref(false)

const settingStore = useHomeSettingNoTimerStore()

const localKeywords = ref(settingStore.getKeywords)
const title = ref(null)
const keywords = ref(null)

useSeoMeta({
  title: title,
  keywords: keywords.value.length
    ? [
      Array.isArray(localKeywords) ? localKeywords.join(', ') : localKeywords,
      keywords.value.join(', '),
    ]
    : Array.isArray(localKeywords) ? localKeywords.join(', ') : localKeywords,
})

onMounted(() => {
  HomePageAPI.fetchById(urlParam.value, {
    success(response) {
      page.value = response.value
      isPageExists.value = true

      localKeywords.value = settingStore.getKeywords

      title.value = page.value.title
      keywords.value = page.value.keywords

      return false
    },
    error() {
      isPageExists.value = false
      return false
    },
    finally() {
      loading.value = false
    },
  })
})
</script>

<style scoped>
@import "../assets/css/skeleton/normalize.css";
@import "../assets/css/skeleton/skeleton.css";
</style>
