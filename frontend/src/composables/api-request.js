import axios from "axios"
import isFunction from 'lodash.isfunction'
import {useAdminAuthStore, useUserAuthStore} from "@/store/StoreUserAuth.js"
import {useToast} from "vue-toastification"
import router from "@/router/index.js";
import isObject from "lodash.isobject";
import {useSafeLocalStorage} from "@/composables/safe-local-storage.js";

export const responseTypes = {
  success: 'success',
  info: 'info',
  warning: 'warning',
  error: 'error',
}

export const responseStatuses = {
  HTTP_CONTINUE: 100,
  HTTP_SWITCHING_PROTOCOLS: 101,
  HTTP_PROCESSING: 102,
  HTTP_EARLY_HINTS: 103,
  HTTP_OK: 200,
  HTTP_CREATED: 201,
  HTTP_ACCEPTED: 202,
  HTTP_NON_AUTHORITATIVE_INFORMATION: 203,
  HTTP_NO_CONTENT: 204,
  HTTP_RESET_CONTENT: 205,
  HTTP_PARTIAL_CONTENT: 206,
  HTTP_MULTI_STATUS: 207,
  HTTP_ALREADY_REPORTED: 208,
  HTTP_IM_USED: 226,
  HTTP_MULTIPLE_CHOICES: 300,
  HTTP_MOVED_PERMANENTLY: 301,
  HTTP_FOUND: 302,
  HTTP_SEE_OTHER: 303,
  HTTP_NOT_MODIFIED: 304,
  HTTP_USE_PROXY: 305,
  HTTP_RESERVED: 306,
  HTTP_TEMPORARY_REDIRECT: 307,
  HTTP_PERMANENTLY_REDIRECT: 308,
  HTTP_BAD_REQUEST: 400,
  HTTP_UNAUTHORIZED: 401,
  HTTP_PAYMENT_REQUIRED: 402,
  HTTP_FORBIDDEN: 403,
  HTTP_NOT_FOUND: 404,
  HTTP_METHOD_NOT_ALLOWED: 405,
  HTTP_NOT_ACCEPTABLE: 406,
  HTTP_PROXY_AUTHENTICATION_REQUIRED: 407,
  HTTP_REQUEST_TIMEOUT: 408,
  HTTP_CONFLICT: 409,
  HTTP_GONE: 410,
  HTTP_LENGTH_REQUIRED: 411,
  HTTP_PRECONDITION_FAILED: 412,
  HTTP_REQUEST_ENTITY_TOO_LARGE: 413,
  HTTP_REQUEST_URI_TOO_LONG: 414,
  HTTP_UNSUPPORTED_MEDIA_TYPE: 415,
  HTTP_REQUESTED_RANGE_NOT_SATISFIABLE: 416,
  HTTP_EXPECTATION_FAILED: 417,
  HTTP_I_AM_A_TEAPOT: 418,
  HTTP_MISDIRECTED_REQUEST: 421,
  HTTP_UNPROCESSABLE_ENTITY: 422,
  HTTP_LOCKED: 423,
  HTTP_FAILED_DEPENDENCY: 424,
  HTTP_TOO_EARLY: 425,
  HTTP_UPGRADE_REQUIRED: 426,
  HTTP_PRECONDITION_REQUIRED: 428,
  HTTP_TOO_MANY_REQUESTS: 429,
  HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE: 431,
  HTTP_UNAVAILABLE_FOR_LEGAL_REASONS: 451,
  HTTP_INTERNAL_SERVER_ERROR: 500,
  HTTP_NOT_IMPLEMENTED: 501,
  HTTP_BAD_GATEWAY: 502,
  HTTP_SERVICE_UNAVAILABLE: 503,
  HTTP_GATEWAY_TIMEOUT: 504,
  HTTP_VERSION_NOT_SUPPORTED: 505,
  HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL: 506,
  HTTP_INSUFFICIENT_STORAGE: 507,
  HTTP_LOOP_DETECTED: 508,
  HTTP_NOT_EXTENDED: 510,
  HTTP_NETWORK_AUTHENTICATION_REQUIRED: 511,
}

const axiosClient = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
})

axiosClient.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
axiosClient.defaults.headers.common['Content-Type'] = 'application/json'

axiosClient.interceptors.request.use((config) => {
  // If we want check maintenance, we should attach secret if exists to sending info
  const secretCode = useSafeLocalStorage.getItem('maintenance_secret')

  if (secretCode) {
    // Add maintenance_secret to the request params or data for all requests
    if (config.params) {
      config.params.maintenance_secret = secretCode
    } else {
      config.params = {maintenance_secret: secretCode}
    }

    if (config.method !== 'get') {
      if (config.data) {
        config.data.maintenance_secret = secretCode
      } else {
        config.data = {maintenance_secret: secretCode}
      }
    }
  }

  // Attach logged-in user token, to headers
  const store = useUserAuthStore()
  const adminStore = useAdminAuthStore()

  if (config.url.indexOf('/admin/') !== -1) {
    if (adminStore.getToken) {
      config.headers.Authorization = `Bearer ${adminStore.getToken}`
    }
  } else {
    if (store.getToken) {
      config.headers.Authorization = `Bearer ${store.getToken}`
    }
  }

  return config
})

