import {ref} from "vue"
import {defineStore} from "pinia"
import {useRequest} from "@/composables/api-request.js";
import {apiRoutes} from "@/router/api-routes.js";

export const useCountingStuffsStore = defineStore('userPanelCounting', () => {
  let counts = ref({})

  function getCount(key) {
    // fetch count every 10 min
    // ...

    return counts[key] || 0
  }

  function fetchCounting() {
    useRequest(
      apiRoutes.user.main.countOfStuffs,
      null,
      {
        success(response) {
          counts = response.data
        },
      }
    )
  }

  function $reset() {
    counts.value = {}
  }

  return {
    counts,
    getCount,
    //
    $reset,
  }
})
