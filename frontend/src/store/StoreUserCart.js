import {defineStore} from "pinia";
import {computed, ref} from "vue";

export const useCartStore = defineStore('userCart', () => {
  const activeCart = ref('main')
  const cartItems = ref([])

  const getActiveCart = computed(() => {
    return activeCart.value
  })

  const getCartItems = computed(() => {
    return cartItems.value
  })

  function changeToMainCart() {
    activeCart.value = 'main'
  }

  function changeToBackupCart() {
    activeCart.value = 'backup'
  }

  function add(code) {
    // TODO: ...
  }

  function remove(code) {
    // TODO: ...
  }

  function empty() {
    // TODO: ...
  }

  function save() {
    // TODO: ...
  }

  function $reset() {
    cartItems.value = []
  }

  return {
    cartItems,
    activeCart,
    //
    getCartItems,
    getActiveCart,
    //
    changeToMainCart,
    changeToBackupCart,
    //
    add,
    remove,
    empty,
    save,
    //
    $reset,
  }
})
