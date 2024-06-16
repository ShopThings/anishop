import {useRequest} from "@/composables/api-request.js";
import {ref} from "vue";

export const useAccurateOnline = ({successDelay, errorDelay} = {successDelay: 3000, errorDelay: 100}) => {
  const testLink = 'https://jsonplaceholder.typicode.com/posts'
  const online = ref(true)
  const isPaused = ref(false)
  let timeout = null

  successDelay = parseInt(successDelay, 10) || 3000
  errorDelay = parseInt(errorDelay, 10) || 100

  function resetTimout() {
    if (timeout) {
      clearTimeout(timeout)
    }
    timeout = null
  }

  function pauseCheck() {
    resetTimout()
    isPaused.value = true
  }

  function checkOnline() {
    return useRequest(testLink, null, {
      silent: true,
      success() {
        online.value = true

        resetTimout()

        if (!isPaused.value) {
          timeout = setTimeout(checkOnline, successDelay)
        }
      },
      error() {
        online.value = false

        resetTimout()

        if (!isPaused.value) {
          timeout = setTimeout(checkOnline, errorDelay)
        }
      },
    })
  }

  checkOnline()

  return {
    online,
    checkOnline,
    pauseCheck,
  }
}
