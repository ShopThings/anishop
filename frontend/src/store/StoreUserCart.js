import {defineStore} from "pinia";
import {computed, nextTick, ref} from "vue";
import {useSafeLocalStorage} from "@/composables/safe-local-storage.js";
import {useUserAuthStore} from "@/store/StoreUserAuth.js";
import {useRequestWrapper} from "@/composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "@/router/api-routes.js";
import {useToast} from "vue-toastification";
import {useConfirmToast} from "@/composables/toast-helper.js";

const safeStorage = useSafeLocalStorage

export const useCartStore = defineStore('userCart', () => {
  const cartStorageKey = 'cart_items'
  const cartNameStorageKey = 'active_cart_name'
  const activeCart = ref(safeStorage.getItem(cartNameStorageKey) || 'shopping')

  const loading = ref(false)

  const carts = ref({
    shopping: [],
    wishlist: [],
  })
  const cartsLocal = ref({
    shopping: [],
    wishlist: [],
  })
  const cartItems = ref([])

  const toast = useToast()
  const userStore = useUserAuthStore()

  const isLoading = computed(() => {
    return loading.value
  })

  const getSavedCarts = computed(() => {
    return carts.value
  })

  const getLocalCarts = computed(() => {
    return cartsLocal.value
  })

  const getActiveCart = computed(() => {
    return activeCart.value
  })

  const getCartItems = computed(() => {
    return cartItems.value
  })

  const isShoppingCartActivated = computed(() => {
    return getActiveCart.value === 'shopping'
  })

  const isWishlistCartActivated = computed(() => {
    return getActiveCart.value === 'wishlist'
  })

  function saveToLocalStorage() {
    safeStorage.setItem(cartStorageKey, JSON.stringify(getLocalCarts.value))
  }

  function saveCurrentToLocalStorage() {
    if (isShoppingCartActivated.value) {
      cartsLocal.value = {
        shopping: cartItems.value,
        wishlist: getLocalCarts.value.wishlist,
      }
    } else if (isWishlistCartActivated.value) {
      cartsLocal.value = {
        shopping: getLocalCarts.value.shopping,
        wishlist: cartItems.value,
      }
    }

    safeStorage.setItem(cartStorageKey, JSON.stringify(getLocalCarts.value))
  }

  function loadFromLocalStorage() {
    const saved = safeStorage.getItem(cartStorageKey)
    cartsLocal.value = {
      shopping: saved?.shopping || getLocalCarts.value.shopping,
      wishlist: saved?.wishlist || getLocalCarts.value.wishlist,
    }
  }

  const count = computed(() => {
    if (!getCartItems.value?.length) return 0

    return getCartItems.value.reduce((sum, item) => {
      const quantity = parseInt(item.quantity, 10) || 0

      return sum + quantity
    }, 0)
  })

  const shipmentCount = computed(() => {
    if (!getCartItems.value?.length) return 0

    let totalShipment = 0
    let hasAnySimpleProduct = false
    getCartItems.value.forEach(item => {
      const quantity = parseInt(item.quantity, 10) || 0

      if (item?.has_separate_shipment) {
        totalShipment += quantity
      } else {
        hasAnySimpleProduct = true
      }
    })

    if (hasAnySimpleProduct) totalShipment += 1

    return totalShipment
  })

  const totalPrice = computed(() => {
    if (!getCartItems.value?.length) return 0

    return getCartItems.value.reduce((total, item) => {
      const quantity = parseInt(item.quantity, 10) || 0
      const price = parseInt(item.actual_price, 10) || 0
      const tax = parseInt(item.tax_rate, 10) || 0

      return total + (quantity * (price + (price * tax / 100)))
    }, 0)
  })

  const totalDiscountedPrice = computed(() => {
    if (!getCartItems.value?.length) return 0

    return getCartItems.value.reduce((total, item) => {
      const quantity = parseInt(item.quantity, 10) || 0
      const price = parseInt(item.price, 10) || 0
      const tax = parseInt(item.tax_rate, 10) || 0

      return total + (quantity * (price + (price * tax / 100)))
    }, 0)
  })

  const subtotalPrice = computed(() => {
    if (!getCartItems.value?.length) return 0

    return getCartItems.value.reduce((total, item) => {
      const quantity = parseInt(item.quantity, 10) || 0
      const price = parseInt(item.actual_price, 10) || 0

      return total + (quantity * price)
    }, 0)
  })

  const subtotalDiscountedPrice = computed(() => {
    if (!getCartItems.value?.length) return 0

    return getCartItems.value.reduce((total, item) => {
      const quantity = parseInt(item.quantity, 10) || 0
      const price = parseInt(item.price, 10) || 0

      return total + (quantity * price)
    }, 0)
  })

  const totalTaxPrice = computed(() => {
    if (!getCartItems.value?.length) return 0

    return getCartItems.value.reduce((total, item) => {
      const quantity = parseInt(item.quantity, 10) || 0
      const price = parseInt(item.price, 10) || 0
      const tax = parseInt(item.tax_rate, 10) || 0

      return total + (quantity * (price * tax / 100))
    }, 0)
  })

  function changeToShoppingCart() {
    safeStorage.setItem(cartNameStorageKey, 'shopping')
    activeCart.value = 'shopping'

    nextTick(() => {
      cartItems.value = getLocalCarts.value[getActiveCart.value] || []
    })
  }

  function changeToWishlistCart() {
    safeStorage.setItem(cartNameStorageKey, 'wishlist')
    activeCart.value = 'wishlist'

    nextTick(() => {
      cartItems.value = getLocalCarts.value[getActiveCart.value] || []
    })
  }

  function loadToLocalShopping() {
    cartsLocal.value.shopping = getSavedCarts.value.shopping || []
  }

  function loadToLocalWishlist() {
    cartsLocal.value.wishlist = getSavedCarts.value.wishlist || []
  }

  function addAllItems(codesQuantities = {}, callbacks = {}) {
    loading.value = true
    useRequestWrapper(
      apiRoutes.cart.addAll,
      {
        method: 'POST',
        data: {
          cart_name: getActiveCart.value,
          codes_quantities: codesQuantities,
          items: getCartItems.value,
        },
      },
      {
        success(response) {
          cartItems.value = response.data
          cartsLocal.value[getActiveCart.value] = response.data

          saveCurrentToLocalStorage()
        },
        finally() {
          loading.value = false
        },
      },
      callbacks
    )
  }

  function addItem(code, quantity = 1, callbacks = {}) {
    loading.value = true
    useRequestWrapper(
      apiReplaceParams(apiRoutes.cart.add, {code}),
      {
        method: 'POST',
        data: {
          cart_name: getActiveCart.value,
          quantity,
          items: getCartItems.value,
        },
      },
      {
        success(response) {
          cartItems.value = response.data
          cartsLocal.value[getActiveCart.value] = response.data

          saveCurrentToLocalStorage()
        },
        finally() {
          loading.value = false
        },
      },
      callbacks
    )
  }

  function removeItem(code) {
    cartItems.value = getCartItems.value.filter(item => {
      return item?.code !== code
    })

    saveCurrentToLocalStorage()
  }

  function fetchAllLocal(callbacks = {}) {
    if (isLoading.value) return

    loading.value = true
    loadFromLocalStorage()

    useRequestWrapper(
      apiRoutes.cart.sessionCarts,
      {
        method: 'POST',
        data: {
          carts: cartsLocal.value,
        },
      },
      {
        success(response) {
          cartsLocal.value = response.data

          if (isShoppingCartActivated.value) {
            cartItems.value = cartsLocal.value.shopping
          } else if (isWishlistCartActivated.value) {
            cartItems.value = cartsLocal.value.wishlist
          }

          saveToLocalStorage()
        },
        finally() {
          loading.value = false
        },
      },
      callbacks
    )
  }

  function empty() {
    useConfirmToast(() => {
      cartItems.value = []
      saveCurrentToLocalStorage()
    }, 'خالی نمودن سبد خرید')
  }

  function fetchAll(callbacks = {}) {
    if (!userStore.getUser) {
      toast.error('برای انجام این عمل، ابتدا به پنل کاربری خود وارد شوید.')
      return
    }

    if (isLoading.value) return

    loading.value = true
    useRequestWrapper(
      apiRoutes.cart.index,
      null,
      {
        success(response) {
          carts.value = response.data
        },
        finally() {
          loading.value = false
        },
      },
      callbacks
    )
  }

  function fetchByName(name, callbacks = {}) {
    if (!userStore.getUser) {
      toast.error('برای انجام این عمل، ابتدا به پنل کاربری خود وارد شوید.')
      return
    }

    if (isLoading.value) return

    loading.value = true
    useRequestWrapper(
      apiReplaceParams(apiRoutes.cart.show, {cart: name}),
      null,
      {
        success(response) {
          carts.value[name] = response.data
        },
        finally() {
          loading.value = false
        },
      },
      callbacks
    )
  }

  function fetchShopping(callbacks = {}) {
    fetchByName('shopping', callbacks)
  }

  function fetchWishlist(callbacks = {}) {
    fetchByName('wishlist', callbacks)
  }

  function save(callbacks = {}) {
    if (!userStore.getUser) {
      toast.error('برای انجام این عمل، ابتدا به پنل کاربری خود وارد شوید.')
      return
    }

    if (isLoading.value) return

    useConfirmToast(() => {
      loading.value = true
      useRequestWrapper(
        apiRoutes.cart.store,
        {
          data: {
            cart_name: getActiveCart.value,
            items: getCartItems.value,
          },
        },
        {
          success() {
            carts.value[getActiveCart.value] = getCartItems.value
          },
          finally() {
            loading.value = false
          },
        },
        callbacks
      )
    }, 'ذخیره سبد خرید')
  }

  function remove(callbacks = {}) {
    if (!userStore.getUser) {
      toast.error('برای انجام این عمل، ابتدا به پنل کاربری خود وارد شوید.')
      return
    }

    if (isLoading.value) return

    useConfirmToast(() => {
      loading.value = true
      useRequestWrapper(
        apiRoutes.cart.destroy,
        {
          data: {
            cart_name: getActiveCart.value,
          },
        },
        {
          success() {
            carts.value[getActiveCart.value] = []
          },
          finally() {
            loading.value = false
          },
        },
        callbacks
      )
    }, 'حذف سبد خرید')
  }

  function $reset() {
    loading.value = false
    carts.value = {
      shopping: [],
      wishlist: [],
    }
    cartsLocal.value = {
      shopping: [],
      wishlist: [],
    }
    cartItems.value = []

    changeToShoppingCart()
    fetchAllLocal()
  }

  fetchAllLocal()

  return {
    isLoading,
    //
    carts, cartsLocal, cartItems,
    activeCart,
    //
    getSavedCarts, getLocalCarts,
    getCartItems, getActiveCart,
    isShoppingCartActivated, isWishlistCartActivated,
    //
    count, shipmentCount,
    totalPrice, totalDiscountedPrice,
    subtotalPrice, subtotalDiscountedPrice,
    totalTaxPrice,
    //
    changeToShoppingCart, changeToWishlistCart,
    //
    addAllItems, addItem, removeItem,
    fetchAllLocal, empty,
    fetchShopping, fetchWishlist, fetchAll, save, remove,
    //
    loadToLocalShopping, loadToLocalWishlist,
    //
    $reset,
  }
})
