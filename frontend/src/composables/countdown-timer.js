import {ref} from "vue";
import isFunction from "lodash.isfunction";

export function useCountdown(duration, elemRef = null) {
  const refreshTimeSeconds = duration
  const refreshTimeElem = elemRef
  const refreshCallbackInterval = ref(null)
  const refreshInterval = ref(null)
  const refreshTimeEnd = ref(null)

  const started = ref(false)

  const minutes = ref('00')
  const seconds = ref('00')

  let userCallback = () => {
  }

  function start(callback) {
    userCallback = callback

    reset()
    resume()
  }

  function stop() {
    pause()
    reset()
  }

  function pause() {
    if (refreshInterval?.value) {
      clearInterval(refreshInterval.value)
      refreshInterval.value = null
    }
    if (refreshCallbackInterval?.value) {
      clearInterval(refreshCallbackInterval.value)
      refreshCallbackInterval.value = null
    }

    started.value = false
  }

  function reset() {
    let now = new Date();
    refreshTimeEnd.value = new Date(now.setSeconds(now.getSeconds() + refreshTimeSeconds));
  }

  function resume() {
    pause()

    refreshInterval.value = setInterval(() => {
      let m, s
      const diff = Math.abs(refreshTimeEnd.value - (new Date())) / 1000

      m = Math.floor(diff / 60)
      s = Math.ceil(diff - (m * 60))

      if (s === 60) {
        m = m + 1
        s = 0
      }

      let minStr = (m + '').padStart(2, '0')
      let secStr = (s + '').padStart(2, '0')

      minutes.value = minStr
      seconds.value = secStr

      if (refreshTimeElem?.value) {
        refreshTimeElem.value.textContent = minStr + ':' + secStr
      }
    }, 1000)

    refreshCallbackInterval.value = setInterval(() => {
      if (isFunction(userCallback)) userCallback()
    }, refreshTimeSeconds * 1000);

    started.value = true
  }

  function isStarted() {
    return started.value
  }

  return {
    start,
    stop,
    reset,
    pause,
    resume,
    isStarted,
    //
    minutes,
    seconds,
  }
}
