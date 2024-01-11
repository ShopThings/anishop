import {ref} from "vue";
import isFunction from "lodash.isfunction";

export function useCountdown(elemRef, refreshSeconds) {
  const refreshTimeSeconds = refreshSeconds
  const refreshTimeElem = elemRef
  const refreshCallbackInterval = ref(null)
  const refreshInterval = ref(null)
  const refreshTimeEnd = ref(null)

  let userCallback = () => {
  }

  function start(callback) {
    userCallback = callback

    reset()
    unpause()
  }

  function stop() {
    if (refreshInterval) clearInterval(refreshInterval.value)
    if (refreshCallbackInterval) clearInterval(refreshCallbackInterval.value)
  }

  function reset() {
    let now = new Date();
    refreshTimeEnd.value = new Date(now.setSeconds(now.getSeconds() + refreshTimeSeconds));
  }

  function unpause() {
    stop()

    refreshInterval.value = setInterval(() => {
      let m, s
      const diff = Math.abs(refreshTimeEnd.value - (new Date())) / 1000

      m = Math.floor(diff / 60)
      s = Math.ceil(diff - (m * 60))

      if (s === 60) {
        m = m + 1
        s = 0
      }

      refreshTimeElem.value.textContent = (m + '').padStart(2, '0') + ':' + (s + '').padStart(2, '0')
    }, 1000)

    refreshCallbackInterval.value = setInterval(() => {
      if (isFunction(userCallback)) userCallback()
    }, refreshTimeSeconds * 1000);
  }

  return {
    start,
    stop,
    reset,
    pause: stop,
    unpause,
  }
}
