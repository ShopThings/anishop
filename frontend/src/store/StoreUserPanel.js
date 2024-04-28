import {computed, onBeforeMount, onBeforeUnmount, reactive, ref} from "vue"
import {defineStore} from "pinia"
import {useCountdown} from "@/composables/countdown-timer.js";
import {UserPanelDashboardAPI} from "@/service/APIUserPanel.js";
import {UserNotificationAPI} from "@/service/APINotification.js";

export const useCountingStuffsStore = defineStore('userPanelCounting', () => {
  let counts = reactive({})
  const countdown = useCountdown(600)

  fetchCounting()

  function getCountByKey(key) {
    return counts[key] || 0
  }

  const getOrderCount = computed(() => {
    return getCountByKey('order_count')
  })

  const getReturnOrderCount = computed(() => {
    return getCountByKey('return_order_count')
  })

  const getProductCommentCount = computed(() => {
    return getCountByKey('product_comment_count')
  })

  const getBlogCommentCount = computed(() => {
    return getCountByKey('blog_comment_count')
  })

  const getFavoriteProductCount = computed(() => {
    return getCountByKey('favorite_product_count')
  })

  const getAddressCount = computed(() => {
    return getCountByKey('address_count')
  })

  const getContactCount = computed(() => {
    return getCountByKey('contact_count')
  })

  function fetchCounting() {
    countdown.pause()

    UserPanelDashboardAPI.getCountOfStuffs({
      silent: true,
      success(response) {
        for (const key in response.data) {
          counts[key] = response.data[key];
        }

        return false
      },
      error() {
        return false
      },
      finally() {
        countdown.reset()
        countdown.resume()
      },
    })
  }

  function $reset() {
    countdown.stop()

    Object.keys(counts).forEach((key) => {
      delete counts[key];
    });

    countdown.start(fetchCounting)
  }

  onBeforeMount(() => {
    if (!countdown.isStarted.value) {
      countdown.start(fetchCounting)
    }
  })

  onBeforeUnmount(() => {
    countdown.stop()
  })

  return {
    counts,
    getCountByKey, getOrderCount, getReturnOrderCount,
    getProductCommentCount, getBlogCommentCount, getAddressCount,
    getFavoriteProductCount, getContactCount,
    //
    $reset,
  }
})

export const useNotificationStore = defineStore('userPanelNotifications', () => {
  let notifications = ref([])
  const countdown = useCountdown(300)

  checkNewNotifications()

  const hasNewNotification = computed(() => {
    return !!(notifications.value?.length)
  })

  const newNotificationsCount = computed(() => {
    return notifications.value?.length ?? 0
  })

  function checkNewNotifications() {
    countdown.pause()

    UserNotificationAPI.checkNotifications({
      silent: true,
      success(response) {
        notifications.value = response.data

        return false
      },
      error() {
        return false
      },
      finally() {
        countdown.reset()
        countdown.resume()
      },
    })
  }

  function $reset() {
    countdown.stop()

    notifications.value = []

    countdown.start(checkNewNotifications)
  }

  onBeforeMount(() => {
    if (!countdown.isStarted.value) {
      countdown.start(checkNewNotifications)
    }
  })

  onBeforeUnmount(() => {
    countdown.stop()
  })

  return {
    notifications,
    hasNewNotification, newNotificationsCount,
    //
    $reset,
  }
})
