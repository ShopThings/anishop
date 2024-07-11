import {defineStore} from "pinia";
import {computed, nextTick, ref} from "vue";
import {useSafeLocalStorage} from "@/composables/safe-local-storage.js";
import {useUserAuthStore} from "@/store/StoreUserAuth.js";
import {useRequestWrapper} from "@/composables/api-request.js";
import {apiReplaceParams, apiRoutes} from "@/router/api-routes.js";
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
    return getActiveCart.value === getShoppingCartName()
  })

  const isWishlistCartActivated = computed(() => {
    return getActiveCart.value === getWishlistCartName()
  })

  function getShoppingCartName() {
    return 'shopping'
  }

  function getWishlistCartName() {
    return 'wishlist'
  }

  async function saveToLocalStorage() {
    if (isShoppingCartActivated.value) {
      cartsLocal.value = {
        shopping: cartItems.value,
        wishlist: getLocalCarts.value.wishlist,
      }

      if (userStore.getUser) {
        carts.value = {
          shopping: cartItems.value,
          wishlist: getLocalCarts.value.wishlist,
        }
      }
    } else if (isWishlistCartActivated.value) {
      cartsLocal.value = {
        shopping: getLocalCarts.value.shopping,
        wishlist: cartItems.value,
      }

      if (userStore.getUser) {
        carts.value = {
          shopping: getLocalCarts.value.shopping,
          wishlist: cartItems.value,
        }
      }
    }

    await nextTick(() => {
      safeStorage.setItem(cartStorageKey, getLocalCarts.value)
      loadFromLocalStorage()
    })
  }

  async function loadFromLocalStorage() {
    const saved = safeStorage.getItem(cartStorageKey)

    cartsLocal.value = {
      shopping: saved?.shopping || getLocalCarts.value.shopping,
      wishlist: saved?.wishlist || getLocalCarts.value.wishlist,
    }

    await nextTick(() => {
      if (isShoppingCartActivated.value) {
        cartItems.value = getLocalCarts.value?.shopping || []
      } else if (isWishlistCartActivated.value) {
        cartItems.value = getLocalCarts.value?.wishlist || []
      }
    })
  }

  function findItemFromLocalCart(code) {
    if (code?.toString() === '') return null

    let product = getCartItems.value.filter(item => {
      return item?.code === code
    })

    return product.length && product[0] ? product[0] : null
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

  function totalPriceFor(code) {
    let item = findItemFromLocalCart(code)

    if (!item) return 0

    const quantity = parseInt(item.quantity, 10) || 0
    const price = parseInt(item.actual_price, 10) || 0
    const tax = parseInt(item.tax_rate, 10) || 0

    return quantity * (price + (price * tax / 100))
  }

  function totalDiscountedPriceFor(code) {
    let item = findItemFromLocalCart(code)

    if (!item) return 0

    const quantity = parseInt(item.quantity, 10) || 0
    const price = parseInt(item.price, 10) || 0
    const tax = parseInt(item.tax_rate, 10) || 0

    return quantity * (price + (price * tax / 100))
  }

  function subtotalPriceFor(code) {
    let item = findItemFromLocalCart(code)

    if (!item) return 0

    const quantity = parseInt(item.quantity, 10) || 0
    const price = parseInt(item.actual_price, 10) || 0

    return quantity * price
  }

  function subtotalDiscountedPriceFor(code) {
    let item = findItemFromLocalCart(code)

    if (!item) return 0

    const quantity = parseInt(item.quantity, 10) || 0
    const price = parseInt(item.price, 10) || 0

    return quantity * price
  }

  async function changeToShoppingCart() {
    safeStorage.setItem(cartNameStorageKey, 'shopping')
    activeCart.value = 'shopping'

    await loadFromLocalStorage()

    if (userStore.getUser) {
      fetchShopping()
    } else {
      await nextTick(() => {
        cartItems.value = getLocalCarts.value[getActiveCart.value] || []
      })
    }
  }

  async function changeToWishlistCart() {
    safeStorage.setItem(cartNameStorageKey, 'wishlist')
    activeCart.value = 'wishlist'

    await loadFromLocalStorage()

    if (userStore.getUser) {
      fetchWishlist()
    } else {
      await nextTick(() => {
        cartItems.value = getLocalCarts.value[getActiveCart.value] || []
      })
    }
  }

  function loadToLocalShopping() {
    cartsLocal.value.shopping = getSavedCarts.value.shopping || []
  }

  function loadToLocalWishlist() {
    cartsLocal.value.wishlist = getSavedCarts.value.wishlist || []
  }

  async function addAllItems(codesQuantities = {}, callbacks = {}) {
    if (isLoading.value) return

    loading.value = true

    await loadFromLocalStorage()

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

          if (userStore.getUser) {
            carts.value[getActiveCart.value] = response.data
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

  async function addItem(code, quantity = 1, callbacks = {}) {
    if (isLoading.value) return

    loading.value = true

    await loadFromLocalStorage()

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

          if (userStore.getUser) {
            carts.value[getActiveCart.value] = response.data
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

  async function removeItem(code) {
    cartItems.value = getCartItems.value.filter(item => {
      return item?.code !== code
    })

    await saveToLocalStorage()

    if (userStore.getUser) {
      save()
    }
  }

  async function fetchAllLocal(callbacks = {}) {
    if (isLoading.value) return
    loading.value = true

    await loadFromLocalStorage()

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

          if (userStore.getUser) {
            carts.value = response.data
          }

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

  async function empty(callbacks = {}, force = false) {
    if (force) {
      cartItems.value = []

      await saveToLocalStorage()

      if (userStore.getUser) {
        remove(callbacks)
      }
    } else {
      useConfirmToast(async () => {
        cartItems.value = []

        await saveToLocalStorage()

        if (userStore.getUser) {
          remove(callbacks)
        }
      }, 'خالی نمودن سبد خرید')
    }
  }

  function fetchAll(callbacks = {}) {
    if (!userStore.getUser || isLoading.value) return

    loading.value = true
    useRequestWrapper(
      apiRoutes.cart.index,
      null,
      {
        success(response) {
          cartsLocal.value = response.data
          carts.value = response.data
        },
        finally() {
          loading.value = false
        },
      },
      callbacks
    )
  }

  function fetchByName(cartName, callbacks = {}) {
    if (!userStore.getUser || isLoading.value) return

    loading.value = true
    useRequestWrapper(
      apiReplaceParams(apiRoutes.cart.show, {cart: cartName}),
      {
        method: 'POST',
        data: {
          items: cartsLocal.value[getActiveCart.value],
        },
      },
      {
        async success(response) {
          carts.value[cartName] = response.data
          cartItems.value = response.data

          await saveToLocalStorage()
        },
        finally() {
          loading.value = false
        },
      },
      callbacks
    )
  }

  function fetchShopping(callbacks = {}) {
    fetchByName(getShoppingCartName(), callbacks)
  }

  function fetchWishlist(callbacks = {}) {
    fetchByName(getWishlistCartName(), callbacks)
  }

  async function save(callbacks = {}, cartName = null) {
    if (!userStore.getUser || isLoading.value) return

    await loadFromLocalStorage()

    cartName = cartName ?? getActiveCart.value

    loading.value = true
    useRequestWrapper(
      apiRoutes.cart.store,
      {
        method: 'POST',
        data: {
          cart_name: cartName,
          items: getCartItems.value,
        },
      },
      {
        finally() {
          loading.value = false
        },
      },
      callbacks
    )
  }

  function remove(callbacks = {}) {
    if (!userStore.getUser || isLoading.value) return

    loading.value = true
    useRequestWrapper(
      apiRoutes.cart.destroy,
      {
        method: 'DELETE',
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

    fetchAllLocal({
      success() {
        changeToShoppingCart()
      },
    })
  }

  fetchAllLocal()

  return {
    isLoading,
    //
    carts, cartsLocal, cartItems,
    activeCart,
    //
    findItemFromLocalCart,
    getSavedCarts, getLocalCarts,
    getCartItems, getActiveCart,
    isShoppingCartActivated, isWishlistCartActivated,
    //
    count, shipmentCount,
    totalPrice, totalDiscountedPrice,
    subtotalPrice, subtotalDiscountedPrice,
    totalTaxPrice,
    //
    totalPriceFor, totalDiscountedPriceFor,
    subtotalPriceFor, subtotalDiscountedPriceFor,
    //
    changeToShoppingCart, changeToWishlistCart,
    //
    addAllItems, addItem, removeItem,
    fetchAllLocal, empty,
    fetchShopping, fetchWishlist, fetchAll, save, remove,
    //
    saveToLocalStorage,
    //
    loadFromLocalStorage,
    loadToLocalShopping, loadToLocalWishlist,
    //
    getShoppingCartName, getWishlistCartName,
    //
    $reset,
  }
})
