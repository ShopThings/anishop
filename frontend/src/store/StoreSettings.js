import {computed, onBeforeMount, onBeforeUnmount, ref, toValue} from "vue"
import {defineStore} from "pinia"
import isObject from "lodash.isobject"
import {useCountdown} from "@/composables/countdown-timer.js";
import {findItemByKey} from "@/composables/helper.js";
import {SETTING_KEYS} from "@/composables/constants.js";
import {HomeSettingAPI} from "@/service/APIHomePages.js";
import isFunction from "lodash.isfunction";

export const useHomeSettingNoTimerStore = defineStore('homeSettingsNoTimer', () => {
  let settings = ref([])

  fetchSettings()

  function getSetting(key, defaultValue) {
    return findItemByKey(settings.value, 'name', key)?.value || defaultValue || ''
  }

  function getSettingObject(key) {
    return findItemByKey(settings.value, 'name', key)
  }

  function setSetting(key, value) {
    if (isObject(key))
      settings.value = toValue(key)
    else
      settings.value[key] = toValue(value)
  }

  const getTitle = computed(() => {
    return getSetting(SETTING_KEYS.TITLE)
  })

  const getDescription = computed(() => {
    return getSetting(SETTING_KEYS.DESCRIPTION)
  })

  const getKeywords = computed(() => {
    return getSetting(SETTING_KEYS.KEYWORDS, [])
  })

  const getAddress = computed(() => {
    return getSetting(SETTING_KEYS.ADDRESS)
  })

  const getPhones = computed(() => {
    return getSetting(SETTING_KEYS.PHONES, [])
  })

  const getStoreProvince = computed(() => {
    return getSetting(SETTING_KEYS.STORE_PROVINCE)
  })

  const getStoreCity = computed(() => {
    return getSetting(SETTING_KEYS.STORE_CITY)
  })

  const getLatLng = computed(() => {
    return getSetting(SETTING_KEYS.LAT_LNG, [])
  })

  const getProductEachPage = computed(() => {
    return getSetting(SETTING_KEYS.PRODUCT_EACH_PAGE, 15)
  })

  const getBlogEachPage = computed(() => {
    return getSetting(SETTING_KEYS.BLOG_EACH_PAGE, 15)
  })

  const getSocials = computed(() => {
    return getSetting(SETTING_KEYS.SOCIALS, '')
  })

  const getFooterDescription = computed(() => {
    return getSetting(SETTING_KEYS.FOOTER_DESCRIPTION)
  })

  const getFooterCopyright = computed(() => {
    return getSetting(SETTING_KEYS.FOOTER_COPYRIGHT)
  })

  const getFooterNamads = computed(() => {
    return getSetting(SETTING_KEYS.FOOTER_NAMADS)
  })

  function fetchSettings(callbacks) {
    return HomeSettingAPI.fetchAll({
      silent: true,
      success(response) {
        settings.value = response.data

        if (isFunction(callbacks?.success)) {
          callbacks.success(response)
        }

        return false
      },
      error(err) {
        if (isFunction(callbacks?.error)) {
          callbacks.error(err)
        }

        return false
      },
      finally() {
        if (isFunction(callbacks?.finally)) {
          callbacks.finally()
        }
      },
    })
  }

  function $reset() {
    settings.value = []
  }

  // refs' become states,
  // computed() become getters
  // and functions become actions
  return {
    settings,
    getSetting, getSettingObject,
    setSetting,
    fetchSettings,
    //
    getTitle, getDescription, getKeywords,
    getAddress, getPhones, getStoreProvince,
    getStoreCity, getLatLng, getProductEachPage,
    getBlogEachPage, getSocials, getFooterDescription,
    getFooterCopyright, getFooterNamads,
    //
    $reset,
  }
})

export const useHomeSettingsStore = defineStore('homeSettings', () => {
  const settingStore = useHomeSettingNoTimerStore()
  const countdown = useCountdown(1800) // 30min

  fetchSettings()

  function fetchSettings() {
    countdown.pause()

    settingStore.fetchSettings({
      finally() {
        countdown.reset()
        countdown.resume()
      },
    })
  }

  function $reset() {
    countdown.stop()

    settingStore.$reset()

    countdown.start(fetchSettings)
  }

  onBeforeMount(() => {
    if (!countdown.isStarted.value) {
      countdown.start(fetchSettings)
    }
  });

  onBeforeUnmount(() => {
    countdown.stop()
  });

  // refs' become states,
  // computed() become getters
  // and functions become actions
  return {
    settings: settingStore.settings,
    getSetting: settingStore.getSetting,
    getSettingObject: settingStore.getSettingObject,
    setSetting: settingStore.setSetting,
    //
    getTitle: settingStore.getTitle,
    getDescription: settingStore.getDescription,
    getKeywords: settingStore.getKeywords,
    getAddress: settingStore.getAddress,
    getPhones: settingStore.getPhones,
    getStoreProvince: settingStore.getStoreProvince,
    getStoreCity: settingStore.getStoreCity,
    getLatLng: settingStore.getLatLng,
    getProductEachPage: settingStore.getProductEachPage,
    getBlogEachPage: settingStore.getBlogEachPage,
    getSocials: settingStore.getSocials,
    getFooterDescription: settingStore.getFooterDescription,
    getFooterCopyright: settingStore.getFooterCopyright,
    getFooterNamads: settingStore.getFooterNamads,
    //
    $reset,
  }
})
