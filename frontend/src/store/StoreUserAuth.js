import {computed, ref, toValue} from "vue"
import {defineStore} from "pinia"
import {useSafeLocalStorage} from "../composables/safe-local-storage.js";
import isArray from "lodash.isarray";

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

export const useUserStore = defineStore('user', () => {
    const userStorageKey = 'user'
    const tokenStorageKey = 'user_token'
    const user = ref(safeStorage.getItem(userStorageKey))
    const token = ref(safeStorage.getItem(tokenStorageKey))

    const getToken = computed(() => {
        return token.value
    })

    function setToken(value) {
        safeStorage.setItem(tokenStorageKey, JSON.stringify(value))
        token.value = toValue(value)
    }

    const getUser = computed(() => {
        return user.value
    })

    function setUser(payload) {
        safeStorage.setItem(userStorageKey, JSON.stringify(payload))
        user.value = toValue(payload)
    }

    function hasRole(role) {
        const roles = user.value?.roles

        if (!roles) return false

        role = !isArray(role) ? [role] : role
        role = role.filter((item, i, ar) => ar.indexOf(item) === i)

        let counter = 0
        for (let r in roles) {
            if (roles.hasOwnProperty(r)) {
                if (role.indexOf(r) !== -1) counter++
            }
        }

        if (counter >= role.length) return true
        return false
    }

    function hasAnyRole(role) {
        const roles = user.value?.roles

        if (!roles) return false

        role = !isArray(role) ? [role] : role
        role = role.filter((item, i, ar) => ar.indexOf(item) === i)

        for (let r in roles) {
            if (roles.hasOwnProperty(r)) {
                if (role.indexOf(r) !== -1) return true
            }
        }

        return false
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
        token,
        getToken,
        setToken,
        //
        user,
        getUser,
        setUser,
        //
        hasRole,
        hasAnyRole,
        //
        $reset
    }
})

export const useAdminStore = defineStore('admin', () => {
    const userStorageKey = 'user_admin'
    const tokenStorageKey = 'user_token_admin'
    const user = ref(safeStorage.getItem(userStorageKey))
    const token = ref(safeStorage.getItem(tokenStorageKey))

    const getToken = computed(() => {
        return token.value
    })

    function setToken(value) {
        safeStorage.setItem(tokenStorageKey, JSON.stringify(value))
        token.value = toValue(value)
    }

    const getUser = computed(() => {
        return user.value
    })

    function setUser(payload) {
        safeStorage.setItem(userStorageKey, JSON.stringify(payload))
        user.value = toValue(payload)
    }

    function hasRole(role) {
        const roles = user.value?.roles

        if (!roles) return false

        role = !isArray(role) ? [role] : role
        role = role.filter((item, i, ar) => ar.indexOf(item) === i)

        let counter = 0
        for (let r in roles) {
            if (roles.hasOwnProperty(r)) {
                if (role.indexOf(r) !== -1) counter++
            }
        }

        if (counter >= role.length) return true
        return false
    }

    function hasAnyRole(role) {
        const roles = user.value?.roles

        if (!roles) return false

        role = !isArray(role) ? [role] : role
        role = role.filter((item, i, ar) => ar.indexOf(item) === i)

        for (let r in roles) {
            if (roles.hasOwnProperty(r)) {
                if (role.indexOf(r) !== -1) return true
            }
        }

        return false
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
        token,
        getToken,
        setToken,
        //
        user,
        getUser,
        setUser,
        //
        hasRole,
        hasAnyRole,
        //
        $reset
    }
})