axiosClient.interceptors.response.use(response => {
  return response
}, error => {
  const store = useUserAuthStore()
  const adminStore = useAdminAuthStore()

  if (error?.response?.status === responseStatuses.HTTP_FORBIDDEN) {
    const route = window.location.pathname

    if (route.indexOf('/admin') !== -1) {
      adminStore.$reset()
      const pushObj = {name: 'admin.login'}

      if (route !== '/admin/login') {
        pushObj.query = {redirect: route}
      }

      router.push(pushObj)
    } else {
      store.$reset()
      const pushObj = {name: 'login'}

      if (route !== '/login') {
        pushObj.query = {redirect: route}
      }

      router.push(pushObj)
    }
  }

  throw error
})

export default axiosClient

export const useRequest = async (url, config, resultConfig) => {
  return new Promise((resolve, reject) => {
    const toast = useToast()

    config = config || {}
    const silent = resultConfig?.silent === true
    const onBeforeRequest = resultConfig?.beforeRequest
    const onSuccess = resultConfig?.success
    const onCriticalError = resultConfig?.criticalError
    const onError = resultConfig?.error
    const onAnyError = resultConfig?.anyError
    const onFinally = resultConfig?.finally

    if (isFunction(onBeforeRequest)) {
      onBeforeRequest()
    }

    config['method'] = config['method'] || 'GET'

    axiosClient(url, config)
      .then((response) => {
        const data = response.data || []
        const type = data?.type || data.data?.type
        const msg = data?.message || data.data?.message

        let total = 0
        if (data?.meta?.total) {
          total = data?.meta?.total
        } else if (Array.isArray(data?.data) || Array.isArray(data)) {
          total = data?.data?.length || data.length
        } else if (isObject(data?.data) || isObject(data)) {
          total = 1
        }

        let ans = true
        if (isFunction(onSuccess)) {
          ans = onSuccess(data, total, response)
        }

        // if returned value is false, overwrite functionality
        if (!silent && ans !== false && msg && response.status !== responseStatuses.HTTP_NO_CONTENT) {
          if (type && toast[type]) {
            toast[type](msg)
          }
        }

        resolve(data) // Resolve with data
      })
      .catch((error) => {
        const data = error?.response?.data || error?.response?.data?.data || []
        const type = data?.type
        const msg = data?.message || data?.data?.message || error?.response?.statusText || error.message

        // mostly it has debugging purposes
        if ((isObject(data) && !Object.keys(data).length) ||
          (Array.isArray(data) && !data.length) ||
          error?.response.status >= responseStatuses.HTTP_INTERNAL_SERVER_ERROR ||
          error?.request.status >= responseStatuses.HTTP_INTERNAL_SERVER_ERROR ||
          +error?.request.status === responseStatuses.HTTP_METHOD_NOT_ALLOWED
        ) {
          if (msg.toLowerCase() !== "canceled") {
            if (import.meta.env.DEV) {
              console.error(error)
            }

            if (isFunction(onAnyError)) {
              onAnyError(null, msg)
            }

            if (isFunction(onCriticalError)) {
              onCriticalError(msg)
            }

            if (!silent) {
              toast.error('خطا در ارتباط با سرور و دریافت اطلاعات!')
            }
          }

          reject(null, msg)
          return
        }

        if (isFunction(onAnyError)) {
          onAnyError(data, msg)
        }

        let ans = true
        if (isFunction(onError)) {
          ans = onError(data, msg)
        }

        // if returned value is false, overwrite functionality
        if (!silent && ans !== false && msg) {
          if (type && toast[type]) {
            toast[type](msg)
          } else {
            toast.error(msg)
          }
        }

        reject(data, msg)
      })
      .finally(() => {
        if (isFunction(onFinally)) {
          onFinally()
        }
      })
  })
}

export async function useRequestWrapper(url, config, callbacks, userCallbacks) {
  const silent = callbacks?.silent === true
  const onBeforeRequest = callbacks?.beforeRequest
  const onSuccess = callbacks?.success
  const onCriticalError = callbacks?.criticalError
  const onError = callbacks?.error
  const onAnyError = callbacks?.anyError
  const onFinally = callbacks?.finally

  const onUserSuccess = userCallbacks?.success
  const onUserCriticalError = userCallbacks?.criticalError
  const onUserError = userCallbacks?.error
  const onUserAnyError = userCallbacks?.anyError
  const onUserFinally = userCallbacks?.finally

  return useRequest(url, config, {
    silent,
    beforeRequest: onBeforeRequest,
    success: (response, total) => {
      if (isFunction(onSuccess)) {
        onSuccess(response, total)
      }

      let answer = true
      if (isFunction(onUserSuccess)) {
        answer = onUserSuccess(response, total)
      }

      return answer !== false
    },
    criticalError: (...err) => {
      if (isFunction(onCriticalError)) {
        onCriticalError(...err)
      }

      if (isFunction(onUserCriticalError)) {
        onUserCriticalError(...err)
      }
    },
    error: (...err) => {
      if (isFunction(onError)) {
        onError(...err)
      }

      let answer = true
      if (isFunction(onUserError)) {
        answer = onUserError(...err)
      }

      return answer !== false
    },
    anyError: (...err) => {
      if (isFunction(onAnyError)) {
        onAnyError(...err)
      }

      if (isFunction(onUserAnyError)) {
        onUserAnyError(...err)
      }
    },
    finally: () => {
      if (isFunction(onFinally)) {
        onFinally()
      }

      if (isFunction(onUserFinally)) {
        onUserFinally()
      }
    },
  })
}
