import {computed, ref} from "vue";
import isFunction from "lodash.isfunction";

export function useCountdown(duration = 0, elemRef = null, showFormat = 'm:s') {
  let refreshTimeSeconds = parseInt(duration, 10) || 0
  const refreshTimeElem = elemRef
  const refreshCallbackInterval = ref(null)
  const refreshInterval = ref(null)
  const refreshTimeEnd = ref(null)

  const started = ref(false)

  let timeFormat = ref(showFormat)

  const days = ref('00')
  const hours = ref('00')
  const minutes = ref('00')
  const seconds = ref('00')

  const daysWithoutPadding = ref(0)
  const hoursWithoutPadding = ref(0)
  const minutesWithoutPadding = ref(0)
  const secondsWithoutPadding = ref(0)

  const includeDays = computed(() => {
    return timeFormat.value.includes('d')
  })
  const includeHours = computed(() => {
    return timeFormat.value.includes('h')
  })
  const includeMinutes = computed(() => {
    return timeFormat.value.includes('m')
  })

  let userCallback = () => {
  }

  function assignDurationToValues(d, h, m, s) {
    daysWithoutPadding.value = d
    hoursWithoutPadding.value = h
    minutesWithoutPadding.value = m
    secondsWithoutPadding.value = s

    let dayStr = daysWithoutPadding.value?.toString().padStart(2, '0') || '00'
    let hourStr = hoursWithoutPadding.value?.toString().padStart(2, '0') || '00'
    let minStr = minutesWithoutPadding.value?.toString().padStart(2, '0') || '00'
    let secStr = secondsWithoutPadding.value?.toString().padStart(2, '0') || '00'

    days.value = dayStr
    hours.value = hourStr
    minutes.value = minStr
    seconds.value = secStr

    if (refreshTimeElem?.value) {
      let tmpFormat = timeFormat.value.replace(/d/i, dayStr)
      tmpFormat = tmpFormat.replace(/h/i, hourStr)
      tmpFormat = tmpFormat.replace(/m/i, minStr)
      tmpFormat = tmpFormat.replace(/s/i, secStr)

      refreshTimeElem.value.textContent = tmpFormat
    }
  }

  function calculateDifference() {
    let d = 0, h = 0, m = 0, s
    const diff = Math.abs(refreshTimeEnd.value - (new Date())) / 1000

    if (includeDays.value) {
      d = Math.floor(diff / (60 * 60 * 24));
      h = Math.floor((diff % (60 * 60 * 24)) / (60 * 60));
    } else if (includeHours.value) {
      h = Math.floor(diff / (60 * 60));
    }
    if (includeHours.value) {
      m = Math.floor((diff % (60 * 60)) / 60)
    } else if (includeMinutes.value) {
      m = Math.floor(diff / 60)
    }
    if (includeMinutes.value) {
      s = Math.floor(diff % 60)
    } else {
      s = Math.floor(diff)
    }

    //

    if (includeMinutes.value && s >= 60) {
      m += Math.floor(s / 60)
      s = s % 60
    }
    if (includeHours.value && m >= 60) {
      h += Math.floor(m / 60)
      m = m % 60
    }
    if (includeDays.value && h >= 24) {
      d += Math.floor(h / 24)
      h = h % 24
    }

    assignDurationToValues(d, h, m, s)
  }

  function start(callback) {
    userCallback = callback

    reset()
    resume()
  }

  function stop() {
    pause()
    reset()

    // to show min number of duration(0)
    assignDurationToValues(0, 0)
  }

  function pause() {
    if (refreshInterval.value) {
      clearInterval(refreshInterval.value)
      refreshInterval.value = null
    }
    if (refreshCallbackInterval.value) {
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

    // to show max number of duration
    calculateDifference()

    refreshInterval.value = setInterval(() => {
      calculateDifference()
    }, 1000)

    refreshCallbackInterval.value = setInterval(() => {
      if (isFunction(userCallback)) userCallback()
    }, refreshTimeSeconds * 1000);

    started.value = true
  }

  const isStarted = computed(() => {
    return started.value
  })

  function changeDuration(duration) {
    refreshTimeSeconds = parseInt(duration, 10) || 0
  }

  function changeFormat(format) {
    try {
      timeFormat.value = format.toString()
    } catch (e) {
      console.error('Provided time format is not acceptable. Please provide string type format')
    }
  }

  return {
    start,
    stop,
    reset,
    pause,
    resume,
    isStarted,
    //
    days, daysWithoutPadding,
    hours, hoursWithoutPadding,
    minutes, minutesWithoutPadding,
    seconds, secondsWithoutPadding,
    //
    changeDuration,
    changeFormat,
  }
}
