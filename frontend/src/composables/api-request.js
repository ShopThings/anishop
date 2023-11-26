import axios from "axios"
import isFunction from 'lodash.isfunction'
import {useAdminAuthStore, useUserAuthStore} from "../store/StoreUserAuth.js"
import {useToast} from "vue-toastification"
import router from "../router/index.js";
import isObject from "lodash.isobject";
import isArray from "lodash.isarray";

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
    const store = useUserAuthStore()
    const adminStore = useAdminAuthStore()
    if (config.url.indexOf('/admin/') !== -1 && adminStore.getToken) {
        config.headers.Authorization = `Bearer ${adminStore.getToken}`
    } else if (store.getToken) {
        config.headers.Authorization = `Bearer ${store.getToken}`
    }
    return config
})

axiosClient.interceptors.response.use(response => {
    return response;
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

export const useRequest = (url, config, resultConfig) => {
    const toast = useToast()

    config = config || {}
    const onBeforeRequest = resultConfig?.beforeRequest
    const onSuccess = resultConfig?.success
    const onError = resultConfig?.error
    const onFinally = resultConfig?.finally

    if (isFunction(onBeforeRequest))
        onBeforeRequest.apply(null)

    config['method'] = config['method'] || 'GET'
    axiosClient(url, config)
        .then((response) => {
            const data = response.data || []
            const type = response.data?.data?.type || data?.type
            const msg = response.data?.data?.message || response.data?.message

            let total = 0
            if (data?.meta?.total) {
                total = data?.meta?.total
            } else if (isArray(data?.data) || isArray(data)) {
                total = data?.data?.length || data.length
            } else if (isObject(data?.data) || isObject(data)) {
                total = 1
            }

            let ans = true
            if (isFunction(onSuccess))
                ans = onSuccess.apply(null, [data, total])

            // if returned value is false, overwrite functionality
            if (ans !== false && msg && response.status !== responseStatuses.HTTP_NO_CONTENT) {
                if (type && type === responseTypes.info)
                    toast.info(msg)
                else
                    toast.success(msg)
            }
        })
        .catch((error) => {
            const data = error?.response?.data?.data || error?.response?.data || []
            const type = error?.response?.data?.data?.type
            const msg = error?.response?.data?.data?.message ||
                error?.response?.data.message ||
                error?.response?.statusText ||
                error.message

            // mostly is has debugging purposes
            if ((isObject(data) && !Object.keys(data).length) ||
                (isArray(data) && !data.length) ||
                error?.response.status >= responseStatuses.HTTP_INTERNAL_SERVER_ERROR ||
                error?.request.status >= responseStatuses.HTTP_INTERNAL_SERVER_ERROR
            ) {
                if (msg.toLowerCase() !== "canceled") {
                    console.error(error)
                    toast.error('خطا در ارتباط با سرور و دریافت اطلاعات!')
                }
                return
            }

            let ans = true
            if (isFunction(onError))
                ans = onError.apply(null, [data])

            // if returned value is false, overwrite functionality
            if (ans !== false && msg) {
                if (type && type === responseTypes.error)
                    toast.error(msg)
                else if (type && type === responseTypes.info)
                    toast.info(msg)
                else if (type && type === responseTypes.warning)
                    toast.warning(msg)
                else
                    toast.error(msg)
            }
        })
        .finally(() => {
            if (isFunction(onFinally))
                onFinally.apply(null)
        })
}

export function useRequestWrapper(url, config, callbacks, userCallbacks) {
    const onBeforeRequest = callbacks?.beforeRequest
    const onSuccess = callbacks?.success
    const onError = callbacks?.error
    const onFinally = callbacks?.finally

    const onUserSuccess = userCallbacks?.success
    const onUserError = userCallbacks?.error
    const onUserFinally = userCallbacks?.finally

    useRequest(url, config, {
        beforeRequest: onBeforeRequest,
        success: (response, total) => {

            if (isFunction(onSuccess))
                onSuccess.apply(null, [response, total])

            let answer = true;
            if (isFunction(onUserSuccess))
                answer = onUserSuccess.apply(null, [response, total])

            return answer !== false
        },
        error: (err) => {
            if (isFunction(onError))
                onError.apply(null, [err])

            let answer = true;
            if (isFunction(onUserError))
                answer = onUserError.apply(null, [err])

            return answer !== false
        },
        finally: () => {
            if (isFunction(onFinally))
                onFinally.apply(null)

            if (isFunction(onUserFinally))
                onUserFinally.apply(null)
        },
    })
}
