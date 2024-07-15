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
  const activeCartItems = ref([])

  const userStore = useUserAuthStore()

  const isLoading = computed(() => {
    return loading.value
  })

  const getCarts = computed(() => {
    return carts.value
  })

  const getActiveCart = computed(() => {
    return activeCart.value
  })

  const getActiveCartItems = computed(() => {
    return activeCartItems.value
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
    if (userStore.getUser) return

    if (isShoppingCartActivated.value) {
      carts.value = {
        shopping: activeCartItems.value,
        wishlist: getCarts.value.wishlist,
      }
    } else if (isWishlistCartActivated.value) {
      carts.value = {
        shopping: getCarts.value.shopping,
        wishlist: activeCartItems.value,
      }
    }

    await nextTick(() => {
      safeStorage.setItem(cartStorageKey, getCarts.value)
      loadFromLocalStorage()
    })
  }

  function emptyLocalStorage() {
    safeStorage.removeItem(cartStorageKey)
  }

  async function loadFromLocalStorage() {
    const saved = safeStorage.getItem(cartStorageKey)

    carts.value = {
      shopping: saved?.shopping || getCarts.value.shopping,
      wishlist: saved?.wishlist || getCarts.value.wishlist,
    }

    await nextTick(() => {
      if (isShoppingCartActivated.value) {
        activeCartItems.value = getCarts.value?.shopping || []
      } else if (isWishlistCartActivated.value) {
        activeCartItems.value = getCarts.value?.wishlist || []
      }
    })
  }

  function findItemFromLocalCart(code) {
    if (code?.toString() === '') return null

    let product = getActiveCartItems.value.filter(item => {
      return item?.code === code
    })

    return product.length && product[0] ? product[0] : null
  }

  //-----------------------------------------------------------------
  // Counting Functions
  //-----------------------------------------------------------------

  const count = computed(() => {
    if (!getActiveCartItems.value?.length) return 0

    return getActiveCartItems.value.reduce((sum, item) => {
      const quantity = parseInt(item.quantity, 10) || 0

      return sum + quantity
    }, 0)
  })

  const shipmentCount = computed(() => {
    if (!getActiveCartItems.value?.length) return 0

    let totalShipment = 0
    let hasAnySimpleProduct = false
    getActiveCartItems.value.forEach(item => {
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
    if (!getActiveCartItems.value?.length) return 0

    return getActiveCartItems.value.reduce((total, item) => {
      const quantity = parseInt(item.quantity, 10) || 0
      const price = parseInt(item.actual_price, 10) || 0
      const tax = parseInt(item.tax_rate, 10) || 0

      return total + (quantity * (price + (price * tax / 100)))
    }, 0)
  })

  const totalDiscountedPrice = computed(() => {
    if (!getActiveCartItems.value?.length) return 0

    return getActiveCartItems.value.reduce((total, item) => {
      const quantity = parseInt(item.quantity, 10) || 0
      const price = parseInt(item.price, 10) || 0
      const tax = parseInt(item.tax_rate, 10) || 0

      return total + (quantity * (price + (price * tax / 100)))
    }, 0)
  })

  const subtotalPrice = computed(() => {
    if (!getActiveCartItems.value?.length) return 0

    return getActiveCartItems.value.reduce((total, item) => {
      const quantity = parseInt(item.quantity, 10) || 0
      const price = parseInt(item.actual_price, 10) || 0

      return total + (quantity * price)
    }, 0)
  })

  const subtotalDiscountedPrice = computed(() => {
    if (!getActiveCartItems.value?.length) return 0

    return getActiveCartItems.value.reduce((total, item) => {
      const quantity = parseInt(item.quantity, 10) || 0
      const price = parseInt(item.price, 10) || 0

      return total + (quantity * price)
    }, 0)
  })

  const totalTaxPrice = computed(() => {
    if (!getActiveCartItems.value?.length) return 0

    return getActiveCartItems.value.reduce((total, item) => {
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

  //-----------------------------------------------------------------

  async function changeToShoppingCart() {
    safeStorage.setItem(cartNameStorageKey, 'shopping')
    activeCart.value = 'shopping'

    await loadFromLocalStorage()

    if (userStore.getUser) {
      fetchShopping()
    } else {
      await nextTick(() => {
        activeCartItems.value = getCarts.value[getActiveCart.value] || []
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
        activeCartItems.value = getCarts.value[getActiveCart.value] || []
      })
    }
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
          items: getActiveCartItems.value,
        },
      },
      {
        success(response) {
          activeCartItems.value = response.data
          carts.value[getActiveCart.value] = response.data

          if (!userStore.getUser) {
            saveToLocalStorage()
          }
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
          items: getActiveCartItems.value,
        },
      },
      {
        success(response) {
          activeCartItems.value = response.data
          carts.value[getActiveCart.value] = response.data

          if (!userStore.getUser) {
            saveToLocalStorage()
          }
        },
        finally() {
          loading.value = false
        },
      },
      callbacks
    )
  }

  async function removeItem(code) {
    activeCartItems.value = getActiveCartItems.value.filter(item => {
      return item?.code !== code
    })

    await nextTick(() => {
      carts.value[getActiveCart.value] = getActiveCartItems.value
    })

    if (userStore.getUser) {
      save()
    } else {
      await saveToLocalStorage()
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
          carts: carts.value,
        },
      },
      {
        success(response) {
          carts.value = response.data

          if (userStore.getUser) {
            emptyLocalStorage()
          } else {
            saveToLocalStorage()
          }

          if (isShoppingCartActivated.value) {
            activeCartItems.value = carts.value.shopping
          } else if (isWishlistCartActivated.value) {
            activeCartItems.value = carts.value.wishlist
          }
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
      activeCartItems.value = []

      if (userStore.getUser) {
        remove(callbacks)
      } else {
        emptyLocalStorage()
      }
    } else {
      useConfirmToast(async () => {
        activeCartItems.value = []

        if (userStore.getUser) {
          remove(callbacks)
        } else {
          emptyLocalStorage()
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
    return useRequestWrapper(
      apiReplaceParams(apiRoutes.cart.show, {cart: cartName}),
      {
        method: 'POST',
        data: {
          items: carts.value[getActiveCart.value],
        },
      },
      {
        async success(response) {
          carts.value[cartName] = response.data
          activeCartItems.value = response.data
        },
        finally() {
          loading.value = false
        },
      },
      callbacks
    )
  }

  function fetchShopping(callbacks = {}) {
    return fetchByName(getShoppingCartName(), callbacks)
  }

  function fetchWishlist(callbacks = {}) {
    return fetchByName(getWishlistCartName(), callbacks)
  }

  async function save(callbacks = {}, cartName = null) {
    if (!userStore.getUser || isLoading.value) return

    await loadFromLocalStorage()

    cartName = cartName ?? getActiveCart.value

    loading.value = true
    return useRequestWrapper(
      apiRoutes.cart.store,
      {
        method: 'POST',
        data: {
          cart_name: cartName,
          items: getCarts.value[cartName],
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
    carts.value = {
      shopping: [],
      wishlist: [],
    }
    activeCartItems.value = []

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
    carts, activeCartItems,
    activeCart,
    //
    findItemFromLocalCart,
    getCarts,
    getActiveCartItems, getActiveCart,
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
    saveToLocalStorage, emptyLocalStorage, loadFromLocalStorage,
    //
    getShoppingCartName, getWishlistCartName,
    //
    $reset,
  }
})
