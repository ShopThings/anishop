import {computed, ref, toValue} from "vue"
import {defineStore} from "pinia"
import {useSafeLocalStorage} from "../composables/safe-local-storage.js";

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
        $reset
    }
})
