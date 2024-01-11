<template>
  <partial-card class="!bg-cyan-50 border-2 border-cyan-500">
    <template #body>
      <div class="p-3">
        <div class="flex flex-wrap gap-3 items-center justify-between">
          <div class="font-iranyekan-light text-sm flex gap-3 items-center">
            <span>زمان باقی‌مانده تا بارگذاری مجدد تنظیمات</span>
            <div
              ref="refreshTimeElem"
              dir="ltr"
              class="text-orange-600 font-iranyekan-bold text-xl"
            ></div>
          </div>
          <div>
            <button
              type="button"
              class="rounded-md border py-2 px-4 text-sm bg-white hover:bg-gray-50 transition shadow-lg"
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
                <setting-form-main :setting="settings"/>
              </template>

              <template #sms>
                <setting-form-sms :setting="settings"/>
              </template>

              <template #info>
                <setting-form-info :setting="settings"/>
              </template>

              <template #shop>
                <setting-form-shop :setting="settings"/>
              </template>

              <template #social>
                <setting-form-social :setting="settings"/>
              </template>

              <template #general>
                <setting-form-general :setting="settings"/>
              </template>

              <template #footer>
                <setting-form-footer :setting="settings"/>
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
import PartialCard from "../../components/partials/PartialCard.vue";
import BaseLoadingPanel from "../../components/base/BaseLoadingPanel.vue";
import BaseTabPanel from "../../components/base/BaseTabPanel.vue";
import SettingFormMain from "./forms/SettingFormMain.vue";
import SettingFormSms from "./forms/SettingFormSms.vue";
import SettingFormInfo from "./forms/SettingFormInfo.vue";
import SettingFormShop from "./forms/SettingFormShop.vue";
import SettingFormGeneral from "./forms/SettingFormGeneral.vue";
import SettingFormFooter from "./forms/SettingFormFooter.vue";
import {useCountdown} from "../../composables/countdown-timer.js";
import SettingFormSocial from "./forms/SettingFormSocial.vue";

const loading = ref(false)
const settings = ref({})
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
const countdown = useCountdown(refreshTimeElem, 600) // 10min

function loadSetting() {
  countdown.reset()

  // fetch settings, reset loader and fill setting variable
  // ...
}

onMounted(() => {
  loadSetting()
  countdown.start(loadSetting)
})

onUnmounted(() => {
  countdown.stop()
})
</script>
