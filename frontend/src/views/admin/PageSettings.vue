<template>
  <partial-card class="!bg-cyan-50 border-2 border-cyan-500">
    <template #body>
      <div class="p-3">
        <div class="flex flex-wrap gap-3 items-center justify-between">
          <div class="font-iranyekan-light text-sm flex gap-3 items-center">
            <span>زمان باقی‌مانده تا بارگذاری مجدد تنظیمات</span>
            <div
              ref="refreshTimeElem"
              class="text-orange-600 font-iranyekan-bold text-xl"
              dir="ltr"
            ></div>
          </div>
          <div>
            <button
              class="rounded-md border py-2 px-4 text-sm bg-white hover:bg-gray-50 transition shadow-lg"
              type="button"
              @click="loadSetting"
            >
              بارگذاری مجدد
            </button>
          </div>
        </div>
      </div>
    </template>
  </partial-card>

  <partial-card class="mt-3">
    <template #body>
      <div class="p-3">
        <base-loading-panel :loading="loading" type="circle">
          <template #content>
            <base-tab-panel
              :tabs="tabs"
              tab-button-extra-class="w-full sm:w-auto sm:grow-0 px-6"
            >
              <template #main>
                <setting-form-main
                  :is-fetching="isFetching"
                  :setting="settings"
                  @updated="loadSetting"
                />
              </template>

              <template #sms>
                <setting-form-sms
                  :is-fetching="isFetching"
                  :setting="settings"
                  @updated="loadSetting"
                />
              </template>

              <template #info>
                <setting-form-info
                  :is-fetching="isFetching"
                  :setting="settings"
                  @updated="loadSetting"
                />
              </template>

              <template #shop>
                <setting-form-shop
                  :is-fetching="isFetching"
                  :setting="settings"
                  @updated="loadSetting"
                />
              </template>

              <template #social>
                <setting-form-social
                  :is-fetching="isFetching"
                  :setting="settings"
                  @updated="loadSetting"
                />
              </template>

              <template #general>
                <setting-form-general
                  :is-fetching="isFetching"
                  :setting="settings"
                  @updated="loadSetting"
                />
              </template>

              <template #footer>
                <setting-form-footer
                  :is-fetching="isFetching"
                  :setting="settings"
                  @updated="loadSetting"
                />
              </template>
            </base-tab-panel>
          </template>
        </base-loading-panel>
      </div>
    </template>
  </partial-card>
</template>

<script setup>
import {onMounted, onUnmounted, ref} from "vue";
import PartialCard from "@/components/partials/PartialCard.vue";
import BaseLoadingPanel from "@/components/base/BaseLoadingPanel.vue";
import BaseTabPanel from "@/components/base/BaseTabPanel.vue";
import SettingFormMain from "./forms/SettingFormMain.vue";
import SettingFormSms from "./forms/SettingFormSms.vue";
import SettingFormInfo from "./forms/SettingFormInfo.vue";
import SettingFormShop from "./forms/SettingFormShop.vue";
import SettingFormGeneral from "./forms/SettingFormGeneral.vue";
import SettingFormFooter from "./forms/SettingFormFooter.vue";
import {useCountdown} from "@/composables/countdown-timer.js";
import SettingFormSocial from "./forms/SettingFormSocial.vue";
import {SettingAPI} from "@/service/APIConfig.js";

const loading = ref(true)
const isFetching = ref(true)
const settings = ref([])
const tabs = {
  main: {
    text: 'اصلی'
  },
  sms: {
    text: 'پیامک',
  },
  info: {
    text: 'اطلاعات',
  },
  shop: {
    text: 'فروشگاه',
  },
  social: {
    text: 'شبکه‌های اجتماعی',
  },
  general: {
    text: 'عمومی',
  },
  footer: {
    text: 'پانوشت/فوتر',
  },
}

const refreshTimeElem = ref(null)
const countdown = useCountdown(600, refreshTimeElem) // 10min

function loadSetting() {
  isFetching.value = true
  countdown.pause()

  SettingAPI.fetchAll(null, {
    success(response) {
      settings.value = response.data
      loading.value = false
      isFetching.value = false
    },
    finally() {
      countdown.reset()
      countdown.resume()
    },
  })
}

onMounted(() => {
  loadSetting()
  countdown.start(loadSetting)
})

onUnmounted(() => {
  countdown.stop()
})
</script>
