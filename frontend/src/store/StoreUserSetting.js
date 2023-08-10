import {ref, toValue} from "vue"
import {defineStore} from "pinia"
import isObject from "lodash.isobject"

export const useAdminSettingsStore = defineStore('settings', () => {
    const settings = ref(null)

    function getSetting(value, defaultValue) {
        if (value) return settings[value] || defaultValue
        return settings
    }

    function setSetting(key, value) {
        if (isObject(key))
            this.settings.value = toValue(key)
        else
            this.settings.value[key] = toValue(value)
    }

    function $reset() {
        settings.value = {}
    }

    // refs' become states,
    // computed() become getters
    // and functions become actions
    return {
        settings,
        getSetting,
        setSetting,
        //
        $reset,
    }
})
