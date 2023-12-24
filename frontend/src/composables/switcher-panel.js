import {reactive, ref} from "vue";
import {watchImmediate} from "@vueuse/core";
import isFunction from "lodash.isfunction";

export function useSwitcherPanel(payload) {
    const panels = reactive(payload.panels ?? {})
    const activePanel = ref(payload.activePanel)
    const activeBackText = ref(payload.activeBackText ?? '')
    const panelsBackHistory = ref(payload.panelsBackHistory ?? [])

    function panelChangeMonitor(callback) {
        watchImmediate(panels, () => {
            if (isFunction(callback)) {
                callback()
            }
        })
    }

    return {
        panels,
        activePanel,
        activeBackText,
        panelsBackHistory,
        //
        panelChangeMonitor,
    }
}
