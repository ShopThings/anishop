import {computed, ref, toValue} from "vue"
import {defineStore} from "pinia"
import {useSafeLocalStorage} from "@/composables/safe-local-storage.js";
import {useRequestWrapper} from "@/composables/api-request.js";
import {apiRoutes} from "@/router/api-routes.js";

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

export const PERMISSIONS = {
  READ: 'read',
  CREATE: 'create',
  UPDATE: 'update',
  DELETE: 'delete',
  PERMANENT_DELETE: 'permanent_delete',
  PRINT: 'print',
  EXPORT: 'export',
  IMPORT: 'import',
  WATCH: 'watch',
  PUBLISH: 'publish',
  VERIFY: 'verify',
  BAN: 'ban',
  DOWNLOAD: 'download',
  UPLOAD: 'upload',
}

export const PERMISSION_PLACES = {
  ADDRESS_USER: 'address_user',
  BLOG: 'blog',
  BLOG_CATEGORY: 'blog_category',
  BLOG_COMMENT: 'blog_comment',
  BLOG_COMMENT_BADGE: 'blog_comment_badge',
  BRAND: 'brand',
  CATEGORY: 'category',
  CATEGORY_IMAGE: 'category_image',
  CITY_POST_PRICE: 'city_post_price',
  COLOR: 'color',
  COMPLAINT: 'complaint',
  CONTACT_US: 'contact_us',
  COUPON: 'coupon',
  FAQ: 'faq',
  FESTIVAL: 'festival',
  MENU: 'menu',
  NEWSLETTER: 'newsletter',
  ORDER: 'order',
  ORDER_BADGE: 'order_badge',
  PAYMENT_METHOD: 'payment_method',
  SEND_METHOD: 'send_method',
  PRODUCT: 'product',
  PRODUCT_COMMENT: 'product_comment',
  PRODUCT_ATTRIBUTE: 'product_attribute',
  RETURN_ORDER_REQUEST: 'return_order_request',
  SETTING: 'setting',
  SLIDER: 'slider',
  SMS_LOG: 'sms_log',
  STATIC_PAGE: 'static_page',
  UNIT: 'unit',
  USER: 'user',
  USER_FAVORITE_PRODUCT: 'user_favorite_product',
  USER_NOTIFICATION: 'user_notification',
  WEIGHT_POST_PRICE: 'weight_post_price',
  FILE_MANAGER: 'file_manager',
}

function placePermission(place, ability) {
  return place + '.' + ability
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

    // remove duplicates
    role = [...role]

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

    // remove duplicates
    role = [...role]

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

  function logout(callbacks) {
    useRequestWrapper(apiRoutes.user.logout, {method: 'POST'}, {
      success() {
        $reset()
      },
    }, callbacks)
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
  const permissionStorageKey = 'user_admin_permissions'
  const tokenStorageKey = 'user_token_admin'
  const user = ref(safeStorage.getItem(userStorageKey))
  const permissions = ref(safeStorage.getItem(permissionStorageKey))
  const token = ref(safeStorage.getItem(tokenStorageKey))
  const loading = ref(false)

  const isLoading = computed(() => loading.value)
  const getToken = computed(() => token.value)
  const getUser = computed(() => user.value)
  const getPermissions = computed(() => permissions.value)

  function setToken(value) {
    safeStorage.setItem(tokenStorageKey, JSON.stringify(value))
    token.value = toValue(value)
  }

  function setUser(payload) {
    safeStorage.setItem(userStorageKey, JSON.stringify(payload))
    user.value = toValue(payload)
  }

  function setPermissions(payload) {
    safeStorage.setItem(permissionStorageKey, JSON.stringify(payload))
    permissions.value = toValue(payload)
  }

  function hasRole(role) {
    const roles = user.value?.roles

    if (!roles) return false

    role = !Array.isArray(role) ? [role] : role

    // remove duplicates
    role = [...role]

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

    // remove duplicates
    role = [...role]

    for (let r in roles) {
      if (roles.hasOwnProperty(r)) {
        if (role.indexOf(r) !== -1) return true
      }
    }

    return false
  }

  function hasPermission(place, ability) {
    if (!permissions.value) return false

    let places = Array.isArray(place) ? place : [place]
    let abilities = Array.isArray(ability) ? ability : [ability]

    // remove duplicates
    places = [...places]
    abilities = [...abilities]

    for (let p of places) {
      for (let a of abilities) {
        let permission = placePermission(p, a)

        for (let r in permissions.value) {
          if (permissions.value.hasOwnProperty(r)) {
            if (permission === permissions.value[r]) return true
          }
        }
      }
    }

    return false
  }

  function login(data, callbacks) {
    return useRequestWrapper(apiRoutes.admin.login, {
      method: 'POST',
      data,
    }, {
      beforeRequest() {
        loading.value = true
      },
      success(response) {
        setToken(response.data?.token)
        setUser(response.data?.user)
        setPermissions(response.data?.permissions)
      },
      finally() {
        // this timeout is a little delay to make success do its thing first
        setTimeout(() => {
          loading.value = false
        }, 25)
      }
    }, callbacks)
  }

  function logout(callbacks) {
    return useRequestWrapper(apiRoutes.admin.logout, {method: 'POST'}, {
      success() {
        $reset()
      },
    }, callbacks)
  }

  function $reset() {
    safeStorage.removeItem(tokenStorageKey)
    safeStorage.removeItem(userStorageKey)
    safeStorage.removeItem(permissionStorageKey)
    user.value = null
    permissions.value = null
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
    permissions, getPermissions, setPermissions,
    //
    hasRole, hasAnyRole,
    hasPermission,
    //
    login, logout, isLoading,
    //
    $reset
  }
})
