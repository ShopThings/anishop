import {computed, ref, toValue} from "vue"
import {defineStore} from "pinia"
import {useSafeLocalStorage} from "../composables/safe-local-storage.js";
import {useRequestWrapper} from "../composables/api-request.js";
import {apiRoutes} from "../router/api-routes.js";

export const ROLES = {
  DEVELOPER: 'developer',
  SUPER_ADMIN: 'super_admin',
  ADMIN: 'admin',
  USER_MANAGER: 'user_manager',
  PRODUCT_MANAGER: 'product_manager',
  ORDER_MANAGER: 'order_manager',
  WRITER: 'writer',
  USER: 'user',
}

const safeStorage = useSafeLocalStorage

export const useUserAuthStore = defineStore('user_auth', () => {
  const userStorageKey = 'user'
  const tokenStorageKey = 'user_token'
  const user = ref(safeStorage.getItem(userStorageKey))
  const token = ref(safeStorage.getItem(tokenStorageKey))
  const loading = ref(false)

  const isLoading = computed(() => loading.value)
  const getToken = computed(() => token.value)
  const getUser = computed(() => user.value)

  function setToken(value) {
    safeStorage.setItem(tokenStorageKey, JSON.stringify(value))
    token.value = toValue(value)
  }

  function setUser(payload) {
    safeStorage.setItem(userStorageKey, JSON.stringify(payload))
    user.value = toValue(payload)
  }

  function hasRole(role) {
    const roles = user.value?.roles

    if (!roles) return false

    role = !Array.isArray(role) ? [role] : role
    role = role.filter((item, i, ar) => ar.indexOf(item) === i)

    let counter = 0
    for (let r in roles) {
      if (roles.hasOwnProperty(r)) {
        if (role.indexOf(r) !== -1) counter++
      }
    }

    return counter >= role.length;

  }

  function hasAnyRole(role) {
    const roles = user.value?.roles

    if (!roles) return false

    role = !Array.isArray(role) ? [role] : role
    role = role.filter((item, i, ar) => ar.indexOf(item) === i)

    for (let r in roles) {
      if (roles.hasOwnProperty(r)) {
        if (role.indexOf(r) !== -1) return true
      }
    }

    return false
  }

  function login(data, callbacks) {
    useRequestWrapper(apiRoutes.user.login, {
      method: 'POST',
      data,
    }, {
      beforeRequest() {
        loading.value = true
      },
      success(response) {
        setToken(response.data?.token)
        setUser(response.data?.user)
      },
      finally() {
        // this timeout is a little delay to make success do its thing first
        setTimeout(() => {
          loading.value = false
        }, 25)
      },
    }, callbacks)
  }

  function logout() {
    useRequestWrapper(apiRoutes.user.logout, {method: 'POST'}, {
      success() {
        $reset()
      },
    })
  }

  function $reset() {
    safeStorage.removeItem(tokenStorageKey)
    safeStorage.removeItem(userStorageKey)
    user.value = null
    token.value = null
  }

  // refs' become states,
  // computed() become getters
  // and functions become actions
  return {
    token, getToken, setToken,
    //
    user, getUser, setUser,
    //
    hasRole, hasAnyRole,
    //
    login, logout, isLoading,
    //
    $reset
  }
})

export const useAdminAuthStore = defineStore('admin_auth', () => {
  const userStorageKey = 'user_admin'
  const tokenStorageKey = 'user_token_admin'
  const user = ref(safeStorage.getItem(userStorageKey))
  const token = ref(safeStorage.getItem(tokenStorageKey))
  const loading = ref(false)

  const isLoading = computed(() => loading.value)
  const getToken = computed(() => token.value)
  const getUser = computed(() => user.value)

  function setToken(value) {
    safeStorage.setItem(tokenStorageKey, JSON.stringify(value))
    token.value = toValue(value)
  }

  function setUser(payload) {
    safeStorage.setItem(userStorageKey, JSON.stringify(payload))
    user.value = toValue(payload)
  }

  function hasRole(role) {
    const roles = user.value?.roles

    if (!roles) return false

    role = !Array.isArray(role) ? [role] : role
    role = role.filter((item, i, ar) => ar.indexOf(item) === i)

    let counter = 0
    for (let r in roles) {
      if (roles.hasOwnProperty(r)) {
        if (role.indexOf(r) !== -1) counter++
      }
    }

    return counter >= role.length
  }

  function hasAnyRole(role) {
    const roles = user.value?.roles

    if (!roles) return false

    role = !Array.isArray(role) ? [role] : role
    role = role.filter((item, i, ar) => ar.indexOf(item) === i)

    for (let r in roles) {
      if (roles.hasOwnProperty(r)) {
        if (role.indexOf(r) !== -1) return true
      }
    }

    return false
  }

  function login(data, callbacks) {
    useRequestWrapper(apiRoutes.admin.login, {
      method: 'POST',
      data,
    }, {
      beforeRequest() {
        loading.value = true
      },
      success(response) {
        setToken(response.data?.token)
        setUser(response.data?.user)
      },
      finally() {
        // this timeout is a little delay to make success do its thing first
        setTimeout(() => {
          loading.value = false
        }, 25)
      }
    }, callbacks)
  }

  function logout() {
    useRequestWrapper(apiRoutes.admin.logout, {method: 'POST'}, {
      success() {
        $reset()
      },
    })
  }

  function $reset() {
    safeStorage.removeItem(tokenStorageKey)
    safeStorage.removeItem(userStorageKey)
    user.value = null
    token.value = null
  }

  // refs' become states,
  // computed() become getters
  // and functions become actions
  return {
    token, getToken, setToken,
    //
    user, getUser, setUser,
    //
    hasRole, hasAnyRole,
    //
    login, logout, isLoading,
    //
    $reset
  }
})
