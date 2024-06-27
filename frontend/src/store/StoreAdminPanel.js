import {computed, onBeforeMount, onBeforeUnmount, reactive, ref} from "vue";
import {defineStore} from "pinia";
import {useCountdown} from "@/composables/countdown-timer.js";
import {AdminPanelDashboardAPI} from "@/service/APIAdminPanel.js";
import {findItemByKey} from "@/composables/helper.js";
import {NotificationAPI} from "@/service/APINotification.js";
import {useOnline} from "@vueuse/core";

export const useCountingAlertsStore = defineStore('adminPanelAlertCounting', () => {
  let counts = reactive({})
  let prevCounts = {
    product_comment_count: 0,
    blog_comment_count: 0,
    return_order_count: 0,
    contact_count: 0,
    complaint_count: 0,
  }
  const countdown = useCountdown(600)
  const online = useOnline()

  function getCountByKey(key) {
    return counts.hasOwnProperty(key) ? counts[key] : null;
  }

  const getProductCommentCount = computed(() => {
    return getCountByKey('product_comment_count')
  })

  const getBlogCommentCount = computed(() => {
    return getCountByKey('blog_comment_count')
  })

  const getReturnOrderCount = computed(() => {
    return getCountByKey('return_order_count')
  })

  const getContactCount = computed(() => {
    return getCountByKey('contact_count')
  })

  const getComplaintCount = computed(() => {
    return getCountByKey('complaint_count')
  })

  const hasAnyCount = computed(() => {
    return getProductCommentCount.value !== null ||
      getBlogCommentCount.value !== null ||
      getReturnOrderCount.value !== null ||
      getContactCount.value !== null ||
      getComplaintCount.value !== null
  })

  const hasNewChange = computed(() => {
    // Check if counts and prevCounts have the same keys and values
    for (const key in counts) {
      if (!prevCounts.hasOwnProperty(key) || +counts[key] !== +prevCounts[key]) {
        return true; // There's a change, return true
      }
    }

    // If we reached here, there are no changes
    return false;
  })

  function fetchCounting() {
    if (!online.value) return

    countdown.pause()

    if (hasNewChange.value) {
      // Update prevCounts only if there's a change
      prevCounts = {...counts};
    }

    return AdminPanelDashboardAPI.getCountAlerts({
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

  fetchCounting()

  async function $reset() {
    countdown.stop()

    prevCounts = {}
    Object.keys(counts).forEach((key) => {
      delete counts[key];
    });

    await fetchCounting()

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
    //
    getCountByKey, getProductCommentCount,
    getBlogCommentCount, getReturnOrderCount,
    getContactCount, getComplaintCount,
    //
    hasAnyCount, hasNewChange,
    //
    $reset,
  }
})

export const useCountingOrdersStore = defineStore('adminPanelOrderCounting', () => {
  const loading = ref(false)
  let counts = ref([])
  let prevCounts = []
  const countdown = useCountdown(300)
  const online = useOnline()

  const isLoading = computed(() => loading.value)

  const getCounts = computed(() => {
    return counts.value
  })

  const hasNewChange = computed(() => {
    if (!counts.value.length) return false

    for (let i of counts.value) {
      let item = findItemByKey(prevCounts, 'code', i.code)
      if (item?.count || +i.count !== +item.count) {
        return true
      }
    }

    return false
  })

  function fetchCounting() {
    if (!online.value || loading.value) return

    countdown.pause()

    if (hasNewChange.value) {
      // Update prevCounts only if there's a change
      prevCounts = [...counts.value];
    }

    loading.value = true

    return AdminPanelDashboardAPI.getCountOrders({
      silent: true,
      success(response) {
        counts.value = response.data

        return false
      },
      error() {
        return false
      },
      finally() {
        loading.value = false

        countdown.reset()
        countdown.resume()
      },
    })
  }

  fetchCounting()

  async function $reset() {
    countdown.stop()

    prevCounts = []
    counts.value = []

    await fetchCounting()

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
    isLoading,
    counts, getCounts,
    //
    fetchCounting,
    hasNewChange,
    //
    $reset,
  }
})

export const useNotificationStore = defineStore('adminPanelNotifications', () => {
  let notifications = ref([])
  let loading = ref(false)
  const countdown = useCountdown(300)
  const online = useOnline()

  const isLoading = computed(() => {
    return loading.value
  })

  const hasNewNotification = computed(() => {
    return !!(notifications.value?.length)
  })

  const newNotificationsCount = computed(() => {
    return notifications.value?.length ?? 0
  })

  function checkNewNotifications() {
    if (!online.value || isLoading.value) return

    countdown.pause()

    loading.value = true
    return NotificationAPI.checkNotifications({
      silent: true,
      success(response) {
        notifications.value = response.data

        return false
      },
      error() {
        return false
      },
      finally() {
        loading.value = false

        countdown.reset()
        countdown.resume()
      },
    })
  }

  checkNewNotifications()

  async function $reset() {
    countdown.stop()

    loading.value = false
    notifications.value = []

    await checkNewNotifications()

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
    loading,
    isLoading,
    //
    notifications,
    hasNewNotification, newNotificationsCount,
    //
    $reset,
  }
})
